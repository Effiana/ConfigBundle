<?php

namespace Effiana\ConfigBundle\Form\Type;

use Effiana\ConfigBundle\Entity\Setting;
use Effiana\ConfigBundle\Entity\SettingInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class SettingType extends AbstractType {

    /**
     * @var string
     */
    protected $entityName;

    /**
     * SettingType constructor.
     * @param $entityName
     */
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['data']->getName() === null) {
            $builder
                ->add('name', null, ['required' => true])
                ->add('type', ChoiceType::class, [
                    'required' => true,
                    'translation_domain' => 'EffianaConfigBundle',
                    'choices' => [
                        'String' => 'string',
                        'Number' => 'int',
                        'Boolean' => 'boolean',
                    ]
                ]);
        }
        if(isset($options['data']) && $options['data'] instanceof Setting) {
            switch ($options['data']->getType()) {
                case 'boolean':
                case 'bool':
                    $builder->add('value', ChoiceType::class, [
                        'choices' => [
                            'On' => 1,
                            'Off' => 0
                        ],
                        'required' => true,
                        'translation_domain' => 'EffianaConfigBundle',
                    ]);
                    break;
                case 'int':
                case 'integer':
                    $builder->add('value', NumberType::class, [
                        'required' => true,
                        'translation_domain' => 'EffianaConfigBundle',
                    ]);
                    break;
                case 'string':
                default:
                    $builder->add('value', null, [
                        'required' => true,
                        'translation_domain' => 'EffianaConfigBundle',
                    ]);
                    break;
            }
        } else {
            $builder->add('value', null, [
                'required' => true,
                'translation_domain' => 'EffianaConfigBundle',
            ]);
        }


        $builder
            ->add('comment', TextareaType::class, [
                'required' => false,
                'translation_domain' => 'EffianaConfigBundle',
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /* @var $setting SettingInterface */
        $setting = $form->getData();

        $oldChilds = $view->children;
        $childs = [];
        if(isset($oldChilds['name'])) {
            $childs['name'] = $oldChilds['name'];
            unset($oldChilds['name']);
        }
        $view->children = array_merge($childs, $oldChilds);
        $view->children['value']->vars['label'] = $setting->getName();



        parent::finishView($view, $form, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => $this->entityName,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix() {
        return 'effiana_config_setting';
    }

}
