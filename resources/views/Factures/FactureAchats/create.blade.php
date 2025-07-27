@extends('layouts.master2')

@section('content')
    <div class="container my-4">

        <!-- Wrapper formulaire avec bordure douce et ombre -->
        <div class="p-4 shadow rounded" style="background-color: #f9faf9; border: 1px solid #d1e7dd;">
            <h4 class="mb-4 text-success">
                <i class="fas fa-info-circle"></i> Détails de la Transaction
            </h4>

            <div class="row g-3 mb-4">
                <!-- Date de la transaction -->
                <div class="col-md-4">
                    <label for="date" class="form-label text-dark"><i class="fas fa-calendar-alt"></i> Date</label>
                    <input type="date" id="date" class="form-control" onkeydown="return false">
                </div>

                <!-- Produit Type -->
                <div class="col-md-4">
                    <label for="produitType" class="form-label text-dark"><i class="fas fa-box"></i> Produit Type</label>
                    <select id="produitType" class="form-control">
                        <option value="" selected disabled>-- Sélectionnez un type --</option>
                        @foreach ($produitTypes as $produitType)
                            <option value="{{ $produitType->id }}">{{ $produitType->produitType }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Société -->
                <div class="col-md-4">
                    <label for="societe" class="form-label text-dark"><i class="fas fa-building"></i> Société</label>
                    <select id="societe" name="societe" class="form-control" required>
                        <option value="" selected disabled>-- Sélectionnez une société --</option>
                        @foreach ($societes as $societe)
                            <option value="{{ $societe->id }}">{{ $societe->societe }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <form id="monFormulaire">
                <div id="msg25" class="mb-3"></div>

                <div class="row g-3 align-items-end">
                    <!-- Produit -->
                    <div class="col-md-3">
                        <label for="produit" class="form-label text-dark"><i class="fas fa-cubes"></i> Produits</label>
                        <select id="produit" class="form-control select2">
                            <option value="" selected disabled>-- Choisissez un produit --</option>
                            <!-- Chargé via JS -->
                        </select>
                    </div>

                    <!-- Quantité -->
                    <div class="col-md-2">
                        <label for="quantite" class="form-label text-dark"><i class="fas fa-sort-numeric-up"></i> Quantité</label>
                        <input type="number" min="0" step="0.01" value="0" id="quantite" name="quantite" class="form-control">
                        <div id="messagePro" class="text-danger small mt-1"></div>
                    </div>

                    <!-- Prix d'achat -->
                    <div class="col-md-2">
                        <label for="prix" class="form-label text-dark"><i class="fas fa-tag"></i> Coût de revient</label>
                        <input type="number" min="0" step="0.01" id="prix" name="prix" class="form-control">
                    </div>

                    <!-- Prix de vente -->
                    <div class="col-md-2">
                        <label for="prixVente" class="form-label text-dark"><i class="fas fa-dollar-sign"></i> Prix de vente</label>
                        <input type="number" min="0" step="0.01" id="prixVente" name="prixVente" class="form-control">
                    </div>

                    <!-- Bouton Ajouter -->
                    <div class="col-md-3 d-grid">
                        <button type="button" class="btn btn-success mt-1" onclick="ajouterAuTableau()" title="Ajouter">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau -->
        <div class="invoice p-4 my-4 shadow rounded" style="background-color: #ffffff; border: 1px solid #d1e7dd;">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="monTableau">
                    <thead class="table-success">
                        <tr>
                            <th>Quantité</th>
                            <th>Produit</th>
                            <th>Coût de revient</th>
                            <th>Prix de vente</th>
                            <th>Total</th>
                            <th>Bénéfice</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody id="monTableauBody">
                        <!-- Lignes ajoutées dynamiquement -->
                    </tbody>
                </table>
            </div>

            <!-- Totaux -->
            <!-- Totaux -->
            <div class="row justify-content-end mt-4">
                <div class="col-md-5 col-lg-4">
                    <div class="card shadow-sm rounded">
                        <div class="card-body p-3">
                            <h5 class="text-success text-center">Récapitulatif</h5>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="text-dark"><i class="fas fa-tag"></i> Total coût de revient :</th>
                                        <td id="totalAchat" class="fw-bold text-success">0.00 CFA</td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark"><i class="fas fa-dollar-sign"></i> Total prix de vente :</th>
                                        <td id="totalVente" class="fw-bold text-success">0.00 CFA</td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark"><i class="fas fa-chart-line"></i> Total bénéfice :</th>
                                        <td id="totalBenefice" class="fw-bold text-success">0.00 CFA</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>  
                    </div>  
                </div>
            </div>


            <!-- Bouton Valider -->
            <div class="row no-print">
                <div class="col-12 d-flex justify-content-end">
                    <button type="button" id="btnValider" class="btn btn-success" style="width: 180px;" onclick="enregistrerDonnees()">
                        <span id="validerText"><i class="fas fa-download"></i> Valider</span>
                        <span id="validerLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>

                </div>
            </div>

            <div id="msg200" class="mt-3"></div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="../../../../AD/toastify-js-master/src/toastify.js"></script>

    <script>
        function ajouterAuTableau() {
            let quantite = document.getElementById("quantite").value.trim();
            let produit = document.getElementById("produit").value.trim();
            let societe = document.getElementById("societe").value.trim();
            let prixAchat = document.getElementById("prix").value.trim();
            let prixVente = document.getElementById("prixVente").value.trim();

            if (!quantite || isNaN(parseFloat(quantite)) || !produit || !societe || !prixAchat || !prixVente) {
                $('#msg25').html(`<p class="text-danger fw-bold">
                                    Veuillez remplir tous les champs avec des valeurs valides.
                                </p>`);
                setTimeout(() => $('#msg25').html(''), 5000);
                return;
            }

            quantite = parseFloat(quantite);
            prixAchat = parseFloat(prixAchat);
            prixVente = parseFloat(prixVente);

            const total = quantite * prixVente;
            const benefice = quantite * (prixVente - prixAchat);

            let tableauBody = document.getElementById("monTableauBody");
            let newRow = tableauBody.insertRow();

            newRow.insertCell(0).textContent = quantite.toFixed(2);
            newRow.insertCell(1).textContent = produit;
            newRow.insertCell(2).textContent = prixAchat.toFixed(2);
            newRow.insertCell(3).textContent = prixVente.toFixed(2);
            newRow.insertCell(4).textContent = total.toFixed(2);
            newRow.insertCell(5).textContent = benefice.toFixed(2);

            let actionCell = newRow.insertCell(6);
            let deleteBtn = document.createElement("button");
            deleteBtn.className = "btn btn-sm btn-outline-danger rounded-circle";
            deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
            deleteBtn.title = "Supprimer";
            deleteBtn.onclick = function () {
                tableauBody.removeChild(newRow);
                mettreAJourTotalHT();
            };
            actionCell.appendChild(deleteBtn);

            mettreAJourTotalHT();

            // Reset form fields
            document.getElementById("quantite").value = "";
            document.getElementById("prix").value = "";
            document.getElementById("prixVente").value = "";
            document.getElementById("produit").value = "";
        }

        function mettreAJourTotalHT() {
            let tableauBody = document.getElementById("monTableauBody");
            let totalAchat = 0;
            let totalVente = 0;

            for (let i = 0; i < tableauBody.rows.length; i++) {
                let quantite = parseFloat(tableauBody.rows[i].cells[0].textContent);
                let prixAchat = parseFloat(tableauBody.rows[i].cells[2].textContent);
                let prixVente = parseFloat(tableauBody.rows[i].cells[3].textContent);

                totalAchat += quantite * prixAchat;
                totalVente += quantite * prixVente;
            }

            let totalBenefice = totalVente - totalAchat;

            document.getElementById("totalAchat").textContent = totalAchat.toFixed(2) + " CFA";
            document.getElementById("totalVente").textContent = totalVente.toFixed(2) + " CFA";
            document.getElementById("totalBenefice").textContent = totalBenefice.toFixed(2) + " CFA";
        }

        function enregistrerDonnees() {
            let tableauBody = document.getElementById("monTableauBody");
            let date = document.getElementById("date").value;
            let societe = document.getElementById("societe").value;
            let totalBenefice = document.getElementById("totalBenefice").textContent;
            let totalAchat = document.getElementById("totalAchat").textContent;
            let totalVente = document.getElementById("totalVente").textContent;
            let produitType = document.getElementById("produitType").value;

            if (!date || !societe || !produitType || tableauBody.rows.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Champs obligatoires !",
                    text: "Veuillez remplir tous les champs et ajouter au moins un produit.",
                    confirmButtonColor: "#2E8B57"
                });
                return;
            }

            let donnees = [];
            for (let i = 0; i < tableauBody.rows.length; i++) {
                let ligne = tableauBody.rows[i];
                donnees.push({
                    quantite: ligne.cells[0].textContent,
                    produit: ligne.cells[1].textContent,
                    prixAchat: ligne.cells[2].textContent,
                    prixVente: ligne.cells[3].textContent,
                    total: ligne.cells[4].textContent,
                    benefice: ligne.cells[5].textContent
                });
            }

            $('.btn-success').prop('disabled', true);
            document.getElementById("validerText").classList.add('d-none');
            document.getElementById("validerLoader").classList.remove('d-none');

            $.ajax({
                type: "POST",
                url: "{{ route('factureAchat.store') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    donnees: JSON.stringify(donnees),
                    societe,
                    date,
                    totalAchat,
                    totalVente,
                    totalBenefice,
                    produitType
                },

                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Facture enregistrée !",
                        text: "Votre facture d'achat a été enregistrée avec succès.",
                        confirmButtonColor: "#2E8B57",
                        timer: 3000,
                        timerProgressBar: true,
                        didClose: () => {
                            window.location.href = "{{ route('factureAchat.index') }}";
                        }
                    });
                },
                error: function(xhr) {
                    let errorMsg = "Une erreur est survenue, veuillez réessayer.";
                    if (xhr.status === 500) {
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (response.error_message) {
                                errorMsg = response.error_message;
                            }
                        } catch (e) {
                            console.error("Erreur de parsing JSON:", e);
                        }
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: errorMsg,
                        confirmButtonColor: "#FF5733",
                        timer: 60000,
                        timerProgressBar: true
                    });
                    document.getElementById("validerText").classList.remove('d-none');
                    document.getElementById("validerLoader").classList.add('d-none');

                    $('.btn-success').prop('disabled', false);
                }
            });
        }

        // Mise à jour dynamique des produits selon le type sélectionné
        function updateProduits() {
            let produitTypeSelect = document.getElementById('produitType');
            let produitsSelect = document.getElementById('produit');
            let selectedProduitType = produitTypeSelect.value;

            produitsSelect.innerHTML = '<option value="" disabled selected>-- Choisissez un produit --</option>';

            @foreach ($produits as $produit)
                if ("{{ $produit->produitType_id }}" == selectedProduitType) {
                    let option = document.createElement('option');
                    option.value = "{{ $produit->libelle }}";
                    option.setAttribute('data-prix', "{{ $produit->prix }}");
                    option.textContent = "{{ $produit->libelle }}";
                    produitsSelect.appendChild(option);
                }
            @endforeach
        }

        document.getElementById('produitType').addEventListener('change', updateProduits);

        // Initialisation
        updateProduits();

        // Date max aujourd'hui
        (function(){
            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = String(today.getMonth() + 1).padStart(2, '0');
            let dd = String(today.getDate()).padStart(2, '0');
            let formatted = `${yyyy}-${mm}-${dd}`;
            let inputDate = document.getElementById('date');
            inputDate.value = formatted;
            inputDate.max = formatted;
        })();
    </script>

        <!-- CSS pour un style plus propre et uniforme (pour l'entete) -->
    <style>    
        .select2-container .select2-selection--single {
            height: 38px !important;
        }
    </style>

@endsection
