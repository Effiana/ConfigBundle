<?php

namespace Effiana\ConfigBundle\Tests\CacheAdapter;

use Doctrine\Common\Cache\ArrayCache;
use Effiana\ConfigBundle\CacheAdapter\DoctrineCacheBundleAdapter;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class DoctrineCacheBundleAdapterTest extends BaseCacheAdapterTest {

	protected function getAdapter() {
		return new DoctrineCacheBundleAdapter(new ArrayCache());
	}

	/**
	 * TODO remove as soon as doctrine/cache >= 1.6 is required
	 */
	public function testSetMultiple_fails() {
		if (method_exists(ArrayCache::class, 'saveMultiple')) {
			$this->markTestSkipped('DoctrineCacheBundle already supports `saveMultiple`.');
		}

		$providerMock = $this->createMock(ArrayCache::class);

		$providerMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false))
		;

		$adapter = new DoctrineCacheBundleAdapter($providerMock);

		$this->assertFalse($adapter->setMultiple(['key' => 'value']));
	}

}
