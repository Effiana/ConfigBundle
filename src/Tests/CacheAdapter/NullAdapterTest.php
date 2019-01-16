<?php

namespace Effiana\ConfigBundle\Tests\CacheAdapter;

use Effiana\ConfigBundle\CacheAdapter\CacheAdapterInterface;
use Effiana\ConfigBundle\CacheAdapter\NullAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class NullAdapterTest extends TestCase {

	/**
	 * @return CacheAdapterInterface
	 */
	protected function getAdapter() {
		return new NullAdapter();
	}

	public function testClear() {
		$this->assertTrue($this->getAdapter()->clear());
	}

	public function testHas() {
		$this->assertFalse($this->getAdapter()->has('key'));
	}

	public function testGet() {
		$this->assertNull($this->getAdapter()->get('key'));
	}

	public function testSet() {
		$this->assertFalse($this->getAdapter()->set('key', 'value'));
	}

	public function testSetMultiple() {
		$this->assertFalse($this->getAdapter()->setMultiple([]));
	}

}
