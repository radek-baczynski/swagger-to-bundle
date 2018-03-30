<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 18:38
 */

namespace Apigen\GeneratorBundle\Service\Generator\Dto;


class DtoProperty
{
	/** @var  string */
	protected $name;

	/** @var DtoType */
	protected $type;

	/**
	 * DtoProperty constructor.
	 *
	 * @param $name
	 * @param $type
	 */
	public function __construct($name, DtoType $type)
	{
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return DtoType
	 */
	public function getType(): DtoType
	{
		return $this->type;
	}
}