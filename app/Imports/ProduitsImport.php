<?php

namespace App\Imports;

use App\Models\grosProduit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
class ProduitsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $messages = [
            'success' => [],
            'errors' => [],
        ];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Ignorer l’en-tête

            $libelle = $row[0] ?? null;
            $produitTypeId = $row[1] ?? null;
            $dateReception = $row[2] ?? null;

            // Valider les données
            $validator = Validator::make([
                'libelle' => $libelle,
                'produitType' => $produitTypeId,
                'dateReception' => $dateReception,
            ], [
                'libelle' => 'required|string|max:255',
                'produitType' => 'required|exists:produit_types,id',
                'dateReception' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                $messages['errors'][] = "Ligne " . ($index + 1) . " : " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Vérifier si le produit existe déjà
            $exists = grosProduit::where('libelle', $libelle)->exists();
            if ($exists) {
                $messages['errors'][] = "Ligne " . ($index + 1) . " : Le produit '$libelle' existe déjà.";
                continue;
            }

            // Générer le code automatique
            $lastId = DB::table('gros_produits')->max('id');
            $newId = $lastId ? $lastId + 1 : 1;
            $code = str_pad($newId, 6, '0', STR_PAD_LEFT);

            // Créer le produit
            grosProduit::create([
                'code' => $code,
                'libelle' => $libelle,
                'prix' => 0,
                'produitType_id' => $produitTypeId,
                'quantite' => 0,
                'dateReception' => $dateReception ?? Carbon::now(),

            ]);

            $messages['success'][] = "Ligne " . ($index + 1) . " : Produit '$libelle' importé avec succès.";
        }

        // Stockage dans la session
        if (!empty($messages['errors'])) {
            Session::flash('error_message', implode('<br>', $messages['errors']));
        }
        if (!empty($messages['success'])) {
            Session::flash('success_message', implode('<br>', $messages['success']));
        }
    }
}
