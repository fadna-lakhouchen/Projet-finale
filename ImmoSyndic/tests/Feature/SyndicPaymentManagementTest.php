<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Immeuble;
use App\Models\Appartement;
use App\Models\Charge;
use App\Models\Paiement;

class SyndicPaymentManagementTest extends TestCase
{
    use RefreshDatabase;

    private $syndic;
    private $resident;
    private $appartement;
    private $charge1;
    private $charge2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Créer un utilisateur syndic
        $this->syndic = User::create([
            'nom' => 'Alami',
            'prenom' => 'Said',
            'email' => 'said@syndic.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $this->syndic->email_verified_at = now();
        $this->syndic->save();

        // 2. Créer un immeuble géré par ce syndic
        $immeuble = Immeuble::create([
            'nom' => 'Résidence Al Amal',
            'adresse' => 'Boulevard Hassan II',
            'ville' => 'Rabat',
            'nombre_etages' => 5,
            'nombre_appartements' => 20,
            'syndic_id' => $this->syndic->id,
        ]);

        // 3. Créer un appartement
        $this->appartement = Appartement::create([
            'immeuble_id' => $immeuble->id,
            'numero' => '12',
            'etage' => 3,
            'superficie' => 95.00,
            'type' => 'F4',
            'statut' => 'occupé',
        ]);

        // 4. Créer un résident et l'associer à l'appartement
        $this->resident = User::create([
            'nom' => 'Tazi',
            'prenom' => 'Fatima',
            'email' => 'fatima@resident.com',
            'password' => bcrypt('password'),
            'role' => 'resident',
            'is_active' => true,
        ]);
        $this->resident->email_verified_at = now();
        $this->resident->save();
        $this->resident->appartements()->attach($this->appartement->id, [
            'type_resident' => 'Copropriétaire',
            'date_entree' => now(),
        ]);

        // 5. Créer deux cotisations (Charges)
        $this->charge1 = Charge::create([
            'appartement_id' => $this->appartement->id,
            'titre' => 'Cotisation Janvier 2026',
            'montant' => 850.00,
            'date_echeance' => '2026-01-25',
            'statut' => 'impayé',
        ]);

        $this->charge2 = Charge::create([
            'appartement_id' => $this->appartement->id,
            'titre' => 'Cotisation Février 2026',
            'montant' => 850.00,
            'date_echeance' => '2026-02-25',
            'statut' => 'impayé',
        ]);
    }

    /**
     * Test de la suppression d'un versement par le syndic.
     * Le statut de la cotisation doit être recalculé à "impayé".
     */
    public function test_syndic_can_delete_payment_and_recalculate_charge_status(): void
    {
        $this->actingAs($this->syndic);

        // Créer un paiement lié à la charge 1
        $paiement = Paiement::create([
            'charge_id' => $this->charge1->id,
            'user_id' => $this->resident->id,
            'montant' => 850.00,
            'date_paiement' => now()->format('Y-m-d'),
            'mode_paiement' => 'Espèces',
            'statut' => 'validé',
        ]);

        // Mettre à jour le statut de la cotisation (simulant la logique de store)
        $this->charge1->update(['statut' => 'payé']);
        $this->assertEquals('payé', $this->charge1->fresh()->statut);

        // Envoyer la requête DELETE
        $response = $this->delete(route('syndic.paiements.destroy', $paiement->id));

        // Vérifier la redirection et la suppression en base de données
        $response->assertStatus(302);
        $this->assertDatabaseMissing('paiements', ['id' => $paiement->id]);

        // Vérifier que la cotisation est revenue en statut impayé
        $this->assertEquals('impayé', $this->charge1->fresh()->statut);
    }

    /**
     * Test de la modification d'un versement (changement de cotisation).
     * Les statuts de l'ancienne et de la nouvelle cotisation doivent être mis à jour.
     */
    public function test_syndic_can_update_payment_and_recalculate_both_charges_status(): void
    {
        $this->actingAs($this->syndic);

        // Créer un paiement initial pour la charge 1
        $paiement = Paiement::create([
            'charge_id' => $this->charge1->id,
            'user_id' => $this->resident->id,
            'montant' => 850.00,
            'date_paiement' => now()->format('Y-m-d'),
            'mode_paiement' => 'Espèces',
            'statut' => 'validé',
        ]);
        $this->charge1->update(['statut' => 'payé']);

        $this->assertEquals('payé', $this->charge1->fresh()->statut);
        $this->assertEquals('impayé', $this->charge2->fresh()->statut);

        // Envoyer la requête PUT pour déplacer le paiement vers la charge 2
        $response = $this->put(route('syndic.paiements.update', $paiement->id), [
            'charge_id' => $this->charge2->id,
            'montant' => 850.00,
            'date_paiement' => now()->format('Y-m-d'),
            'statut' => 'validé',
        ]);

        // Vérifier la redirection
        $response->assertStatus(302);

        // L'ancienne cotisation doit repasser en impayé
        $this->assertEquals('impayé', $this->charge1->fresh()->statut);

        // La nouvelle cotisation doit passer en payé
        $this->assertEquals('payé', $this->charge2->fresh()->statut);

        // Le paiement en DB doit pointer vers la charge 2
        $this->assertEquals($this->charge2->id, $paiement->fresh()->charge_id);
    }
}
