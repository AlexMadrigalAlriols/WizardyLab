<?php

namespace App\Http\DataTables;

use App\Models\Client;
use App\Models\Item;

class ItemsDataTable extends DataTable
{

    public function __construct(protected ?string $name = 'items') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'items';
            $model = 'item';
            $viewGate = 'item_show';
            $editGate = 'item_edit';
            $deleteGate = 'item_delete';

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate'
            ));
        });

        $table->editColumn('image', function($row) {
            return $row->cover ? '<img src="' . $row->cover . '" style="width: 75px; aspect-ratio: 1 / 1; object-fit: cover;" class="img-thumbnail" />' : '';
        });

        $table->editColumn('name', function($row) {
            return $row->name ?: '';
        });

        $table->editColumn('reference', function($row) {
            return $row->reference ?: '-';
        });

        $table->editColumn('stock', function($row) {
            return $row->stock ?: '0';
        });

        $table->editColumn('created_at', function($row) {
            return $row->created_at ?: '';
        });

        $table->rawColumns(['placeholder', 'image', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Item::query();

        $created_at_range = request()?->get('created_at_range');
        $stock_min = request()?->get('stock_min');
        $stock_max = request()?->get('stock_max');

        if(!empty($created_at_range)) {
            $range = str_replace(' a ', ' - ', $created_at_range);
            $range = explode(' - ', $range);

            $query->whereDate('created_at', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('created_at', '<=', $range[1]);
            }
        }

        if($stock_min) {
            $query->where('stock', '>=', $stock_min);
        }

        if($stock_max) {
            $query->where('stock', '<=', $stock_max);
        }

        return $query;
    }
}
