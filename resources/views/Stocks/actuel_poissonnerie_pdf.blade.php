<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Stocks Poissonnerie PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        .total {
            font-weight: bold;
            color: #0d6efd;
        }

        .title {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="title">
        📦 <strong>Stocks actuels - Poissonnerie</strong><br>
        <small>Date : {{ $date }}</small>
    </div>

    @php
        $totalGeneral = 0;
        $totalPrixVenteTotal = 0;
        $totalMarge = 0;
    @endphp

    <table>
        <thead class="bg-secondary text-white">
            <tr>
                <th>Produits</th>
                <th>Quantité</th>
                @if (auth()->user()->role_id == 1)
                    <th>CRu</th>
                    <th>Coût de revient total</th>
                @endif
                <th>P.V</th>
                @if (auth()->user()->role_id == 1)
                    <th>P.V Total</th>
                    <th>Marge Brute</th>
                @endif
               
            </tr>
        </thead>

        <tbody>
            @foreach ($produits as $produit)
                @php
                    $total = $produit->stock_actuel * $produit->prixAchat;
                    $totalVente = $produit->stock_actuel * $produit->prix;
                    $marge = $totalVente - $total;
                    $totalGeneral += $total;
                    $totalPrixVenteTotal += $totalVente;
                    $totalMarge += $marge;
                @endphp
                <tr>
                    <td>{{ $produit->libelle }}</td>
                    <td>{{ number_format($produit->stock_actuel, 2, '.', ' ') }}</td>
                    @if (auth()->user()->role_id == 1)
                        <td>{{ number_format($produit->prixAchat, 0, '.', ' ') }} </td>
                        <td>{{ number_format($total, 0, '.', ' ') }}</td>
                    @endif
                    <td>{{ number_format($produit->prix, 0, '.', ' ') }} </td>
                    @if (auth()->user()->role_id == 1)
                        <td>{{ number_format($totalVente, 0, '.', ' ') }} </td>
                        <td>{{ number_format($marge, 0, '.', ' ') }} </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        @if (auth()->user()->role_id == 1)
            <tfoot class="bg-light">
                <tr>
                    <th colspan="3" class="text-end align-middle">Total Coût de Revient :</th>
                    <th><span class="text-primary fw-bold">{{ number_format($totalGeneral, 0, '.', ' ') }} FCFA</span>
                    </th>

                    <th class="text-end align-middle">Total P.V :</th>
                    <th><span class="text-success fw-bold">{{ number_format($totalPrixVenteTotal, 0, '.', ' ') }}FCFA</span></th>

                    <th class="text-danger fw-bold">{{ number_format($totalMarge, 0, '.', ' ') }} FCFA</th>
                </tr>
            </tfoot>
        @endif
    </table>

</body>

</html>
