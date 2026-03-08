<?php

namespace App\Services\Logging\Providers;

use App\Models\AuditLog;
use App\Services\Logging\LoggerInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DynamoDbLogger implements LoggerInterface
{
    public function logAudit(
        string $event,
        string $message,
        ?string $auditableType = null,
        $auditableId = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = [],
        string $level = 'info'
    ): void {
        $user = Auth::user();

        try {
            AuditLog::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'level' => $level,
                'event' => $event,
                'message' => $message,
                'user_type' => $user ? get_class($user) : null,
                'user_id' => $user ? $user->id : null,
                'auditable_type' => $auditableType ?: 'System',
                'auditable_id' => $auditableId,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'correlationId' => request()->header('X-Correlation-ID'),
                'timestamp' => now()->toIso8601String(),
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            // Fallback to local log if DynamoDB writing fails
            Log::error("DynamoDB Audit Logging Failed: " . $e->getMessage());
        }
    }

    public function info(string $message, string $service, array $metadata = []): void
    {
        $this->logAudit('info', $message, $service, null, [], [], $metadata, 'info');
    }

    public function warn(string $message, string $service, array $metadata = []): void
    {
        $this->logAudit('warn', $message, $service, null, [], [], $metadata, 'warn');
    }

    public function error(string $message, string $service, array $metadata = []): void
    {
        $this->logAudit('error', $message, $service, null, [], [], $metadata, 'error');
    }

    public function debug(string $message, string $service, array $metadata = []): void
    {
        $this->logAudit('debug', $message, $service, null, [], [], $metadata, 'debug');
    }
}
