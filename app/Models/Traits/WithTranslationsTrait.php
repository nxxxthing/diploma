<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Trail WithTranslationsTrait
 * Add translation scope to class
 *
 * @package App\Models\Traits
 */
trait WithTranslationsTrait
{

    /**
     * @param Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTranslations(Builder $query)
    {
        return $query->with(
            [
                'translations' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ]
        );
    }

    /**
     * @param             $query
     * @param string|null $modelTable
     * @param string|null $translationsTable
     * @param string|null $modelTableKey
     * @param string|null $translationsTableKey
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeJoinTranslations(
        Builder $query,
        $modelTable = null,
        $translationsTable = null,
        $modelTableKey = null,
        $translationsTableKey = null
    ) {
        if (!$modelTable) {
            $modelTable = $this->getTable();
        }

        $singularModelTable = Str::singular($modelTable);

        if (!$translationsTable) {
            $translationsTable = $singularModelTable."_translations";
        }

        $translationsTableKey = (empty($translationsTableKey) ? $singularModelTable."_id" : $translationsTableKey);
        $modelTableKey = (empty($modelTableKey) ? "id" : $modelTableKey);

        return $query->leftJoin(
            $translationsTable,
            function ($join) use ($modelTable, $translationsTable, $translationsTableKey, $modelTableKey) {
                $join->on("$translationsTable.$translationsTableKey", '=', "$modelTable.$modelTableKey")
                    ->where('locale', '=', app()->getLocale());
            }
        );
    }

    /**
     * @param             $query
     * @param string|null $modelTable
     * @param string|null $translationsTable
     * @param string|null $modelTableKey
     * @param string|null $translationsTableKey
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeJoinAllTranslations(
        Builder $query,
        $modelTable = null,
        $translationsTable = null,
        $modelTableKey = null,
        $translationsTableKey = null
    ) {
        if (!$modelTable) {
            $modelTable = $this->getTable();
        }

        $singularModelTable = Str::singular($modelTable);

        if (!$translationsTable) {
            $translationsTable = $singularModelTable."_translations";
        }

        $translationsTableKey = (empty($translationsTableKey) ? $singularModelTable."_id" : $translationsTableKey);
        $modelTableKey = (empty($modelTableKey) ? "id" : $modelTableKey);

        return $query->leftJoin(
            $translationsTable,
            function ($join) use ($modelTable, $translationsTable, $translationsTableKey, $modelTableKey) {
                $join->on("$translationsTable.$translationsTableKey", '=', "$modelTable.$modelTableKey");
            }
        );
    }
}
