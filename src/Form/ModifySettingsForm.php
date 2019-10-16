<?php

namespace Effiana\ConfigBundle\Form;

use Effiana\ConfigBundle\Form\Type\SettingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ModifySettingsForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('settings', CollectionType::class, [
			'entry_type' => SettingType::class,
            'allow_add' => true,
            'allow_delete' => true
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'effiana_config_modifySettings';
	}

}