<?php

namespace App\Exports;

use App\Models\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StockPoissonnerieExport implements FromView
{
    protected $dateDebut;
    protected $dateFin;
    protected $libelle;

    public function __construct($dateDebut, $dateFin, $libelle = null)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->libelle = $libelle;
    }

    public function view(): View
    {
        $query = Stock::query()
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('produitType_id', 1);

        if ($this->libelle) {
            $query->where('libelle', $this->libelle);
        }

        $stocks = $query
            ->selectRaw('date, libelle, SUM(quantite) as total_quantite, user_id')
            ->groupBy('date', 'libelle', 'user_id')
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();

        return view('Stocks.entreePoissonnerieexcel', [
            'stocks' => $stocks,
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
        ]);
    }
}

