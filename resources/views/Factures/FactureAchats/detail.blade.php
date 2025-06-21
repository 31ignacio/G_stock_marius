@extends('layouts.master2')

@section('content')
<div class="container my-4">

    <div class="card shadow rounded border-0">
        <div class="card-body">
            <!-- EN-TÊTE DE FACTURE -->
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="text-success">
                        <i class="fas fa-receipt"></i> Facture d'achat
                    </h3>
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code)
                            <div class="text-muted">
                                <strong>Ref :</strong> {{ $facture->code }}
                            </div>
                            @break
                        @endif
                    @endforeach
                </div>
                <div>
                    <span class="badge bg-success fs-6">Date : {{ date('d/m/Y', strtotime($date)) }}</span>
                </div>
            </div>

            <!-- INFO SOCIÉTÉ -->
            <div class="row mt-4">
                <div class="col-md-4">
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code)
                            <div class="card p-3 rounded border-0 shadow-sm">
                                <h5 class="text-success"><i class="fas fa-building"></i> Société</h5>
                                <p class="mb-1"><strong>Raison sociale :</strong> {{ $facture->societe->societe }}</p>
                                <p class="mb-1"><strong>Type :</strong> 
                                    @if($facture->produitType_id == 1)
                                        <span class="badge bg-primary">Poissonnerie</span>
                                    @else
                                        <span class="badge bg-secondary">Divers</span>
                                    @endif
                                </p>
                                <p><strong>Comptable :</strong> <i class="fas fa-user"></i> {{ $facture->user->name }}</p>
                            </div>
                            @break
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- TABLEAU DES PRODUITS -->
            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered align-middle rounded">
                    <thead class="table-success">
                        <tr>
                            <th>Quantité</th>
                            <th>Produits</th>
                            <th>Prix d'achat</th>
                            <th>Prix de vente</th>
                            <th>Total</th>
                            <th>Bénéfice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($factures as $facture)
                            <tr>
                                <td>{{ $facture->quantite }}</td>
                                <td>{{ $facture->produit }}</td>
                                <td>{{ number_format($facture->prix, 2) }} CFA</td>
                                <td>{{ number_format($facture->prixVente, 2) }} CFA</td>
                                <td>{{ number_format($facture->total, 2) }} CFA</td>
                                <td>{{ number_format($facture->benefice, 2) }} CFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TOTALS DE LA FACTURE -->
            <div class="row justify-content-end mt-4">
                <div class="col-md-5">
                    <div class="card p-3 rounded border-0 shadow-sm">
                        <table class="table table-borderless m-0">
                            @foreach ($factures as $facture)
                                @if ($facture->date == $date && $facture->code == $code)
                                    <tr>
                                        <th class="text-success"><i class="fas fa-coins"></i> Total d'achat :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->totalAchat, 2) }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-success"><i class="fas fa-money-bill"></i> Total prix de vente :</th>
                                        <td><span class="fw-bold">{{ number_format($facture->totalVente, 2) }} CFA</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-success"><i class="fas fa-chart-line"></i> Total Bénéfice :</th>
                                        <td><span class="fw-bold text-success">{{ number_format($facture->totalBenefice, 2) }} CFA</span></td>
                                    </tr>
                                    @break
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <!-- BOUTON DE TÉLÉCHARGEMENT PDF -->
            <div class="text-end mt-3">
                @foreach ($factures as $facture)
                    @if ($facture->date == $date && $facture->code == $code)
                        <a href="{{ route('factureAchat.telecharger', ['code' => $facture->code, 'date' => $facture->date]) }}" 
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
