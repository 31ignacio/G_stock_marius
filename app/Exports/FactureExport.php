<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FactureExport implements FromCollection, WithHeadings
{
    protected $factures;

    public function __construct($factures)
    {
        $this->factures = $factures;
    }

    public function collection()
    {
        return $this->factures->map(function($facture) {
            return [
                $facture->client_nom,
                date('d/m/Y', strtotime($facture->date)),
                $facture->totalTTC,
                $facture->montantPaye,
                $facture->montantRendu,
                $facture->montantFinal,
                $facture->produitType_id == 1 ? 'POISSONNERIE' : 'DIVERS',
                $facture->user->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Client',
            'Date',
            'Total TTC',
            'Encaissé',
            'Reliquat',
            'Montant Final',
            'Type',
            'Caissier',
        ];
    }
}
