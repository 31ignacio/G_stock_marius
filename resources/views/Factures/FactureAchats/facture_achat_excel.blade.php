<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Code</th>
            <th>Societe</th>
            <th>Total Bénéfice</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        @foreach($factureAchats as $fa)
            <tr>
                <td>{{ \Carbon\Carbon::parse($fa->date)->format('d/m/Y') }}</td>
                <td>{{ $fa->code }}</td>
                <td>{{ $fa->societe->societe ?? '' }}</td>
                <td>{{ number_format($fa->totalAchat, 0, ',', '.') }}</td>
                <td>{{ number_format($fa->totalVente, 0, ',', '.') }}</td>
                <td>{{ number_format($fa->totalBenefice, 0, ',', '.') }}</td>
                <td>{{ $fa->produitType_id == 1 ? 'POISSONNERIE' : 'DIVERS' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
