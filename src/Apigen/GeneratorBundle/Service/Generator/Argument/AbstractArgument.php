<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 16:24
 */

namespace Apigen\GeneratorBundle\Service\Generator\Argument;


use Apigen\GeneratorBundle\Service\Generator\Dto\DtoType;

abstract class AbstractArgument
{
	/** @var DtoType  */
	protected $type;

	protected $name;

	protected $required;

	/**
	 * AbstractArgument constructor.
	 *
	 * @param DtoType $type
	 * @param $name
	 * @param $required
	 */
	public function __construct(DtoType $type, $name, $required)
	{
		$this->type     = $type;
		$this->name     = $name;
		$this->required = $required;
	}

	public function __toString()
	{
		return sprintf('%s $%s%s', $this->type->toPhp(), $this->name, $this->required ? '' : '=null');
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}
}