@extends('layouts.master2')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-md-8"></div>

            <div class="col-md-2">
                <a href="{{ route('stock.entrer') }}" class="btn btn-outline-primary mt-3">
                    <i class="fas fa-archive"></i> Entrée de stock
                </a>
            </div>

            <div class="col-md-2">
                <a href="{{ route('stock.sortie') }}" class="btn btn-outline-warning mt-3">
                    <i class="fas fa-sign-out-alt"></i> Sortie de Stocks
                </a>
            </div>
        </div>

        @php
            $totalGeneral = 0;
            $totalPrixVenteTotal= 0;
            $totalMarge= 0;
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <h4 class="card-title">📦 Stocks actuels - Divers</h4>
                    </div>

                    <div class="row justify-content-end mt-3 mb-3">
                      <div class="col-auto">
                          <a href="{{ route('stocks.actuel.divers.excel') }}" class="btn btn-success">
                              <i class="fas fa-file-excel"></i> Télécharger Excel
                          </a>
                      </div>
                      <div class="col-auto">
                          <a href="{{ route('stocks.actuel.divers.pdf') }}" class="btn btn-danger">
                              <i class="fas fa-file-pdf"></i> Télécharger PDF
                          </a>
                      </div>
                  </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th>Produits</th>
                                        <th>Quantité</th>
                                        @if(auth()->user()->role_id == 1)
                                        <th>CRu</th>
                                        <th>Coût de revient total</th>
                                        @endif
                                        <th>P.V</th>
                                        @if(auth()->user()->role_id == 1)
                                        <th>P.V Total</th>
                                        <th>Marge Brute</th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($produits as $produit)
                                        @php
                                            $total = $produit->stock_actuel * $produit->prixAchat;
                                            $totalVente = $produit->stock_actuel * $produit->prix;
                                            $marge=$totalVente - $total;
                                            $totalGeneral += $total;
                                            $totalPrixVenteTotal += $totalVente;
                                            $totalMarge += $marge
                                        @endphp
                                        <tr>
                                            <td>{{ $produit->libelle }}</td>
                                            <td>{{ number_format($produit->stock_actuel, 2, '.', ' ') }}</td>
                                            @if(auth()->user()->role_id == 1)
                                            <td>{{ number_format($produit->prixAchat, 0, '.', ' ') }} </td>
                                            <td>{{ number_format($total, 0, '.', ' ') }}</td>
                                            @endif
                                            <td>{{ number_format($produit->prix, 0, '.', ' ') }} </td>
                                            @if(auth()->user()->role_id == 1)
                                            <td>{{ number_format($totalVente, 0, '.', ' ') }} </td>
                                            <td>{{ number_format($marge, 0, '.', ' ') }} </td>
                                                
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(auth()->user()->role_id == 1)
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="3" class="text-end align-middle">Total Coût de Revient :</th>
                                        <th><span class="text-primary fw-bold">{{ number_format($totalGeneral, 0, '.', ' ') }} FCFA</span></th>

                                        <th class="text-end align-middle">Total P.V :</th>
                                        <th><span class="text-success fw-bold">{{ number_format($totalPrixVenteTotal, 0, '.', ' ') }} FCFA</span></th>

                                        <th class="text-danger fw-bold">{{ number_format($totalMarge, 0, '.', ' ') }} FCFA</th>
                                        {{-- <th><span class="text-danger fw-bold">{{ number_format($totalMarge, 0, '.', ' ') }} FCFA</span></th> --}}
                                       
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
