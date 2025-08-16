@extends('layouts.master2')

@section('content')
<div class="container my-4">

    <div class="card shadow rounded border-0">
        <div class="card-body">

            <!-- EN-TÊTE DE FACTURE DE VENTE -->
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="text-secondary"><i class="fas fa-receipt"></i> Facture de Vente</h3>
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code)
                            <div class="text-muted"><strong>Référence :</strong> {{ $facture->code }}</div>
                            @break
                        @endif
                    @endforeach
                </div>
                <div>
                    <span class="badge bg-secondary fs-6">Date : {{ date('d/m/Y', strtotime($date)) }}</span>
                </div>
            </div>

            <!-- INFO CLIENT / CAISSIER -->
            <div class="row mt-4">
                <div class="col-md-4">
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code)
                            <div class="card p-3 rounded border-0 shadow-sm">
                                <h5 class="text-secondary"><i class="fas fa-user"></i> Client & Caissier</h5>
                                <p class="mb-1"><strong>Client :</strong> {{ $facture->client_nom }}</p>
                                <p class="mb-1"><strong>Caissier :</strong> {{ $facture->user->name }}</p>
                            </div>
                            @break
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- TABLEAU DES PRODUITS VENDUS -->
            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered align-middle rounded">
                    <thead class="table-secondary">
                        <tr>
                            <th>Quantité</th>
                            <th>Produit</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($factures as $facture)
                            <tr>
                                <td>{{ $facture->quantite }}</td>
                                <td>{{ $facture->produit }}</td>
                                <td>{{ number_format($facture->prix, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($facture->total, 0, ',', ' ') }} CFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TOTALS DE LA FACTURE DE VENTE -->
            <div class="row justify-content-end mt-4">
                <div class="col-md-5">
                    <div class="card p-3 rounded border-0 shadow-sm">
                        <table class="table table-borderless m-0">
                            @foreach ($factures as $facture)
                                @if ($facture->date == $date && $facture->code == $code)
                                    <tr>
                                        <th class="text-secondary"><i class="fas fa-coins"></i> Total HT :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->totalHT, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary"><i class="fas fa-percent"></i> TVA :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->totalTVA, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary"><i class="fas fa-money-bill"></i> Total TTC :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->totalTTC, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-success"><i class="fas fa-cash-register"></i> Montant encaissé :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->montantPaye, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-danger"><i class="fas fa-hand-holding-usd"></i> Solde à encaissé :</th>
                                        <td><span class="fw-bold">
                                            @if ($facture->montantPaye > $facture->totalTTC) 
                                                0,00 CFA 
                                            @else 
                                                {{ number_format($facture->montantRendu, 2, ',', ' ') }} CFA
                                            @endif
                                        </span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-warning"><i class="fas fa-tags"></i> Remise :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->reduction, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary"><i class="fas fa-money-bill-wave"></i> Rendu :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->monnaie, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    <tr class="table-secondary rounded">
                                        <th class="text-dark"><strong><i class="fas fa-receipt"></i> Total réglé :</strong></th>
                                        <td><span class="fw-bold">{{ number_format($facture->montantFinal, 2, ',', ' ') }} CFA</span></td>
                                    </tr>
                                    @break
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <!-- BOUTONS ACTIONS -->
            <div class="text-end mt-3">
                @foreach ($factures as $facture)
                    @if ($facture->date == $date && $facture->code == $code)
                        <a href="{{ route('facture.impression', ['code' => $facture->code, 'date' => $facture->date]) }}" 
                           class="btn btn-success">
                           <i class="fas fa-print"></i> Imprimer
                        </a>
                        <a href="{{ route('facture.telecharger', ['code' => $facture->code, 'date' => $facture->date]) }}" 
                           class="btn btn-danger">
                           <i class="fas fa-download"></i> Télécharger PDF
                        </a>
                        @break
                    @endif
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection
