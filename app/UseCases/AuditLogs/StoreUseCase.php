<?php

namespace App\UseCases\AuditLogs;

use App\Models\AuditLog;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Illuminate\Database\Eloquent\Model;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected User $user,
        protected Model $model,
        protected string $event = 'created',
        protected array $data = []
    ) {
    }

    public function action(): AuditLog
    {
        $log = AuditLog::create([
            'user_id' => $this->user->id,
            'event' => $this->event,
            'auditable_type' => get_class($this->model),
            'auditable_id' => $this->model->id,
            'data' => $this->data
        ]);

        return $log;
    }
}
