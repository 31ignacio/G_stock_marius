@extends('layouts.master2')

@section('content')

<section class="content">
    <div class="container">

        {{-- Bouton PDF --}}
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2 mt-3">
                <button class="btn btn-danger" onclick="generatePDF()">
                    <i class="fas fa-download"></i> Générer PDF
                </button>
            </div>
        </div>

        {{-- Titre --}}
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card" id="my-table">
                    <div class="card-header text-center">
                        <h5><b>Écart d'inventaire Divers du {{ date('d/m/Y', strtotime($today)) }}</b></h5>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <i class="fas fa-globe mx-2"></i> <b>APAL TRADING</b><br>
                                <small><b>IFU :</b> 01234567891011</small><br>
                                <small><b>Téléphone :</b> (229) 0196472907 / 0161233719</small>
                            </div>
                            <div class="col-md-6 text-right">
                                <small><b>Date :</b> {{ date('d/m/Y', strtotime($today)) }}</small>
                            </div>
                        </div>

                        {{-- Tableau --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="bg-light text-black">
                                    <tr>
                                        <th>Produits</th>
                                        <th>Type</th>
                                        <th>Stock théorique</th>
                                        <th>Stock réel</th>
                                        <th>Écart d'Inventaire</th>
                                    </tr>
                                </thead>
                                <tbody id="inventaireTableBody">
                                    @foreach ($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->libelle }}</td>
                                        <td>
                                            @if($produit->produitType_id == 2)
                                                <span class="text-warning">DIVERS</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($produit->stock_actuel, 2, ',', ' ') }}</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control stock-physique"
                                                data-stock-actuel="{{ $produit->stock_actuel }}"
                                                placeholder="Saisir le stock réel">
                                        </td>
                                        <td class="ecart-inventaire">0,00</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div> {{-- col --}}
        </div> {{-- row --}}
    </div> {{-- container --}}
</section>

{{-- JS --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const inputs = document.querySelectorAll(".stock-physique");

            inputs.forEach(input => {
                input.addEventListener("input", () => {
                    const row = input.closest("tr");
                    const stockActuel = parseFloat(input.dataset.stockActuel || 0);
                    const stockPhysique = parseFloat(input.value || 0);
                    const ecart = stockPhysique - stockActuel;

                    row.querySelector(".ecart-inventaire").textContent =
                        ecart.toLocaleString("fr-FR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                });
            });
        });
    </script>

{{-- Mon js pour pdf --}}
    <script>
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
            var filename = 'Ecart_inventaire_divers_a_la_date_du' + formattedDate + '.pdf';

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
