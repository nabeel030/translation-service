<?php

namespace App\Services;

use App\Repositories\TranslationRepositoryInterface;

class TranslationService
{
    protected $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function getAllTranslations()
    {
        return $this->translationRepository->getAllTranslations();
    }

    public function storeTranslation($validated)
    {
        return $this->translationRepository->storeTranslation($validated);
    }

    public function getTranslationById($translationId)
    {
        return $this->translationRepository->getTranslationById($translationId);
    }

    public function updateTranslation($translationId, $validated)
    {
        return $this->translationRepository->updateTranslation($translationId, $validated);
    }

    public function deleteTranslation($translationId)
    {
        return $this->translationRepository->deleteTranslation($translationId);
    }

    public function searchTranslations($request)
    {
        return $this->translationRepository->searchTranslations($request);
    }

    public function exportAsJSON() {
        echo '[';
        $first = true;

        foreach ($this->translationRepository->exportAsJSON() as $translation) {
            if (!$first) {
                echo ',';
            }

            echo json_encode([
                'id' => $translation->id,
                'key' => $translation->key,
                'tag' => $translation->tag,
                'contents' => $translation->contents->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'translation_id' => $c->translation_id,
                        'locale' => $c->locale,
                        'content' => $c->content
                    ];
                }),
            ], JSON_UNESCAPED_UNICODE);

            $first = false;
        }

        echo ']';
    }
}
