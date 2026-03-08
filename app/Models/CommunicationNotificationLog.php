<?php

namespace App\Models;

use Kitar\Dynamodb\Model\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunicationNotificationLog extends Model
{
    protected $connection = 'dynamodb';
    protected $table = 'CommunicationNotificationLogs';

    /**
     * Chaves Primárias
     * Importante: Se na sua tabela AWS a Sort Key ainda se chamar 'auditable_type',
     * você deve renomear 'sk' para 'auditable_type' abaixo ou recriar a tabela.
     */
    protected $primaryKey = 'primary_key';
    protected $sortKey = 'timestamp';

    /**
     * O DynamoDB não é auto-increment. 
     * O UUID será controlado manualmente no Command/Service.
     */
    public $incrementing = false;

    protected $fillable = [
        'primary_key',     // UUID (meta_trace_code)
        'timestamp',              // Linha do tempo: PREFIXO#TIMESTAMP
        'proposal_id',     // ID da proposta para busca rápida via GSI
        'origin',          // Map: integrator_id, notification_id, actor_id
        'delivery',        // Map: type, attempt, email
        'aws_tracking',    // Map: event_type, message_id, payload
        'error_info',      // Map: message, email_body
        'ttl'              // Timestamp Unix para deleção automática pela AWS
    ];

    /**
     * Casts para garantir que o Laravel trate os Maps como arrays/JSON
     */
    protected $casts = [
        'origin'       => 'array',
        'delivery'     => 'array',
        'aws_tracking' => 'array',
        'error_info'   => 'array',
        'ttl'          => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos (Referenciais)
    |--------------------------------------------------------------------------
    */

    // public function proposal(): BelongsTo
    // {
    //     return $this->belongsTo(Proposal::class, 'proposal_id');
    // }

    // public function integrator(): BelongsTo
    // {
    //     // Acesso ao dado aninhado no Map 'origin'
    //     return $this->belongsTo(Integrator::class, 'origin->integrator_id');
    // }

    // public function template(): BelongsTo
    // {
    //     return $this->belongsTo(CommunicationNotificationTemplate::class, 'origin->communication_notification_id');
    // }
}