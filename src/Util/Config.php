<?php

namespace Effiana\ConfigBundle\Util;

use Doctrine\ORM\EntityManager;
use Effiana\ConfigBundle\CacheAdapter\CacheAdapterInterface;
use Effiana\ConfigBundle\CacheAdapter\NullAdapter;
use Effiana\ConfigBundle\Entity\SettingInterface;
use Effiana\ConfigBundle\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Config {

	/**
	 * @var CacheAdapterInterface
	 */
	protected $cache;

	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var SettingRepository
	 */
	protected $repo;

	/**
	 * @var string
	 */
	protected $entityName;

	public function __construct(CacheAdapterInterface $cache = null) {
		$this->setCache($cache ?? new NullAdapter());
	}

	public function setCache(CacheAdapterInterface $cache) {
		$this->cache = $cache;
	}

	public function setEntityManager(EntityManager $em) {
		if ($this->em !== $em) {
			if ($this->em !== null) {
				$this->cache->clear();
			}

			$this->em = $em;
			$this->repo = null;
		}
	}

	public function setEntityName($entityName) {
		$this->entityName = $entityName;
		$this->repo = null;
	}

    /**
     * @param string $name Name of the setting.
     * @param null $default
     * @return string|null Value of the setting.
     */
	public function get($name, $default = null) {
		if ($this->cache->has($name)) {
			return $this->cache->get($name);
		}

		$setting = $this->getRepo()->findOneBy([
			'name' => $name,
		]);

		if ($setting === null) {
		    if($default === null) {
                throw $this->createNotFoundException($name);
            }
            return $default;
		}

		$this->cache->set($name, $setting->getValue());

		return $setting->getValue();
	}

    /**
     * @param string $name Name of the setting to update.
     * @param string|null $value New value for the setting.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
	public function set($name, $value) {
		$setting = $this->getRepo()->findOneBy([
			'name' => $name,
		]);

		if ($setting === null) {
			throw $this->createNotFoundException($name);
		}

		$setting->setValue($value);
		$this->em->flush($setting);

		$this->cache->set($name, $value);
	}

    /**
     * @param array $newSettings List of settings (as name => value) to update.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
	public function setMultiple(array $newSettings) {
		if (empty($newSettings)) {
			return;
		}

		$settings = $this->getRepo()->findByNames(array_keys($newSettings));

		foreach ($newSettings as $name => $value) {
			if (!isset($settings[$name])) {
				throw $this->createNotFoundException($name);
			}

			$settings[$name]->setValue($value);
		}

		$this->em->flush();

		$this->cache->setMultiple($newSettings);
	}

	/**
	 * @return array with name => value
	 */
	public function all() {
		$settings = $this->getAsNamesAndValues($this->getRepo()->findAll());

		$this->cache->setMultiple($settings);

		return $settings;
	}

	/**
	 * @param string|null $section Name of the section to fetch settings for.
	 * @return array with name => value
	 */
	public function getBySection($section) {
		$settings = $this->getAsNamesAndValues($this->getRepo()->findBy(['section' => $section]));

		$this->cache->setMultiple($settings);

		return $settings;
	}

	/**
	 * @param SettingInterface[] $entities
	 * @return array with name => value
	 */
	protected function getAsNamesAndValues(array $settings) {
		$result = [];

		foreach ($settings as $setting) {
            $value = $setting->getValue();
            if($value instanceof File) {
                $value = $value->getPathname();
            }
            $result[$setting->getName()] = $value;
		}

		return $result;
	}

	/**
	 * @return SettingRepository
	 */
	protected function getRepo() {
		if ($this->repo === null) {
			$this->repo = $this->em->getRepository($this->entityName);
		}

		return $this->repo;
	}

	/**
	 * @param string $name Name of the setting.
	 * @return \RuntimeException
	 */
	protected function createNotFoundException($name) {
		return new \RuntimeException(sprintf('Setting "%s" couldn\'t be found.', $name));
	}

}
