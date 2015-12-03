<?php

namespace Sidus\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SidusAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

//        // Currently not working, we need to add admin services manually
//        $configuration = new Configuration();
//        $config = $this->processConfiguration($configuration, $configs);
//
//        // Automatically declare an admin for each family configured
//        foreach ($config['families'] as $code => $familyConfiguration) {
//            $this->addFamilyServiceDefinition($code, $familyConfiguration, $container);
//        }
    }

    /**
     * @param string $code
     * @param array $familyConfiguration
     * @param ContainerBuilder $container
     */
    protected function addFamilyServiceDefinition($code, $familyConfiguration, ContainerBuilder $container)
    {
        $definition = clone $container->getDefinition('sidus_admin.admin.prototype');
        $definition->addTag('sonata.admin', ['manager_type' => 'orm']);
        $definition->addMethodCall('setFamilyCode', [$code]);
        $container->setDefinition('sidus_eav_model.family.admin.' . $code, $definition);
    }
}
