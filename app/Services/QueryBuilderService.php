<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Query Builder Service. This creates generic queries according to the parameters received.
 */
class QueryBuilderService
{
    /**
     * Create a query
     *
     * @param  string $model
     * @param  array $filters
     * @param  ?string $orderBy
     * @param  ?string $orderDirection
     * @param  ?array $withRelations
     * @return Builder
     */
    public function query(
        string $model,
        array $filters,
        ?string $orderBy = null,
        ?string $orderDirection = null,
        ?array $withRelations = []
    ): Builder {
        $filterable = $this->getModelFilterable($model);
        $orderBy = $orderBy && in_array(strtolower($orderBy), $filterable, true) ? strtolower($orderBy) : 'id';

        if ($orderDirection && !in_array(strtolower($orderDirection), ['asc', 'desc'], true)) {
            throw new \RuntimeException('Order direction must be asc or desc');
        }

        $modelInstance = new $model();
        $query = $modelInstance
            ->query()
            ->with($this->validateRelations($modelInstance, $withRelations));

        foreach ($filters as $key => $filter) {
            if (in_array($key, $filterable, true)) {
                $query = $this->makeQuery($query, $key, $filter);
            }
        }

        foreach ($this->getFiltersToRelations($filters) as $relationName => $relationFilters) {
            $query = $this->queryRelationship($modelInstance, $relationName, $relationFilters, $query);
        }

        if ($orderBy && $orderDirection) {
            $query->orderBy($orderBy, $orderDirection);
        }

        return $query;
    }

    /**
     * Create a query with the relationship
     *
     * @param  Model $parentModel
     * @param  string $relationModel
     * @param  array $filters
     * @param  Builder $query
     * @param  bool $advanced_query
     * @return Builder
     */
    private function queryRelationship(
        Model $parentModel,
        string $relationModel,
        array $filters,
        Builder $query,
        bool $advanced_query = false
    ): Builder {
        $relation = $parentModel->$relationModel()->getRelated();
        $filterable = $this->getModelFilterable($relation::class);

        $query->whereHas($relationModel, function (Builder $query) use ($filters, $filterable, $advanced_query) {
            foreach ($filters as $key => $filter) {
                if($advanced_query) {
                    if(str_contains($filter['field'], '.')) {
                        $relationName = explode('.', $filter['field'])[0];
                        $relationField = explode('.', $filter['field'])[1];
                        $query->whereHas($relationName, function (Builder $query) use ($relationField, $filter) {
                            $query->where($relationField, $this->matchOperator($filter['operator']), $this->matchValue($filter['value'], $filter['operator']));
                        });
                    } elseif(in_array($filter['field'], $filterable, true)) {
                        $query = $this->makeAdvancedQuery(
                            $query,
                            $filter['field'],
                            [
                                'condition' => $filter['condition'],
                                'operation' => $filter['operator'],
                                'value' => $filter['value']
                            ]
                        );
                    }
                } else {
                    if (in_array($key, $filterable, true)) {
                        $this->makeQuery($query, $key, $filter);
                    }
                }
            }
        });

        return $query;
    }

    /**
     * Create a advanced filter for a model
     *
     * @param  Model $parentModel
     * @param  string $relationModel
     * @param  array $filters
     * @param  Builder $query
     * @return Builder
     */
    public function advancedQuery(
        string $model,
        array $filters,
        ?array $withRelations = []
    ): Builder {
        $modelInstance = new $model();
        $filterable = $this->getModelFilterable($model);

        $query = $modelInstance
            ->query()
            ->with($this->validateRelations($modelInstance, $withRelations))
            ->select(sprintf('%s.*', $modelInstance->getTable()));

        if(count($filters) && is_array($filters['values'])) {
            foreach ($filters['values'] as $idx => $value) {
                if(!str_contains($filters['fields'][$idx], '.') && in_array($filters['fields'][$idx], $filterable, true)) {
                    $query = $this->makeAdvancedQuery(
                        $query,
                        $filters['fields'][$idx],
                        [
                            'condition' => $filters['conditions'][$idx],
                            'operation' => $filters['operators'][$idx],
                            'value' => $value
                        ]
                    );
                }
            }

            foreach ($this->getAdvancedFiltersToRelations($filters) as $relationName => $relationFilters) {
                $query = $this->queryRelationship($modelInstance, $relationName, $relationFilters, $query, true);
            }
        }

        return $query;
    }

    private function getFiltersToRelations(array $filters): array
    {
        $filterableToRelations = [];

        foreach ($filters as $filterName => $filterOperation) {
            if (Str::contains($filterName, '.')) {
                $filterRelationName = explode('.', $filterName)[0];
                $filterRelationParam = Str::replace($filterRelationName . '.', '', $filterName);
                $filterableToRelations[$filterRelationName][$filterRelationParam] = $filterOperation;
            }
        }

        return $filterableToRelations;
    }

