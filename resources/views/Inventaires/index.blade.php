@extends('layouts.master2')

@section('content')

<section class="content">
    <div class="container">

        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2 mt-3">
                <button class="btn btn-danger" onclick="generatePDF()"><i class="fas fa-download"></i> Générer PDF</button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <div class="card" id="my-table"><br>
                    <div class="row">
                        <div class="col-12 mt-5">
                            <h5>
                                <i class="fas fa-globe mx-3"></i> <b>Léoni's </b> <br>
                                <small class="float-right mx-5"><b>Date:</b> {{ date('d/m/Y', strtotime($today)) }}</small><br><br>
                                <small class="float-left mx-5"><b>IFU :</b> 01234567891011</small><br>
                                <small class="float-left mx-5"><b>Téléphone :</b> (229) 0196472907 / 0161233719 </small>
                            </h5>
                        </div>
                    </div>

                    <div class="card-header">
                        <h5 class="text-center"><b> Écart d'inventaire superette du {{ date('d/m/Y', strtotime($today)) }}</b></h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="text-black">
                                    <tr>
                                        <th>Produits</th>
                                        <th>Type</th>
                                        <th>Stock actuel</th>
                                        <th>Stock Physique</th>
                                        <th>Écart d'Inventaire</th>
                                        <th>PU</th>
                                        <th>Montant d'écart</th>
                                    </tr>
                                </thead>
                                <tbody id="inventaireTableBody">
                                    @foreach ($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->libelle }}</td>
                                        <td>
                                            @if( $produit->produitType_id == 1)
                                                <span class="text-success">SUPERETTE</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($produit->stock_actuel, 2, ',', ' ') }}</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control stock-physique" 
                                                data-stock-actuel="{{ $produit->stock_actuel }}" 
                                                data-prix="{{ $produit->prix }}" 
                                                placeholder="Saisir le stock physique">
                                        </td>
                                        <td class="ecart-inventaire">0.00</td>
                                        <td>{{ number_format($produit->prix, 2, ',', ' ') }} FCFA</td>
                                        <td class="montant-ecart">0.00 FCFA</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="5" class="text-right font-weight-bold">Total Montant d'écart :</td>
                                        <td colspan="2" id="totalEcart" class="font-weight-bold text-danger">0.00 FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Bouton pour afficher les produits avec écart -->
                        <div class="mt-3">
                            <button class="btn btn-warning" id="afficherEcart">Afficher les produits avec écart d'inventaire</button>
                        </div>

                        <!-- Tableau des produits avec écart -->
                        <div id="produitsAvecEcartTable" class="table-responsive mt-4" style="display: none;">
                            <h5 class="text-center text-danger">Produits avec écart d'inventaire</h5>
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="bg-danger text-dark">
                                    <tr>
                                        <th>Produits</th>
                                        <th>Stock actuel</th>
                                        <th>Stock Physique</th>
                                        <th>Écart d'Inventaire</th>
                                        <th>PU</th>
                                        <th>Montant d'écart</th>
                                    </tr>
                                </thead>
                                <tbody id="ecartsProduitsBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const inputs = document.querySelectorAll(".stock-physique");
        const totalEcartElement = document.getElementById("totalEcart");
        const afficherEcartButton = document.getElementById("afficherEcart");
        const ecartsProduitsBody = document.getElementById("ecartsProduitsBody");

        let totalEcart = 0;

        // Gérer les changements dans les champs de stock physique
        inputs.forEach(input => {
            input.addEventListener("input", () => {
                const row = input.closest("tr");
                const stockActuel = parseFloat(input.dataset.stockActuel || 0);
                const prix = parseFloat(input.dataset.prix || 0);
                const stockPhysique = parseFloat(input.value || 0);

                // Calcul de l'écart
                const ecart = stockPhysique - stockActuel;
                const montantEcart = ecart * prix;

                // Mettre à jour les cellules correspondantes
                row.querySelector(".ecart-inventaire").textContent = ecart.toLocaleString("fr-FR", { useGrouping: true, maximumFractionDigits: 0 });
                const montantCell = row.querySelector(".montant-ecart");
                montantCell.textContent = `${montantEcart.toLocaleString("fr-FR", { useGrouping: true, maximumFractionDigits: 0 })} FCFA`;

                // Ajouter ou supprimer la classe rouge si montantEcart est 0
                if (montantEcart === 0) {
                    montantCell.classList.add("text-danger");
                } else {
                    montantCell.classList.remove("text-danger");
                }

                // Recalculer le total des écarts
                recalculerTotalEcart();
            });
        });

        // Fonction pour recalculer le total des montants d'écart
        function recalculerTotalEcart() {
            totalEcart = 0;

            document.querySelectorAll(".montant-ecart").forEach(cell => {
                const montant = parseFloat(cell.textContent.replace(/[^\d.-]/g, "")) || 0;
                totalEcart += montant;
            });

            totalEcartElement.textContent = `${totalEcart.toLocaleString("fr-FR", { useGrouping: true, maximumFractionDigits: 0 })} FCFA`;
        }

        // Afficher les produits avec un écart d'inventaire
        afficherEcartButton.addEventListener("click", () => {
            ecartsProduitsBody.innerHTML = ""; // Réinitialiser le tableau

            document.querySelectorAll("#inventaireTableBody tr").forEach(row => {
                const ecart = parseFloat(row.querySelector(".ecart-inventaire").textContent.replace(",", ".") || 0);

                if (ecart !== 0) {
                    const produit = row.querySelector("td:first-child").textContent;
                    const stockActuel = row.querySelector("td:nth-child(3)").textContent;
                    const stockPhysique = row.querySelector(".stock-physique").value;
                    const prix = row.querySelector("td:nth-child(6)").textContent;
                    const montantEcart = row.querySelector(".montant-ecart").textContent;

                    // Créer une nouvelle ligne pour le tableau des écarts
                    const newRow = document.createElement("tr");
                    newRow.innerHTML = `
                        <td>${produit}</td>
                        <td>${stockActuel}</td>
                        <td>${parseFloat(stockPhysique).toLocaleString("fr-FR", { useGrouping: true, maximumFractionDigits: 0 })}</td>
                        <td class="text-danger font-weight-bold">${ecart.toLocaleString("fr-FR", { useGrouping: true, maximumFractionDigits: 0 })}</td>
                        <td>${prix}</td>
                        <td class="text-danger font-weight-bold">${montantEcart}</td>
                    `;

                    ecartsProduitsBody.appendChild(newRow);
                }
            });

            // Afficher le tableau des écarts
            document.getElementById("produitsAvecEcartTable").style.display = "block";
        });
    });

    // Fonction pour générer le PDF
    function generatePDF() {
        // Récupérer le contenu du tableau HTML
        var element = document.getElementById('my-table');

        // Vérifier si l'élément existe
        if (!element) {
            console.error("Le tableau avec l'ID 'my-table' est introuvable.");
            return;
        }

        // Obtenir la date actuelle
        var today = new Date();

        // Formater la date en yyyy-mm-dd
        var day = ('0' + today.getDate()).slice(-2);
        var month = ('0' + (today.getMonth() + 1)).slice(-2);
        var year = today.getFullYear();

        // Construire la chaîne de date
        var formattedDate = year + '-' + month + '-' + day;

        // Créer le nom de fichier avec la date du jour
        var filename = 'Ecart_inventaire_superette_a_la_date_du' + formattedDate + '.pdf';

        // Options pour la méthode html2pdf
        var opt = {
            margin: [20, 10, 20, 10], // Marges en haut, droite, bas, gauche (en millimètres)
            filename: filename,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2, // Amélioration de la qualité
                useCORS: true, // Charger les ressources externes
                logging: false, // Désactiver les logs pour une meilleure performance
                dpi: 300 // Augmenter la résolution pour améliorer la qualité visuelle
            },
            jsPDF: { 
                unit: 'mm', // Unité de mesure en millimètres
                format: 'a4', // Format A4
                orientation: 'landscape', // Orientation paysage
                autoRotation: true // Rotation automatique si le contenu ne tient pas
            },

            pagebreak: { 
            mode: ['avoid-all', 'css', 'legacy'], // Évite la coupe du tableau
            before: '.page-break', // Ajoute une classe pour forcer les sauts de page
        }
        };

        // Utiliser html2pdf avec les options définies
        html2pdf()
            .from(element) // Le contenu à convertir en PDF
            .set(opt) // Appliquer les options
            .toPdf()
            .get('pdf')
            .then(function (pdf) {
                // Vérifier si le tableau est trop grand et ajuster l'échelle si nécessaire
                var pageHeight = pdf.internal.pageSize.height;
                var contentHeight = element.scrollHeight;

                if (contentHeight > pageHeight) {
                    var scale = pageHeight / contentHeight;
                    pdf.setScale(scale);
                }

                console.log("PDF généré avec des marges et sans coupe de tableau !");
            })
            .save(); // Télécharger le PDF
    }

</script>

@endsection
