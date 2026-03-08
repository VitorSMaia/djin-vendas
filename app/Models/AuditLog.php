<?php

namespace App\Models;

use Kitar\Dynamodb\Model\Model;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    protected $connection = 'dynamodb';
    protected $table = 'AuditLogs';

    /**
     * No Single Table Design, a Primary Key é Composta:
     * Partition Key (PK) = auditable_type
     * Sort Key (SK) = timestamp
     */
    protected $primaryKey = 'id';
    protected $sortKey = 'timestamp';

    protected $fillable = [
        'id',             // UUID único para identificação interna
        'level',
        'event',
        'message',
        'user_type',
        'user_id',
        'auditable_type', // Agora atua como Partition Key
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'correlationId',
        'timestamp',      // Agora atua como Sort Key
        'metadata',
    ];

    public $timestamps = false;

    /**
     * Boot do Model para garantir que o ID e o Timestamp 
     * sejam gerados automaticamente ao criar um log.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->timestamp)) {
                $model->timestamp = now()->toIso8601String();
            }
        });
    }
}