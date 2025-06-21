<table>
    <thead>
        <tr>
            <th colspan="3">
                Sortie de stock  divers du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
            </th>
        </tr>
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
