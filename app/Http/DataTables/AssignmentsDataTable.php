<?php

namespace App\Http\DataTables;

use App\Models\Client;
use App\Models\Item;
use App\Models\UserInventory;
use App\Services\QueryBuilderService;

class AssignmentsDataTable extends DataTable
{

    public function __construct(protected ?string $name = 'assignments') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'assignments';
            $model = 'assignment';
            $viewGate = 'assignment_view';
            $editGate = 'assignment_edit';
            $deleteGate = 'assignment_delete';

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate'
            ));
        });

        $table->editColumn('user', function($row) {
            return view('partials.users.details', ['user' => $row->user]);
        });

        $table->editColumn('items', function($row) {
            $items = '';

            foreach ($row->items ?? [] as $item) {
                $items .= '<span class="badge bg-primary">' . $item->item->reference . '</span>';
            }

            return $items;
        });

        $table->editColumn('extract_date', function($row) {
            return $row->extract_date ?: '-';
        });

        $table->editColumn('return_date', function($row) {
            return $row->return_date ?: '-';
        });

        $table->rawColumns(['placeholder', 'user', 'items', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = UserInventory::query();

        $query = (new QueryBuilderService())->advancedQuery(
            UserInventory::class,
            [
                'conditions' => request()?->get('conditions') ?? '',
                'fields' => request()?->get('fields') ?? '',
                'operators' => request()?->get('operators') ?? '',
                'values' => request()?->get('values') ?? '',
            ],
            ['user', 'items']
        );

        $extracted_date_range = request()?->get('extract_date_range');
        $return_date_range = request()?->get('return_date_range');
        $user_name = request()?->get('user_name');
        $items = request()?->get('items');

        if(!empty($extracted_date_range)) {
            $range = str_replace(' a ', ' - ', $extracted_date_range);
            $range = explode(' - ', $range);

            $query->whereDate('extract_date', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('extract_date', '<=', $range[1]);
            }
        }

        if(!empty($return_date_range)) {
            $range = str_replace(' a ', ' - ', $return_date_range);
            $range = explode(' - ', $range);

            $query->whereDate('return_date', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('return_date', '<=', $range[1]);
            }
        }

        if($user_name) {
            $query->whereHas('user', function($query) use ($user_name) {
                $query->where('name', 'like', "%{$user_name}%");
            });
        }

        if($items) {
            $query->whereHas('items', function($query) use ($items) {
                $query->whereHas('item', function($query) use ($items) {
                    $query->where('reference', 'like', "%{$items}%");
                });
            });
        }

        return $query;
    }
}
