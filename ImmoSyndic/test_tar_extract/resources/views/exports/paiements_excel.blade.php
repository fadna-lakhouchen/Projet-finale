<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        table { 
            border-collapse: collapse; 
            width: 100%; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        h2 {
            color: #1e293b;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-bottom: 5px;
        }
        p {
            color: #64748b;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        th { 
            background-color: #3b66f5; 
            color: #ffffff; 
            font-weight: bold; 
            border: 1px solid #cbd5e1; 
            padding: 10px; 
            text-align: left; 
        }
        td { 
            border: 1px solid #e2e8f0; 
            padding: 8px; 
            text-align: left; 
            color: #334155;
        }
        .text-rose { 
            color: #e11d48; 
            font-weight: bold; 
        }
        .text-green { 
            color: #16a34a; 
            font-weight: bold; 
        }
        .text-amber {
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Rapport de Suivi des Paiements - ImmoSyn</h2>
    <p>Généré le : {{ date('d/m/Y H:i') }} | Espace Syndic Référent</p>
    <table>
        <thead>
            <tr>
                <th>Résident / Copropriétaire</th>
                <th>Immeuble</th>
                <th>Appartement</th>
                <th>Mois de Charge</th>
                <th>Montant de Charge</th>
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
                    
                    $colorClass = 'text-amber';
                    if ($statutLower === 'payé') {
                        $colorClass = 'text-green';
                    } elseif ($statutLower === 'impayé') {
                        $colorClass = 'text-rose';
                    }
                    
                    $dateFr = \Carbon\Carbon::parse($item->date_echeance)->translatedFormat('F Y');
                    $dateFr = ucfirst($dateFr);
                @endphp
                <tr>
                    <td>{{ $residentName }}</td>
                    <td>{{ $immeubleNom }}</td>
                    <td>{{ $apptNum }}</td>
                    <td>{{ $dateFr }}</td>
                    <td>{{ number_format($item->montant, 2, '.', '') }} DH</td>
                    <td>{{ number_format($totalPaye, 2, '.', '') }} DH</td>
                    <td>{{ number_format($reste, 2, '.', '') }} DH</td>
                    <td class="{{ $colorClass }}">{{ ucfirst($item->statut) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
