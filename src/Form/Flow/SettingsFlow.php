<?php
/**
 * This file is part of the Effiana package.
 *
 * (c) Effiana, LTD
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Dominik Labudzinski <dominik@labudzinski.com>
 */
declare(strict_types=0);

namespace Effiana\ConfigBundle\Form\Flow {

    use Craue\FormFlowBundle\Form\FormFlow;
    use Craue\FormFlowBundle\Form\FormFlowInterface;
    use Effiana\ConfigBundle\Form\Type\SettingType;

    /**
     * Class SettingsFlow
     * @package Effiana\ConfigBundle\Form\Flow
     */
    class SettingsFlow extends FormFlow
    {

        protected $allowDynamicStepNavigation = true;
        protected $allowRedirectAfterSubmit = true;

        protected function loadStepsConfig(): array
        {
            return [
                1 => [
                    'label' => 'Setting Type',
                    'form_type' => SettingType::class,
                    'skip' => static function ($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                        return $estimatedCurrentStepNumber > 1 && $flow->getFormData()->getType() !== null;
                    },
                ],
                2 => [
                    'form_type' => SettingType::class,
                ],
            ];

        }
    }
}