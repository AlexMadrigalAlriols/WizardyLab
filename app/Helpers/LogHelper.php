<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogHelper {
    public static function saveLogAction(Model $model, string $action, string $message = '', $data = []): AuditLog
    {
        if(!in_array($action, \App\Models\AuditLog::ACTIONS)) {
            throw new \Exception('Invalid event');
        }

        if(!in_array(get_class($model), \App\Models\AuditLog::MORPHABLES)) {
            throw new \Exception('Invalid model');
        }

        $log = new \App\UseCases\AuditLogs\StoreUseCase(
            Auth::user(),
            $model,
            $action,
            array_merge([
                'message' => $message
            ], $data)
        );
        return $log->action();
    }
}
