<?php

namespace Sidus\EAVDemoBundle\Admin;

use LAG\AdminBundle\DependencyInjection\Configuration;
use LAG\AdminBundle\Event\AdminFactoryEvent;
use Sidus\EAVModelBundle\Configuration\FamilyConfigurationHandler;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;
use Twig_Environment;

class SidusAdminSubscriber implements EventSubscriberInterface
{
    /** @var FamilyConfigurationHandler */
    protected $familyConfigurationHandler;

    /** @var Twig_Environment */
    protected $twig;

    /** @var string */
    protected $dataClass;

    /**
     * @param FamilyConfigurationHandler $familyConfigurationHandler
     * @param Twig_Environment $twig
     * @param $dataClass
     */
    public function __construct(FamilyConfigurationHandler $familyConfigurationHandler, Twig_Environment $twig, $dataClass)
    {
        $this->familyConfigurationHandler = $familyConfigurationHandler;
        $this->twig = $twig;
        $this->dataClass = $dataClass;
    }

    public static function getSubscribedEvents()
    {
        return [
            AdminFactoryEvent::ADMIN_CREATION => 'createAdmins',
        ];
    }

    public function createAdmins(AdminFactoryEvent $event)
    {
        $admins = $event->getAdminsConfiguration();
        foreach ($this->familyConfigurationHandler->getFamilies() as $family) {
            $key = 'sidus_' . $family->getCode();
            if (isset($admins[$key])) {
                continue;
            }
            $admins[$key] = $this->getAdminConfigurationForFamily($family, $key);
        }
        $event->setAdminsConfiguration($admins);
    }

    /**
     * @param FamilyInterface $family
     * @param string $admin
     * @return array
     */
    protected function getAdminConfigurationForFamily($family, $admin)
    {
        $ymlConfig = $this->twig->render('SidusEAVDemoBundle:Config:admin_template.yml.twig', [
            'family' => $family,
            'data_class' => $this->dataClass,
            'admin' => $admin,
        ]);

        $config = Yaml::parse($ymlConfig);
        $config = $this->finalizeConfiguration($config);
        return $config;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function finalizeConfiguration($config)
    {
        $builder = new TreeBuilder();
        $builder->root('sidus_test')->append((new Configuration())->getAdminsConfigurationNode());

        $configTree = $builder->buildTree();
        $configs = $configTree->normalize([
            'admins' => [
                'test' => $config,
            ],
        ]);
        return $configTree->finalize($configs)['admins']['test'];
    }
}