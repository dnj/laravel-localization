<?php

namespace dnj\Localization\Contracts;

interface ITranslatableModel
{
    public function getTranslate(string $locale): ?ITranslateModel;

    /**
     * @return iterable<ITranslateModel>
     */
    public function getTranslates(): iterable;
}
