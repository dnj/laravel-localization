<?php

namespace dnj\Localization\Eloquent;

trait IsTranslate
{
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return array<string,string>
     */
    public function getFields(): array
    {
        $result = [];
        foreach ($this->getFieldKeys() as $k) {
            $result[$k] = $this->{$k};
        }

        return $result;
    }

    public function getField(string $key): string
    {
        return $this->{$key};
    }

    /**
     * @return string[]
     */
    public function getFieldKeys(): array
    {
        return array_diff(array_keys($this->getAttributes()), array_keys($this->getCasts()));
    }
}
