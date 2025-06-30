@extends('layouts.master2')

@section('content')
    <div class="container">

        <!-- Wrapper avec une bordure et ombre légère pour le style -->
        <div class="callout callout-info shadow-sm p-4 rounded" style="background-color: #f8f9fa;">
            
            <h5 class="mb-4 text-primary"><i class="fas fa-info-circle"></i> Détails de la Transaction</h5>

            <div class="row">
                <!-- Date de la transaction -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date"><i class="fas fa-calendar-alt"></i> Date</label>
                        <input type="date" id="date" class="form-control" onkeydown="return false">
                    </div>
                </div>

               <!-- Sélection du Client -->
                <div class="col-md-4">
                    <div class="form-group d-flex align-items-end">
                        <div class="w-100">
                            <label for="client"><i class="fas fa-user"></i> Clients</label>
                            <select class="form-control select2 m-2" id="client" required>
                                <option></option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }} {{ $client->nom }}" 
                                            @if ($client->nom == 'Client') selected @endif>
                                        {{ $client->nom }} {{ $client->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bouton vers client.index -->
                        <a href="{{ route('client.index') }}" class="btn btn-outline-info m-2" title="Gérer les clients">
                            <i class="fas fa-arrow-circle-down"></i>
                        </a>
                    </div>
                </div>


                <!-- Sélection du type de produit -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="produitType"><i class="fas fa-box"></i> Produit Type</label>
                        <select class="form-control" id="produitType">
                            <option></option>
                            
                            @foreach ($produitTypes as $produitType)
                                <option value="{{ $produitType->id }}">{{ $produitType->produitType }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>

            <form id="monFormulaire">
                <div id="msg25"></div>
                
                <div class="row align-items-center">
                    <!-- Sélection du Produit -->
                    <div class="mb-3 col-md-4">
                        <label for="produit"><i class="fas fa-cubes"></i> Produits</label>
                        <select class="form-control select2" id="produit">
                            <option></option>
                            <!-- Les produits sont chargés depuis le JS -->
                        </select>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-3 col-md-4">
                        <label for="quantite"><i class="fas fa-sort-numeric-up"></i> Quantité</label>
                        <input type="number" value="0" min="0" class="form-control" id="quantite">
                        <div id="messagePro" class="text-danger mt-1"></div>
                    </div>

                    <input type="hidden" min=0 class="form-control" id='tva'>
                    <!-- Bouton d'ajout -->
                    <div class="mb-3 col-md-4">
                        <button type="button" class="btn btn-info mt-3" onclick="ajouterAuTableau()" title="Ajouter">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>


    <div class="invoice mt-4">
        <div class="table-responsive">
            <table class="table table-bordered" id="monTableau">
                <thead>
                    <tr>
                        <th>Quantité</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody id="monTableauBody">
                    <!-- Lignes dynamiques -->
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6"></div>

            <div class="col-md-6">
                
                <table class="table">
                    <tr>
                        <th>Total HT :</th>
                        <td id="totalHT">0</td>
                    </tr>
                    <tr>
                        <th>Total TVA :</th>
                        <td id="totalTVA">0</td>
                    </tr>
                    <tr>
                        <th>Total TTC :</th>
                        <td><span id="totalTTC" class="badge-info">0</span></td>
                    </tr>
                    <tr>
                        <th>Encaissé :</th>
                        <td><input type="text" class="form-control" id="montantPaye" oninput="ajouterValider()"></td>
                    </tr>
                    <tr>
                        <th>Solde à encaissé :</th>
                        <td><input type="text" class="form-control" id="montantRendu" disabled style="background:#eee;"></td>
                    </tr>
                    <tr>
                        <th>Remise :</th>
                        <td><input type="text" class="form-control" id="remise" oninput="ajouterValider()"></td>
                    </tr>
                    <tr>
                        <th>Rendu :</th>
                        <td><input type="text" class="form-control" id="monnaie" disabled style="background:#eaeaea;"></td>
                    </tr>
                    <tr>
                        <th>Total Réglé :</th>
                        <td><input type="text" class="form-control" id="montantFinal" disabled style="background-color:#d4edda;"></td>
                    </tr>
                </table>
                <div id="msg30"></div>
            </div>
        </div>

        <div class="text-right">
            <button type="button" class="btn btn-info btn-lg valider" onclick="enregistrerDonnees()">
                <i class="fas fa-check-circle"></i> Valider
            </button>
        </div>

        <div id="msg200"></div>
    </div>
</div>



     <script src="../../../../AD/toastify-js-master/src/toastify.js"></script>

    {{-- Ajouter produit dans le tableau --}}
    <script>
        function ajouterAuTableau() {
            var quantite = document.getElementById("quantite").value;
            var produit = document.getElementById("produit").value;
            var client = document.getElementById("client").value;
            var selectProduit = $('#produit');
            var prix = $('option:selected', selectProduit).data('prix');

            if (quantite.trim() == "" || isNaN(parseFloat(quantite)) || produit.trim() == "" || client.trim() == "") {
                Toastify({
                    text: "Veuillez remplir tous les champs (quantité, produit, client) avec des valeurs valides.",
                    duration: 4000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true,
                    close: true
                }).showToast();
                return;
            }

            var total = quantite * prix;
            var tableauBody = document.getElementById("monTableauBody");
            var newRow = tableauBody.insertRow(tableauBody.rows.length);

            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);
            var cell4 = newRow.insertCell(3);
            var cell5 = newRow.insertCell(4);

            cell1.innerHTML = quantite;
            cell2.innerHTML = produit;
            cell3.innerHTML = prix;
            cell4.innerHTML = total;

            var deleteButton = document.createElement("button");
            deleteButton.innerHTML = '<i class="fas fa-times"></i>';
            deleteButton.className = "btn btn-sm btn-outline-danger rounded-circle";
            deleteButton.title = "Supprimer";
            deleteButton.onclick = function () {
                var row = this.parentNode.parentNode;
                tableauBody.deleteRow(row.rowIndex - 1);
                mettreAJourTotalHT();
            };
            cell5.appendChild(deleteButton);

            // Effet d’ajout visuel
            newRow.classList.add("table-success");
            setTimeout(() => {
                newRow.classList.remove("table-success");
            }, 1000);

            mettreAJourTotalHT();
            document.getElementById("quantite").value = "";
        }


        function mettreAJourTotalHT() {
            var tva = document.getElementById("tva").value;
            var tableauBody = document.getElementById("monTableauBody");
            var totalHT = 0;
    
            for (var i = 0; i < tableauBody.rows.length; i++) {
                var cell = tableauBody.rows[i].cells[3];
                totalHT += parseFloat(cell.innerHTML) / 1.18;
            }
            var totalTVA = (totalHT * 18) / 100;
            var totalTTC = totalHT + totalTVA;
    
            document.getElementById("totalHT").innerHTML = totalHT.toFixed(2);
            document.getElementById("totalTVA").innerHTML = totalTVA.toFixed(2);
            document.getElementById("totalTTC").innerHTML = totalTTC.toFixed(2);
        }
    </script>

     {{-- Valider pour montant final --}}

    <script>
        function ajouterValider() {
            // Récupérer les valeurs du formulaire
            var montantPercu = parseFloat(document.getElementById("montantPaye").value) || 0;
            var totalTTC = parseFloat(document.getElementById('totalTTC').innerText) || 0;
            var remise = parseFloat(document.getElementById("remise").value) || 0;
        
            var montantRendu = 0;
            var monnaie = 0;
            var montantFinal = 0;
        
            // Vérifications de base
            if (isNaN(montantPercu) || totalTTC === 0) {
                $('#msg30').html('<p class="text-danger"><strong>Veuillez remplir tous les champs correctement.</strong></p>');
                setTimeout(function() {
                    $('#msg30').html('');
                }, 5000);
                return;
            }
        
            // Ajustement du total TTC avec la remise
            var totalApresRemise = totalTTC - remise;
            if (totalApresRemise < 0) totalApresRemise = 0; // éviter un total négatif
        
            // Calcul du montant rendu et de la monnaie
            if (montantPercu < totalApresRemise) {
                montantRendu = totalApresRemise - montantPercu ; // Client doit encore payer
                monnaie = 0;
                montantFinal = montantPercu;
            } else {
                montantRendu = 0;
                monnaie = montantPercu - totalApresRemise; // Monnaie à rendre
                montantFinal = totalApresRemise;
            }
        
            // Mise à jour des champs
            document.getElementById("montantFinal").value = montantFinal.toFixed(2);
            document.getElementById("montantRendu").value = montantRendu.toFixed(2);
            document.getElementById("monnaie").value = monnaie.toFixed(2);
        }
    </script>
        
        {{-- Enregistrer une facture --}}
    
    <script>
        function enregistrerDonnees() {
            var tableauBody = document.getElementById("monTableauBody");
            var date = document.getElementById("date").value;
            var client = document.getElementById("client").value;
            var totalHT = document.getElementById("totalHT").textContent;
            var totalTVA = document.getElementById("totalTVA").textContent;
            var totalTTC = document.getElementById("totalTTC").textContent;
            var montantPaye = document.getElementById("montantPaye").value;
            var monnaie = document.getElementById("monnaie").value;
            var produitType = document.getElementById("produitType").value;
            var remise = document.getElementById("remise").value;
            var montantFinal = document.getElementById("montantFinal").value;
            var montantRendu = document.getElementById("montantRendu").value;

            if (produitType == "" || montantPaye == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Champs obligatoires !",
                    text: "Veuillez remplir tous les champs obligatoires.",
                    confirmButtonColor: "#FF5733"
                });
                return;
            }

            var donnees = [];
            for (var i = 0; i < tableauBody.rows.length; i++) {
                var ligne = tableauBody.rows[i];
                donnees.push({
                    quantite: ligne.cells[0].textContent,
                    produit: ligne.cells[1].textContent,
                    prix: ligne.cells[2].textContent,
                    total: ligne.cells[3].textContent
                });
            }

            $('.valider').hide();

            $.ajax({
                type: "POST",
                url: "{{ route('facture.store') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    donnees: JSON.stringify(donnees),
                    client,
                    date,
                    totalTTC,
                    totalHT,
                    totalTVA,
                    montantPaye,
                    produitType,
                    remise,
                    montantFinal,monnaie,montantRendu
                },
                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Facture enregistrée !",
                        text: "Votre facture a été enregistrée avec succès.",
                        confirmButtonColor: "#4CAF50",
                        timer: 5000,
                        timerProgressBar: true,
                        didClose: () => {
                            window.location.href = "{{ route('accueil.index') }}";
                        }
                    });
                },
                error: function(xhr) {
                    var errorMsg = "Une erreur est survenue, veuillez réessayer.";
                    if (xhr.status === 500) {
                        try {
                            var response = JSON.parse(xhr.responseText);
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
                        timer: 7000,
                        timerProgressBar: true,
                        didClose: () => {
                            window.location.href = "{{ route('accueil.index') }}";
                        }
                    });
                }
            });
        }
    </script>

        {{-- verifier le stock --}}
    <script>
        var quantiteInput = document.getElementById("quantite");
        var produitSelect = document.getElementById("produit");
        var message = document.getElementById("messagePro");
        var previousValue = quantiteInput.value;
        var previousSelectedIndex = produitSelect.selectedIndex;

        quantiteInput.addEventListener("input", function() {
            validateQuantite();
        });

        produitSelect.addEventListener("change", function() {
            validateQuantite();
        });

        function validateQuantite() {
            var selectedOption = produitSelect.options[produitSelect.selectedIndex];
            var stock = parseFloat(selectedOption.getAttribute("data-stock"));
            var quantite = parseFloat(quantiteInput.value);

            if (isNaN(quantite) || isNaN(stock) || quantite <= stock) {
                message.textContent = "";
                quantiteInput.style.borderColor = "";
            } else {
                message.textContent = "Stock insuffisant!";
                quantiteInput.style.borderColor = "red";

                // Efface le champ de quantité après 3 secondes
                setTimeout(function() {
                    quantiteInput.value = "";
                }, 100);
            }

            // Vérifiez si l'utilisateur a changé de produit
            if (produitSelect.selectedIndex !== previousSelectedIndex) {
                quantiteInput.value = "";
                previousSelectedIndex = produitSelect.selectedIndex;
            }

            // Vérifiez si la quantité a été modifiée manuellement
            if (quantiteInput.value !== previousValue) {
                previousSelectedIndex = produitSelect.selectedIndex;
            }
        }
        // Vous pouvez appeler validateQuantite() au chargement de la page pour vérifier la quantité initiale
        validateQuantite();
    </script> 
    

        {{-- Control sur la date --}}
    <script>
        // Récupérer la date d'aujourd'hui
        var dateActuelle = new Date();
        var annee = dateActuelle.getFullYear();
        var mois = ('0' + (dateActuelle.getMonth() + 1)).slice(-2);
        var jour = ('0' + dateActuelle.getDate()).slice(-2);
    
        // Formater la date pour l'attribut value de l'input
        var dateAujourdhui = annee + '-' + mois + '-' + jour;
    
        // Définir la valeur et la propriété max de l'input
        var inputDate = document.getElementById('date');
        inputDate.value = dateAujourdhui;
        inputDate.max = dateAujourdhui;
    </script>


        <!-- JavaScript pour la mise à jour dynamique (Produit type et produit) -->
    <script>
        // Fonction pour mettre à jour la liste des produits en fonction du Produit Type sélectionné
        function updateProduits() {
            var produitTypeSelect = document.getElementById('produitType');
            var produitsSelect = document.getElementById('produit');

            // Obtient la valeur sélectionnée du Produit Type
            var selectedProduitType = produitTypeSelect.value;

            // Efface les options précédentes
            produitsSelect.innerHTML = '<option></option>';

            // Filtrage des produits en fonction du Produit Type sélectionné
            @foreach ($produits as $produit)
                if ("{{ $produit->produitType_id }}" == selectedProduitType) {
                    var option = document.createElement('option');
                    option.value = "{{ $produit->libelle }}";
                    option.setAttribute('data-prix', "{{ $produit->prix }}");
                    option.setAttribute('data-stock', "{{ $produit->stock_actuel }}");

                    option.textContent = "{{ $produit->libelle }}";
                    produitsSelect.appendChild(option);
                }
            @endforeach
        }

        // Ajoute un écouteur d'événements pour détecter les changements dans le Produit Type
        document.getElementById('produitType').addEventListener('change', updateProduits);

        // Appelle la fonction updateProduits initialement pour configurer la liste des produits
        updateProduits();
    </script>

    <!-- CSS pour un style plus propre et uniforme (pour l'entete) -->
    <style>
        
        .select2-container .select2-selection--single {
            height: 38px !important;
        }
        
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
    </style>


@endsection

