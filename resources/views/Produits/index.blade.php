@extends('layouts.master2')

@section('content')

<section class="content">
  <div id="loader" class="d-none text-center mt-3">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Chargement...</span>
    </div>
    <p class="mt-2">Importation en cours, veuillez patienter...</p>
</div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

           
            <a href="#" type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-xl">
    Ajouter Produit
</a>

<!-- Bouton d'import Excel -->
<form id="importForm" action="{{ route('produit.import') }}" method="POST" enctype="multipart/form-data" style="display: inline;">
    @csrf
    <label class="btn btn-outline-success mb-0">
        <i class="fas fa-file-excel"></i> Importer Excel
        <input type="file" name="excel_file" accept=".xlsx, .xls" hidden onchange="submitImport()">
    </label>
</form>

<br><br><br>
          
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Liste des produits</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Code</th>
                  <th>Produits</th>
                  <th>Types</th>
                  <th>Prix de vente</th>
                  <th>Actions</th>

                </tr>
                </thead>
                <tbody>
                  @forelse ($produits as $produit)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $produit->code }}</td>
                      <td>{{ $produit->libelle }}</td>
                      <td>
                        @if($produit->produitType_id == 1)
                            <span class="badge badge-success">Poissonnerie</span>
                        @else
                            <span class="badge badge-warning text-dark">Divers</span>
                        @endif
                      </td>
                      <td> {{ $produit->prix }}</td>
                      <td>
                        <a class="btn btn-sm btn-outline-primary rounded-pill m-2" href="#" data-toggle="modal" data-target="#showEntree{{ $loop->iteration }}" title="Voir les détails">
                          <i class="fas fa-eye"></i>
                        </a>

                        <a href="#!" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}" class="btn btn-sm btn-outline-warning rounded-pill m-2" title="Editer le produit"><i class="fas fa-edit"></i></a>

                        <form action="{{ route('produit.delete', ['produit' => $produit->id]) }}" method="POST" style="display: inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Supprimer le produit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                              <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="cell text-center" colspan="5">Aucun produit ajoutés</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
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

    {{-- Modal pour enregistrer un produit --}}
    <div class="modal fade" id="modal-xl" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalTitle"><i class="fas fa-box"></i> Nouveau Produit</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body p-4">
            <form action="{{ route('produit.store') }}" method="POST">
                @csrf

                <!-- Libelle du produit -->
                <div class="form-group">
                  <label for="libelle"><i class="fas fa-tag"></i> Produit</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="libelle" 
                    name="libelle" 
                    value="{{ old('libelle') }}" 
                    required>
                </div>

                <div class="form-row">
                  {{-- Quantité --}}
                  {{-- <div class="form-group col-md-6">
                    <label for="quantite"><i class="fas fa-sort-numeric-up"></i> Quantité</label>
                    <input 
                    type="number" 
                    class="form-control" 
                    id="quantite" 
                    name="quantite" 
                    value="{{ old('quantite') }}" 
                    step="0.01" 
                    min="0" 
                    required>

                  </div> --}}

                  <!-- Prix de Vente -->
                  {{-- <div class="form-group col-md-6">
                    <label for="prix"><i class="fas fa-dollar-sign"></i> Prix de Vente</label>
                    <input 
                      type="number" 
                      class="form-control" 
                      id="prix" 
                      name="prix" 
                      value="{{ old('prix') }}" 
                      step="0.01" 
                      min="0" 
                      required>
                  </div> --}}
                </div>

                <!-- Type de Produit -->
                <div class="form-group">
                  <label for="produitType"><i class="fas fa-boxes"></i> Type de Produit</label>
                  <select 
                    name="produitType" 
                    id="produitType" 
                    class="form-control" 
                    required>
                    <option value="">-- Sélectionnez le type de produit --</option>
                    @foreach ($produitTypes as $produitType )
                      <option value="{{$produitType->id}}">{{$produitType->produitType}}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Date de réception -->
                <div class="form-group">
                  <label for="dateReception"><i class="far fa-calendar-alt"></i> Date de réception</label>
                  <input 
                    type="date" 
                    class="form-control" 
                    id="dateReception" 
                    name="dateReception" 
                    value="{{ old('dateReception') }}" 
                    required 
                    onkeydown="return false">
                </div>

                <!-- Boutons de validation -->
                <div class="d-flex justify-content-end mt-3">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Enregistrer
                  </button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

   
      {{-- Modifier produit --}}
    @foreach ($produits as $produit)
      <div class="modal fade" id="editEntry{{ $loop->iteration }}" tabindex="-1" aria-labelledby="editModalTitle{{ $loop->iteration }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content shadow">
            <div class="modal-header bg-dark text-white">
              <h5 class="modal-title" id="editModalTitle{{ $loop->iteration }}">
                  <i class="fas fa-edit"></i> Éditer le produit
              </h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body p-4">
              <form action="{{ route('produit.update', ['produit' => $produit->id]) }}" method="POST">
                  @csrf
                  @method('PUT')

                  <!-- Libellé du produit -->
                  <div class="form-group">
                    <label for="libelle{{ $loop->iteration }}">
                      <i class="fas fa-tag"></i> Produit
                    </label>
                    <input 
                      type="text" 
                      class="form-control" 
                      id="libelle{{ $loop->iteration }}" 
                      name="libelle" 
                      value="{{ $produit->libelle }}" 
                      >
                  </div>

                  <div class="form-row">
                    <!-- Prix d'Achat -->
                    <div class="form-group col-md-6">
                      <label for="prixAchat{{ $loop->iteration }}">
                        <i class="fas fa-dollar-sign"></i> Prix d'Achat
                      </label>
                      <input 
                        type="number" 
                        class="form-control" 
                        id="prixAchat{{ $loop->iteration }}" 
                        name="prixAchat" 
                        value="{{ $produit->prixAchat }}" 
                        step="0.01" 
                        min="0" 
                        required>
                    </div>

                    <div class="form-group col-md-6">
                      <label for="prix{{ $loop->iteration }}">
                        <i class="fas fa-dollar-sign"></i> Prix de Vente
                      </label>
                      <input 
                        type="number" 
                        class="form-control" 
                        id="prix{{ $loop->iteration }}" 
                        name="prix" 
                        value="{{ $produit->prix }}" 
                        step="0.01" 
                        min="0" 
                        required>
                    </div>

                    
                  </div>

                  <!-- Date de Réception -->
                  <div class="form-row">

                    <!-- Type de Produit -->
                    <div class="form-group col-md-6">
                      <label for="produitType{{ $loop->iteration }}">
                        <i class="fas fa-boxes"></i> Type de Produit
                      </label>
                      <select 
                        id="produitType{{ $loop->iteration }}" 
                        name="produitType" 
                        class="form-control" 
                        required>
                          <option value="">-- Sélectionnez un type de produit --</option>
                          @foreach ($produitTypes as $produitType)
                            <option value="{{ $produitType->id }}" 
                              {{ $produit->produitType_id == $produitType->id ? 'selected' : '' }}>
                              {{ $produitType->produitType }}
                            </option>
                          @endforeach
                      </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label for="dateReception{{ $loop->iteration }}">
                        <i class="far fa-calendar-alt"></i> Date de Réception
                      </label>
                      <input 
                        type="date" 
                        class="form-control" 
                        id="dateReception{{ $loop->iteration }}" 
                        name="dateReception" 
                        value="{{ \Carbon\Carbon::parse($produit->dateReception)->format('Y-m-d') }}" 
                        required 
                        onkeydown="return false">
                    </div>

                  </div>

                  <!-- Boutons -->
                  <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success">
                      <i class="fas fa-save"></i> Mettre à jour
                    </button>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach


      {{-- Details d"un produit --}}
    @foreach ($produits as $produit)
    <div class="modal fade" id="showEntree{{ $loop->iteration }}" tabindex="-1" aria-labelledby="showModalLabel{{ $loop->iteration }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="showModalLabel{{ $loop->iteration }}">
                <i class="fas fa-box"></i> Détails du produit : <b>{{ $produit->libelle }}</b>
            </h5>
            <button 
                type="button" 
                class="close text-white" 
                data-dismiss="modal" 
                aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body p-4">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <tbody>
                  <tr>
                    <th><i class="fas fa-barcode"></i> Code</th>
                    <td>{{ $produit->code }}</td>
                  </tr>
                  <tr>
                    <th><i class="fas fa-tag"></i> Produit</th>
                    <td>{{ $produit->libelle }}</td>
                  </tr>
                  <tr>
                    <th><i class="fas fa-boxes"></i> Type de produit</th>
                    <td>{{ $produit->produitType->produitType }}</td>
                  </tr>

                  <tr>
                    <th><i class="fas fa-dollar-sign"></i> Prix d'Achat</th>
                    <td class="text-warning font-weight-bold">
                      {{ number_format($produit->prixAchat, 0, ',', ' ') }} CFA
                    </td>
                  </tr>
                  <tr>
                    <th><i class="fas fa-dollar-sign"></i> Prix de vente</th>
                    <td class="text-danger font-weight-bold">
                      {{ number_format($produit->prix, 0, ',', ' ') }} CFA
                    </td>
                  </tr>
                  <tr>
                    <th><i class="far fa-calendar-alt"></i> Date de réception</th>
                    <td>{{ date('d/m/Y', strtotime($produit->dateReception)) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="modal-footer">
            <button 
                type="button" 
                class="btn btn-secondary" 
                data-dismiss="modal">
                <i class="fas fa-times"></i> Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
    @endforeach


     {{-- Control sur la date --}}
    <script>
        // Récupérer la date d'aujourd'hui
        var dateActuelle = new Date();
        var annee = dateActuelle.getFullYear();
        var mois = ('0' + (dateActuelle.getMonth() + 1)).slice(-2);
        var jour = ('0' + dateActuelle.getDate()).slice(-2);
    
        // Formater la date pour l'attribut value de l'input
        var dateAujourdhui = annee + '-' + mois + '-' + jour;
    
        // Définir la valeur et la propriété max de l'input
        var inputDate = document.getElementById('dateReception');
        inputDate.value = dateAujourdhui;
        inputDate.max = dateAujourdhui;
    </script>


    <script>
    function submitImport() {
        document.getElementById('loader').classList.remove('d-none'); // Affiche le loader
        document.getElementById('importForm').submit();               // Soumet le formulaire
    }
</script>



@endsection
