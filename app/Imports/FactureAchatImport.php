<?php

namespace App\Imports;

use App\Models\FactureAchat;
use Maatwebsite\Excel\Concerns\ToModel;

class FactureAchatImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new FactureAchat([
            //
        ]);
    }
}
