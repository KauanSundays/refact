<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Parcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'venda_id',
        'cliente_id',
        'valor',
        'data_vencimento',
    ];

    protected $dates = [
        'data_vencimento',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
