<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 17:16
 */

namespace Apigen\GeneratorBundle\Service\Generator\Config;


use EXSyst\Component\Swagger\Swagger;
use EXSyst\Component\Swagger\Tag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;

class ApiConfig
{
	protected $arr;

	/** @var  Environment */
	protected $twig;

	/**
	 * ApiConfig constructor.
	 *
	 * @param $arr
	 * @param Environment $twig
	 */
	public function __construct($arr, Environment $twig)
	{
		$this->arr  = $arr;
		$this->twig = $twig;
	}

	public function getValue($path)
	{
		$value = PropertyAccess::createPropertyAccessor()->getValue($this->arr, $path);
		if (is_array($value))
		{
			return $value;
		}

		return $this->templateString($path, []);
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

	public function getResourceName(Swagger $swagger, $path, $method)
	{
		$resourceTags = $this->arr['resources']['tags'];
		$path         = $swagger->getPaths()->get($path);
		$pathTags     = $path->getOperation($method)->getTags();
		$pathTags     = array_map(function (Tag $tag) {
			return $tag->getName();
		}, $pathTags);

		$common = array_intersect($pathTags, $resourceTags);

		return ucfirst($common[0]) ?? 'Other';
	}
}