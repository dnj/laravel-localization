<?php

namespace dnj\Localization\Eloquent;

use dnj\Localization\Contracts\ITranslateModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait HasTranslate
{
    public function getTranslate(string $locale): ?ITranslateModel
    {
        if (!method_exists($this, 'translates')) {
            throw new \Exception("define a One-Many relation in your model with 'translates' name");
        }
        if ($this->relationLoaded('translates')) {
            return $this->translates->where('locale', $locale)->first();
        }

        return $this->translates()->where('locale', $locale)->first();
    }

    public function getTranslateForUpdate(string $locale): ITranslateModel
    {
        if (!method_exists($this, 'translates')) {
            throw new \Exception("define a One-Many relation in your model with 'translates' name");
        }

        return $this->translates()
            ->where('locale', $locale)
            ->lockForUpdate()
            ->first();
    }

    /**
     * @return iterable<ITranslateModel>
     */
    public function getTranslates(): iterable
    {
        if (!method_exists($this, 'translates')) {
            throw new \Exception("define a One-Many relation in your model with 'translates' name");
        }

        return $this->translates;
    }

    /**
     * @param array<string,array<string,string>> $changes
     */
    public function updateTranslates(array $changes): static
    {
        DB::transaction(function () use ($changes) {
            $translates = iterator_to_array($this->getTranslates());
            $currentLocales = array_column($translates, 'locale');
            $newLocales = array_keys($changes);

            $addLocales = array_diff($newLocales, $currentLocales);
            $deleteLocales = array_diff($currentLocales, $newLocales);
            $editLocales = array_intersect($newLocales, $currentLocales);

            if ($deleteLocales) {
                $this->deleteTranslate($deleteLocales);
            }

            if ($addLocales) {
                foreach ($addLocales as $locale) {
                    $this->addTranslate($locale, $changes[$locale]);
                }
            }

            if ($editLocales) {
                foreach ($editLocales as $locale) {
                    $this->updateTranslate($locale, $changes[$locale]);
                }
            }
        });

        return $this;
    }

    /**
     * @param array<string,string> $fields
     */
    public function addTranslate(string $locale, array $fields): ITranslateModel
    {
        return $this->translates()->create(array_merge(['locale' => $locale], $fields));
    }

    public function deleteTranslate(string|array $locale): void
    {
        if (is_string($locale)) {
            $locale = [$locale];
        }

        $this->translates()->whereIn('locale', $locale)->delete();
    }

    public function updateTranslate(string $locale, array $fields): ITranslateModel
    {
        /**
         * @var ITranslateModel&Model
         */
        $translate = $this->getTranslateForUpdate($locale);
        $translate->update($fields);

        return $translate;
    }
}
