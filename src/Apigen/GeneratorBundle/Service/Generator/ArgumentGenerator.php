<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 16:23
 */

namespace Apigen\GeneratorBundle\Service\Generator;


use Apigen\GeneratorBundle\Service\Generator\Argument\ClassArgument;
use Apigen\GeneratorBundle\Service\Generator\Argument\ScalarArgument;
use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Schema;

class ArgumentGenerator
{
	/** @var  DtoGenerator */
	protected $dtoGenerator;

	/**
	 * ArgumentGenerator constructor.
	 *
	 * @param DtoGenerator $dtoGenerator
	 */
	public function __construct(DtoGenerator $dtoGenerator)
	{
		$this->dtoGenerator = $dtoGenerator;
	}

	public function getArguments(Operation $operation)
	{
		$arguments = [];

		$queryParams = [];

		/** @var Parameter $parameter */
		foreach ($operation->getParameters() as $parameter)
		{
			if ($parameter->getIn() === 'path')
			{
				$arguments[] = new ScalarArgument($this->dtoGenerator->getParameterType($parameter), $parameter->getName(), $parameter->getRequired());
			}
			elseif ($parameter->getIn() == 'body')
			{
				if ($parameter->getSchema()->hasRef())
				{
					$arguments[] = new ClassArgument(
						$this->dtoGenerator->addByRef($parameter->getSchema()->getRef()),
						$parameter->getName(),
						$parameter->getRequired()
					);
				}
				elseif ($parameter->getSchema()->getType() == 'array' && $parameter->getSchema()->getItems()->hasRef())
				{
					$arguments[] = new ClassArgument(
						$this->dtoGenerator->addArrayByRef($parameter->getSchema()),
						$parameter->getName(),
						$parameter->getRequired()
					);
				}
				else
				{
					throw new \Exception('Body parameter unsupported in operation: '.$operation->getOperationId());
				}
			}
			elseif ($parameter->getIn() == 'query')
			{
				$queryParams[] = $parameter;
			}
		}

		if ($queryParams)
		{
			$queryFqn    = $this->dtoGenerator->addForQuery($operation, $queryParams);
			$arguments[] = new ClassArgument($queryFqn, 'query', true);
		}

		return $arguments;
	}
}