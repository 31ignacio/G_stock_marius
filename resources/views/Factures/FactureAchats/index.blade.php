@extends('layouts.master2')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <a href="{{ route('factureAchat.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-file-invoice-dollar"></i> Ajouter une facture d'achat
              </a><br><br>
            
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
                                <button type="submit" class="btn btn-md btn-outline-success rounded-pill mt-4" title="Rechercher...."><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form> <br><br>

                    <table id="example1" class="table table-bordered table-striped">
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
                                    <td><b>{{ $factureUnique->user->name }}</b></td>
                                    <td> 
                                        
                                        <a href="{{ route('factureAchat.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill m-2" title="Voir la facture"><i class="fas fa-eye"></i>
                                        </a>

                                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill m-2" title="Annuler la facture" data-toggle="modal"
                                                data-target="#confirmationModal"
                                                onclick="updateModal('{{ $factureUnique->code }}')"><i class="fas fa-times-circle me-1"></i>
                                            </a>
                                        
                                    </td>
                                </tr>
                                
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <!-- /.card-body -->
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
