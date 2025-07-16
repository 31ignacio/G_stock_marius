@extends('layouts.master2')

@section('content')

    <section class="content">
        <div class="container-fluid">
              

                <div class="card table-responsive">
                    <div class="card-header">
                        <h3 class="card-title">Liste des factures d'achats</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body col-md-12">
                        
                             
                        <form method="GET" action="{{ route('factureAchat.search') }}">
                            @csrf
                            @method('GET')
                            <div class="row">

                            <div class="col-md-3">
                                <label for="dateDebut">Date début :</label>
                                <input type="date" class="form-control" name="dateDebut" value="{{ request('dateDebut') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="dateFin">Date fin :</label>
                                <input type="date" class="form-control" name="dateFin" value="{{ request('dateFin') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="societe_id">Société :</label>
                                <select name="societe_id" class="form-control">
                                    <option value="">-- Toutes les sociétés --</option>
                                    @foreach ($societes as $societe)
                                        <option value="{{ $societe->id }}" {{ request('societe_id') == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->societe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-md btn-outline-success rounded-pill mt-4" title="Rechercher....."><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        </form>

                        <div class="row">
                            <div class="col-md-8"></div>

                             <div class="col-md-2 mt-3">
                                <a class="btn btn-success" 
                                    href="{{ route('factureAchat.genererExcel', [
                                        'dateDebut' => request('dateDebut'),
                                        'dateFin' => request('dateFin'),
                                        'societe_id' => request('societe_id'),
                                    ]) }}">
                                    Exporter en Excel
                                </a>

                            </div>
                            <div class="col-md-2 mt-3">
                                <a href="{{ route('factureAchat.genererPDF', ['dateDebut' => request('dateDebut'), 'dateFin' => request('dateFin'), 'societe_id' => request('societe_id')]) }}" 
                                class="btn btn-danger">
                                <i class="fas fa-download"></i> Générer PDF
                                </a>
                            </div>
                        </div>
                        
                        <div id="my-table">
                            <div class="row">
                                <div class="col-12">
                                    <h5>
                                        <i class="fas fa-globe"></i> <b>APL TRADING</b>.
                                        <small class="float-right">Date: {{ date('d/m/Y', strtotime($date)) }}
                                        </small>
                                    </h5>
                                </div>
                                <!-- /.col -->
                            </div>
                            <h6 class="mt-3 mb-5 text-center"><b> Sommation des facture d'achats à la date du
                                {{ date('d/m/Y', strtotime($dateDebut)) }} au
                                {{ date('d/m/Y', strtotime($dateFin)) }}</b>
                            </h6>

                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                                <p class="mb-0"><b>Total Bénéfice Divers :</b> {{ number_format($totalTTCType1, 0, ',', '.') }} CFA</p>
                                <p class="mb-0"><b>Total Bénéfice poissonnerie :</b> {{ number_format($totalTTCType3, 0, ',', '.') }} CFA</p>
                            </div>
                            
                        
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Société</th>
                                        <th>Total bénéfice</th>
                                        <th>Type</th>
                                        <th>Comptable</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($codesFacturesUniques as $factureUnique)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $factureUnique->code }}</td>
                                            <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>
                                            <td>{{ $factureUnique->societe->societe }}</td>
                                            <td>{{ $factureUnique->totalBenefice }}</td>
                                            <td>
                                                @if($factureUnique->produitType_id == 1)
                                                <span class="badge badge-success">POISSONNERIE</span>
                                                @else
                                                <span class="badge badge-warning" > DIVERS </span>
                                                @endif
                                            </td>
                                            <td>{{ $factureUnique->user->name }}</td>
                                            <td> 
                                        
                                                <a href="{{ route('factureAchat.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill m-2" title="Voir la facture"><i class="fas fa-eye"></i>
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
                    <form method="get" action="{{ route('factureAchat.annuler') }}">
                        @csrf
                        <div class="modal-body">
                            Voulez-vous annuler cette facture d'achat ?
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
