<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProduitType;
use App\Models\User;

class StockAttente extends Model
{
    use HasFactory;

    // Protège tous les champs sauf ceux explicitement autorisés (si tu veux tout autoriser, utilise [])
    protected $guarded = [''];

    // Relation avec ProduitType (clé étrangère : produitType_id)
    public function produitType()
    {
        return $this->belongsTo(ProduitType::class, 'produitType_id');
    }

    // Relation avec User (clé étrangère : user_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
