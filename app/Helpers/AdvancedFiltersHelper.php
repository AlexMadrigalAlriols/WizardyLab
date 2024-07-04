<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskAttendance;
use App\Models\User;
use Carbon\Carbon;

class AdvancedFiltersHelper {
    public const OPERATORS = [
        'like' => 'Like',
        'not_like' => 'Not like',
        'equal' => 'Equal',
        'not_equal' => 'Not equal',
        'greater_than' => 'Greater than',
        'less_than' => 'Less than',
        'greater_than_or_equal' => 'Greater than or equal',
        'less_than_or_equal' => 'Less than or equal'
    ];

    public static function getFilters($model): array
    {
        $fields = self::getModelFilterable($model);

        foreach ($fields as $idx => $field) {
            if (strpos($field, '.') !== false) {
                $fields[explode('.', $field)[0]][] = isset(explode('.', $field)[2]) ? explode('.', $field)[1] . '.' . explode('.', $field)[2] : explode('.', $field)[1];
            } else {
                $fields['self'][] = $field;
            }

            unset($fields[$idx]);
        }
        $operators = self::OPERATORS;

        return [
            'fields' => $fields,
            'operators' => $operators
        ];
    }

    private static function getModelFilterable(string $model): array
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
}
