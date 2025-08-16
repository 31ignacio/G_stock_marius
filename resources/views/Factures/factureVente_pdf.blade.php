<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Facture Vente</title>
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-size: 12px;
            font-family: Arial, sans-serif;
            line-height: 1.3;
        }

        #container {
            position: relative;
            padding: 1%;
        }

        #header {
            height: 80px;
        }

        #logo {
            float: left;
            text-align: center;
            padding: 10px;
        }

        .logo-text {
            font-weight: 600;
            font-size: 18px;
            text-transform: uppercase;
        }

        #reference {
            float: right;
            text-align: right;
            font-size: 12px;
        }

        #people {
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-start !important;
            font-size: 12px !important;
            margin: 20px 0 !important;
        }

        #people .person {
            font-size: 12px !important;
        }

        #items {
            font-size: 11px;
        }

        #items table {
            width: 100%;
            border-collapse: collapse;
        }

        #items table th,
        #items table td {
            border: 1px solid grey;
            padding: 3px;
        }

        #items table th {
            text-align: left;
            background-color: #405189;
            color: #fff;
        }

        #summary {
            margin: 10px 0;
            font-size: 11px;
        }

        #summary #note {
            float: left;
            font-size: 9px;
        }

        #summary #total table {
            float: right;
            font-size: 11px;
            border-collapse: collapse;
        }

        #summary #total table td {
            padding: 3px;
        }

        #summary #total table th {
            text-align: left;
        }

        #footer {
            position: absolute;
            left: 3%;
            right: 3%;
            bottom: 3%;
            border-top: 1px solid grey;
            text-align: center;
            font-size: 9px;
        }

        .page-number:before {
            content: "Page " counter(page);
        }
    </style>
</head>

<body>
    <div id="container">
        <div id="header">
            <div id="logo">
                <div class="logo-text">
                    <img src="logo.png" alt="" srcset="">
                </div>
            </div>
            <div id="reference">
                <h3>Brouillard</h3>
                <p>Date facturation : {{ date('d/m/Y', strtotime($date)) }}</p>
                @php $infosAffichees = false; @endphp
                @foreach ($factures as $facture)
                    @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                        <address><strong>Référence :</strong> {{ $facture->code }}</address>
                        @php $infosAffichees = true; @endphp
                    @endif
                @endforeach
            </div>
        </div>
        <br><br>
        <!-- CAISSIER & CLIENT SUR LA MÊME LIGNE -->
        <div id="people">
            <div class="person">
                <strong>Caissier :</strong>
                @php $infosAffichees = false; @endphp
                @foreach ($factures as $facture)
                    @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                        {{ $facture->user->name }} | Tél : {{ $facture->user->telephone }}
                        @php $infosAffichees = true; @endphp
                    @endif
                @endforeach
            </div>
            <div class="person">
                <strong>Client :</strong>
               {{ $facture->client_nom }}
            </div>
        </div>

        <!-- TABLEAU PRODUITS -->
        <div id="items">
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factures as $facture)
                        <tr>
                            <td>{{ $facture->produit }}</td>
                            <td>{{ $facture->quantite }}</td>
                            <td>{{ number_format($facture->prix, 0, ',', ' ') }}</td>
                            <td>{{ number_format($facture->total, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOTALS -->
        <div id="summary">
            <div id="note">
                <h4>Note</h4>
                <p>Merci pour votre achat ! À bientôt.</p>
            </div>
            <div id="total">
                @php $facture = $factures->first(); @endphp
                <table>
                    <tr>
                        <th>Total HT :</th>
                        <td>{{ number_format($facture->totalHT, 0, ',', ' ') }} CFA</td>
                    </tr>
                    <tr>
                        <th>Total TVA :</th>
                        <td>{{ number_format($facture->totalTVA, 0, ',', ' ') }} CFA</td>
                    </tr>
                    <tr>
                        <th>Total TTC :</th>
                        <td>{{ number_format($facture->totalTTC, 2, ',', ' ') }} CFA</td>
                    </tr>
                    <tr>
                        <th>Encaissé :</th>
                        <td>{{ number_format($facture->montantPaye, 2, ',', ' ') }} CFA</td>
                    </tr>
                    <tr>
                        <th>Solde à encaissé :</th>
                        <td>
                            @if ($facture->montantPaye > $facture->totalTTC)
                                {{ number_format(0, 2, ',', ' ') }}
                            @else
                                {{ number_format($facture->montantRendu, 2, ',', ' ') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Remise :</th>
                        <td>{{ number_format($facture->reduction, 0, ',', ' ') }} CFA</td>
                    </tr>
                    <tr>
                        <th>Rendu :</th>
                        <td>{{ number_format($facture->monnaie, 0, ',', ' ') }} CFA</td>
                    </tr>
                    <tr>
                        <th>Total Réglé :</th>
                        <td><strong>{{ number_format($facture->montantFinal, 0, ',', ' ') }} CFA</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div id="footer">
        <p>RCCM : RB/COT/25 B 40622 |     Tél : +229 01 97 93 96 98</p>
        <div class="page-number"></div>
    </div>
</body>

</html>
