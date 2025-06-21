@extends('layouts.master2')

@section('content')

<section class="content">
    <div class="container-fluid">

        {{-- Top Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow border-0 text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Clients</h6>
                            <h2 class="font-weight-bold">{{ $nombreClient }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                    <div class="card-footer text-white text-center border-0">
                        <a href="{{ route('client.index') }}" class="text-white">Voir tous <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0 text-white" style="background: linear-gradient(135deg, #43cea2, #185a9d);">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Factures</h6>
                            <h2 class="font-weight-bold">+</h2>
                        </div>
                        <i class="fas fa-file-invoice-dollar fa-3x opacity-75"></i>
                    </div>
                    <div class="card-footer text-white text-center border-0">
                        <a href="{{ route('facture.create') }}" class="text-white">Nouvelle facture <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0 text-white" style="background: linear-gradient(135deg, #ff9966, #ff5e62);">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Stock</h6>
                            <h2 class="font-weight-bold">+</h2>
                        </div>
                        <i class="fas fa-boxes fa-3x opacity-75"></i>
                    </div>
                    <div class="card-footer text-white text-center border-0">
                        <a href="{{ route('stock.index') }}" class="text-white">Voir le stock <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0 text-white" style="background: linear-gradient(135deg, #4e54c8, #8f94fb);">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Ventes</h6>
                            <h2 class="font-weight-bold">{{ $totalMontantFinal }} CFA</h2>
                        </div>
                        <i class="fas fa-coins fa-3x opacity-75"></i>
                    </div>
                    <div class="card-footer text-white text-center border-0">
                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                            <a href="{{ route('facture.index') }}" class="text-white">Voir les ventes <i class="fas fa-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Résumé du jour --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-white shadow-sm border-left-success">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ventes Poissonnerie</h6>
                            <h4 class="text-success">{{ number_format($sommeMontantPoissonnerie, 0, ',', '.') }} CFA</h4>
                        </div>
                        <i class="fas fa-fish fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-white shadow-sm border-left-warning">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ventes Divers</h6>
                            <h4 class="text-warning">{{ number_format($sommeMontant, 0, ',', '.') }} CFA</h4>
                        </div>
                        <i class="fas fa-store fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau des ventes --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-list-alt"></i> Ventes du jour</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="thead-white">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Total TTC</th>
                            <th>Montant Reçu</th>
                            <th>Reliquat</th>
                            <th>Caissier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facturesAujourdhui as $facture)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $facture->client_nom }}</td>
                                <td>
                                    @if($facture->produitType_id == 1)
                                        <span class="badge badge-success">Poissonnerie</span>
                                    @else
                                        <span class="badge badge-warning text-dark">Divers</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y', strtotime($facture->date)) }}</td>
                                <td>{{ number_format($facture->montantFinal, 0, ',', '.') }} CFA</td>
                                <td>{{ number_format($facture->montantPaye, 0, ',', '.') }} CFA</td>
                                <td>{{ number_format($facture->montantPaye - $facture->montantFinal, 0, ',', '.') }} CFA</td>
                                <td><strong>{{ $facture->user->name }}</strong></td>
                                <td>
                                    <a href="{{ route('facture.details', ['code' => $facture->code, 'date' => $facture->date]) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                    @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                        <a href="#" class="btn btn-outline-danger btn-sm m-2" data-toggle="modal"
                                            data-target="#confirmationModal"
                                            onclick="updateModal('{{ $facture->code }}')">
                                            <i class="fas fa-times-circle"></i> Annuler
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>




     {{-- Modal pour annuler une facture --}}

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <form method="get" action="{{ route('facture.annuler') }}">

                    @csrf

                    <div class="modal-body">

                        Voulez-vous annuler cette facture ?

                    </div>

                    <input type="hidden" id="factureCode" name="factureCode">

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>

                        <button type="submit" class="btn btn-danger">Oui</button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function updateModal(code) {

            // Mettez à jour le contenu du span avec le code spécifique

            document.getElementById('factureCode').value = code;

        }
    </script>

    <style>
        @keyframes blink {
            50% {
                opacity: 0;
            }
        }

        .blink {
            animation: blink 1s infinite;
        }

    </style>

@endsection
