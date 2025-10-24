<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    public const ESTADO_PENDENTE = 0;
    public const ESTADO_PAGO     = 1;
    public const ESTADO_ANULADO  = 2;

    protected $table = 'faturas';

    protected $fillable = [
        'user_id',
        'mes',
        'valor',
        'estado_pagamento',
        'numero_recibo',
        'referencia_pagamento',
        'data_emissao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'estado_pagamento' => 'integer',
        'data_emissao' => 'datetime',
    ];

    protected $attributes = [
        'valor' => 0.00,
    ];

    protected static function booted()
    {
        static::creating(function (Fatura $f) {
            if ($f->valor === null) {
                $f->valor = 0; // 👈 garante sempre no INSERT
            }
        });
    }

      public function getReferenciaPagamentoAttribute()
    {
        return $this->attributes['numero_recibo'] ?? null;
    }

     public function setReferenciaPagamentoAttribute($value)
    {
        // Mantém as duas colunas coerentes (enquanto existir a referida coluna)
        $this->attributes['numero_recibo'] = $value;
        $this->attributes['referencia_pagamento'] = $value;
    }
  
    public static function estadoLabel(int $v): string {
        return match($v) {
            self::ESTADO_PAGO    => 'Pago',
            self::ESTADO_ANULADO => 'Anulado',
            default              => 'Pendente',
        };
    }

    // Relação: uma fatura tem muitos itens
    public function itens()
    {
        return $this->hasMany(\App\Models\FaturaItem::class, 'fatura_id');
    }

    // Relação: uma fatura pertence a um utilizador
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(FaturaItem::class, 'fatura_id');
    }


    public function recalcularTotal(): void
    {
        $total = $this->items()
            ->selectRaw('COALESCE(SUM(COALESCE(total_linha, valor_unitario * quantidade * (1 + IFNULL(imposto_percentual,0)/100))), 0) AS soma')
            ->value('soma');

        $this->forceFill(['valor' => $total])->save();
    }
}
