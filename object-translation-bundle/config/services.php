<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Aurel\ObjectTranslationBundle\ObjectTranslator;

return static function (ContainerConfigurator $container) {
   $container->services()
       ->set('aurel.object_translator', ObjectTranslator::class)
        ->args([
            service('translation.locale_switcher'),
            param('kernel.default_locale'),
        ])
       ->alias(ObjectTranslator::class, 'aurel.object_translator')
   ;
};
