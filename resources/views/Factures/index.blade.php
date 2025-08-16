@extends('layouts.master2')

@section('content')
    <section class="content">
        <div class="container-fluid">
            
            <div class="card table-responsive">
                <div class="card-header">
                    <h3 class="card-title">Liste des factures</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body col-md-12">


                    <form method="GET" action="{{ route('facture.search') }}">
                        @csrf
                        @method('GET')

                        <div class="row mb-3">
                            <div class="col-md-4 col-lg-4 col-sm-4">
                                <label>Date Début :</label>
                                <input type="date" class="form-control" name="dateDebut" required>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                                <label>Date Fin :</label>
                                <input type="date" class="form-control" name="dateFin" required>
                            </div>

                            <div class="col-md-4 col-lg-4 col-sm-4 mt-4">
                                <button type="submit" class="btn btn-md btn-outline-success rounded-pill" title="Rechercher.....">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Total TTC</th>
                                <th>Encaissé</th>
                                <th>Montant Final</th>
                                {{-- <th>Solde à encaissé</th> --}}
                                <th>Type</th>
                                <th>Caissier</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($codesFacturesUniques as $factureUnique)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $factureUnique->code }}</td>
                                        <td>{{ $factureUnique->client_nom }}</td>
                                        <td data-date="{{ $factureUnique->date }}">{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>
                                        <td>{{ $factureUnique->totalTTC }}</td>
                                        <td>{{ $factureUnique->montantPaye }}</td>
                                        <td>{{ $factureUnique->montantFinal }}</td>
                                        <td>
                                            @if($factureUnique->produitType_id == 2)
                                                <span class="text-warning">Divers</span>
                                            @else
                                                <span class="text-success" > Poissonnerie </span>
                                            @endif
                                        </td>
                                        <td><b>{{ $factureUnique->user->name }}</b></td>
                                        <td>
                                            <a href="{{ route('facture.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill m-2" title="Voir les détails"><i class="fas fa-eye"></i>
                                            </a>
                                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                                <a href="#" class="btn btn-sm btn-outline-danger rounded-pill m-2" title="Annuler la facture" data-toggle="modal"
                                                    data-target="#confirmationModal"
                                                    onclick="updateModal('{{ $factureUnique->code }}')"><i class="fas fa-times-circle me-1"></i>
                                                </a>
                                            @endif
                                            
                                        </td>
                                    </tr>
                            @endforeach
                    </table>

                    <br>
                    
                </div>
                <!-- /.card-body -->
            </div>
           
        </div>
        <!-- /.container-fluid -->

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
@endsection
