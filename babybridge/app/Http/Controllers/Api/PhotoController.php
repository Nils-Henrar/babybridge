<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Child;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    // Méthode pour récupérer les photos des enfants par section et date
    public function getPhotosBySectionAndDate($sectionId, $date)
    {
        $section = Section::with(['childSections.child.photos' => function ($query) use ($date) {
            $query->whereDate('taken_at', $date);
        }])->findOrFail($sectionId);

        $photos = $section->childSections->flatMap(function ($childSection) {
            return $childSection->child->photos;
        });

        return response()->json($photos);
    }

    // Méthode pour mettre à jour ou créer une photo
    public function storeOrUpdatePhoto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'child_id' => 'required|integer|exists:children,id',
                'description' => 'nullable|string',
                'taken_at' => 'required|date_format:Y-m-d H:i:s',
                'photo' => 'required|file|mimes:jpeg,jpg,png|max:2048'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $childId = $request->child_id;
            $childName = Child::find($childId)->fullname;
    
            // Récupérer le fichier photo et son nom original
            $file = $request->file('photo');
            $originalName = $file->getClientOriginalName();

            // enlever les espaces et les caractères spéciaux
            $originalName = preg_replace('/[^A-Za-z0-9\-]/', '', $originalName);
            $targetPath = "public/photos/{$childName}";
    
            // Stocker le fichier dans le dossier spécifié et conserver le nom original
            $file->storeAs($targetPath, $originalName);
    
            // Construire le chemin complet pour enregistrer dans la base de données
            $storagePath = "photos/{$childName}/{$originalName}";
    
            $photoPath = $request->file('photo')->store("photos/{$childName}", 'public');
            $photo = Photo::updateOrCreate(
                [
                    'child_id' => $childId,
                    'taken_at' => $request->taken_at
                ],
                [
                    'description' => $request->description,
                    'path' => $photoPath // Chemin relatif stocké
                ]
            );
            
    
            return response()->json(['message' => 'Photo saved successfully', 'photo' => $photo]);
        } catch (\Exception $e) {
            Log::error('Failed to save photo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save photo: ' . $e->getMessage()], 500);
        }
    }
    

    

}
