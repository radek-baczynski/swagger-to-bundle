<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 08/08/17
 * Time: 21:23
 */

namespace Apigen\GeneratorBundle\Service;

use Apigen\GeneratorBundle\Service\Generator\ArgumentGenerator;
use Apigen\GeneratorBundle\Service\Generator\Config\ApiConfig;
use Apigen\GeneratorBundle\Service\Generator\SwaggerGenerator;
use Doctrine\Common\Util\Inflector;
use EXSyst\Component\Swagger\Operation;
use EXSyst\Component\Swagger\Parts\TypePart;
use EXSyst\Component\Swagger\Swagger;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;

class TwigExtension extends \Twig_Extension
{
	/** @var  Environment */
	protected $twig;
	/**
	 * @var ArgumentGenerator
	 */
	private $argumentGenerator;
	/**
	 * @var SwaggerGenerator
	 */
	private $swaggerGenerator;
	/**
	 * @var ApiConfig
	 */
	private $config;

	/**
	 * TwigExtension constructor.
	 *
	 * @param Environment $twig
	 * @param SwaggerGenerator $swaggerGenerator
	 * @param ApiConfig $config
	 */
	public function __construct(Environment $twig, SwaggerGenerator $swaggerGenerator, ApiConfig $config)
	{
		$this->twig             = $twig;
		$this->swaggerGenerator = $swaggerGenerator;
		$this->config           = $config;
	}

	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('route_name', function (string $path, string $method) {
				$name = $this->templateString('[routing][name_pattern]', [
					'method' => $method,
					'path'   => $path,
				]);

				return $name;
			}),

			new \Twig_SimpleFunction('route_controller', function (string $path, string $method) {

				$fqn    = $this->call('controller_fqn', $path, $method);
				$action = $this->call('action_name', $path, $method);

				$bundleName = $this->getConfig()['bundle']['name'];
				preg_match("#^(.+)\\\\$bundleName(\\\\(?<pname>.+))?\\\\Controller\\\\(?<cname>.+)Controller$#ui", $fqn, $match);

				return $bundleName . ':' . $match['cname'] . ':' . preg_replace('#Action$#', '', $action);

			}),

			new \Twig_SimpleFunction('controller_fqn', function (string $path, string $method) {

				$namespace = $this->templateString('[controller][namespace]', ['path' => $path, 'method' => $method]);
				$className = $this->call('controller_class_name', $path, $method);

				return $namespace . '\\' . $className;
			}),

			new \Twig_SimpleFunction('handler_fqn', function (string $resource) {

				$namespace = $this->templateString('[handler][namespace]', []);
				$className = Inflector::classify($resource) . 'HandlerInterface';

				return $namespace . '\\' . $className;
			}),

			new \Twig_SimpleFunction('controller_class_name', function (string $path, string $method) {

				$name = $this->templateString('[controller][class]', [
					'path'   => $path,
					'method' => $method,
				]);

				return $name;
			}),

			new \Twig_SimpleFunction('controller_namespace', function (string $path, string $method) {

				$namespace = $this->templateString('[controller][namespace]', ['path' => $path, 'method' => $method]);

				return $namespace;
			}),

			new \Twig_SimpleFunction('depends', function ($configProperty, ...$params) {

				$value = $this->templateString($configProperty, $params);

				return $value;
			}),

			new \Twig_SimpleFunction('resource_name', function (string $path, string $method) {

				$value = $this->getResourceName($path, $method);

				return $value;
			}),

			new \Twig_SimpleFunction('action_arguments', function (Operation $operation) {

				$arguments = $this->swaggerGenerator->getArgumentGenerator()->getArguments($operation);

				$string = implode(', ', array_map(function ($arg) {
					return (string)$arg;
				}, $arguments));

				return $string;
			}),

			new \Twig_SimpleFunction('handler_arguments', function (Operation $operation, $type = false) {

				$arguments = $this->swaggerGenerator->getArgumentGenerator()->getArguments($operation);

				$string = implode(', ', array_map(function ($arg) use ($type) {
					return $type ? (string)$arg : '$' . $arg->getName();
				}, $arguments));

				return $string;
			}),

			new \Twig_SimpleFunction('property_type', function ($parameter) {

				$type = $this->swaggerGenerator->getDtoGenerator()->getParameterType($parameter);

				return $type;
			}),

			new \Twig_SimpleFunction('action_name', function (string $path, string $method) {

				$id   = $this->getSwagger()->getPaths()->get($path)->getOperation($method)->getOperationId();
				$name = $method . Inflector::classify($id) . 'Action';

				return $name;
			}),
		];
	}

	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('underscore', function ($val) {

				$val = Inflector::tableize($val);
				$val = trim($val, '_');

				return $val;
			}),
			new \Twig_SimpleFilter('pathToWords', function ($val) {
				$val = preg_replace('#[/]+#', ' ', $val);
				$val = preg_replace('#{[a-z0-9_\-]+}#ui', ' ', $val);
				$val = preg_replace('#[ ]+#', ' ', $val);
				$val = trim($val);

				return $val;
			}),
			new \Twig_SimpleFilter('classify', [Inflector::class, 'classify']),
		];
	}

	public function templateString($templateString, $params = [])
	{
		$apiConfig = $this->twig->getGlobals()['api_config'];

		$value = PropertyAccess::createPropertyAccessor()->getValue($apiConfig, $templateString);
		if ($value)
		{
			$templateString = $value;
		}

		$rendered = $this->twig
			->createTemplate($templateString)
			->render(array_merge($apiConfig, $params));

		return $rendered;
	}

	private function call($function, ...$params)
	{
		$callable = $this->twig->getFunction($function)->getCallable();

		return call_user_func($callable, ...$params);
	}

	/**
	 * @return Swagger
	 */
	private function getSwagger()
	{
		return $this->twig->getGlobals()['swagger'];
	}

	public function getConfig()
	{
		return $this->twig->getGlobals()['api_config'];
	}

	private function getResourceName($path, $method)
	{
		return $this->config->getResourceName($this->getSwagger(), $path, $method);
	}

}