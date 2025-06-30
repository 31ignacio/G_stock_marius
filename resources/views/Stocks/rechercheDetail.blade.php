@extends('layouts.master2')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">Entrés de stocks divers</h1>
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

                            <div class="row">
                                <div class="col-md-8"></div>

                                <div class="col-md-2 mt-3">
                                   
                                    <a href="{{ route('stock.exportExcel', request()->all()) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-file-excel"></i> Exporter Excel
                                    </a>
                                </div>
                                <div class="col-md-2 mt-3">

                                    <a href="{{ route('stock.generatePDF', request()->all()) }}" class="btn btn-danger mb-3" target="_blank">
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
                                <h5 class="mt-3"><b> Stock enregistré divers du
                                    {{ date('d/m/Y', strtotime($dateDebut)) }} au
                                    {{ date('d/m/Y', strtotime($dateFin)) }}</b>
                                </h5>
                                <div class="tble-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Produits</th>
                                                <th>Quantité</th>
                                                <th>Auteur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($stocks as $stock)
                                                <tr>
                                                    <td>{{ date('d/m/Y', strtotime($stock->date)) }}</td>
                                                    <td>{{ $stock->libelle }}</td>
                                                    <td>{{ $stock->total_quantite }}</td>
                                                    <td>
                                                        {{ $stock->user ? $stock->user->prenom . ' ' . $stock->user->name : 'Null' }} 
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                <td class="cell text-center" colspan="4">Aucun stock ajoutés</td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
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

@endsection
