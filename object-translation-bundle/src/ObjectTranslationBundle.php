<?php

namespace Aurel\ObjectTranslationBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class ObjectTranslationBundle extends AbstractBundle
{
    protected string $extensionAlias = 'aurel_object_translation';
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->stringNode('translation_class')
                    ->info('The class name of your translation entity.')
                    ->example('App\Entity\Translation')
                ->end()
            ->end();
    }
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createXmlMappingDriver(
                [__DIR__.'/../config/doctrine/mapping' => 'Aurel\ObjectTranslationBundle\Model'],
            )
        );
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
    }
}
