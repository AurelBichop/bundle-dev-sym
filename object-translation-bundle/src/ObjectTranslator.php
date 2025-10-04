<?php

namespace Aurel\ObjectTranslationBundle;


use Symfony\Contracts\Translation\LocaleAwareInterface;

final class ObjectTranslator
{
    public function __construct(
        private readonly LocaleAwareInterface $localeAware,
        private readonly string               $defaultLocale,
        private string $translationClass,
    )
    {

    }

    /**
     * @template T of object
     *
     * @param T $object
     *
     * @return T
     */
    public function translate(object $object): object
    {
        $locale = $this->localeAware->getLocale();

        if($this->defaultLocale === $locale) {
            return $object;
        }

        return new TranslatedObject($object);
    }
}
