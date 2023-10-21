<?php

namespace dnj\Localization\Http\Resources;

use dnj\Localization\Contracts\ITranslatableModel;
use dnj\Localization\Contracts\ITranslateModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * @property ITranslatableModel $resource
 */
trait HasTranslate
{
    protected bool $localized = false;

    protected function localize(string|array $locale = null): array
    {
        if (!$this->localized) {
            return [
                'translates' => $this->exportTranslates(),
            ];
        }
        if (null === $locale) {
            $locale = [App::getLocale(), App::getFallbackLocale()];
        } elseif (is_string($locale)) {
            $locale = [$locale];
        }

        foreach ($locale as $l) {
            $translate = $this->resource->getTranslate($l);
            if ($translate) {
                return $this->exportTranslate($translate);
            }
        }
        $translates = $this->resource->getTranslates();
        if (!is_array($translates) and !$translates instanceof Collection) {
            $translates = iterator_to_array($translates);
        }
        if (0 == count($translates)) {
            return [
                'locale' => $locale[0],
            ];
        }
        $first = is_array($translates) ? $translates[0] : $translates->first();

        return $this->exportTranslate($first);
    }

    protected function exportTranslates(): array
    {
        $result = [];
        foreach ($this->resource->getTranslates() as $translate) {
            $result[$translate->getLocale()] = $translate->getFields();
        }

        return $result;
    }

    protected function exportTranslate(ITranslateModel $translate): array
    {
        return array_merge(['locale' => $translate->getLocale()], $translate->getFields());
    }
}
