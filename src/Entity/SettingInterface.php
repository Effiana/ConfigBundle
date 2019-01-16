<?php

namespace Effiana\ConfigBundle\Entity;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
interface SettingInterface {

    public function getName(): ?string;

    /**
     * @param string $name
     * @return SettingInterface
     */
    public function setName(string $name): SettingInterface;

    /**
     * @return null|string
     */
    public function getValue();

    /**
     * @param null|string $value
     * @return SettingInterface
     */
    public function setValue(string $value): SettingInterface;

    /**
     * @return null|string
     */
    public function getSection(): string;

    /**
     * @param null|string $section
     * @return SettingInterface
     */
    public function setSection(string $section): SettingInterface;

    /**
     * @return null|string
     */
    public function getType(): ?string;

    /**
     * @param null|string $type
     * @return SettingInterface
     */
    public function setType(string $type): SettingInterface;

    /**
     * @return null|string
     */
    public function getComment(): ?string;

    /**
     * @param null|string $comment
     * @return SettingInterface
     */
    public function setComment(string $comment): SettingInterface;

}
