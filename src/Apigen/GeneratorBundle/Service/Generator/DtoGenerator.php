<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 16:23
 */

namespace Apigen\GeneratorBundle\Service\Generator;


use Apigen\GeneratorBundle\Service\Generator\Config\ApiConfig;
use Apigen\GeneratorBundle\Service\Generator\Dto\DtoBag;
use Apigen\GeneratorBundle\Service\Generator\Dto\DtoType;
use Doctrine\Common\Util\Inflector;
use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Parts\TypePart;
use EXSyst\Component\Swagger\Schema;
use EXSyst\Component\Swagger\Swagger;
use Twig\Environment;

class DtoGenerator
{
	/** @var  Swagger */
	protected $swagger;

	/** @var  Environment */
	protected $twig;

	/** @var DtoBag */
	protected $dtoBag;

	private $config;

	/**
	 * DtoGenerator constructor.
	 *
	 * @param Swagger $swagger
	 * @param Environment $twig
	 * @param $config
	 */
	public function __construct(Swagger $swagger, Environment $twig, ApiConfig $config)
	{
		$this->swagger = $swagger;
		$this->twig    = $twig;
		$this->config  = $config;
		$this->dtoBag  = new DtoBag();
	}


	public function addByRef($ref)
	{
		$className = $this->getClassByRef($ref);

		$namespace = $this->config->templateString('[dto][namespace]');

		$definition = $this->swagger->getDefinitions()->get($className);
		if (!$definition)
		{
			throw new \Exception('Not found definition for ref ' . $ref);
		}

		$type = new DtoType($className, true, $namespace, false);
		$this->dtoBag->add($type, $definition);

		return $type;
	}

	public function addForQuery(Operation $operation, $queryParams)
	{
		$namespace = $this->config->templateString('[dto][namespace]');

		$namespace = $namespace . '\\Query';
		$className = Inflector::classify($operation->getOperationId()) . 'Query';

		$indexed = [];
		foreach ($queryParams as $param)
		{
			$indexed[$param->getName()] = $param->toArray();
		}

		$schema = new Schema([
			'type'       => 'object',
			'properties' => $indexed,
		]);

		$type = new DtoType($className, true, $namespace, false);
		$this->dtoBag->add($type, $schema);

		return $type;
	}

	public function addArrayByRef(Schema $schema): DtoType
	{
		if ($schema->getType() !== 'array')
		{
			throw new \Exception('Only schema type array is supported');
		}

		$ref       = $schema->getItems()->getRef();
		$className = $this->getClassByRef($ref) . 'Collection';
		$namespace = $this->config->templateString('[dto][namespace]');

		$type = new DtoType($className, true, $namespace, DtoType::COLLECTION);
		$this->dtoBag->add($type, $schema);

		return $type;
	}

	private function addDefinition($name, Schema $schema)
	{
		$merged = new Schema();

		/** @var Schema $schemaItem */
		foreach ($schema->getAllOf() as $schemaItem)
		{
			$merged->merge($schemaItem->toArray());
		}

		$namespace = $this->config->templateString('[dto][namespace]');;

		$type = new DtoType($name, true, $namespace, false);
		$this->dtoBag->add($type, $merged);

		return $type;
	}

	/**
	 * @param $ref
	 *
	 * @return mixed|null
	 * @throws \Exception
	 */
	protected function getClassByRef($ref)
	{
		$matches = [];
		preg_match(' /#\/definitions\/([a-z0-9]+)/ui', $ref, $matches);
		$className = $matches[1] ?? null;

		return $className;
	}

	public function generateDtos()
	{
		$this->addChildrenDtos();

		$classes = [];

		foreach ($this->dtoBag->getClasses() as $dtoClass)
		{
			$code = $this->twig->render(
				'ApigenGeneratorBundle:skeleton/code:Dto.php.twig', [
					'dto' => $dtoClass,
				]
			);

			$classes[$dtoClass->getFqn()] = $code;
		}

		return $classes;
	}

	/**
	 * @param TypePart $parameter
	 */
	public function getParameterType($parameter)
	{
		$type = $parameter->getType();

		if ($type == 'array')
		{
			$arrType = $parameter->getItems()->getType();
			if ($arrType)
			{
				return new DtoType($arrType, false, null, true);
			}
			elseif ($parameter->getItems()->hasRef())
			{
				return $this->addArrayByRef($parameter);
			}
		}
		else if ($type)
		{
			return new DtoType($type, false, null);
		}
		else
		{
			$ref = $parameter->getRef();

			return $this->addByRef($ref);
		}


	}

	/**
	 * @param $parentName
	 *
	 * @return Schema[]
	 */
	private function findChildren($parentName)
	{
		$children = [];
		/**
		 * @var string $name
		 * @var Schema $definition
		 */
		foreach ($this->swagger->getDefinitions() as $childName => $definition)
		{
			if ($definition->getAllOf())
			{
				foreach ($definition->getAllOf() as $parent)
				{
					$ref = $parent->getRef();
					if ($ref == '#/definitions/' . $parentName)
					{
						$children[$childName] = $definition;
					}
				}
			}
		}

		return $children;
	}

	protected function addChildrenDtos(): void
	{
		/**
		 * @var string $parentName
		 * @var Schema $definition
		 */
		foreach ($this->swagger->getDefinitions() as $parentName => $definition)
		{
			if ($definition->getDiscriminator())
			{
				$children = $this->findChildren($parentName);

				foreach ($children as $childName => $schema)
				{
					$type = $this->addDefinition($childName, $schema);

					$fqn    = '\\' . $this->config->templateString('[dto][namespace]') . '\\' . $parentName;
					$parent = $this->dtoBag->find($fqn);
					if ($parent)
					{
						$parent->addChildType($type);
					}
				}
			}
		}
	}

}