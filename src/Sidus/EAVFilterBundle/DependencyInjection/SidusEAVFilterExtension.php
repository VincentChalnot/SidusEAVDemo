<?php

namespace Sidus\EAVFilterBundle\DependencyInjection;

use Sidus\FilterBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SidusEAVFilterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration('sidus_eav_filter');
        $config = $this->processConfiguration($configuration, $configs);

        // Automatically declare a service for each attribute configured
        foreach ($config['configurations'] as $code => $configuration) {
            $this->addConfigurationServiceDefinition($code, $configuration, $container);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param string $code
     * @param array $configuration
     * @param ContainerBuilder $container
     */
    protected function addConfigurationServiceDefinition($code, array $configuration, ContainerBuilder $container)
    {
        $definition = new Definition(new Parameter('sidus_eav_filter.configuration.class'), [
            $code,
            new Reference('doctrine'),
            new Reference('sidus_filter.filter.factory'),
            $configuration,
            new Reference('sidus_eav_model.family_configuration.handler'),
        ]);
        $container->setDefinition('sidus_eav_filter.configuration.' . $code, $definition);
    }
}
