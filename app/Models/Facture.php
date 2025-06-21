<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
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

    public function client()
    {  
        return $this->belongsTo(client::class, 'client');
    }


}
