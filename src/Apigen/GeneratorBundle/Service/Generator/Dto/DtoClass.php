<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 17:58
 */

namespace Apigen\GeneratorBundle\Service\Generator\Dto;


use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Schema;

class DtoClass
{
	/** @var  Schema */
	protected $schema;

	/** @var DtoType[] */
	protected $childTypes = [];
	/**
	 * @var DtoType
	 */
	private $type;

	/**
	 * DtoClass constructor.
	 *
	 * @param DtoType $type
	 * @param Schema $schema
	 */
	public function __construct(DtoType $type, Schema $schema)
	{
		$this->schema = $schema;
		$this->type   = $type;
	}

	/**
	 * @return \EXSyst\Component\Swagger\Collections\Definitions
	 */
	public function getProperties()
	{
		return $this->schema->getProperties();
	}

	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->type->getType();
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->type->getNamespace();
	}

	/**
	 * @return Schema
	 */
	public function getSchema(): Schema
	{
		return $this->schema;
	}

	public function getFqn()
	{
		return $this->getNamespace() . '\\' . $this->getClassName();
	}

	public function addChildType(DtoType $type)
	{
		$this->childTypes[] = $type;
		$type->setParentClass($this);
	}

	/**
	 * @return DtoType
	 */
	public function getType(): DtoType
	{
		return $this->type;
	}

	/**
	 * @return DtoType[]
	 */
	public function getChildTypes()
	{
		return $this->childTypes;
	}
}