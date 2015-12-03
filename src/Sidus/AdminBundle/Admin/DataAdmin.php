<?php

namespace Sidus\AdminBundle\Admin;

use Sidus\EAVModelBundle\Configuration\FamilyConfigurationHandler;
use Sidus\EAVModelBundle\Entity\Data;
use Sidus\EAVModelBundle\Model\AttributeInterface;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DataAdmin extends Admin
{
    /** @var string */
    protected $familyCode;

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var Data $data */
        $data = $this->getSubject();
        if ($data && $data->getFamilyCode()) {
            $this->buildValuesForm($formMapper, $data);
        } else {
            $this->buildCreateForm($formMapper);
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('familyCode');
        $datagridMapper->add('createdAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('label');
        $listMapper->add('family');
        $listMapper->add('createdAt');
    }

    public function getLabel()
    {
        $data = $this->getSubject();
        if ($data instanceof Data && $data->getFamily()) {
            return (string) $data->getFamily();
        }
        return (string) $this->getFamily();
    }

    /**
     * @return FamilyConfigurationHandler
     */
    protected function getFamilyConfigurationHandler()
    {
        return $this->getConfigurationPool()->getContainer()->get('sidus_eav_model.family_configuration.handler');
    }

    /**
     * @return FamilyInterface
     */
    protected function getFamily()
    {
        return $this->getFamilyConfigurationHandler()->getFamily($this->familyCode);
    }

    /**
     * @param string $familyCode
     */
    public function setFamilyCode($familyCode)
    {
        $this->familyCode = $familyCode;
    }

    /**
     * @param FormMapper $builder
     */
    public function buildCreateForm(FormMapper $builder)
    {
        $builder->add('familyCode', 'choice', [
            'choices' => $this->getFamilyChoices(),
        ]);
    }

    /**
     * @param FormMapper $builder
     * @param Data $data
     */
    public function buildValuesForm(FormMapper $builder, Data $data)
    {
        $family = $this->getFamilyConfigurationHandler()->getFamily($data->getFamilyCode());
        foreach ($family->getAttributes() as $attribute) {
            $attributeType = $attribute->getType();
            $label = $this->getFieldLabel($family, $attribute);

            if ($attribute->isMultiple()) {
                $formOptions = $attribute->getFormOptions();
                $formOptions['label'] = false;
                $builder->add($attribute->getCode(), 'bootstrap_collection', [
                    'label' => $label,
                    'type' => $attributeType->getFormType(),
                    'options' => $formOptions,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]);
            } else {
                $formOptions = array_merge(['label' => $label], $attribute->getFormOptions());
                $builder->add($attribute->getCode(), $attributeType->getFormType(), $formOptions);
            }
        }
    }

    protected function getFamilyChoices()
    {
        $choices = [];
        foreach ($this->getFamilyConfigurationHandler()->getFamilies() as $family) {
            $choices[$family->getCode()] = $this->translator->trans($family->getCode());
        }
        return $choices;
    }

    /**
     * Use label from formOptions or use translation or automatically create human readable one
     *
     * @param FamilyInterface $family
     * @param AttributeInterface $attribute
     * @return string
     */
    protected function getFieldLabel(FamilyInterface $family, AttributeInterface $attribute)
    {
        $transKey = "{$family->getCode()}.attribute.{$attribute->getCode()}";
        $label = $this->translator->trans($transKey . '.label');
        if ($label === $transKey . '.label') {
            $label = ucfirst(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $attribute->getCode()));
        }
        return $label;
    }
}