<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 08/08/17
 * Time: 19:59
 */

namespace Apigen\GeneratorBundle\Command;


use Apigen\GeneratorBundle\Service\Generator\SwaggerGenerator;
use EXSyst\Component\Swagger\Path;
use EXSyst\Component\Swagger\Swagger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class GenerateCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this->setName('api:generate');
		$this->addArgument('apiName');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$config = $this->getContainer()->getParameter('apigen_config')['apis'][$input->getArgument('apiName')];

		$swagger = Swagger::fromFile($config['file']);

		$twig = $this->getContainer()->get('twig');
		$gen  = new SwaggerGenerator($swagger, $twig, $config);

		foreach ($config['store'] as $dir)
		{
			if (file_exists($dir))
			{
				$this->rrmdir($dir);
			}
		}


		$routesYml = $twig->render(
			'@ApigenGenerator/skeleton/config/routes.yml.twig',
			[
				'swagger' => $swagger,
			]
		);

		$gen->storeFile('Resources/config/routes.yml', $routesYml);

		$this->generateControllers($swagger, $twig, $gen);
		$this->generateDtos($gen);
		$this->generateRequestHandlers($gen);
		$this->generateBundleClasses($gen);
	}

	private function generateControllers(Swagger $swagger, Environment $twig, SwaggerGenerator $gen)
	{
		$controllers = [];

		/**
		 * @var string $pathString
		 * @var Path $path
		 */
		foreach ($swagger->getPaths() as $pathString => $path)
		{
			foreach ($path->getOperations() as $method => $operation)
			{
				$controllerFqn = $twig->getFunction('controller_fqn')->getCallable()($pathString, $method);
				$className     = $twig->getFunction('controller_class_name')->getCallable()($pathString, $method);
				$namespace     = $twig->getFunction('controller_namespace')->getCallable()($pathString, $method);

				if (!($controllers[$controllerFqn] ?? false))
				{
					$controllers[$controllerFqn] = [
						'className' => $className,
						'fqn'       => $controllerFqn,
						'namespace' => $namespace,
						'actions'   => [],
					];
				}

				$controllers[$controllerFqn]['actions'][] = [
					'path'       => $path,
					'pathString' => $pathString,
					'method'     => $method,
					'operation'  => $operation,
				];
			}
		}

		foreach ($controllers as $controllerFqn => $controller)
		{
			$controllerCode = $twig->render('ApigenGeneratorBundle:skeleton/code:Controller.php.twig', $controller);

			$gen->storeCode(
				$controllerFqn,
				$controllerCode
			);
		}
	}

	private function generateDtos(SwaggerGenerator $gen)
	{
		$classes = $gen->getDtoGenerator()->generateDtos();

		foreach ($classes as $fqn => $code)
		{
			$gen->storeCode($fqn, $code);
		}
	}

	private function generateRequestHandlers(SwaggerGenerator $gen)
	{
		$classes = $gen->getHandlerGenerator()->generateHandlers();

		foreach ($classes as $fqn => $code)
		{
			$gen->storeCode($fqn, $code);
		}
	}

	private function rrmdir($dir)
	{
		foreach (glob($dir . '/*') as $file)
		{
			if (is_dir($file))
			{
				$this->rrmdir($file);
			}
			else
			{
				unlink($file);
			}
		}
		rmdir($dir);
	}

	private function generateBundleClasses(SwaggerGenerator $gen)
	{
		$gen->generateBundleClasses();
	}


}