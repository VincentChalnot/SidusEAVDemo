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

            // Bootstrap layout
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),

            // Additionnal Bundles
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Samson\Bundle\UnexpectedResponseBundle\SamsonUnexpectedResponseBundle(),
            new Samson\Bundle\AutocompleteBundle\SamsonAutocompleteBundle(),
            new Pinano\Select2Bundle\PinanoSelect2Bundle(),

            // Optimizations (should be optional)
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),

            // Sidus bundles
            new Sidus\EAVModelBundle\SidusEAVModelBundle(),
            new Sidus\EAVDemoBundle\SidusEAVDemoBundle(),
            new Sidus\FilterBundle\SidusFilterBundle(),
            new Sidus\EAVFilterBundle\SidusEAVFilterBundle(),
            new Sidus\EAVBootstrapBundle\SidusEAVBootstrapBundle(),
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
