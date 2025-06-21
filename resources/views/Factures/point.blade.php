@extends('layouts.master2')
@section('content')
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-white shadow-sm border-left-success">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Ventes Poissonnerie</h6>
                                <h4 class="text-success">{{ number_format($sommeMontantHierPoissonnerie, 0, ',', '.') }} CFA</h4>
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
                                <h4 class="text-warning">{{ number_format($sommeMontantHierDivers, 0, ',', '.') }} CFA</h4>
                            </div>
                            <i class="fas fa-store fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Les ventes d'hier</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Total TTC</th>
                                        <th>Montant Perçu</th>
                                        <th>Reliquat</th>
                                        <th>Type</th>
                                        <th>Caissier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($facturesHier as $factureUnique)
                                        <tr>
                                            <td>{{ $factureUnique->client_nom }}</td>

                                            <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>

                                            <td>{{ $factureUnique->montantFinal }}</td>

                                            <td>{{ $factureUnique->montantPaye }}</td>

                                            <td>
                                                <span><b>{{ $factureUnique->montantPaye - $factureUnique->montantFinal }}</b></span>

                                            </td>
                                            <td>
                                                @if($factureUnique->produitType_id == 1)
                                                    <span class="text-success">Poissonnerie</span>
                                                @else
                                                    <span class="text-warning">Divers</span>
                                                @endif
                                            </td>

                                                <td><b>{{ $factureUnique->user->name }}</b></td>
                                            <td>
                                                <a href="{{ route('facture.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                                    class="btn btn-sm btn-outline-primary m-2"><i class="fas fa-eye"></i> Détails
                                                </a>

                                                @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                                    <a href="#" class="btn btn-sm btn-outline-danger m-2" data-toggle="modal"
                                                        data-target="#confirmationModal"
                                                        onclick="updateModal('{{ $factureUnique->code }}')"><i class="fas fa-times-circle me-1"></i> Annuler
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
            </div>
        </div>

        {{-- Modal pour annuler une facture --}}

        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmationModalLabel" aria-hidden="true">

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

    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function updateModal(code) {
            // Mettez à jour le contenu du span avec le code spécifique
            document.getElementById('factureCode').value = code;
        }
    </script>


    <style>
        @keyframes clignoter {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
        }

        .clignotant {
            animation: clignoter 1s infinite;
        }

    </style>
@endsection
