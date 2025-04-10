<?php

namespace App\Repositories;

/**
 * Interface TranslationRepositoryInterface
 *
 * @package App\Repositories
 */
interface TranslationRepositoryInterface
{
    /**
     * Get all translations.
     *
     * @return mixed
     */
    public function getAllTranslations();

    /**
     * Store a new translation.
     *
     * @param array $data
     * @return mixed
     */
    public function storeTranslation(array $data);

    /**
     * Get a translation by its ID.
     *
     * @param int $translationId
     * @return mixed
     */
    public function getTranslationById(int $translationId);

    /**
     * Update an existing translation.
     *
     * @param int $translationId
     * @param array $data
     * @return mixed
     */
    public function updateTranslation(int $translation, array $data);

    /**
     * Delete a translation.
     *
     * @param int $translationId
     * @return mixed
     */
    public function deleteTranslation(int $translation);

    /**
     * Search translations based on the provided criteria.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function searchTranslations($request);

    /**
     * Export all translations as JSON.
     *
     * @return mixed
     */
    public function exportAsJSON();
}
