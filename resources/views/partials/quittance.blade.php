<div class="invoice-header" style="text-align:center;border-bottom:1px dashed #000;margin-bottom:3px;">
    <img src="{{ asset('logop.png') }}" alt="Logo" style="width:40px;height:auto;">
    <p style="margin:1px;font-size:10px;">Date: {{ date('d/m/Y', strtotime($date)) }}</p>
    <h5 style="margin:2px;font-size:12px;">Brouillard</h5>
</div>

<div class="invoice-address" style="line-height:1.1;border-bottom:1px dashed #000;margin-bottom:3px;font-size:9px;">
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
    <table style="width:100%;border-collapse:collapse;font-size:8px;table-layout:fixed;">
        <thead>
            <tr>
                <th style="border:1px solid #000;padding:2px;width:10%;">Qté</th>
                <th style="border:1px solid #000;padding:2px;width:50%;">Produit</th> 
                <th style="border:1px solid #000;padding:2px;width:20%;">Prix</th>
                <th style="border:1px solid #000;padding:2px;width:20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factures as $facture)
                @if ($facture->date == $date && $facture->code == $code)
                    <tr>
                        <td class="center-text" style="text-align:center;padding:1px;">{{ $facture->quantite }}</td>
                        <td style="padding:1px;">{{ Str::limit($facture->produit, 20) }}</td>
                        <td class="center-text" style="text-align:center;padding:1px;">{{ number_format($facture->prix, 2, ',', ' ') }}</td>
                        <td class="center-text" style="text-align:center;padding:1px;">{{ number_format($facture->total, 2, ',', ' ') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

<div class="invoice-details">
    <table style="width:100%;border-collapse:collapse;font-size:7px;table-layout:fixed;word-wrap:break-word;">
        <thead>
            <tr>
                <th style="border:1px solid #000;padding:1px;width:10%;">Qté</th>
                <th style="border:1px solid #000;padding:1px;width:40%;">Produit</th>
                <th style="border:1px solid #000;padding:1px;width:25%;">Prix</th>
                <th style="border:1px solid #000;padding:1px;width:25%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factures as $facture)
                @if ($facture->date == $date && $facture->code == $code)
                    <tr>
                        <td style="text-align:center;padding:1px;">{{ $facture->quantite }}</td>
                        <td style="padding:1px;">{{ Str::limit($facture->produit, 15) }}</td>
                        <td style="text-align:center;padding:1px;">{{ number_format($facture->prix, 2, ',', ' ') }}</td>
                        <td style="text-align:center;padding:1px;">{{ number_format($facture->total, 2, ',', ' ') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>


<div class="thank-you" style="text-align:center;font-size:8px;margin-top:2px;font-weight:bold;line-height:1.1;">
    Merci pour votre achat ! À bientôt.
    <hr style="margin:2px 0;">
    <small style="font-size:6px;">RCCM: RB/COT/25 B 40622 || Tél : 01 97 93 96 98</small>
</div>
