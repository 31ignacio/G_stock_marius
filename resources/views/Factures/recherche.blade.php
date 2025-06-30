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
                                    <button type="submit" class="btn btn-md btn-success" >
                                      <i class="fa fa-search"></i> Recherche
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-8"></div>

                            <div class="col-md-2 mt-3">
                                <a class="btn btn-success mb-3" 
                                    href="{{ route('facture.genererExcel', ['dateDebut' => $dateDebut, 'dateFin' => $dateFin]) }}">
                                    <i class="fas fa-file-excel"></i> Exporter en Excel
                                </a>
                            </div>
                            <div class="col-md-2 mt-3">
                                <a class="btn btn-danger mb-3" 
                                    href="{{ route('facture.genererPDF', ['dateDebut' => $dateDebut, 'dateFin' => $dateFin]) }}">
                                    <i class="fas fa-download"></i> Générer PDF
                                </a>

                            </div>
                        </div>
                        
                        <div id="my-table">
                            <div class="row">
                                <div class="col-12">
                                    <h5>
                                        <i class="fas fa-globe"></i> <b>APAL TRADING</b>.
                                        <small class="float-right">Date: {{ date('d/m/Y', strtotime($date)) }}
                                        </small>
                                    </h5>
                                </div>
                                <!-- /.col -->
                            </div>
                            <h6 class="mt-3 mb-5 text-center"><b> Sommation des facture à la date du
                                {{ date('d/m/Y', strtotime($dateDebut)) }} au
                                {{ date('d/m/Y', strtotime($dateFin)) }}</b>
                            </h6>

                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                                <p class="mb-0"><b>Total supérette :</b> {{ number_format($totalTTCType1, 0, ',', '.') }} CFA</p>
                                @if (auth()->user()->role_id != 2 )
                                <p class="mb-0"><b>Total poissonnerie :</b> {{ number_format($totalTTCType3, 0, ',', '.') }} CFA</p>
                                @endif
                            </div>
                            
                        
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Total TTC</th>
                                        <th>Encaissé</th>
                                        <th>Reliquat </th>
                                        <th>Montant Final</th>
                                        <th>Type</th>
                                        <th>Caissier</th>
                                        <TH>Actions</TH>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($codesFacturesUniques as $factureUnique)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $factureUnique->client_nom }}</td>
                                            <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>
                                            <td>{{ $factureUnique->totalTTC }}</td>
                                            <td>{{ $factureUnique->montantPaye }}</td>
                                            <td>{{ $factureUnique->montantRendu }}</td>
                                            <td>{{ $factureUnique->montantFinal }}</td>
                                            <td>
                                                @if($factureUnique->produitType_id == 1)
                                                <span class="badge badge-success">POISSONNERIE</span>
                                                @else
                                                <span class="badge badge-warning" > DIVERS </span>
                                                @endif
                                            </td>
                                            <td>{{ $factureUnique->user->name }}</td>
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
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </section>

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

    <script>
        function updateModal(code) {
            
            // Mettez à jour le contenu du span avec le code spécifique
            document.getElementById('factureCode').value = code;
        }
    </script>
@endsection
