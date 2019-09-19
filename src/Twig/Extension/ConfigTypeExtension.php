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

namespace Effiana\ConfigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class ConfigTypeExtension
 * @package Effiana\ConfigBundle\Twig\Extension
 */
class ConfigTypeExtension extends AbstractExtension
{
    /**
     * @return array|\TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('configType', [$this, 'configType']),
        ];
    }

    /**
     * @param string $value
     * @param string $type
     * @return int|string
     */
    public function configType(string $value, string $type)
    {
        if($type === 'file') {
            return sprintf('<img src="%s" width="100" height="20"/>', $value);
        }
        settype($value, $type);
        switch($type) {
            case 'boolean':
            case 'bool':
                return ($value === true)?'On':'Off';
                break;
            case 'int':
            case 'integer':
                return (int)$value;
                break;
            case 'string':
            default:
                return $value;
                break;
        }
    }
}