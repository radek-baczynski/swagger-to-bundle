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
use Doctrine\Common\Util\Inflector;
use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Path;
use EXSyst\Component\Swagger\Schema;
use EXSyst\Component\Swagger\Swagger;
use Twig\Environment;

class HandlerGenerator
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
	}

	public function generateHandlers()
	{
		$handlers = [];

		/**
		 * @var string $pathString
		 * @var Path $path
		 */
		foreach ($this->swagger->getPaths() as $pathString => $path)
		{
			foreach ($path->getOperations() as $method => $operation)
			{
				$resource = $this->config->getResourceName($this->swagger, $pathString, $method);

				$handlerFqn = $this->twig->getFunction('handler_fqn')->getCallable()($resource);
				preg_match('#(.+)\\\\(.+)$#', $handlerFqn, $matches);

				if (!($handlers[$handlerFqn] ?? false))
				{
					$handlers[$handlerFqn] = [
						'className' => $matches[2],
						'resource'  => $resource,
						'fqn'       => $handlerFqn,
						'namespace' => $matches[1],
						'methods'   => [],
					];
				}

				$handlers[$handlerFqn]['methods'][] = [
					'path'       => $path,
					'pathString' => $pathString,
					'method'     => $method,
					'operation'  => $operation,
				];
			}
		}

		$ret = [];
		foreach ($handlers as $handlerFqn => $handler)
		{
			$code = $this->twig->render('ApigenGeneratorBundle:skeleton/code:HandlerInterface.php.twig', $handler);
			$ret[$handlerFqn] = $code;
		}

		return $ret;
	}
}