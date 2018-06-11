<?php

declare(strict_types=1);

namespace Base\Service;

// todo interface + abstract class to load translations from different sources
class TranslationService
{
    private $locales;
    private $translatedMessages;
    private $pathToTranslations;

    public function __construct(array $locales, string $pathToTranslations)
    {
        $this->locales = $locales;
        $this->pathToTranslations = $pathToTranslations;

        foreach ($this->locales as $locale) {
            $pathToFile = $this->pathToTranslations . DIRECTORY_SEPARATOR . $locale . '.php';
            $this->translatedMessages[$locale] = require_once $pathToFile;
        }
    }

    /**
     * @param $category string
     * @param $message string
     * @return string
     */
    public function t(string $category, string $message): string
    {
        if (!isset($this->translatedMessages[$category])) {
            return $message;
        }

        return $this->translatedMessages[$category][$message] ?? $message;
    }
}