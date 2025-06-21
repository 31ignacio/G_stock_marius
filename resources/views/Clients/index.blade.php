@extends('layouts.master2')

@section('content')


<section class="content">
    <div class="container-fluid">

 
      <div class="row">
        <div class="col-12">

          <a href="#" class="btn bg-gradient-primary" data-toggle="modal" data-target="#addUserModal">
            <i class="fas fa-user-plus"></i> Ajouter un client
          </a><br><br>
           
            
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Liste des clients</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Raison sociale</th>
                  <th>Nom</th>
                  <th>Téléphone</th>
                  <th>Ville</th>
                  <th>Transaction</th>

                </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)

                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $client->raisonSociale }} </td>
                  <td>{{ $client->nom }}</td>
                  <td>{{ $client->telephone }}</td>
                  <td>{{ $client->ville }}</td>

                  <td>
                    <a href="{{ route('client.detail', ['client' => $client->id]) }}" class="btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                    <a href="#!" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}" class="btn-sm btn-warning mx-1"><i class="fas fa-edit"></i></a>

                    @auth
                    @if(auth()->user()->role_id == 1)
                   
                      <form action="{{ route('client.delete', ['client' => $client->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>                 
                    @endif
                    @endauth
                  </td>
                </tr>
                @empty

                <tr>
                    <td class="cell text-center" colspan="7">Aucun client ajoutés</td>

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


     <!-- Modal pour enregistrer un client -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- En-tête du modal -->
              <div class="modal-header">
                  <h5 class="modal-title" id="addUserModalLabel">Ajouter un client</h5>
              </div>
              <!-- Corps du modal -->
              <div class="modal-body">
                  <form id="addUserForm" method="POST" action="{{ route('client.store') }}">
                      @csrf
                      <div class="row">
                          <div class="col-md-12 mb-3">
                              <label for="societe" class="form-label">Raison sociale</label>
                              <input type="text" class="form-control" id="societe" name="societe" value="{{ old('societe') }}" required>
                              @error('societe')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>
                          <div class="col-md-12 mb-3">
                              <label for="nom" class="form-label">Nom</label>
                              <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                              @error('nom')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12 mb-3">
                              <label for="telephone" class="form-label">Téléphone</label>
                              <input type="number" min="0" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                              @error('telephone')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>
                          <div class="col-md-12 mb-3">
                              <label for="ville" class="form-label">Ville</label>
                              <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville') }}" required>
                              @error('ville')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                      <!-- Boutons du modal -->
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Ajouter</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>

    {{-- Modifier client --}}
   @foreach ($clients as $client)
    <div class="modal fade" id="editEntry{{ $loop->iteration }}">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editer le client</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              <form class="settings-form" method="POST" action="{{ route('client.update',$client->id) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="raisonSociale">Raison sociale</label>
                            <input type="text" class="form-control" id="raisonSociale" value="{{ $client->raisonSociale }}" name="societe" required>
                        
                        {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                        @error('raisonSociale')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>

                    
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="{{ $client->nom }}" required>
                      
                            {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                            @error('nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                

                    <div class="row">
                      
                        <div class="col-md-12 mb-3">
                            <label for="telephone">Téléphone</label>
                            <input type="number" class="form-control" id="telephone" value="{{ $client->telephone }}" name="telephone" required>

                            {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                            @error('telephone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="ville">Ville</label>
                            <input type="text" class="form-control" id="ville" value="{{ $client->ville }}" name="ville" required>

                            {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                            @error('ville')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                <button type="submit" class="btn btn-warning">Editer</button>
                </div>
            </form>
            </div>
        
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  @endforeach
  

  @endsection
