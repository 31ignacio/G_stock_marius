@extends('layouts.master2')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">Sortie de stock Divers</h1>
                        </div>
                        <div class="card-body">

                          <form method="GET" action="{{ route('sortieDetail.search') }}">
                            @csrf
                            @method('GET')
                        
                            <div class="row mb-3">
                                <div class="col-md-4 col-lg-3 col-sm-4">
                                    <label>Date Début :</label>
                                    <input type="date" class="form-control" name="dateDebut" value="{{ request('dateDebut') }}" required>
                                </div>
                                <div class="col-md-4 col-lg-3 col-sm-4">
                                    <label>Date Fin :</label>
                                    <input type="date" class="form-control" name="dateFin" value="{{ request('dateFin') }}" required>
                                </div>
                        
                                <div class="col-md-4 col-lg-4 col-sm-4">
                                    <label>Produit :</label>
                                    <select class="form-control select2" id="produit" name="produit">
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach ($produits as $produit)
                                            <option value="{{ $produit->libelle }}" {{ request('produit') == $produit->libelle ? 'selected' : '' }}>
                                                {{ $produit->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <div class="col-md-4 col-lg-2 col-sm-4 mt-4">
                                    <button type="submit" class="btn btn-md btn-success">
                                        <i class="fa fa-search"></i> Recherche
                                    </button>
                                </div>
                            </div>
                        </form>
                        
    
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Produits</th>
                                        <th>Quantité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($factures as $facture)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($facture->date)) }}</td>
                                            <td>{{ $facture->produit }}</td>
                                            <td>{{ $facture->total_quantite }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CSS pour un style plus propre et uniforme (pour l'entete) -->
        <style>
           
            .select2-container .select2-selection--single {
                height: 38px !important;
            }
            
        </style>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-o6R3mBSB/q5FvB9RL+DyZD+g3tTPO9P1bxDjCIOtv8yD4QKPIrPCw/tFbk8smJ9Y" crossorigin="anonymous"></script>
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
    </script>

@endsection
