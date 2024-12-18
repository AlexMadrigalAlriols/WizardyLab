<?php

namespace App\Http\DataTables;

use App\Models\DeliveryNote;
use App\Services\QueryBuilderService;

class DeliveryNotesDataTable extends DataTable
{

    public function __construct(protected ?string $name = 'deliveryNotes') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'deliveryNotes';
            $model = 'deliveryNote';
            $viewGate = false;
            $editGate = false;
            $deleteGate = 'deliveryNote_delete';
            $links = [
                [
                    'href' => route('dashboard.deliveryNotes.download', $row->id),
                    'icon' => 'bx bx-download',
                    'text' => 'Download'
                ]
            ];

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate',
                'links'
            ));
        });

        $table->editColumn('number', function($row) {
            return $row->number ?: '';
        });

        $table->editColumn('client', function($row) {
            return $row->client->name ?? ($row->project->client->name ?? '');
        });

        $table->editColumn('issue_date', function($row) {
            return $row->issue_date ?: '';
        });

        $table->editColumn('total', function($row) {
            return $row->total ? $row->total . ' ' . $row->client?->currency?->symbol : '';
        });

        $table->rawColumns(['placeholder', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = DeliveryNote::with(['client']);

        $issue_date_range = request()?->get('issue_date_range');
        $client_name = request()?->get('client_name');
        $total_min = request()?->get('total_min');
        $total_max = request()?->get('total_max');

        $query = (new QueryBuilderService())->advancedQuery(
            DeliveryNote::class,
            [
                'conditions' => request()?->get('conditions') ?? '',
                'fields' => request()?->get('fields') ?? '',
                'operators' => request()?->get('operators') ?? '',
                'values' => request()?->get('values') ?? '',
            ],
            ['client']
        );

        if(!empty($issue_date_range)) {
            $range = str_replace(' a ', ' - ', $issue_date_range);
            $range = explode(' - ', $range);

            $query->whereDate('issue_date', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('issue_date', '<=', $range[1]);
            }
        }

        if(!empty($client_name)) {
            $query->whereHas('client', function($q) use ($client_name) {
                $q->where('name', 'like', '%' . $client_name . '%');
            });
        }

        if(!empty($total_min)) {
            $query->where('total', '>=', $total_min);
        }

        if(!empty($total_max)) {
            $query->where('total', '<=', $total_max);
        }

        return $query->select('delivery_notes.*');
    }
}
