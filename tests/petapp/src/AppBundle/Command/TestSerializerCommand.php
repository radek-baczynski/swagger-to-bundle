<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 21/08/17
 * Time: 10:16
 */

namespace AppBundle\Command;


use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TestSerializerCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this->setName('test:serializer');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$refExtranctor = new ReflectionExtractor();
		$extractor     = new InheritanceTypeExtractor($refExtranctor);

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$normalizer = new ObjectNormalizer($classMetadataFactory, null, null, $extractor); //
		$serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);

		$obj = $serializer->denormalize(
			['inner' => ['foo' => 'foo', 'bar' => 'bar', 'testA' => 'valA'], 'date' => '1988/01/21'],
			ObjectOuter::class
		);

		dump($obj);
	}


}

class InheritanceTypeExtractor implements PropertyTypeExtractorInterface
{
	/** @var  ReflectionExtractor */
	protected $innerExtractor;

	/**
	 * InheritanceTypeExtractor constructor.
	 *
	 * @param ReflectionExtractor $innerExtractor
	 */
	public function __construct(ReflectionExtractor $innerExtractor)
	{
		$this->innerExtractor = $innerExtractor;
	}

	/**
	 * Gets types of a property.
	 *
	 * @param string $class
	 * @param string $property
	 * @param array $context
	 *
	 * @return Type[]|null
	 */
	public function getTypes($class, $property, array $context = [])
	{
		return $this->innerExtractor->getTypes($class, $property, $context);
	}
}

class InheritanceObjectNormalizer extends ObjectNormalizer
{

}

class ObjectOuter
{
	private $inner;
	private $date;

	public function getInner()
	{
		return $this->inner;
	}

	public function setInner(ObjectInner $inner)
	{
		$this->inner = $inner;
	}

	public function setDate(\DateTimeInterface $date)
	{
		$this->date = $date;
	}

	public function getDate()
	{
		return $this->date;
	}
}

class ObjectInner
{
	public $foo;
	public $bar;
}

class ObjectInnerA extends ObjectInner
{
	public $testA;
}

class ObjectInnerB extends ObjectInner
{
	public $testB;
}