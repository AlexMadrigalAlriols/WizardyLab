<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ConfigurationHelper;
use App\Helpers\InvoiceHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\StoreRequest;
use App\Models\Client;
use App\Models\GlobalConfiguration;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\UseCases\Invoices\StoreUseCase;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Invoice::class);

        $invoices = $query->get();
        $total = $query->count();

        return view('dashboard.invoices.index', compact('invoices', 'total', 'pagination'));
    }

    public function create()
    {
        $invoice = new Invoice();
        [$types, $statuses, $projects, $tasks, $clients] = $this->getData();

        return view('dashboard.invoices.create', compact('invoice', 'types', 'statuses', 'projects', 'tasks', 'clients'));
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
            $data = ['tasks_ids' => $amounts['tasks_ids']];
            $client = $project->client;
        } else {
            $amount = $request->input('amount');
            $tax = ($amount * ConfigurationHelper::get('tax_value', 21)) / 100;
            $total = $amount + $tax;
            $client = Client::find($request->input('client_id'));
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
        $dompdf = new Dompdf();
        $logoPath = public_path('img/LogoLetters.png');
        $logoBase64 = base64_encode(file_get_contents($logoPath));
        $price_per_hour = ConfigurationHelper::get('price_per_hour', 15);

        $tasks = Task::whereIn('id', $invoice->data['tasks_ids'])->get();

        // Generar la vista como HTML
        $html = view('templates.invoice', compact('invoice', 'logoBase64', 'tasks', 'price_per_hour'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->stream($invoice->number . '.pdf', ['Attachment' => true]);
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
