<!DOCTYPE html>
<html>
<head>
    <title>Facture Achats du {{ $dateDebut }} au {{ $dateFin }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h3 {
            text-align: center;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table thead {
            background: #f8f9fa;
        }
        .table, .table th, .table td {
            border: 1px solid #ccc;
        }
        .table th, .table td {
            padding: 5px;
            text-align: left;
        }
        .totals {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h3>Sommaire des factures d'achats du {{ date('d/m/Y', strtotime($dateDebut)) }} au {{ date('d/m/Y', strtotime($dateFin)) }}</h3>

    <div class="totals">
        <strong>Total Divers :</strong> {{ number_format($totalTTCType1, 0, ',', '.') }} CFA<br/>
        <strong>Total Poissonnerie :</strong> {{ number_format($totalTTCType3, 0, ',', '.') }} CFA
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                {{-- <th>Code</th> --}}
                <th>Date</th>
                <th>Société</th>
                <th>Total d'Achat</th>
                <th>Total prix de vente</th>
                <th>Total Bénéfice</th>
                <th>Type</th>
                <th>Comptable</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($codesFacturesUniques as $index => $factureUnique)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    {{-- <td>{{ $factureUnique->code }}</td> --}}
                    <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>
                    <td>{{ $factureUnique->societe->societe }}</td>
                    <td>{{ number_format($factureUnique->totalAchat, 0, ',', '.') }} CFA</td>
                    <td>{{ number_format($factureUnique->totalVente, 0, ',', '.') }} CFA</td>
                    <td>{{ number_format($factureUnique->totalBenefice, 0, ',', '.') }} CFA</td>
                    <td>{{ $factureUnique->produitType_id == 1 ? 'POISSONNERIE' : 'DIVERS' }}</td>
                    <td>{{ $factureUnique->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
