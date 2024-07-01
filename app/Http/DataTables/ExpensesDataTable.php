<?php

namespace App\Http\DataTables;

use App\Models\Expense;
use App\Models\Item;
use App\Services\QueryBuilderService;
use Termwind\Components\Span;

class ExpensesDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'expenses') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'expenses';
            $model = 'expense';
            $viewGate = 'expense_view';
            $editGate = false;
            $deleteGate = 'expense_delete';

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate'
            ));
        });

        $table->editColumn('project', function($row) {
            return '<a href="'.route('dashboard.projects.board', $row->project->id).'">'.$row->project->name.'</a>';
        });

        $table->editColumn('name', function($row) {
            if($row->item_id) {
                return '<a target="_blank" href="'.route('dashboard.items.show', $row->item_id).'">'.$row->name.'</a>';
            }

            return $row->name;
        });

        $table->editColumn('quantity', function($row) {
            return $row->quantity ?: 1;
        });

        $table->editColumn('amount', function($row) {
            return $row->total ? $row->total . $row->project?->client?->currency->symbol : '-';
        });

        $table->editColumn('facturable', function($row) {
            return !$row->facturable ? '<span class="badge bg-success">Facturated</span>'
                : '<span class="badge bg-danger">Non-Facturated</span>';
        });


        $table->editColumn('created_at', function($row) {
            return $row->created_at ?: '';
        });

        $table->rawColumns(['placeholder', 'project', 'facturable', 'name', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Expense::query();

        $created_at_range = request()?->get('created_at_range');
        $quantity_min = request()?->get('quantity_min');
        $quantity_max = request()?->get('quantity_max');
        $amount_min = request()?->get('amount_min');
        $amount_max = request()?->get('amount_max');

        $query = (new QueryBuilderService())->advancedQuery(
            Expense::class,
            [
                'conditions' => request()?->get('conditions') ?? '',
                'fields' => request()?->get('fields') ?? '',
                'operators' => request()?->get('operators') ?? '',
                'values' => request()?->get('values') ?? '',
            ],
            ['project', 'item']
        );

        if(!empty($created_at_range)) {
            $range = str_replace(' a ', ' - ', $created_at_range);
            $range = explode(' - ', $range);

            $query->whereDate('created_at', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('created_at', '<=', $range[1]);
            }
        }


        if(!empty($quantity_min)) {
            $query->where('quantity', '>=', $quantity_min);
        }

        if(!empty($quantity_max)) {
            $query->where('quantity', '<=', $quantity_max);
        }

        if(!empty($amount_min)) {
            $query->where('amount', '>=', $amount_min);
        }

        if(!empty($amount_max)) {
            $query->where('amount', '<=', $amount_max);
        }

        return $query;
    }
}
