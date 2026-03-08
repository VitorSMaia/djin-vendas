<?php

namespace App\Models;

use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;
    use HasAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'sale_date',
        'total',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sale_date' => 'datetime',
    ];

    /**
     * Boot the model and attach audit logging.
     */
    protected static function booted(): void
    {
        static::created(function (self $sale): void {
            $sale->audit(
                'created',
                "Venda registrada: #{$sale->id}",
                self::class,
                $sale->id,
                [],
                $sale->getAttributes()
            );
        });

        static::updated(function (self $sale): void {
            $sale->audit(
                'updated',
                "Venda atualizada: #{$sale->id}",
                self::class,
                $sale->id,
                $sale->getOriginal(),
                $sale->getDirty()
            );
        });

        static::deleted(function (self $sale): void {
            $sale->audit(
                'deleted',
                "Venda excluída: #{$sale->id}",
                self::class,
                $sale->id,
                $sale->getOriginal(),
                []
            );
        });
    }

    /**
     * Get the user that owns the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the sale.
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
