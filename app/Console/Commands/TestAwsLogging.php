<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\CommunicationNotificationLog;
use App\Models\Product;
use App\Services\Logging\LoggerFactory;
use Illuminate\Console\Command;
use Str;

class TestAwsLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-aws-logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test logs to AWS CloudWatch to verify configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       // 1. Geramos o código único que rastreará todo o ciclo de vida
        $metaTraceCode = (string) Str::uuid();

        try {
            // 2. Criação do registro no DynamoDB
            CommunicationNotificationLog::create([
                // Chaves Primárias (Infra)
                'primary_key' => $metaTraceCode,
                'timestamp'          => 'INITIAL#' . now()->toIso8601String(),
                
                // Referência de Negócio (Opcional, mas útil para GSI)
                'proposal_id' => 1,

                // Bloco 'origin': Dados que não mudam (Quem/O quê)
                'origin' => [
                    'integrator_id'                 => 1,
                    'communication_notification_id' => 1,
                    'proposal_actor_id'             => null,
                ],

                // Bloco 'delivery': Estado atual do envio
                'delivery' => [
                    'type'    => 'queued',
                    'attempt' => 0,
                    'email'   => 'vitor.smaia1@gmail.com',
                ],

                // Bloco 'error_info': Usado aqui para auditoria do corpo inicial se necessário
                'error_info' => [
                    'email_body' => null, // Será preenchido no Job quando o HTML for renderizado
                ],

                // Time to Live: A AWS deletará este log automaticamente após 90 dias
                'ttl' => now()->addDays(90)->timestamp,
            ]);

            return self::SUCCESS;

        } catch (\Throwable $e) {
            // Logamos o erro no arquivo local caso o DynamoDB falhe
            \Illuminate\Support\Facades\Log::error("Falha ao registrar log no DynamoDB: " . $e->getMessage());
            throw $e;
        }
    }
}
