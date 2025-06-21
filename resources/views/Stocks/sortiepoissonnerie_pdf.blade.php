<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PDF Sortie de Stock</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h4 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: left;
        }

        .title {
            text-align: center;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h3 class="title">Sortie de Stock Poissonnerie</h3>
    <p><strong>Période :</strong> {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factures as $facture)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}</td>
                    <td>{{ $facture->produit }}</td>
                    <td>{{ $facture->total_quantite }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
