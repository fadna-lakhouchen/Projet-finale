<?php

namespace App\Http\Controllers;

use App\Models\Immeuble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImmeubleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'syndic_id' => 'nullable|exists:users,id',
            'nombre_etages' => 'nullable|integer',
            'nombre_appartements' => 'nullable|integer',
        ]);

        Immeuble::create($request->all());

        return back()->with('success', 'Immeuble ajouté avec succès.');
    }

    public function update(Request $request, $id)
    {
        $immeuble = Immeuble::findOrFail($id);
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'syndic_id' => 'nullable|exists:users,id',
        ]);

        $immeuble->update($request->all());

        return back()->with('success', 'Immeuble mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $immeuble = Immeuble::findOrFail($id);
        $immeuble->delete();
        return back()->with('success', 'Immeuble supprimé avec succès.');
    }

    // Syndic space actions
    public function storeBySyndic(Request $request)
    {
        $request->merge(['syndic_id' => Auth::id()]);
        return $this->store($request);
    }

    public function updateBySyndic(Request $request, $id)
    {
        $immeuble = Immeuble::where('id', $id)->where('syndic_id', Auth::id())->firstOrFail();
        return $this->update($request, $id);
    }

    public function destroyBySyndic($id)
    {
        $immeuble = Immeuble::where('id', $id)->where('syndic_id', Auth::id())->firstOrFail();
        return $this->destroy($id);
    }
}
