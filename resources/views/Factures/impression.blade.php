<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factures Imprimées</title>
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
        .page {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .invoice {
            padding: 10px;
            border: 1px solid #000;
            width: 78mm;
            margin-bottom: 10px;
        }
        .cut-line {
            border-top: 2px dashed #000;
            margin: 5px 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
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
        .invoice-address {
            line-height: 1.5;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
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
    <div class="page">
        
        {{-- Première quittance --}}
        <div class="invoice">
            @include('partials.quittance', ['factures' => $factures, 'date' => $date, 'code' => $code])
        </div>

        <div class="cut-line">✂ Découper ici</div>

        {{-- Deuxième quittance (copie) --}}
        <div class="invoice">
            @include('partials.quittance', ['factures' => $factures, 'date' => $date, 'code' => $code])
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
