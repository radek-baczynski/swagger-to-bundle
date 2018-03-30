<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 17/08/17
 * Time: 23:09
 */

namespace Apigen\GeneratorBundle\Service\Generator\Dto;


class DtoType
{
	const ARRAY      = 'array';
	const COLLECTION = 'collection';

	/** @var  string */
	protected $type;
	/** @var  boolean */
	protected $isClass;
	/** @var  string */
	protected $namespace;
	/** @var string */
	protected $arrType;
	/** @var  DtoClass */
	protected $parentClass;

	/**
	 * DtoType constructor.
	 *
	 * @param string $type
	 * @param bool $isClass
	 * @param string $namespace
	 * @param bool $arrType
	 */
	public function __construct($type, $isClass, $namespace, $arrType = false)
	{
		$this->type      = $type;
		$this->isClass   = $isClass;
		$this->namespace = $namespace;
		$this->arrType   = $arrType;
	}

	public function toPhp()
	{
		return ($this->namespace ? '\\' . $this->namespace . '\\' : '') . $this->type;
	}

	public function toPhpDoc()
	{
		return $this->toPhp() . ($this->arrType == self::ARRAY ? '[]' : '');
	}

	public function toJmsSerializer()
	{
		return $this->arrType == self::ARRAY ? 'array<' . ltrim($this->toPhp(), '\\') . '>' : ltrim($this->toPhp(), '\\');
	}

	public function getType()
	{
		return $this->type;
	}

	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @param DtoClass $parentClass
	 *
	 * @return DtoType
	 */
	public function setParentClass(DtoClass $parentClass): DtoType
	{
		$this->parentClass = $parentClass;

		return $this;
	}

	/**
	 * @return DtoClass|null
	 */
	public function getParentClass(): ?DtoClass
	{
		return $this->parentClass;
	}
}