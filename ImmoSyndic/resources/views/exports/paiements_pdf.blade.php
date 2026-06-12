<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Financier - ImmoSyndic</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            color: #1e293b; 
            margin: 45px; 
            line-height: 1.5;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 2px solid #e2e8f0; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .logo-title { 
            font-size: 26px; 
            font-weight: 800; 
            color: #3b66f5; 
            letter-spacing: -0.5px;
        }
        .logo-title span {
            color: #8b5cf6;
        }
        .report-info { 
            text-align: right; 
            font-size: 12px; 
            color: #64748b; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 13px; 
        }
        th { 
            background-color: #f8fafc; 
            color: #475569; 
            font-weight: bold; 
            border-bottom: 2px solid #cbd5e1; 
            padding: 12px 10px; 
            text-align: left; 
        }
        td { 
            border-bottom: 1px solid #e2e8f0; 
            padding: 12px 10px; 
            text-align: left; 
            color: #334155;
        }
        .badge { 
            display: inline-block; 
            padding: 4px 10px; 
            border-radius: 9999px; 
            font-size: 10px; 
            font-weight: bold; 
            text-transform: uppercase;
        }
        .badge-success { 
            background-color: #dcfce7; 
            color: #15803d; 
        }
        .badge-warning { 
            background-color: #fef3c7; 
            color: #b45309; 
        }
        .badge-danger { 
            background-color: #fee2e2; 
            color: #b91c1c; 
        }
        .total-box { 
            margin-top: 40px; 
            display: flex; 
            justify-content: flex-end; 
        }
        .total-card { 
            background-color: #f8fafc; 
            border: 1px solid #e2e8f0; 
            border-radius: 14px; 
            padding: 18px 25px; 
            min-width: 280px; 
        }
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 8px; 
            font-size: 13px; 
            color: #475569;
        }
        .total-final { 
            display: flex; 
            justify-content: space-between; 
            font-weight: bold; 
            font-size: 16px; 
            border-top: 1px solid #cbd5e1; 
            padding-top: 8px; 
            margin-top: 8px; 
        }
        @media print {
            body { 
                margin: 20px; 
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="logo-title">Immo<span>Syndic</span></div>
            <div style="font-size: 14px; color: #475569; margin-top: 5px; font-weight: 600;">Rapport de Suivi Financier & Recettes</div>
        </div>
        <div class="report-info">
            <div>Généré le : <strong>{{ date('d/m/Y H:i') }}</strong></div>
            <div>Statut comptable : <strong>Toutes les charges de l'année</strong></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Résident / Copropriétaire</th>
                <th>Immeuble</th>
                <th>Appartement</th>
                <th>Période</th>
                <th>Montant Charge</th>
                <th>Montant Réglé</th>
                <th>Reste à Payer</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chargesList as $item)
                @php
                    $resident = $item->appartement ? $item->appartement->residents->first() : null;
                    $residentName = $resident ? "{$resident->prenom} {$resident->nom}" : 'Non assigné';
                    $immeubleNom = $item->appartement ? $item->appartement->immeuble->nom : 'N/A';
                    $apptNum = $item->appartement ? "Appt " . $item->appartement->numero : 'N/A';
                    
                    $totalPaye = $item->paiements->where('statut', 'validé')->sum('montant');
                    $reste = $item->montant - $totalPaye;
                    $statutLower = strtolower($item->statut);
                    
                    $badgeClass = 'badge-warning';
                    if ($statutLower === 'payé') {
                        $badgeClass = 'badge-success';
                    } elseif ($statutLower === 'impayé') {
                        $badgeClass = 'badge-danger';
                    }
                    
                    $dateFr = \Carbon\Carbon::parse($item->date_echeance)->translatedFormat('F Y');
                    $dateFr = ucfirst($dateFr);
                @endphp
                <tr>
                    <td><strong>{{ $residentName }}</strong></td>
                    <td>{{ $immeubleNom }}</td>
                    <td>{{ $apptNum }}</td>
                    <td>{{ $dateFr }}</td>
                    <td>{{ number_format($item->montant, 2) }} DH</td>
                    <td>{{ number_format($totalPaye, 2) }} DH</td>
                    <td style="color: {{ $reste > 0 ? '#b91c1c' : '#475569' }}; font-weight: {{ $reste > 0 ? 'bold' : 'normal' }}">{{ number_format($reste, 2) }} DH</td>
                    <td>
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->statut) }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-card">
            @php
                $totalCharges = $chargesList->sum('montant');
                $totalRegle = $chargesList->map(function($c) { return $c->paiements->where('statut', 'validé')->sum('montant'); })->sum();
                $totalRestant = $totalCharges - $totalRegle;
            @endphp
            <div class="total-row">
                <span>Total de charges facturées :</span>
                <span>{{ number_format($totalCharges, 2) }} DH</span>
            </div>
            <div class="total-row" style="color: #16a34a; font-weight: 600;">
                <span>Total perçu (Réglé) :</span>
                <span>{{ number_format($totalRegle, 2) }} DH</span>
            </div>
            <div class="total-final" style="color: #e11d48;">
                <span>Solde Restant Dû :</span>
                <span>{{ number_format($totalRestant, 2) }} DH</span>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
