@extends('layouts.master2')

@section('content')

     <span id="client-id" hidden>{{$admin}}</span> 
     
    <section class="content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon text-white"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Salaire Mensuel</span>
                                    <?php
                                        // Formater le montant en ajoutant un point après chaque groupe de trois chiffres en partant de la droite
                                        $salaires = number_format($salaire, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">{{$salaires}} FCFA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon text-white"><i class="fas fa-sun"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Point de la journée</span>
                                    <?php
                                        // Formater le montant en ajoutant un point après chaque groupe de trois chiffres en partant de la droite
                                        $sommeMontantFinalAujourdhui_format = number_format($sommeMontantFinalAujourdhui, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">{{$sommeMontantFinalAujourdhui_format}} FCFA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon text-white"><i class="far fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Mois actuel</span>
                                    <?php
                                        // Formater le montant en ajoutant un point après chaque groupe de trois chiffres en partant de la droite
                                        $sommeMontantFinalMoisActuel_format = number_format($sommeMontantFinalMoisActuel, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">{{$sommeMontantFinalMoisActuel_format}} FCFA</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon text-white"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Chiffres d'affaires global</span>
                                    <?php
                                        // Formater le montant en ajoutant un point après chaque groupe de trois chiffres en partant de la droite
                                        $sommeMontantFinalTousMois_format = number_format($sommeMontantFinalTousMois, 0, ',', '.');
                                    ?>

                                    <span class="info-box-number" style="max-width: 150px; word-wrap: break-word;">
                                        {{$sommeMontantFinalTousMois_format}} FCFA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                    </div>
                    
                    <a href="#" class="btn bg-gradient-success" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-wallet"></i> Paiement de salaire
                      </a><br><br>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Historique paiement de salaire  : <b>{{ $admins->name }}</b></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mois/Année</th>
                                        <th>Employé</th>
                                        <th>Salaire</th>
                                        <th>Montant soustraire</th>
                                        <th>Montant Percu</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    @forelse ($employerSalaire as $salair)
                                        <tr>
                                            <td>  {{ ucfirst($salair->date) }}</td>
                                            <td>{{ $salair->user->name ?? 'Utilisateur inconnu' }}</td>
                                            <td>{{ number_format($salair->salaire, 0, ',', '.') }}</td>
                                            <td>{{ number_format($salair->soustraire, 0, ',', '.') }}</td>
                                            <td>{{ number_format($salair->montantPercu, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('salaire.detail', ['salaire' => $salair->id]) }}" title="Voir quittance" class="btn-sm btn-primary m-2">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="#" title="Supprimer" class="btn-sm btn-danger" data-toggle="modal" data-target="#confirmationModal{{ $salair->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>

                                            <!-- Modal pour la confirmation de suppression -->
                                        <div class="modal fade" id="confirmationModal{{ $salair->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel{{ $salair->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmationModalLabel{{ $salair->id }}">Confirmation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer ce paiement  ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                                        <form method="post" action="{{ route('salaire.delete', ['salaire' => $salair->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Oui</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Aucun salaire enregistré.</td>
                                        </tr>
                                    @endforelse         
                                </tbody>
                            </table>
                        </div>
                   
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->


         <!-- Modal pour enregistrer un paiement -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- En-tête du modal -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Enregistrer un paiement de salaire</h5>
                    </div>
                    <!-- Corps du modal -->
                        <div class="modal-body">
                            <h5 class="mb-3">⚠️ Informations sur les prélèvements</h5>
                            <p class="text-muted">
                                Vous pouvez effectuer un prélèvement sur le salaire de l'utilisateur en indiquant le montant et le motif du prélèvement.
                            </p>

                            <form id="addUserForm" action="{{ route('admin.salaire.store') }}" method="POST">
                                @csrf
                                
                                <div class="col-md-12 mb-3">
                                    <label for="salaire" class="form-label">Montant à soustraire</label>
                                    <input type="number" min="0" class="form-control" id="salaire" name="soustraire" required 
                                        value="{{ old('soustraire', 0) }}">
                                    @error('soustraire')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="motif" class="form-label">Motif du prélèvement</label>
                                    <textarea name="motif" id="motif" class="form-control" rows="3" placeholder="Exemple : Avance sur salaire, retard, etc."></textarea>
                                    @error('motif')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input id="user" name="user" value="{{ $admins->id }}" type="hidden"> 

                                <button type="submit" class="btn btn-success">Valider le prélèvement</button>
                            </form>

                        </div>
                    <!-- Pied du modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

        
@endsection
