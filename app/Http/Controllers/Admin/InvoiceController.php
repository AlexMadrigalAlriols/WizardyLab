<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdvancedFiltersHelper;
use App\Helpers\ConfigurationHelper;
use App\Helpers\InvoiceHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\InvoicesDataTable;
use App\Http\Requests\Invoices\StoreRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Traits\MiddlewareTrait;
use App\UseCases\Invoices\StoreUseCase;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('invoice');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new InvoicesDataTable('invoices');
            return $dataTable->make();
        }

        $query = Invoice::query();
        $total = $query->count();
        $statuses = Status::where('morphable', Invoice::class)->get();
        $json_statuses = [['value' => '', 'label' => '-']];

        foreach ($statuses as $status) {
            $json_statuses[] = ['value' => $status->id, 'label' => $status->title];
        }

        $statuses = json_encode($json_statuses, JSON_UNESCAPED_UNICODE);
        $advancedFilters = AdvancedFiltersHelper::getFilters(Invoice::class);
        return view('dashboard.invoices.index', compact('total', 'statuses', 'advancedFilters'));
    }

    public function create()
    {
        $invoice = new Invoice();
        [$types, $statuses, $projects, $tasks, $clients] = $this->getData();

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

        return view('dashboard.invoices.create', compact(
            'invoice',
            'types',
            'statuses',
            'projects',
            'tasks',
            'clients',
            'inventories',
            'inventoryArray'
        ));
    }

    public function store(StoreRequest $request) {
        $project = null;
        $data = null;
        $client = null;

        if($request->input('type') === 'tasks') {
            $tasks = Task::whereIn('id', $request->input('tasks'))->get();
            $amount = InvoiceHelper::getTotalTasksAmount($tasks);
            $tax = ($amount * ConfigurationHelper::get('tax_value', 21)) / 100;
            $total = $amount + $tax;
            $data = ['tasks_ids' => $request->input('tasks')];
            $client = Client::find($request->input('client_id'));

        } elseif($request->input('type') === 'projects') {
            $project = Project::find($request->input('project_id'));
            $amounts = InvoiceHelper::generateProjectInvoice($project);
            $amount = $amounts['amount'];
            $tax = $amounts['tax'];
            $total = $amounts['total'];
            $data = [
                'tasks_ids' => $amounts['tasks_ids'],
                'items' => $amounts['items']
            ];
            $client = $project->client;
        } else {
            $amount = $request->input('amount');
            $tax = ($amount * ConfigurationHelper::get('tax_value', 21)) / 100;
            $total = $amount + $tax;
            $client = Client::find($request->input('client_id'));
            $data = ['items' => $request->input('items')];

            foreach ($request->input('items') as $dataItem) {
                if($dataItem['id'] && $item = Item::find($dataItem['id'])) {
                    $item->update(['stock' => $item->stock - $dataItem['qty']]);
                }
            }
        }

        if($amount === 0) {
            toast('No tasks hours to invoice', 'info');
            return back();
        }

        $invoice = (new StoreUseCase(
            $project,
            Carbon::parse($request->input('issue_date')),
            $amount,
            $tax,
            $total,
            Status::find($request->input('status_id')),
            $data,
            $client
        ))->action();

        toast('Invoice created', 'success');
        return redirect()->route('dashboard.invoices.index');
    }

    public function generateProjectInvoice(Request $request, Project $project)
    {
        $amounts = InvoiceHelper::generateProjectInvoice($project);

        if($amounts['amount'] === 0) {
            toast('No tasks hours to invoice', 'info');
            return back();
        }

        $invoice = (new StoreUseCase(
            $project,
            now(),
            $amounts['amount'],
            $amounts['tax'],
            $amounts['total'],
            Status::find(ConfigurationHelper::get('default_invoice_status')),
            ['tasks_ids' => $amounts['tasks_ids']]
        ))->action();

        toast('Invoice Created', 'success');
        return back();
    }

    public function downloadInvoice(Invoice $invoice)
    {
        $path = $invoice->file_path;

        if(!Storage::disk('public')->exists($path)) {
            $dompdf = new Dompdf();
            $logoPath = $invoice->portal->logo;
            $logoBase64 = base64_encode(file_get_contents($logoPath));
            $price_per_hour = ConfigurationHelper::get('price_per_hour', 15);
            $billingClient = Client::find(ConfigurationHelper::get('invoice_client_id'));

            $tasks = Task::whereIn('id', $invoice->data['tasks_ids'] ?? [])->get();
            $items = [];

            foreach ($invoice->data['items'] as $item) {
                $items[] = [
                    'name' => $item['name'],
                    'quantity' => $item['qty'],
                    'amount' => $item['amount'],
                    'total' => $item['qty'] * $item['amount']
                ];
            }

            // Generar la vista como HTML
            $html = view('templates.invoice', compact(
                'billingClient',
                'invoice',
                'logoBase64',
                'items',
                'tasks',
                'price_per_hour'
            ))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();

            Storage::disk('public')->put($path, $dompdf->output());
        }

        return Storage::disk('public')->download($path);
    }

    public function destroy(Invoice $invoice)
    {
        foreach ($invoice->data['tasks_ids'] ?? [] as $taskId) {
            $task = Task::find($taskId);
            $task->update(['status_id' => ConfigurationHelper::get('completed_task_status')]);
        }

        $invoice->delete();

        toast('Invoice deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Invoice::whereIn('id', $ids)->delete();

        toast('Invoices deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getData()
    {
        $types = [
            'tasks',
            'manual',
            'projects'
        ];
        $projects = Project::where('status_id', ConfigurationHelper::get('completed_project_status'))->get();
        $tasks = Task::where('status_id', ConfigurationHelper::get('completed_task_status'))->get();
        $statuses = Status::where('morphable', Invoice::class)->get();
        $clients = Client::where('active', 1)->get();

        return [$types, $statuses, $projects, $tasks, $clients];
    }
}
