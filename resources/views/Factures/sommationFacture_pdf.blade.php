<!-- resources/views/facture/pdf.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture du {{ $dateDebut }} au {{ $dateFin }}</title>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }
    h2, h3 {
        text-align: center;
    }
    table {
        border-collapse: collapse;
        width:100%;
        margin:10px 0;
    }
    table, th, td {
        border:1px solid #ccc;
    }
    th {
        background:#f2f2f2;
    }
</style>
</head>
<body>
    <img src="logop.png" alt="" srcset="">
<h2>APL TRADING</h2>
<h3>Sommaire des factures du {{ date('d/m/Y', strtotime($dateDebut)) }} au {{ date('d/m/Y', strtotime($dateFin)) }}</h3>

<div>
    <strong>Total ventes :</strong> {{ number_format($totalTTCType1, 0, ',', '.') }} CFA<br/>
    {{-- <strong>Total Poissonnerie :</strong> {{ number_format($totalTTCType3, 0, ',', '.') }} CFA --}}
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Date</th>
            <th>Total TTC</th>
            <th>Encaissé</th>
            <th>Reliquat</th>
            <th>Montant Final</th>
            {{-- <th>Type</th> --}}
            <th>Caissier</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($codesFacturesUniques as $factureUnique)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $factureUnique->client_nom }}</td>
            <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>
            <td>{{ number_format($factureUnique->totalTTC, 0, ',', '.') }}</td>
            <td>{{ number_format($factureUnique->montantPaye, 0, ',', '.') }}</td>
            <td>{{ number_format($factureUnique->montantRendu, 0, ',', '.') }}</td>
            <td>{{ number_format($factureUnique->montantFinal, 0, ',', '.') }}</td>
            {{-- <td>{{ $factureUnique->produitType_id == 1 ? 'POISSONNERIE' : 'DIVERS' }}</td> --}}
            <td>{{ $factureUnique->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
