<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement #{{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 font-sans text-gray-800">

    <div class="max-w-3xl mx-auto bg-white p-10 rounded-lg shadow-md border border-gray-200">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-blue-600">{{ __('ImmoSyn') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Gestion transparente de votre copropriété') }}</p>
                
                <div class="mt-4">
                    <p class="font-semibold text-gray-700">{{ __('Syndic responsable :') }}</p>
                    <p>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-700 uppercase tracking-wider">{{ __('Reçu') }}</h2>
                <p class="text-gray-500 mt-1">N° REF-{{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-gray-500">Date : {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Info Résident & Immeuble -->
        <div class="flex justify-between mb-8">
            <div>
                <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">{{ __('Détails du Résident') }}</h3>
                <p class="font-bold text-lg">{{ $paiement->user->nom }} {{ $paiement->user->prenom }}</p>
                <p class="text-gray-600">{{ $paiement->user->email }}</p>
                <p class="text-gray-600">{{ $paiement->user->telephone ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">{{ __('Détails de l\'Immeuble') }}</h3>
                <p class="font-bold text-lg">{{ $paiement->charge->appartement->immeuble->nom }}</p>
                <p class="text-gray-600">Appartement : {{ $paiement->charge->appartement->numero }}</p>
                <p class="text-gray-600">{{ $paiement->charge->appartement->immeuble->adresse }}</p>
                <p class="text-gray-600">{{ $paiement->charge->appartement->immeuble->ville }}</p>
            </div>
        </div>

        <!-- Détails du paiement -->
        <div class="mb-8">
            <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-4">{{ __('Détails de la transaction') }}</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="py-3 px-4 font-semibold text-gray-700">{{ __('Description') }}</th>
                        <th class="py-3 px-4 font-semibold text-gray-700">{{ __('Mois/Année') }}</th>
                        <th class="py-3 px-4 font-semibold text-gray-700 text-right">{{ __('Montant') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 px-4 text-gray-800">
                            {{ __('Paiement de charge commune') }}<br>
                            <span class="text-sm text-gray-500">{{ __('Méthode :') }} {{ ucfirst($paiement->methode_paiement) }}</span>
                            @if($paiement->reference_transaction)
                                <br><span class="text-sm text-gray-500">{{ __('Réf:') }} {{ $paiement->reference_transaction }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-gray-800">
                            {{ \Carbon\Carbon::parse($paiement->charge->date_echeance)->translatedFormat('F Y') }}
                        </td>
                        <td class="py-4 px-4 text-right font-bold text-gray-800">
                            {{ number_format($paiement->montant, 2, ',', ' ') }} {{ __('DH') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="flex justify-end mb-8">
            <div class="w-1/2 bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-700">{{ __('Total Payé :') }}</span>
                    <span class="text-2xl font-bold text-blue-700">{{ number_format($paiement->montant, 2, ',', ' ') }} {{ __('DH') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-6 mt-8 text-center text-sm text-gray-500">
            <p>{{ __('Ce document sert de reçu officiel pour le paiement des charges de copropriété.') }}</p>
            <p>{{ __('Merci pour votre coopération.') }}</p>
        </div>

    </div>

    <!-- Actions (No Print) -->
    <div class="max-w-3xl mx-auto mt-6 text-center no-print flex justify-center space-x-4">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            {{ __('Imprimer le Reçu') }}
        </button>
        <a href="{{ route('syndic.paiements') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow inline-flex items-center">
            {{ __('Retour') }}
        </a>
    </div>

</body>
</html>
