<?php

namespace App\UseCases\Clients;

use App\Models\Client;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Client $client,
        protected string $name,
        protected bool $active = false,
        protected ?Company $company = null,
        protected string $email,
        protected Currency $currency,
        protected ?string $phone = null,
        protected ?string $vat_number = null,
        protected ?Language $language = null,
        protected ?string $address = null,
        protected ?string $city = null,
        protected ?string $zip_code = null,
        protected ?Country $country = null,
        protected ?string $state = null,
    ) {
    }

    public function action(): Client
    {
        $this->client->update([
            'name' => $this->name,
            'active' => $this->active,
            'company_id' => $this->company?->id,
            'email' => $this->email,
            'phone' => $this->phone,
            'vat_number' => $this->vat_number,
            'language_id' => $this->language?->id,
            'currency_id' => $this->currency->id,
            'address' => $this->address,
            'city' => $this->city,
            'zip' => $this->zip_code,
            'country_id' => $this->country?->id,
            'state' => $this->state,
        ]);

        return $this->client;
    }
}
