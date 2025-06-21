<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grosProduit extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function produitType()
    {
        return $this->belongsTo(produitType::class, 'produitType_id');
    }
}
