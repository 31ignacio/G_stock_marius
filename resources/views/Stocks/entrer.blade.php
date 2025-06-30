@extends('layouts.master2')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="row">
                        <div class="col-md-2 mt-3">
                            <!-- Utilisez une colonne de taille moyenne pour aligner les boutons à gauche -->
                                @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                    <a href="#" class="btn btn-outline-primary" title="Faire une entrée de stock" data-toggle="modal"
                                        data-target="#stockEntryModal"><i class="fas fa-arrow-circle-down"></i> Entrées de stock</a><br><br>
                                @endif
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-2">
                            <!-- Utilisez une colonne de taille moyenne pour aligner les boutons à gauche -->
                            <a href="{{ route('stock.actuel') }}" class="btn btn-outline-success mt-3" title="Voir le stock actuel"><i
                                    class="fas fa-archive"></i> Stocks actuels</a><br><br>
                        </div>
                        <div class="col-md-2">
                            <!-- Utilisez une colonne de taille moyenne pour aligner les boutons à gauche -->
                            <a href="{{ route('stock.sortie') }}" class="btn btn-outline-warning mt-3" title="Voir les sorties de stock"><i
                                    class="fas fa-sign-out-alt"></i> Sortie de stocks</a><br><br>
                        </div>
                    </div>

                      <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">Entrée de stocks divers</h1>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form method="GET" action="{{ route('stock.rechercheDetail') }}">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3 col-lg-3 col-sm-6 mb-3">
                                        <label>Date Début :</label>
                                        <input type="date" class="form-control" name="dateDebut" value="{{ request('dateDebut') }}" required>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-6 mb-3">
                                        <label>Date Fin :</label>
                                        <input type="date" class="form-control" name="dateFin" value="{{ request('dateFin') }}" required>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-6 mb-6">
                                        <label for="libelle" class="form-label">Produit</label>
                                        <select class="form-control select2" id="libelle" name="libelle">
                                            <option value="">Sélectionnez un produit</option>
                                            @foreach ($produits as $produit)
                                                <option value="{{ $produit->libelle }}" {{ request('libelle') == $produit->libelle ? 'selected' : '' }}>
                                                    {{ $produit->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-6 mb-3 mt-4">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-search"></i> Rechercher
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                        <th>Date</th>
                                        <th>Produits</th>
                                        <th>Quantité</th>
                                        <th>Auteur</th>
                                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                                <th>Supprimer</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stocks as $stock)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($stock->date)) }}</td>
                                            <td>{{ $stock->libelle }}</td>
                                            <td>{{ $stock->quantite }}</td>
                                            <td>
                                                {{ $stock->user ? $stock->user->prenom . ' ' . $stock->user->name : 'Null' }}
                                            </td>
                                            
                                                @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                            data-target="#editModal{{ $stock->id }}" title="Annuler cette entrée"><i
                                                                class="fas fa-trash-alt"></i> Annuler</button>
                                                    </td>
                                                @endif
                                        </tr>

                                        <!-- Modal pour supprimer entrée-->
                                        <div class="modal fade" id="editModal{{ $stock->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Supprimer le stock</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <!-- Inside the modal body -->
                                                    <div class="modal-body">
                                                    <!-- Your form inputs for editing vehicle information here -->
                                                    <form action="{{ route('stock.update', $stock->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        
                                                        <div class="form-group col-md-12">
                                                            <label for="marque">Produit :</label>
                                                            <input type="text" class="form-control" id="libelle"
                                                                name="libelle" value="{{ $stock->libelle }}" readonly>
                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <label for="quantite">Quantité :</label>
                                                            <input type="text" class="form-control" id="quantite"
                                                                name="quantite" value="{{ $stock->quantite }}"
                                                                readonly>
                                                        </div>

                                                        </div>

                                                        <div class="modal-footer">

                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger">Supprimer</button>
                                                        </div>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                        </div>
                                        @empty

                                        <tr>
                                            <td class="cell text-center" colspan="5">Aucun stock ajoutés</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                             <br>
                            {{-- LA PAGINATION --}}
                            <div class="d-flex justify-content-center mt-4">
                                {{ $stocks->links() }}
                            </div>
                      </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- CSS pour un style plus propre et uniforme (pour l'entete) -->
        <style>
           
            .select2-container .select2-selection--single {
                height: 38px !important;
            }
            
        </style>



      <!-- Modal pour les entrées de stock -->
    <div class="modal fade" id="stockEntryModal" tabindex="-1" aria-labelledby="stockEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockEntryModalLabel">Entrée de stock</h5>
                </div>
    
                <div class="modal-body">
                    <form method="POST" action="{{ route('stock.store') }}">
                        @csrf
    
                        <!-- Produit avec Select2 -->
                        <div class="col-12 mb-3">
                            <label for="produit" class="form-label">Produit</label>
                            <select class="form-control select2" id="produit" name="produit" required>
                                <option value="">Sélectionnez un produit</option>
                                @foreach ($produits as $produit)
                                    <option value="{{ $produit->libelle }}" data-prix="{{ $produit->prix }}">{{ $produit->libelle }}</option>
                                @endforeach
                            </select>
                            
                        </div>
    
                        <!-- Prix (en lecture seule) -->
                        <div class="col-12 mb-3">
                            <label for="prix" class="form-label">Prix</label>
                            <input type="text" class="form-control" name="prix" id="prix">
                        </div>
    
                        <!-- Quantité -->
                        <div class="col-12 mb-3">
                            <label for="quantite" class="form-label">Quantité</label>
                            <input type="text" class="form-control" id="quantite" name="quantite" value="{{ old('quantite') }}" required>
                            @error('quantite')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Boutons -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-o6R3mBSB/q5FvB9RL+DyZD+g3tTPO9P1bxDjCIOtv8yD4QKPIrPCw/tFbk8smJ9Y" crossorigin="anonymous"></script>
    <!-- CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- JS de Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- jQuery (nécessaire pour Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
      $(document).ready(function () {
          // Initialisation standard de Select2
          $('.select2').select2({
              placeholder: "Sélectionnez une option",
              allowClear: true,
          });

          // Réinitialiser Select2 lors de l'ouverture du modal
          $('#stockEntryModal').on('shown.bs.modal', function () {
              $('.select2').select2({
                  dropdownParent: $('#stockEntryModal'), // Permet de s'assurer que le menu apparaît dans le modal
              });
          });
      });
    </script> --}}


    <script>
        $(document).ready(function() {
        // Initialisation de Select2
        $('.select2').select2();
        
        // Événement lors de la sélection d'un produit
        $('#produit').on('select2:select change', function() {
            console.log("Changement détecté");
            var selectedOption = $(this).find('option:selected');
            var prix = selectedOption.data('prix');
            console.log("Prix sélectionné:", prix);
            
            if(prix) {
                // Formater le prix si nécessaire (par exemple pour ajouter le symbole €)
                $('#prix').val(prix);
            } else {
                $('#prix').val('');
            }
        });
        });
    </script>

  

@endsection
