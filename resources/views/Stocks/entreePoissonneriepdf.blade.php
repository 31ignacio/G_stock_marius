<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Stock Poissonnerie</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>APL TRADING</h2>
    <p>Date de génération : {{ $date->format('d/m/Y') }}</p>
    <h4>Stock enregistré poissonnerie du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Produits</th>
                <th>Quantité</th>
                <th>Auteur</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stocks as $stock)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($stock->date)->format('d/m/Y') }}</td>
                    <td>{{ $stock->libelle }}</td>
                    <td>{{ $stock->total_quantite }}</td>
                    <td>
                        {{ $stock->user ? $stock->user->prenom . ' ' . $stock->user->name : 'Null' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Aucun stock trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
