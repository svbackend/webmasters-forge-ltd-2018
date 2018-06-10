<?php

declare(strict_types=1);

namespace Base\Service;

use League\Plates\Engine;

class TemplateService extends Engine
{
    private $translationService;

    /**
     * @var ThumbnailService
     */
    private $imageService;

    public function setTranslationService(object $translationService)
    {
        $this->translationService = $translationService;
    }

    public function setImageService(object $imageService)
    {
        $this->imageService = $imageService;
    }

    public function initCustomTemplateFunctions()
    {
        $translationService = $this->translationService;
        $this->registerFunction('t', function (string $category, string $message) use ($translationService) {
            return $translationService->trans($category, $message);
        });

        $imageService = $this->imageService;
        $this->registerFunction('image', function ($imageUrl) use ($imageService) {
            return $imageService->image($imageUrl);
        });
    }
}