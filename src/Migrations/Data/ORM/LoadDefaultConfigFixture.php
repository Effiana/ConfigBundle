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

namespace Effiana\ConfigBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Effiana\ConfigBundle\Entity\Setting;
use Effiana\MigrationBundle\Fixture\LoadedFixtureVersionAwareInterface;
use Effiana\MigrationBundle\Fixture\VersionedFixtureInterface;


/**
 * Class LoadDefaultConfigFixture
 * @package Effiana\ConfigBundle\Migrations\Data\ORM
 */
class LoadDefaultConfigFixture extends AbstractFixture implements VersionedFixtureInterface, LoadedFixtureVersionAwareInterface
{
    /**
     * @var $currentDBVersion string
     */
    protected $currentDBVersion = null;

    /**
     * {@inheritdoc}
     */
    public function setLoadedVersion($version = null)
    {
        $this->currentDBVersion = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $setting = Setting::create('urgent_color', 'boolean', 1, 'MAIN', 'Change urgent objects color to red');
        $manager->persist($setting);
        $manager->flush();
    }
}
