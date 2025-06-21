<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureAchat extends Model
{
    use HasFactory;

         protected $guarded = [''];

    public function produitType()
    {
        return $this->belongsTo(produitType::class, 'produitType_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'societe_id');
    }

}
