<?php

namespace dnj\Localization\Contracts;

interface ITranslateModel
{
    public function getLocale(): string;

    /**
     * @return array<string,string>
     */
    public function getFields(): array;

    public function getField(string $key): string;
}
