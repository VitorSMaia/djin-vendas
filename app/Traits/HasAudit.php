<?php

namespace App\Traits;

use App\Services\Logging\LoggerFactory;
use Illuminate\Support\Facades\Auth;

trait HasAudit
{
    /**
     * Helper to log an audit event.
     */
    protected function audit(
        string $event,
        string $message,
        ?string $auditableType = null,
        $auditableId = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = []
    ): void {
        LoggerFactory::create()->logAudit(
            $event,
            $message,
            $auditableType,
            $auditableId,
            $oldValues,
            $newValues,
            $metadata
        );
    }

    /**
     * Auto-audit for model changes (generic helper).
     */
    protected function auditModelChange($model, string $event, array $old = [], array $new = []): void
    {
        $this->audit(
            $event,
            "Model " . class_basename($model) . " was {$event}",
            get_class($model),
            $model->getKey(),
            $old,
            $new
        );
    }
}
