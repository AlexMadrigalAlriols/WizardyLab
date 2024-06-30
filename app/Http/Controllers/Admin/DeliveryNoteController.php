<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdvancedFiltersHelper;
use App\Helpers\ConfigurationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\DeliveryNotesDataTable;
use App\Http\Requests\DeliveryNotes\StoreRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\Client;
use App\Models\DeliveryNote;
use App\Models\Item;
use App\Models\Status;
use App\Models\Task;
use App\Traits\MiddlewareTrait;
use App\UseCases\DeliveryNotes\StoreUseCase;
use App\UseCases\Invoices\StoreUseCase as InvoicesStoreUseCase;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DeliveryNoteController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('deliveryNote');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new DeliveryNotesDataTable('deliveryNotes');
            return $dataTable->make();
        }

        $query = DeliveryNote::query();
        $total = $query->count();
        $advancedFilters = AdvancedFiltersHelper::getFilters(DeliveryNote::class);

        return view('dashboard.deliveryNotes.index', compact('total', 'advancedFilters'));
    }

    public function create()
    {
        $deliveryNote = new DeliveryNote();
        [$types, $clients] = $this->getData();

        $inventories = Item::all();
        $inventories = $inventories->filter(function ($item) {
            return $item->remaining_stock > 0;
        });
        foreach ($inventories ?? [] as $item) {
            $item->remaining_stock = $item->remaining_stock;
            $item->cover = $item->cover;
            $item->name = $item->name . ' (' . $item->reference . ')';
        }
        $inventoryArray = array_values(array_filter($inventories->toArray()));

        return view('dashboard.deliveryNotes.create', compact(
            'deliveryNote',
            'types',
            'clients',
            'inventories',
            'inventoryArray'
        ));
    }

    public function store(StoreRequest $request) {
        $data = null;
        $client = null;

        $amount = $request->input('amount');
        $tax = ($amount * ConfigurationHelper::get('tax_value', 21)) / 100;
        $total = $amount + $tax;
        $client = Client::find($request->input('client_id'));
        $data = ['items' => $request->input('items'), 'type' => $request->input('type'), 'notes' => $request->input('notes')];

        if($request->input('substract_stock') && !$request->input('generate_invoice')) {
            foreach ($request->input('items') as $dataItem) {
                if($dataItem['id'] && $item = Item::find($dataItem['id'])) {
                    $item->update(['stock' => $item->stock - $dataItem['qty']]);
                }
            }
        }

        $deliveryNote = (new StoreUseCase(
            $client,
            Carbon::parse($request->input('issue_date')),
            $amount,
            $tax,
            $total,
            $data
        ))->action();

        if($request->input('generate_invoice')) {
            foreach ($request->input('items') as $dataItem) {
                if($dataItem['id'] && $item = Item::find($dataItem['id'])) {
                    $item->update(['stock' => $item->stock - $dataItem['qty']]);
                }
            }

            $invoice = (new InvoicesStoreUseCase(
                null,
                Carbon::parse($request->input('issue_date')),
                $amount,
                $tax,
                $total,
                Status::find(ConfigurationHelper::get('default_invoice_status')),
                $data,
                $client,
            ))->action();
        }

        toast('Delivery Note created', 'success');
        return redirect()->route('dashboard.deliveryNotes.index');
    }

    public function download(DeliveryNote $deliveryNote)
    {
        $path = $deliveryNote->file_path;

        if(!Storage::disk('public')->exists($path)) {
            $dompdf = new Dompdf();
            $logoPath = $deliveryNote->portal->logo;
            $logoBase64 = base64_encode(file_get_contents($logoPath));
            $price_per_hour = ConfigurationHelper::get('price_per_hour', 15);
            $billingClient = Client::find(ConfigurationHelper::get('invoice_client_id'));

            $items = [];
            foreach ($deliveryNote->data['items'] as $item) {
                $items[] = [
                    'name' => $item['name'],
                    'quantity' => $item['qty'],
                    'amount' => $item['amount'],
                    'total' => $item['qty'] * $item['amount']
                ];
            }

            // Generar la vista como HTML
            $html = view('templates.deliveryNote', compact(
                'billingClient',
                'deliveryNote',
                'logoBase64',
                'items',
                'price_per_hour'
            ))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();

            Storage::disk('public')->put($path, $dompdf->output());
        }

        return Storage::disk('public')->download($path);
    }

    public function destroy(DeliveryNote $deliveryNote)
    {
        $deliveryNote->delete();

        toast('Delivery Note deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        DeliveryNote::whereIn('id', $ids)->delete();

        toast('Delivery Notes deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getData()
    {
        $types = [
            'valued',
            'non-valued'
        ];
        $clients = Client::where('active', 1)->get();

        return [$types, $clients];
    }
}
