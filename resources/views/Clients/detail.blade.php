@extends('layouts.master2')

@section('content')

     {{-- <span id="client-id" hidden>{{$client}}</span>  --}}
     
    <section class="content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">

                  
                    <div class="row">
                       

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                              <span class="info-box-icon bg-success elevation-1 blink"><i class="fas fa-shopping-cart"></i></span>
                
                              <div class="info-box-content">
                                <span class="info-box-text">Totals des achats </span>
                                <span class="info-box-number">{{ number_format($totalTTCClient, 0, ',', '.') }} CFA</span>
                                
          
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>
                       
                    </div>


                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Facture client</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th id="montant">Total TTC</th>
                                        <th>Montant Perçu</th>
                                        <th>Reliquat</th>
                                        @auth
                                        @if(auth()->user()->role_id === 1)
                                      
                                        <th>Caissier</th>
                                        @endif
                                        @endauth
                                        <th>Actions</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($codesFacturesUniques as $client)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d/m/Y', strtotime($client->date)) }}</td>
                                            <td>{{ $client->montantFinal }}</td>
                                            <td>{{ $client->montantPaye }}</td>
                                            <td><b>{{ $client->montantPaye - $client->montantFinal}}</b></td>

                                            @auth
                                            @if(auth()->user()->role_id === 1)
                                          
                                            <td><b>{{ $client->user->name}}</b></td>
                                            @endif
                                            @endauth
                                           
                                            <td>
                                                <a href="{{ route('facture.details', ['code' => $client->code, 'date' => $client->date]) }}" class="btn-sm btn-primary">Détail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="cell text-center" colspan="8">Aucune opération éffectuée</td>
                                        </tr>
                                    @endforelse
                                    </tfoot>
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
        
@endsection
