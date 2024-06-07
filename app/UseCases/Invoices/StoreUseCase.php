<?php

namespace App\UseCases\Invoices;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Status;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected ?Project $project = null,
        protected Carbon $issue_date,
        protected float $amount,
        protected float $tax,
        protected float $total,
        protected ?Status $status = null,
        protected ?array $data = null,
        protected ?Client $client = null
    ) {
    }

    public function action(): Invoice
    {
        $number = 'INV-' . now()->format('YmdHis');

        $invoice = Invoice::create([
            'number' => $number,
            'project_id' => $this->project?->id,
            'issue_date' => $this->issue_date,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'total' => $this->total,
            'status_id' => $this->status?->id,
            'data' => $this->data,
            'client_id' => $this->client?->id
        ]);

        return $invoice;
    }
}
