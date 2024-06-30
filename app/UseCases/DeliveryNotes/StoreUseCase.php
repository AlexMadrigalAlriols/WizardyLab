<?php

namespace App\UseCases\DeliveryNotes;

use App\Models\Client;
use App\Models\DeliveryNote;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Client $client,
        protected Carbon $issue_date,
        protected float $amount,
        protected float $tax,
        protected float $total,
        protected ?array $data = null
    ) {
    }

    public function action(): DeliveryNote
    {
        $number = 'ALB-' . now()->format('YmdHis');

        $deliveryNote = DeliveryNote::create([
            'number' => $number,
            'issue_date' => $this->issue_date,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'total' => $this->total,
            'data' => $this->data,
            'client_id' => $this->client->id
        ]);

        return $deliveryNote;
    }
}
