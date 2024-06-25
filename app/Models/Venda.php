<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'parcelas'];

    // Relação com o modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relação muitos para muitos com o modelo Produto
    public function produtos()
    {
        return $this->belongsToMany(Produto::class)->withPivot('quantidade');
    }
}
