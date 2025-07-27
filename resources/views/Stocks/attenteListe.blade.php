@extends('layouts.master2')

@section('content')
<section class="content">
    <div class="container-fluid">

        <!-- Title -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h3 class="text-dark font-weight-bold">📝 Liste des Stocks en Attente de Validation</h3>
            </div>
        </div>

        <!-- Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow rounded-lg">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Type</th>
                                        <th>Auteur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $stock)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($stock->date)) }}</td>
                                            <td>{{ $stock->libelle }}</td>
                                            <td>{{ number_format($stock->quantite, 2, ',', ' ') }}</td>
                                            <td>
                                                @if($stock->produitType_id == 1)
                                                    <span class="badge badge-info px-3 py-1">Poissonnerie</span>
                                                @else
                                                    <span class="badge badge-warning text-dark px-3 py-1">Divers</span>
                                                @endif
                                            </td>
                                            <td>{{ $stock->user ? $stock->user->prenom . ' ' . $stock->user->name : '-' }}</td>

                                            
                                                <td>
                                                    @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                                        <button class="btn btn-outline-success btn-sm rounded-pill" data-toggle="modal"
                                                            data-target="#editModal{{ $stock->id }}" title="Valider cette entrée">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    @endif
                                                    <form action="{{ route('stock.delete', ['stock' => $stock->id]) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill m-2" title="Supprimer le produit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce stock en attente ?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="editModal{{ $stock->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="editModalLabel{{ $stock->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title" id="editModalLabel{{ $stock->id }}">
                                                            ✅ Valider le Stock
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('stockAttente.valider', $stock->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label><i class="fas fa-box"></i> Produit</label>
                                                                <input type="text" class="form-control" name="produit" value="{{ $stock->libelle }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label><i class="fas fa-tag"></i> Type de Produit</label>
                                                                <input type="text" class="form-control" name="type" value="{{ $stock->produitType->produitType }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label><i class="fas fa-sort-numeric-up-alt"></i> Quantité</label>
                                                                <input type="text" class="form-control" name="quantite" value="{{ $stock->quantite }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success rounded-pill" id="validerBtn{{ $stock->id }}" onclick="showLoader({{ $stock->id }})">
                                                                <span id="btnText{{ $stock->id }}"><i class="fas fa-check"></i> Confirmer la validation</span>
                                                                <span id="btnLoader{{ $stock->id }}" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                            </button>

                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function showLoader(id) {
        const btn = document.getElementById('validerBtn' + id);
        const text = document.getElementById('btnText' + id);
        const loader = document.getElementById('btnLoader' + id);

        text.classList.add('d-none');
        loader.classList.remove('d-none');
    }
</script>

@endsection
