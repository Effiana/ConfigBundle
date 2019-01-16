<?php

namespace Effiana\ConfigBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class EffianaConfigBundle extends Bundle {

	/**
	 * {@inheritDoc}
	 */
	public function build(ContainerBuilder $container) {
		parent::build($container);
		$this->addRegisterMappingsPass($container);
	}

	/**
	 * @param ContainerBuilder $container
	 */
	private function addRegisterMappingsPass(ContainerBuilder $container) {
		$mappings = [
			realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'Effiana\ConfigBundle\Entity',
		];

		$container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, [], 'effiana_config.db_driver.doctrine_orm'));
	}

}
