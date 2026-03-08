<?php

namespace App\Services\Logging\Providers;

use App\Services\Logging\LoggerInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class LocalStorageLogger implements LoggerInterface
{
    private string $filePath;
    private int $maxEntries = 500;

    public function __construct()
    {
        $this->filePath = storage_path('logs/audit.json');

        if (!File::exists(dirname($this->filePath))) {
            File::makeDirectory(dirname($this->filePath), 0755, true);
        }

        if (!File::exists($this->filePath)) {
            File::put($this->filePath, json_encode([]));
        }
    }

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

        $entry = [
            'id' => uniqid(),
            'level' => $level,
            'event' => $event,
            'message' => $message,
            'user_type' => $user ? get_class($user) : null,
            'user_id' => $user ? $user->id : null,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'correlationId' => request()->header('X-Correlation-ID'),
            'timestamp' => now()->toIso8601String(),
            'metadata' => $metadata,
        ];

        $this->persist($entry);
    }

    private function persist(array $entry): void
    {
        $logs = json_decode(File::get($this->filePath), true) ?: [];

        array_unshift($logs, $entry);

        if (count($logs) > $this->maxEntries) {
            $logs = array_slice($logs, 0, $this->maxEntries);
        }

        File::put($this->filePath, json_encode($logs, JSON_PRETTY_PRINT));
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
