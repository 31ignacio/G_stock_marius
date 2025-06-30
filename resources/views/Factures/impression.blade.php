<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Imprimée</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .invoice {
            padding: 10px;
            border: 1px solid #000;
            margin: 10px auto;
            width: 78mm; /* Ajusté pour les marges internes */
        }
        .invoice-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .invoice-header h5 {
            margin: 5px 0;
            font-size: 16px;
        }
        .invoice-header p {
            margin: 0;
            font-size: 12px;
        }
        .invoice-address, .invoice-details, .invoice-total {
            margin-bottom: 10px;
        }
        .invoice-address {
            line-height: 1.5;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            text-align: left;
            padding: 4px;
            font-size: 12px;
            border: 1px solid #000;
        }
        th {
            text-align: center;
        }
        .total-table th, .total-table td {
            font-size: 14px;
            text-align: right;
        }
        .total-table th {
            width: 50%;
            text-align: left;
        }
        .total-table td {
            font-weight: bold;
        }
        .center-text {
            text-align: center;
        }
        .thank-you {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <h5><b>APAL TRADING</b></h5>
            <p>Date: {{ date('d/m/Y', strtotime($date)) }}</p>
        </div>

        <div class="invoice-address">
            @php $infosAffichees = false; @endphp
            @foreach ($factures as $facture)
                @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                    <p><strong>Ref :</strong> {{ $facture->code ?? '00000000' }}</p>
                    <p><strong>Caisier :</strong> {{ $facture->user->name ?? 'Caisse' }}</p>
                    <p><strong>Client :</strong> {{ $facture->client_nom ?? 'Client' }}</p>
                    @php $infosAffichees = true; @endphp
                @endif
            @endforeach
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>Qté</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factures as $facture)
                        @if ($facture->date == $date && $facture->code == $code)
                            <tr>
                                <td class="center-text">{{ $facture->quantite }}</td>
                                <td>{{ $facture->produit }}</td>
                                <td class="center-text">{{ number_format($facture->prix, 2, ',', ' ') }}</td>
                                <td class="center-text">{{ number_format($facture->total, 2, ',', ' ') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="invoice-total">
            <table class="total-table">
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
                            <th>Total TTC</th>
                            <td>{{ number_format($facture->totalTTC, 2, ',', ' ') }} </td>
                        </tr>
                        <tr>
                            <th>Encaissé :</th>
                            <td>{{ number_format($facture->montantPaye, 2, ',', ' ') }} </td>
                        </tr>
                        <tr>
                            <th>Reliquat :</th>
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

        <div class="thank-you">
            Merci pour votre achat ! <br>
            À bientôt chez <b>APAL TRADING</b>.
            <hr>
              <small> Tél : 0101010101 / 0102020202 </small>
        </div>
    </div>
       

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>
</html>
