<?php

namespace App\Helpers;

use App\Models\AuditLog;

class AuditHelper
{
    public static function log(
        $action,
        $model,
        $description = null
    ) {

        AuditLog::create([

            'user_id' => auth()->id(),

            'action' => $action,

            'model_type' => get_class($model),

            'model_id' => $model->id,

            'ip_address' => request()->ip(),

            'description' => $description
        ]);
    }
}