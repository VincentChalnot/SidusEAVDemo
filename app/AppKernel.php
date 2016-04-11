<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            ///// Sidus bundles
            new Sidus\EAVModelBundle\SidusEAVModelBundle(), //  Base bundle for EAV model
            new Sidus\FilterBundle\SidusFilterBundle(), // Data filtering based on user input
            new Sidus\EAVFilterBundle\SidusEAVFilterBundle(), // Data filtering with EAV support
            new Sidus\EAVBootstrapBundle\SidusEAVBootstrapBundle(), // Bootstrap integration + additionnal EAV components
            new Sidus\DataGridBundle\SidusDataGridBundle(), // Datagrid made easy
            new Sidus\EAVDataGridBundle\SidusEAVDataGridBundle(), // EAV support for datagrids
            new Sidus\EAVVariantBundle\SidusEAVVariantBundle(), // Handle variants of EAV entities with axles validation
            new Sidus\FileUploadBundle\SidusFileUploadBundle(), // Easily attach file to doctrine's entities
            new Sidus\AdminBundle\SidusAdminBundle(), // Very basic admin configuration in YML to regroup entities and route actions easily

            // Disabled for the demo, maybe too technical ?
            //new Sidus\PublishingBundle\SidusPublishingBundle(), // Collect entities, serialize and push them on configured remote servers

            ///// Dependencies

            // Required by SidusEAVBootstrapBundle
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(), // Easy Bootstrap integration
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(), // TinyMCE WYSIWYG support
            new Samson\Bundle\AutocompleteBundle\SamsonAutocompleteBundle(), // Autocomplete Doctrine entities
            new Samson\Bundle\UnexpectedResponseBundle\SamsonUnexpectedResponseBundle(),
            new Pinano\Select2Bundle\PinanoSelect2Bundle(),

            // Required by SidusFilterBundle
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(), // Best Sf pager out there

            // Required by SidusFileUploadBundle
            new Oneup\UploaderBundle\OneupUploaderBundle(),  // Resource uploader
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(), // Filesystem abstraction

            // Required by SidusPublishingBundle
            new JMS\SerializerBundle\JMSSerializerBundle(), // Easier serialization in Symfony (although not necessarily better)
            new Circle\RestClientBundle\CircleRestClientBundle(), // Rest client

            // Optimizations (should be optional)
            new FOS\ElasticaBundle\FOSElasticaBundle(), // Disabled by default, can be turned on to improve datagrid's performances

            // Additional, required by the current demo
            new Liip\ImagineBundle\LiipImagineBundle(), // Automatic image resizing


            // Demo bundles
            new Demo\EAVModelBundle\DemoEAVModelBundle(),
            new Demo\LayoutBundle\DemoLayoutBundle(),
            new Demo\AdminBundle\DemoAdminBundle(),
            new Demo\AssetBundle\DemoAssetBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
