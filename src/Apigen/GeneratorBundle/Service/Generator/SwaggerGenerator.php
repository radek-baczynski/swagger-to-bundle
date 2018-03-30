<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 13/08/17
 * Time: 16:54
 */

namespace Apigen\GeneratorBundle\Service\Generator;


use Apigen\GeneratorBundle\Service\Generator\Config\ApiConfig;
use Apigen\GeneratorBundle\Service\TwigExtension;
use EXSyst\Component\Swagger\Swagger;
use EXSyst\Component\Swagger\Tag;
use Twig\Environment;

class SwaggerGenerator
{
	/** @var  Swagger */
	protected $swagger;

	/** @var DtoGenerator */
	protected $dtoGenerator;

	/** @var ArgumentGenerator */
	protected $argumentGenerator;

	/** @var HandlerGenerator */
	protected $handlerGenerator;
	/**
	 * @var Environment
	 */
	private $twig;

	/**
	 * @var ApiConfig
	 */
	private $config;

	/**
	 * SwaggerGenerator constructor.
	 *
	 * @param Swagger $swagger
	 * @param Environment $twig
	 * @param $config
	 */
	public function __construct(Swagger $swagger, Environment $twig, $configArr)
	{
		$this->swagger = $swagger;
		$this->twig    = $twig;

		$config = new ApiConfig($configArr, $twig);
		$this->twig->addExtension(
			new TwigExtension($twig, $this, $config)
		);

		$this->twig->addGlobal('api_config', $configArr);
		$this->twig->addGlobal('swagger', $swagger);


		$this->dtoGenerator      = new DtoGenerator($swagger, $twig, $config);
		$this->argumentGenerator = new ArgumentGenerator($this->dtoGenerator);
		$this->handlerGenerator  = new HandlerGenerator($swagger, $twig, $config);

		$this->config = $config;
	}

	/**
	 * @return ArgumentGenerator
	 */
	public function getArgumentGenerator(): ArgumentGenerator
	{
		return $this->argumentGenerator;
	}

	/**
	 * @return DtoGenerator
	 */
	public function getDtoGenerator(): DtoGenerator
	{
		return $this->dtoGenerator;
	}

	public function storeCode($fqn, $code)
	{
		foreach ($this->config->getValue('[store]') ?? [] as $prefix => $dir)
		{
			if (preg_match('#^' . preg_quote($prefix) . '#ui', $fqn))
			{
				$path = str_replace($prefix, '', $fqn);
				$path = $dir . str_replace('\\', '/', $path);
				$dir  = dirname($path);
				if (!is_dir($dir))
				{
					mkdir($dir, 0777, true);
				}

				file_put_contents($path . '.php', $code);
			}

		}
	}

	public function storeFile($file, $content)
	{
		$baseDir = $this->config->getValue('[bundle][dir]');
		$dir     = $baseDir . '/' . dirname($file);
		if (!is_dir($dir))
		{
			mkdir($dir, 0777, true);
		}

		$filePath = $dir . '/' . basename($file);
		file_put_contents($filePath, $content);
	}

	/**
	 * @return ApiConfig
	 */
	public function getConfig(): ApiConfig
	{
		return $this->config;
	}

	public function generateBundleClasses()
	{
		$fqn = sprintf(
			'%s\\DependencyInjection\\%sExtension',
			$this->config->getValue('[bundle][namespace]'),
			$this->config->getValue('[bundle][name]')
		);

		$code = $this->twig->render('ApigenGeneratorBundle:skeleton/code:Extension.php.twig');
		$this->storeCode($fqn, $code);

		$fqn = sprintf(
			'%s\\%s',
			$this->config->getValue('[bundle][namespace]'),
			$this->config->getValue('[bundle][name]')
		);

		$code = $this->twig->render('ApigenGeneratorBundle:skeleton/code:Bundle.php.twig');
		$this->storeCode($fqn, $code);

		$this->storeFile('Resources/config/services.yml', 'services: ~');
	}

	/**
	 * @return HandlerGenerator
	 */
	public function getHandlerGenerator(): HandlerGenerator
	{
		return $this->handlerGenerator;
	}
}