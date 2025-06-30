@extends('layouts.master2')

@section('content')


<section class="content">

    <div class="row">
            <div class="col-md-1"></div>
        <div class="col-md-4">
            <form id="addUserForm" method="POST" action="{{ route('societe.store') }}">
                @csrf
                
                <div class="form-group">
                    <label>Ajouter une société</label>
                    <input type="text" class="form-control" placeholder="Entrez la société" style="border-radius: 10px;" id="categorie" name="societe" required>
                </div>
            
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:8px;"><i class="fas fa-plus-circle"></i>Ajouter</button>   
    
            </form>
        </div>

        
        <div class="col-md-7">
          
            <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>#</th>
                      <th>Société</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                    <tbody>
                        @foreach ($societes as $societe)
                        
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$societe->societe}}</td>
                            
                                <td> 
                                    <a href="#!" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}" class="btn-sm btn-warning m-2" title="Editer la société"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('societe.delete', $societe->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-danger m-2" title="Supprimer la société" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette société ?')">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </form>
                                </td>
                            
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    {{ $societes->links() }}
                </div>
                 
            </div>
             
        </div>


    </div>
    
    {{-- Modifier societe --}}
   @foreach ($societes as $societe)
        <div class="modal fade" id="editEntry{{ $loop->iteration }}">
            <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Editer la societe</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                            <form class="settings-form" method="POST" action="{{ route('societe.update',$societe->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="societe">societé</label>
                                            <input type="text" class="form-control" id="societe" value="{{ $societe->societe }}" name="societe" required>
                                                @error('societe')
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


</section>

@endsection
