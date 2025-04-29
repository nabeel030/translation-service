<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Models\TranslationContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Cache;

/**
 * Class TranslationRepository
 *
 * @package App\Repositories
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * Get all translations with their contents.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllTranslations(): \Illuminate\Pagination\LengthAwarePaginator
    {
        // return Translation::select('id', 'key', 'tag')->with(['contents:id,translation_id,locale,content'])->paginate(5000);

        $page = request()->get('page', 1);
        $cacheKey = "translations_page_{$page}";

        return Cache::remember($cacheKey, 3600, function () {
            return Translation::select('id', 'key', 'tag')->with(['contents:id,translation_id,locale,content'])->paginate(5000);
        });
    }

    /**
     * Store a new translation with its contents.
     *
     * @param array $validated
     * @return \App\Models\Translation
     * @throws Exception
     */
    public function storeTranslation(array $validated): Translation
    {
        try {
            DB::beginTransaction();
            $translation = Translation::create([
                'key' => $validated['key'],
                'tag' => $validated['tag'],
            ]);

            foreach ($validated['content'] as $item) {
                $translation->contents()->create($item);
            }

            DB::commit();
            return $translation->load('contents');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Error storing translation: ' . $ex->getMessage());
            throw new Exception('Error storing translation');
        }
    }

    /**
     * Get a translation by its ID.
     *
     * @param int $translationId
     * @return \App\Models\Translation
     */
    public function getTranslationById(int $translationId): Translation
    {
        $translation = Translation::findOrFail($translationId);
        return $translation->load('contents');
    }

    /**
     * Update an existing translation.
     *
     * @param int $translationId
     * @param array $validated
     * @return \App\Models\Translation
     * @throws Exception
     */
    public function updateTranslation(int $translationId, array $validated): Translation
    {
        $translation = Translation::findOrFail($translationId);

        try {
            DB::beginTransaction();
            $translation->update([
                'key' => $validated['key'],
                'tag' => $validated['tag'],
            ]);

            foreach ($validated['content'] as $item) {
                TranslationContent::updateOrCreate(
                    ['locale' => $item['locale'], 'translation_id' => $translation->id],
                    ['content' => $item['content']]
                );
            }

            DB::commit();
            return $translation->fresh('contents');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Error updating translation: ' . $ex->getMessage());
            throw new Exception('Error updating translation');
        }
    }

    /**
     * Delete a translation and its contents.
     *
     * @param int $translationId
     * @throws Exception
     */
    public function deleteTranslation(int $translationId): void
    {
        $translation = Translation::findOrFail($translationId);

        try {
            DB::beginTransaction();
            $translation->contents()->delete();
            $translation->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Error deleting translation: ' . $ex->getMessage());
            throw new Exception('Error deleting translation');
        }
    }

    /**
     * Search translations based on provided filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchTranslations($request): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Translation::query()
            ->when($request->filled('tag'), function ($query) use ($request) {
                $query->where('tag', $request->input('tag'));
            })
            ->when($request->filled('key'), function ($query) use ($request) {
                $query->where('key', 'like', '%' . $request->input('key') . '%');
            })
            ->when($request->filled('content'), function ($query) use ($request) {
                $query->whereHas('contents', function ($q) use ($request) {
                    $q->where('content', 'like', '%' . $request->input('content') . '%');
                });
            })
            ->select('translations.id', 'translations.tag', 'translations.key')
            ->with(['contents:id,translation_id,locale,content'])
            ->paginate(50);
    }

    public function exportAsJSON() {
        return Translation::with('contents')->lazy();
    }
}
