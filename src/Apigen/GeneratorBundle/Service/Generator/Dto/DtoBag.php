<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 16:22
 */

namespace Apigen\GeneratorBundle\Service\Generator\Dto;


use EXSyst\Component\Swagger\Schema;

class DtoBag
{
	/** @var \SplObjectStorage|DtoClass[] */
	protected $classes;

	/**
	 * DtoBag constructor.
	 */
	public function __construct()
	{
		$this->classes = new \ArrayObject();
	}


	public function add(DtoType $type, Schema $definition)
	{
		$key = $type->toPhp();
		if ($this->classes[$key] ?? false)
		{
			return $this->classes[$key];
		}

		$this->classes[$key] = new DtoClass($type, $definition);
	}

	/**
	 * @return \SplObjectStorage|DtoClass[]
	 */
	public function getClasses()
	{
		return $this->classes;
	}

	/**
	 * @param $name
	 *
	 * @return DtoClass|null
	 */
	public function find($name)
	{
		return $this->classes[$name] ?? null;
	}
}