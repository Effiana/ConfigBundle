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

namespace Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Effiana\MigrationBundle\Migration\Column;
use Effiana\MigrationBundle\Migration\Installation;
use Effiana\MigrationBundle\Migration\QueryBag;

/**
 * Class EffianaConfigBundleInstaller
 * @package Migrations\Schema
 */
class EffianaConfigBundleInstaller implements Installation
{
    /**
     * @inheritdoc
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        if(!$schema->hasTable('effiana_config_setting')) {
            $table = $schema->createTable('effiana_config_setting');
            $table->addColumn('name', Column::STRING, ['length' => 255]);
            $table->addColumn('section', Column::STRING, ['length' => 255]);
            $table->addColumn('value', Column::STRING, ['length' => 255]);
            $table->addColumn('comment', Column::TEXT, ['length' => 255, 'notnull' => false]);
            $table->addColumn('type', Column::STRING, ['length' => 255]);
            $table->setPrimaryKey(['name']);
        }

    }
}