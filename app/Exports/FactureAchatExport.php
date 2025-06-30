<?php

namespace App\Exports;

use App\Models\FactureAchat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FactureAchatExport implements FromView
{
    protected $dateDebut;
    protected $dateFin;
    protected $societeId;

    public function __construct($dateDebut, $dateFin, $societeId = null)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->societeId = $societeId;
    }

    public function view(): View
    {
        $query = FactureAchat::with(['user','societe'])
            ->whereBetween('date', [$this->dateDebut, $this->dateFin]);

        if ($this->societeId) {
            $query->where('societe_id', $this->societeId);
        }

        $factureAchats = $query->get();

        return view('Factures.FactureAchats.facture_achat_excel', [
            'factureAchats' => $factureAchats,
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
        ]);
    }
}
