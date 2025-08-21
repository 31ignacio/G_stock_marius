<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quittance</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
            /* adapté à 3 cm */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
        }

        .invoice {
            width: 3cm;
            /* largeur réduite */
            padding: 3px;
            margin: 2px auto;
            border: 1px dashed #000;
            background: #fff;
            box-sizing: border-box;
        }

        .invoice-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }

        .invoice-header img {
            width: 15px;
            height: auto;
        }

        .invoice-header h5 {
            font-size: 8px;
            margin: 1px 0;
        }

        .invoice-header div {
            font-size: 6px;
        }

        .invoice-details {
            margin-bottom: 2px;
        }

        .product {
            margin-bottom: 1px;
            border-bottom: 1px dotted #ddd;
            padding-bottom: 1px;
        }

        .product-name {
            font-weight: bold;
            display: block;
            font-size: 7px;
        }

        .product-info {
            display: flex;
            justify-content: space-between;
            font-size: 6px;
        }

        .invoice-total {
            margin-top: 2px;
            font-size: 6px;
        }

        .invoice-total table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-total th,
        .invoice-total td {
            padding: 1px 0;
            text-align: left;
            font-size: 6px;
        }

        .qr-code {
            text-align: center;
            margin-top: 2px;
            font-size: 6px;
        }

        .qr-code img {
            width: 15px;
            margin: 2px 0;
        }

        .cut-line {
            border-top: 1px dashed #000;
            margin: 3px 0;
            width: 100%;
            text-align: center;
            font-size: 6px;
            color: #666;
        }

        .thank-you {
            text-align: center;
            font-size: 7px;
            margin-top: 2px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div>
        {{-- Première quittance --}}
        <div class="invoice">
            <div class="invoice-header">
                <img src="{{ asset('logop.png') }}" alt="Logo">
                <h5>Bon de livraison - Quittance N°{{ $numero }}</h5>
                <div>
                    <div>Ref: {{ $code }}</div>
                    <div>Date: {{ date('d/m/Y', strtotime($date)) }}</div>
                    <div>Opérateur: {{ $factures[0]->user->name ?? 'Caisse' }}</div>
                </div>
            </div>


            <div class="invoice-details">
                @foreach ($factures as $facture)
                    <div class="product">
                        <span class="product-name">{{ $facture->produit }}</span>
                        <span class="product-info">
                            <span>Qté: {{ $facture->quantite }}</span>
                            <span>Prix: {{ number_format($facture->prix, 2, ',', ' ') }}</span>
                            <span>Total: {{ number_format($facture->total, 2, ',', ' ') }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
            <hr>
            <div class="invoice-total">
                <table>
                    @php $infosAffichees = false; @endphp
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                            <tr>
                                <th>Total HT :</th>
                                <td>{{ number_format($facture->totalHT, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>TVA :</th>
                                <td>{{ number_format($facture->totalTVA, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>Total TTC :</th>
                                <td>{{ number_format($facture->totalTTC, 2, ',', ' ') }}</td>
                            </tr>
                            <tr>
                                <th>Encaissé :</th>
                                <td>{{ number_format($facture->montantPaye, 2, ',', ' ') }}</td>
                            </tr>
                            <tr>
                                <th>Solde :</th>
                                <td>{{ number_format(max(0, $facture->montantRendu), 2, ',', ' ') }}</td>
                            </tr>
                            <tr>
                                <th>Remise :</th>
                                <td>{{ number_format($facture->reduction, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>Rendu :</th>
                                <td>{{ number_format($facture->monnaie, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>Total réglé :</th>
                                <td>{{ number_format($facture->montantFinal, 2, ',', ' ') }} CFA</td>
                            </tr>
                            @php $infosAffichees = true; @endphp
                        @endif
                    @endforeach
                </table>
            </div>
            <hr>
            <div class="qr-code">
                RCCM : RB/COT/25 B 40622<br>
                Tél : +229 01 97 93 96 98
            </div>

            <div class="thank-you">Merci & à bientôt</div>
        </div>

        <div class="cut-line">✂ Découper ici</div>

        {{-- Deuxième quittance identique --}}
        <div class="invoice">
            <div class="invoice-header">
                <img src="{{ asset('logop.png') }}" alt="Logo">
                <h5>Bon de livraison - Quittance N°{{ $numero }}</h5>
                <div>
                    <div>Ref: {{ $code }}</div>
                    <div>Date: {{ date('d/m/Y', strtotime($date)) }}</div>
                    <div>Opérateur: {{ $factures[0]->user->name ?? 'Caisse' }}</div>
                </div>
            </div>


            <div class="invoice-details">
                @foreach ($factures as $facture)
                    <div class="product">
                        <span class="product-name">{{ $facture->produit }}</span>
                        <span class="product-info">
                            <span>Qté: {{ $facture->quantite }}</span>
                            <span>Prix: {{ number_format($facture->prix, 2, ',', ' ') }}</span>
                            <span>Total: {{ number_format($facture->total, 2, ',', ' ') }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
            <hr>
            <div class="invoice-total">
                <table>
                    @php $infosAffichees = false; @endphp
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                            <tr>
                                <th>Total HT :</th>
                                <td>{{ number_format($facture->totalHT, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>TVA :</th>
                                <td>{{ number_format($facture->totalTVA, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>Total TTC :</th>
                                <td>{{ number_format($facture->totalTTC, 2, ',', ' ') }}</td>
                            </tr>
                            <tr>
                                <th>Encaissé :</th>
                                <td>{{ number_format($facture->montantPaye, 2, ',', ' ') }}</td>
                            </tr>
                            <tr>
                                <th>Solde :</th>
                                <td>{{ number_format(max(0, $facture->montantRendu), 2, ',', ' ') }}</td>
                            </tr>
                            <tr>
                                <th>Remise :</th>
                                <td>{{ number_format($facture->reduction, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>Rendu :</th>
                                <td>{{ number_format($facture->monnaie, 2, ',', ' ') }} CFA</td>
                            </tr>
                            <tr>
                                <th>Total réglé :</th>
                                <td>{{ number_format($facture->montantFinal, 2, ',', ' ') }} CFA</td>
                            </tr>
                            @php $infosAffichees = true; @endphp
                        @endif
                    @endforeach
                </table>
            </div>
            <hr>
            <div class="qr-code">
                RCCM : RB/COT/25 B 40622<br>
                Tél : +229 01 97 93 96 98
            </div>

            <div class="thank-you">Merci & à bientôt</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
