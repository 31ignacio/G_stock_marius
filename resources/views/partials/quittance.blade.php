<div class="invoice-header">
    <img src="{{ asset('logop.png') }}" alt="Logo">
    <p>Date: {{ date('d/m/Y', strtotime($date)) }}</p>
    <h5>Brouillard</h5>
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
                    <th>Solde à encaissé :</th>
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
    Merci pour votre achat ! À bientôt.
    <hr>
    <small>RCCM: RB/COT/25 B 40622 || Tél : 01 97 93 96 98 </small>
</div>
