<?php

namespace App\Services\Logging\Providers;

use App\Services\Logging\LoggerInterface;
use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CloudWatchLogger implements LoggerInterface
{
    private CloudWatchLogsClient $client;
    private string $groupName;
    private string $streamName;

    public function __construct()
    {
        $this->client = new CloudWatchLogsClient([
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);

        $this->groupName = env('CLOUDWATCH_LOG_GROUP', 'djin-vendas-logs');
        $this->streamName = env('CLOUDWATCH_LOG_STREAM', 'app-logs-' . now()->format('Y-m-d'));
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

        $logData = [
            'id' => bin2hex(random_bytes(8)),
            'timestamp_iso' => now()->toIso8601String(),
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
            'metadata' => $metadata,
        ];

        $this->sendToCloudWatch($logData);
    }

    private function sendToCloudWatch(array $data): void
    {
        $retryCount = 0;
        $maxRetries = 3;

        while ($retryCount < $maxRetries) {
            try {
                $this->ensureGroupAndStreamExists();

                $params = [
                    'logGroupName' => $this->groupName,
                    'logStreamName' => $this->streamName,
                    'logEvents' => [
                        [
                            'timestamp' => round(microtime(true) * 1000),
                            'message' => json_encode($data),
                        ],
                    ],
                ];

                // CloudWatch v3 SDK putLogEvents handles sequenceToken automatically in some contexts, 
                // but for manual putLogEvents we usually need to fetch it.
                $streamInfo = $this->client->describeLogStreams([
                    'logGroupName' => $this->groupName,
                    'logStreamNamePrefix' => $this->streamName,
                ]);

                if (!empty($streamInfo['logStreams'][0]['uploadSequenceToken'])) {
                    $params['sequenceToken'] = $streamInfo['logStreams'][0]['uploadSequenceToken'];
                }

                $this->client->putLogEvents($params);
                return;

            } catch (AwsException $e) {
                if ($e->getAwsErrorCode() === 'InvalidSequenceTokenException' || $e->getAwsErrorCode() === 'DataAlreadyAcceptedException') {
                    $retryCount++;
                    continue;
                }

                Log::error("CloudWatch Logging Critical Error: " . $e->getMessage());
                break;
            } catch (\Exception $e) {
                Log::error("CloudWatch Generic Error: " . $e->getMessage());
                break;
            }
        }
    }

    private function ensureGroupAndStreamExists(): void
    {
        try {
            $this->client->createLogGroup(['logGroupName' => $this->groupName]);
        } catch (AwsException $e) {
            if ($e->getAwsErrorCode() !== 'ResourceAlreadyExistsException')
                throw $e;
        }

        try {
            $this->client->createLogStream([
                'logGroupName' => $this->groupName,
                'logStreamName' => $this->streamName
            ]);
        } catch (AwsException $e) {
            if ($e->getAwsErrorCode() !== 'ResourceAlreadyExistsException')
                throw $e;
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
