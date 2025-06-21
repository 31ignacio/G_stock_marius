
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Facture </title>
    </head>

    <body>

        <style>
            @page {
                margin-top: 100px;
                /* create space for header */
                margin-bottom: 70px;
                /* create space for footer */
            }

            header {
                position: fixed;
                left: 0px;
                top: -180px;
                right: 0px;
                height: 150px;
                background-color: orange;
                text-align: center;
            }

            #footer .page:after {
                container: counter(page, upper-roman);
            }
        </style>

        <style>
            /* @import "https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700"; */

            html,
            body,
            div,
            span,
            applet,
            object,
            iframe,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            blockquote,
            pre,
            a,
            abbr,
            acronym,
            address,
            big,
            cite,
            code,
            del,
            dfn,
            em,
            img,
            ins,
            kbd,
            q,
            s,
            samp,
            small,
            strike,
            strong,
            sub,
            sup,
            tt,
            var,
            b,
            u,
            i,
            center,
            dl,
            dt,
            dd,
            ol,
            ul,
            li,
            fieldset,
            form,
            label,
            legend,
            table,
            caption,
            tbody,
            tfoot,
            thead,
            tr,
            th,
            td,
            article,
            aside,
            canvas,
            details,
            embed,
            figure,
            figcaption,
            footer,
            header,
            hgroup,
            menu,
            nav,
            output,
            ruby,
            section,
            total,
            time,
            mark,
            audio,
            video {
                margin: 0;
                padding: 0;
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline
            }

            article,
            aside,
            details,
            figcaption,
            figure,
            footer,
            header,
            hgroup,
            menu,
            nav,
            section {
                display: block
            }

            body {
                line-height: 1
            }

            ol,
            ul {
                list-style: none
            }

            blockquote,
            q {
                quotes: none
            }

            blockquote:before,
            blockquote:after,
            q:before,
            q:after {
                content: '';
                content: none
            }

            table {
                border-collapse: collapse;
                border-spacing: 0
            }

            body {
                height: 1120px;
                width: 700px;
                margin: auto;
                font-family: 'Open Sans', sans-serif;
                font-size: 15px
            }

            strong {
                font-weight: 700
            }

            #container {
                position: relative;
                padding: 1%
            }

            #header {
                height: 100px
            }

            #header>#reference {
                float: right;
                text-align: right
            }

            #header>#reference h3 {
                margin: 0
            }

            #header>#reference h4 {
                margin: 0;
                font-size: 85%;
                font-weight: 700
            }

            #header>#reference p {
                margin: 0;
                margin-top: 2%;
                font-size: 85%
            }

            #header>#logo {
                width: 50%;
                float: left
            }

            #fromto {
                height: 160px
            }

            #fromto>#from,
            #fromto>#to {
                width: 45%;
                min-height: 150px;
                margin-top: 30px;
                font-size: 85%;
                padding: 1.5%;
                line-height: 120%
            }

            #fromto>#from {
                float: left;
                width: 45%;
                background: #efefef;
                margin-top: 30px;
                font-size: 85%;
                padding: 1.5%
            }

            #fromto>#to {
                float: right;
                border: solid grey 1px
            }

            #items {
                margin-top: 10px
            }

            #items>p {
                font-weight: 700;
                text-align: right;
                margin-bottom: 1%;
                font-size: 65%
            }

            #items>table {
                width: 100%;
                font-size: 85%;
                border: solid grey 1px
            }

            #items>table th:first-child {
                text-align: left
            }

            #items>table th {
                font-weight: 400;
                padding: 1px 4px
            }

            #items>table td {
                padding: 1px 4px
            }

            #items>table th:nth-child(2),
            #items>table th:nth-child(4) {
                width: 45px
            }

            #items>table th:nth-child(3) {
                width: 60px
            }

            #items>table th:nth-child(5) {
                width: 80px
            }

            #items>table tr td:not(:first-child) {
                text-align: right;
                padding-right: 1%
            }

            #items table td {
                border-right: solid grey 1px
            }

            #items table tr td {
                padding-top: 10px;
                padding-bottom: 3px;
                height: 10px
            }

            #items table tr:nth-child(1) {
                border: solid grey 1px
            }

            #items table tr th {
                border-right: solid grey 1px;
                padding: 3px
            }

            #items table tr:nth-child(2)>td {
                padding-top: 8px
            }

            #summary {
                height: 170px;
                margin-top: 30px
            }

            #summary #note {
                float: left;
                width:60%;
            }

            #summary #note h4 {
                font-size: 10px;
                font-weight: 700;
                font-style: italic;
                margin-bottom: 4px
            }

            #summary #note p {
                font-size: 10px;
                font-style: italic
            }

            #summary #total table {
                font-size: 85%;
                width: 260px;
                float: right
            }

            #summary #total table td {
                padding: 3px 4px
            }

            #summary #total table tr td:last-child {
                text-align: right
            }

            #summary #total table tr:nth-child(3) {
                background: #efefef;
                font-weight: 700
            }

            #footer {
                margin: auto;
                position: absolute;
                left: 4%;
                bottom: 4%;
                right: 4%;
                border-top: solid grey 1px
            }

            #footer p {
                margin-top: 1%;
                font-size: 65%;
                line-height: 140%;
                text-align: center
            }

            #footer .page-number {
                font-size: 10px;
                text-align: right;
            }

            .page-number:before {
                content: "Page " counter(page);
            }
            .status { padding: 5px 10px;
                        border-radius: 5px; background-color: #28a745; 
                        color: white; font-weight: bold; 
                    }
        </style>

        <script type="text/php">
            if (isset($pdf)) {
                $text = "page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>

        <div id="footer">
        
            <p style="margin-top: 2%;margin-bottom:2%">
            
                Merci pour votre achat !  À bientôt chez G_STOCK.
            </p>
            <hr>
            <p>
                IFU : 0201810341704 | RCCM : N° RB/COT/18-A-38736 | Tél : +229 0161105005/ 0194515453 - Godomey
            
            </p>

            <div class="page-number"></div>
        </div>

        <div id="container">
            <div id="header" style="padding-top: 8%">
                <div id="logo">
                    <i class="logo-text">G_STOCK</i>
                    {{-- <img style="pointer-events: none; position: relative; height: 80%; z-index: -1;" src="logo.jpg" alt="Léoni's"> --}}
                </div>
                
                <style>
                    #logo {
                        text-align: center; /* Centrer le texte et l'image */
                        position: relative;
                        padding: 20px;
                    }
                
                    .logo-text {
                        font-family: 'Poppins', sans-serif; /* Police élégante */
                        font-weight: 600; /* Mettre en gras */
                        font-size: 25px; /* Taille du texte */
                    
                        text-transform: uppercase; /* Texte en majuscules */
                        letter-spacing: 2px; /* Espacement entre les lettres */
                        position: relative;
                        z-index: 1;
                        display: inline-block;
                    }
                
                    /* Effet d'ombre portée sur le texte pour le rendre plus élégant */
                    .logo-text::after {
                        content: "";
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        /* background: rgba(0, 0, 0, 0.2); */
                        z-index: -1;
                        filter: blur(5px);
                        transform: scale(1.1);
                    }
                
                    #logo img {
                        display: block;
                        margin: 0 auto;
                        max-width: 120px; /* Limiter la taille de l'image */
                    }
                </style>
                
                <div id="reference">
                    <h3><strong>Facture de vente</strong></h3><br>
                    <p>Date facturation : {{ date('d/m/Y', strtotime($date)) }}</p> <br>
                    @php $infosAffichees = false; @endphp
                        @foreach ($factures as $facture)
                            @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                                <address><strong>Référence :</strong> {{ $facture->code }}</address>
                                @php $infosAffichees = true; @endphp
                            @endif
                        @endforeach
                
                </div>
            </div>


            <div id="fromto">
                <div id="from">
                    <p>
                        <strong style="text-align: center">Caissier</strong><br><br>
                        @php $infosAffichees = false; @endphp
                        @foreach ($factures as $facture)
                            @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                                <address> {{ $facture->user->name }}</address><br>
                                <address>  Tél :{{ $facture->user->telephone }}</address>

                                @php $infosAffichees = true; @endphp
                            @endif
                        @endforeach
                    </p>
                </div>
                <div id="to">
                    <p>
                        <p>
                            <strong style="text-align: center"> Client</strong><br><br>
                            @php $infosAffichees = false; @endphp
                            @foreach ($factures as $facture)
                                @if ($facture->date == $date && $facture->code == $code && !$infosAffichees)
                                    <address> {{ $facture->client_nom }}</address>
                                    @php $infosAffichees = true; @endphp
                                @endif
                            @endforeach    
                        </p>
                        
                    </p>
                </div>
            </div>

            <div id="items" style="margin-top:10%">
                {{-- <p>Montants exprimés en Euros</p> --}}
                <table>
                    
                    <thead>
                        
                        <tr style="background-color:#405189;">
                            <th style="color:#FFF; font-size:12px;">Désignations</th>
                            <th style="color:#FFF; font-size:12px;">Quantité</th>
                            <th style="color:#FFF; font-size:12px;">Prix Unitaire</th>
                            <th style="color:#FFF; font-size:12px;">Total</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        @foreach ($factures as $facture)
                            <tr>
                                <td>{{ $facture->produit }}</td>
                                <td>{{ $facture->quantite }}</td>
                                <td>{{ number_format($facture->prix, 0, ',', ' ') }}</td>
                                <td>{{ number_format($facture->total, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
            
                </table>
                <br />
                
            </div>

            <div id="summary">
                
                <div id="total">
                    {{-- <table border="1">
                        
                        <tr>
                            <td>Total</td>
                            <td><strong> CFA</strong></td>
                        </tr>
                        <span class="invoice-head-top-right" style="display: block; text-align: right;">
                            <img src="cachet.png" alt="Cachet" style="max-width: 110px;">
                        </span>
                    </table> --}}

                    @php $facture = $factures->first(); @endphp
                    <table border="10">
                        <tr>
                            <th>Total HT :</th>
                            <td>{{ number_format($facture->totalHT, 0, ',', ' ') }} CFA</td>
                        </tr><br>
                        <tr>
                            <th>Total TVA :</th>
                            <td>{{ number_format($facture->totalTVA, 0, ',', ' ') }} CFA</td>
                        </tr><br>
                        <tr>
                            <th>Total TTC</th>
                            <td>{{ number_format($facture->totalTTC, 2, ',', ' ') }} </td>
                        </tr><br>
                        <tr>
                            <th>Encaissé :</th>
                            <td>{{ number_format($facture->montantPaye, 2, ',', ' ') }} </td>
                        </tr><br>
                        <tr>
                            <th>Reliquat :</th>
                            <td>
                                @if ($facture->montantPaye > $facture->totalTTC)
                                    {{ number_format(0, 2, ',', ' ') }}
                                @else
                                    {{ number_format($facture->montantRendu, 2, ',', ' ') }}
                                @endif
                            </td>
                        </tr> <br>
                        
                        <tr>
                            <th>Remise :</th>
                            <td>{{ number_format($facture->reduction, 0, ',', ' ') }} CFA</td>
                        </tr><br>
                        <tr>
                            <th>Rendu :</th>
                            <td>{{ number_format($facture->monnaie, 0, ',', ' ') }} CFA</td>
                        </tr><br>
                        <tr>
                            <th>Total Réglé :</th>
                            <td><strong>{{ number_format($facture->montantFinal, 0, ',', ' ') }} CFA</strong></td>
                        </tr>
                    </table>
            
                </div>
            </div>
        
        </div>

    </body>

</html>

