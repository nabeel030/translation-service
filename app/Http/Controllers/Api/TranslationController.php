<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TranslationRequest;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Get all translations along with contents
     */
    public function index()
    {
        $translations = $this->translationService->getAllTranslations();
        return response()->json($translations);
    }

    /**
     * Store a new translation with its contents
     */
    public function store(TranslationRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $translation = $this->translationService->storeTranslation($validated);
            return response()->json([
                'success' => 'Translation added successfully!', 
                'translation' => $translation
            ], 201);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Something went wrong. Try again!'], 500);
        }
    }

    /**
     * Get the translation with its contents
     */
    public function show($translationId)
    {
        $translation = $this->translationService->getTranslationById($translationId);
        return response()->json(['translations' => $translation]);
    }

    /**
     * Update the translation
     */
    public function update(TranslationRequest $request, $translationId)
    {
        $validated = $request->validated();
        
        try {
            $updatedTranslation = $this->translationService->updateTranslation($translationId, $validated);
            return response()->json([
                'success' => 'Translation updated successfully!', 
                'translation' => $updatedTranslation
            ]);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Something went wrong. Try again!'], 500);
        }
    }

    /**
     * Delete translation and its contents
     */
    public function destroy($translationId)
    {
        try {
            $this->translationService->deleteTranslation($translationId);
            return response()->json(['success' => 'Translation deleted successfully!'], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Something went wrong. Try again!'], 500);
        }
    }

    /**
     * Search translations
     */
    public function searchTranslations(Request $request) 
    {
        $translations = $this->translationService->searchTranslations($request);
        return response()->json($translations);
    }

    public function exportAsJSON() {
        $response = Response::stream(function () {
            $this->translationService->exportAsJSON();
        }, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="translations.json"',
        ]);
    
        return $response;
    }
}
