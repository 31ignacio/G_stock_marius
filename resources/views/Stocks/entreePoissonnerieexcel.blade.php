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
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ \Carbon\Carbon::parse($stock->date)->format('d/m/Y') }}</td>
                <td>{{ $stock->libelle }}</td>
                <td>{{ $stock->total_quantite }}</td>
                <td>{{ $stock->user ? $stock->user->prenom . ' ' . $stock->user->name : 'Null' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
