@extends('layouts.master2')

@section('content')
    <section class="content">
        <div class="container-fluid">

                <div class="row">
                        
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <!-- Utilisez une colonne de taille moyenne pour aligner les boutons à gauche -->
                        <a href="{{ route('stock.entrerPoissonerie') }}" class="btn btn-outline-primary mt-3" ><i
                                class="fas fa-archive"></i> Entrée de stock</a><br><br>
                    </div>
                    <div class="col-md-2">
                        <!-- Utilisez une colonne de taille moyenne pour aligner les boutons à gauche -->
                        <a href="{{ route('stock.actuelPoissonerie') }}" class="btn btn-outline-success mt-3" title="Voir le stock actuel"><i
                                class="fas fa-archive"></i> Stocks actuels</a><br><br>
                    </div>
                    
                </div>


            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">Sortie de stock poissonnerie</h1>
                        </div>
                        <div class="card-body">

                            <form method="GET" action="{{ route('sortiePoissonnerie.search') }}">
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
                            
                                    <div class="col-md-2 col-lg-2 col-sm-6 mb-3 mt-4">
                                        <button type="submit" class="btn btn-outline-success rounded-pill" title="Rechercher.....">
                                            <i class="fa fa-search"></i>
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

@endsection
