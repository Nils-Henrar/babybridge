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
     public function storePhoto(Request $request)
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

            $file = $request->file('photo');
            $originalName = $file->getClientOriginalName();
            $originalName = preg_replace('/[^A-Za-z0-9\-]/', '', $originalName);
            $targetPath = "public/photos/{$childName}";
            $file->storeAs($targetPath, $originalName);
            $storagePath = "photos/{$childName}/{$originalName}";

            $photo = Photo::create([
                'child_id' => $childId,
                'description' => $request->description,
                'taken_at' => $request->taken_at,
                'path' => $storagePath
            ]);

            return response()->json(['message' => 'Photo saved successfully', 'photo' => $photo]);
        } catch (\Exception $e) {
            Log::error('Failed to save photo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save photo: ' . $e->getMessage()], 500);
        }
    }

    public function updatePhoto(Request $request, $id)
    
    {
        try {
            $validator = Validator::make($request->all(), [
                'child_id' => 'required|integer|exists:children,id',
                'description' => 'nullable|string',
                'taken_at' => 'required|date_format:Y-m-d H:i:s',
                'photo' => 'file|mimes:jpeg,jpg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $photo = Photo::findOrFail($id);

            if ($request->hasFile('photo')) {
                $childName = $photo->child->fullname;
                $file = $request->file('photo');
                $originalName = $file->getClientOriginalName();
                $originalName = preg_replace('/[^A-Za-z0-9\-]/', '', $originalName);
                $targetPath = "public/photos/{$childName}";
                $file->storeAs($targetPath, $originalName);
                $storagePath = "photos/{$childName}/{$originalName}";
                $photo->path = $storagePath;
            }

            $photo->description = $request->description;
            $photo->taken_at = $request->taken_at;
            $photo->save();

            return response()->json(['message' => 'Photo updated successfully', 'photo' => $photo]);
        } catch (\Exception $e) {
            Log::error('Failed to update photo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update photo: ' . $e->getMessage()], 500);
        }
    }

    public function getPhoto($id)
    
    {
        try {
            $photo = Photo::findOrFail($id);
            return response()->json($photo);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve photo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve photo: ' . $e->getMessage()], 500);
        }
    }

    public function deletePhoto($id)
    
    {
        try {
            $photo = Photo::findOrFail($id);
            Storage::disk('public')->delete($photo->path);
            $photo->delete();

            return response()->json(['message' => 'Photo deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to delete photo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete photo: ' . $e->getMessage()], 500);
        }
    }


    

    

}