    private function getAdvancedFiltersToRelations(array $filters): array
    {
        $filterableToRelations = [];

        foreach ($filters['fields'] as $idx => $field) {
            if (Str::contains($field, '.')) {
                $filterRelationName = explode('.', $field)[0];
                $filterableToRelations[$filterRelationName][] = [
                    'condition' => $filters['conditions'][$idx],
                    'operator' => $filters['operators'][$idx],
                    'field' => Str::replace($filterRelationName . '.', '', $field),
                    'value' => $filters['values'][$idx] ?? ''
                ];
            }
        }

        return $filterableToRelations;
    }

    /**
     * Obtains the model fields to make the query
     *
     * @param  string $model
     * @return array
     */
    private function getModelFilterable(string $model): array
    {
        $modelInstance = new $model();

        if (property_exists($modelInstance, 'filterable') && is_array($modelInstance::$filterable)) {
            return $modelInstance::$filterable;
        }

        if (is_array($modelInstance->getFillable())) {
            return $modelInstance->getFillable();
        }

        return [];
    }

    /**
     * Make the query
     *
     * @param  Builder $query
     * @param  string $key
     * @param  string|array|null $filter
     * @return Builder
     */
    private function makeQuery(Builder $query, string $key, string|array|null $filter): Builder
    {
        $dates = $query->getModel()->getDates();
        // Check if those dates are casted to datetime, if so, they should be formatted as Y-m-d H:i:s
        $casted_dates = array_keys(array_filter($query->getModel()->getCasts(), static function ($cast) {
            return $cast === 'datetime';
        }), 'datetime');

        // Check wich of the $dates are casted to datetime
        $dates_timestamp = array_intersect($dates, $casted_dates);

        if (in_array($key, $dates_timestamp, true)) {
            if (is_array($filter)) {
                $query->where($key, $filter['operation'], Carbon::parse($filter['value'])->format('Y-m-d H:i:s'));
            } else {
                $query->where($key, Carbon::parse($filter)->format('Y-m-d H:i:s'));
            }
        } elseif (in_array($key, $dates, true)) {
            if (is_array($filter)) {
                $query->whereDate($key, $filter['operation'], $filter['value']);
            } else {
                $query->whereDate($key, $filter);
            }
        } else {
            if (is_array($filter)) {
                $query->where($key, $filter['operation'], $filter['value']);
            } else {
                $query->where($key, $filter);
            }
        }

        return $query;
    }

    /**
     * Make the advanced filter query
     *
     * @param  Builder $query
     * @param  string $key
     * @param  string|array|null $filter
     * @return Builder
     */
    private function makeAdvancedQuery(Builder $query, string $key, ?array $filter): Builder
    {
        if (is_array($filter)) {
            if($filter['condition'] === 'or') {
                $query->orWhere(
                    $key,
                    $this->matchOperator($filter['operation']),
                    $this->matchValue($filter['value'], $filter['operation'])
                );
            } else {
                $query->where(
                    $key,
                    $this->matchOperator($filter['operation']),
                    $this->matchValue($filter['value'], $filter['operation'])
                );
            }
        }

        return $query;
    }

    /**
     * Check if exists the relationship of the model
     *
     * @param  Model $modelInstance
     * @param  ?array $relations
     * @return array
     */
    private function validateRelations(Model $modelInstance, ?array $relations = []): array
    {
        if (is_array($relations)) {
            return array_filter($relations, static function ($relation) use ($modelInstance) {
                if (str_contains($relation, '.')) {
                    $subRelations = explode('.', $relation);
                    $valid = true;

                    foreach ($subRelations as $key => $subRelation) {
                        if ($key === 0) {
                            $valid = $valid && $modelInstance->isRelation($subRelation);
                        } else {
                            $subClass = '\\App\\Models\\'
                                . Str::ucfirst(Str::singular(Str::camel($subRelations[$key - 1])));
                            $subRelationInstance = new $subClass();
                            $valid = $valid && $subRelationInstance->isRelation($subRelation);
                        }
                    }

                    return $valid;
                }

                return $modelInstance->isRelation($relation);
            });
        }

        return [];
    }

    private function matchOperator(string $operator): string
    {
        return match ($operator) {
            'equal' => '=',
            'not_equal' => '!=',
            'greater_than' => '>',
            'greater_than_or_equal' => '>=',
            'less_than' => '<',
            'less_than_or_equal' => '<=',
            'like' => 'like',
            'not_like' => 'not like',
        };
    }

    private function matchValue(string $value, string $operator): string|int|array|null
    {
        return match ($operator) {
            'like' => '%' . $value . '%',
            'not_like' => '%' . $value . '%',
            default => $value
        };
    }
}
