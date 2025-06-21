<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SortieStockExport implements FromView
{
    public $factures;
    public $dateDebut;
    public $dateFin;

    public function __construct($factures, $dateDebut, $dateFin)
    {
        $this->factures = $factures;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function view(): View
    {
        return view('Stocks.sortie_excel', [
            'factures' => $this->factures,
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
        ]);
    }
}

