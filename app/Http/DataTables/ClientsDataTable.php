<?php

namespace App\Http\DataTables;

use App\Models\Client;

class ClientsDataTable extends DataTable
{

    public function __construct(protected ?string $name = 'clients') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'clients';
            $model = 'client';
            $viewGate = false;
            $editGate = 'client_edit';
            $deleteGate = 'client_delete';

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate'
            ));
        });

        $table->editColumn('name', function($row) {
            return $row->name ?: '';
        });

        $table->editColumn('email', function($row) {
            return $row->email ?: '';
        });

        $table->editColumn('phone', function($row) {
            return $row->phone ?: '-';
        });

        $table->editColumn('active', function($row) {
            return $row->active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
        });

        $table->editColumn('created_at', function($row) {
            return $row->created_at ?: '';
        });

        $table->rawColumns(['placeholder', 'active', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Client::query();

        $email = request()?->get('email');
        $created_at_range = request()?->get('created_at_range');

        if($email) {
            $query->where('email', 'like', "%{$email}%");
        }

        if(!empty($created_at_range)) {
            $range = str_replace(' a ', ' - ', $created_at_range);
            $range = explode(' - ', $range);

            $query->whereDate('created_at', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('created_at', '<=', $range[1]);
            }
        }

        return $query;
    }
}
