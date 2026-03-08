<?php

namespace App\Services\Logging;

interface LoggerInterface
{
    /**
     * Log an audit event with detailed context.
     *
     * @param string $event The type of action: created, updated, deleted, restored, or custom.
     * @param string $message A descriptive message of the event.
     * @param string|null $auditableType The Model that was altered (ex: App\Models\Product).
     * @param int|string|null $auditableId The ID of the record altered.
     * @param array $oldValues State of findings before alteration.
     * @param array $newValues State of findings after alteration.
     * @param array $metadata Additional free-form information.
     * @param string $level Log level: info, warn, error, debug.
     */
    public function logAudit(
        string $event,
        string $message,
        ?string $auditableType = null,
        $auditableId = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = [],
        string $level = 'info'
    ): void;

    /**
     * Legacy support/Simple logging methods.
     */
    public function info(string $message, string $service, array $metadata = []): void;
    public function warn(string $message, string $service, array $metadata = []): void;
    public function error(string $message, string $service, array $metadata = []): void;
    public function debug(string $message, string $service, array $metadata = []): void;
}
