@extends('layouts.master2')

@section('content')

    <section class="content">
        <div class="container-fluid">
             
            <div class="card table-responsive">
                <div class="card-header">
                    <h3 class="card-title">Sortie de stock divers</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body col-md-12">
                    
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

                    <div class="row">
                        <div class="col-md-8"></div>

                        <div class="col-md-2 mt-3">
                            <a href="{{ route('sortieDetail.excel', ['dateDebut' => request('dateDebut'), 'dateFin' => request('dateFin'), 'produit' => request('produit')]) }}"
                                class="btn btn-success mb-3" target="_blank">
                                <i class="fas fa-file-excel"></i> Exporter Excel
                            </a>
                        </div>
                        <div class="col-md-2 mt-3">

                            <a href="{{ route('sortieDetail.pdf', ['dateDebut' => request('dateDebut'), 'dateFin' => request('dateFin'), 'produit' => request('produit')]) }}"
                                class="btn btn-danger mb-3" target="_blank">
                                <i class="fas fa-download"></i> Générer PDF
                            </a>
                        </div>
                    </div>

                    <div id="my-table">
                        <div class="row">
                            <div class="col-12">
                                <h5>
                                    <i class="fas fa-globe"></i> <b>G_STOCK</b>.
                                    <small class="float-right">Date: {{ date('d/m/Y', strtotime($date)) }}
                                    </small>
                                </h5>
                            </div>
                            <!-- /.col -->
                        </div>
                        <h4 class="mt-3"><b> Sortie de stock à la date du
                            {{ date('d/m/Y', strtotime($dateDebut)) }} au
                            {{ date('d/m/Y', strtotime($dateFin)) }}</b>
                        </h4>
                        
                        <table class="table table-bordered table-striped">
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

    </section>

    <!-- CSS pour un style plus propre et uniforme (pour l'entete) -->
        <style>
           
            .select2-container .select2-selection--single {
                height: 38px !important;
            }
            
        </style>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
    @endsection
