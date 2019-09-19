<?php

namespace Effiana\ConfigBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Setting
 * @package Effiana\ConfigBundle\Entity
 *
 * @Doctrine\ORM\Mapping\Table(name="effiana_config_setting")
 * @Doctrine\ORM\Mapping\Entity()
 */
class Setting implements SettingInterface {

	/**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(name="name", type="string", length=255)
	 * @var string
	 * @Assert\NotBlank
	 */
	protected $name;

	/**
	 * @var string|null
     * @Assert\NotBlank
     * @Doctrine\ORM\Mapping\Column(name="value", type="string", length=255)
	 */
	protected $value;

	/**
	 * @var string|null
     * @Doctrine\ORM\Mapping\Column(name="section", type="string", length=255)
	 */
	protected $section = 'GLOBAL';

    /**
     * @var string|null
     * @Assert\NotBlank
     * @Doctrine\ORM\Mapping\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string|null
     * @Doctrine\ORM\Mapping\Column(name="comment", type="string", length=255)
     */
    protected $comment;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SettingInterface
     */
    public function setName(string $name): SettingInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        $value = $this->value;
        if($this->type === 'file') {
            if($value !== null) {
                return new File($value, false);
            }
            return $value;
        }
        if($this->type) {
            settype($value, $this->type);
        }
        return $value;
    }

    /**
     * @param null|string $value
     * @return SettingInterface
     */
    public function setValue(string $value): SettingInterface
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }

    /**
     * @param null|string $section
     * @return SettingInterface
     */
    public function setSection(string $section): SettingInterface
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return SettingInterface
     */
    public function setType(string $type): SettingInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return SettingInterface
     */
    public function setComment(string $comment): SettingInterface
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Creates a {@code SettingInterface}.
     * @param string $name
     * @param $type
     * @param string|null $value
     * @param string|null $section
     * @param null $comment
     * @return SettingInterface
     */
	public static function create(string $name, string $type, string $value = null, string $section = null, string $comment = null) {
		$setting = new static();
		$setting->setName($name);
		$setting->setType($type);
		$setting->setValue($value);
		$setting->setSection($section);
		$setting->setComment($comment);

		return $setting;
	}

}
