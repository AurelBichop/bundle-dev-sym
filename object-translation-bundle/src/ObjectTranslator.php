<?php

namespace Aurel\ObjectTranslationBundle;


use App\Entity\Translation;
use Aurel\ObjectTranslationBundle\Mapping\Translatable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\LocaleAwareInterface;

final class ObjectTranslator
{
    public function __construct(
        private readonly LocaleAwareInterface $localeAware,
        private readonly string               $defaultLocale,
        private string $translationClass,
        private ManagerRegistry $doctrine,
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

        return new TranslatedObject($object, $this->translationsFor($object, $locale));
    }

    private function translationsFor(object $object, string $locale): array
    {
        $class = new \ReflectionClass($object);
        $type = $class->getAttributes(Translatable::class)[0]?->newInstance()->name ?? null;

        if (!$type) {
            throw new \LogicException(sprintf('Class "%s" is not translatable.', $object::class));
        }

        $om = $this->doctrine->getManagerForClass($object::class);
        if (!$om) {
            throw new \LogicException(sprintf('No object manager found for class "%s".', $object::class));
        }

        $id = $om->getClassMetadata($object::class)
            ->getIdentifierValues($object)
        ;

        if (1 !== count($id)) {
            throw new \LogicException(sprintf('Class "%s" must have a single identifier to be translatable.', $object::class));
        }

        $id = reset($id);

        /** @var Translation[] $translations */
        $translations = $this->doctrine->getRepository($this->translationClass)->findBy([
            'locale' => $locale,
            'objectType' => $type,
            'objectId' => $id,
        ]);

        $tanslationValues = [];

        foreach ($translations as $translation) {
            $tanslationValues[$translation->field] = $translation->value;
        }

        return $tanslationValues;
    }
}
