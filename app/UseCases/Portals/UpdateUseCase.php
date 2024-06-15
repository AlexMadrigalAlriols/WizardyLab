<?php

namespace App\UseCases\Portals;

use App\Models\Portal;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Portal $portal,
        protected string $name,
        protected string $subdomain,
        protected array $data = []
    ) {
    }

    public function action(): Portal
    {
        $this->portal->update([
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'data' => array_merge($this->portal->data, $this->data)
        ]);

        return $this->portal;
    }
}
