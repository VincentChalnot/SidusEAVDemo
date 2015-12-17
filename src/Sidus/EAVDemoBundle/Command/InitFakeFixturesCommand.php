<?php

namespace Sidus\EAVDemoBundle\Command;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Sidus\EAVDemoBundle\Entity\Data;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitFakeFixturesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sidus:eav-demo:init-fake-fixtures')
            ->setDescription("Use faker to fill data");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $total = 10000;
        $batch = 50;
        $familyConfigurationHandler = $this->getContainer()->get('sidus_eav_model.family_configuration.handler');
        $dataClass = $this->getContainer()->getParameter('sidus_eav_model.entity.data.class');
        $valueClass = $this->getContainer()->getParameter('sidus_eav_model.entity.value.class');

        $families = $familyConfigurationHandler->getFamilies();
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $faker = Factory::create();
        /** @var ProgressBar $progress */
        $progress = new ProgressBar($output, $total * count($families));
        $progress->advance();

        foreach ($families as $family) {
            for ($i = 0; $i < $total; $i++) {
                /** @var Data $data */
                $data = new $dataClass($family);
                $data->setValueClass($valueClass);
                $data->setCreatedAt($faker->dateTimeBetween('-5 years'));
                foreach ($family->getAttributes() as $attribute) {
                    $times = 1;
                    if ($attribute->isMultiple()) {
                        $times = $faker->numberBetween(0, 10);
                    }
                    $fd = [];
                    for ($j = 0; $j < $times; $j++) {
                        switch ($attribute->getType()->getDatabaseType()) {
                            case 'boolValue':
                                $fd[] = $faker->boolean();
                                break;
                            case 'integerValue':
                                $fd[] = $faker->numberBetween(0, 9999);
                                break;
                            case 'decimalValue':
                                $fd[] = $faker->randomFloat(3, 0, 9999);
                                break;
                            case 'dateValue':
                            case 'datetimeValue':
                                $fd[] = $faker->dateTime;
                                break;
                            case 'stringValue':
                                if ($attribute->getCode() === 'code') {
                                    $fd[] = $faker->ean13;
                                } else {
                                    $fd[] = $faker->text(50);
                                }
                                break;
                            case 'textValue':
                                $fd[] = $faker->text(300);
                                break;
                            default:
                                //var_dump($attribute->getType()->getDatabaseType());
                        }
                        if (!empty($fd)) {
                            $data->setValuesData($attribute, $fd);
                        }
                    }
                }
                $em->persist($data);
                if ($i % $batch === 0) {
                    $em->flush();
                    $em->flush();
                    $em->clear();
                }
                $progress->advance();
            }
        }
        $em->flush();
        $em->flush();
        $em->clear();
    }
}
