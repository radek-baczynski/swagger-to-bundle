<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 14/08/17
 * Time: 23:13
 */

namespace Apigen\ApiSupportBundle\Http;


use JMS\Serializer\DeserializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;

class ApiParamConverter implements ParamConverterInterface
{
	const OPTION_VALIDATION = 'validation';
	const OPTION_TYPE       = 'type';

	/** @var SerializerInterface */
	private $serializer;
	/**
	 * @var ValidatorInterface
	 */
	private $validator;
	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;


	/**
	 * @param SerializerInterface $serializer
	 * @param ValidatorInterface $validator
	 * @param TokenStorageInterface $tokenStorage
	 */
	public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, TokenStorageInterface $tokenStorage)
	{
		$this->serializer   = $serializer;
		$this->validator    = $validator;
		$this->tokenStorage = $tokenStorage;
	}

	/** {@inheritdoc} */
	public function apply(Request $request, ParamConverter $configuration)
	{
		$options = $configuration->getOptions();

		$class = $options[self::OPTION_TYPE] ?? $configuration->getClass();

		$params = null;
		switch ($request->getMethod())
		{
			case 'GET':
				$params = $request->query->all();
				break;

			case 'POST':
			case 'PUT':
			case 'PATCH':
				$params = $request->request->all();
				break;

			default:
				throw new \InvalidArgumentException(sprintf('Unknown source "%s" to get request data from', $request->getMethod()));
		}

		$context = new DeserializationContext();
		$context->setSerializeNull(true);

		$object = $this->serializer->fromArray($params, $class, $context);

		// default true
		if ($options[self::OPTION_VALIDATION] ?? true)
		{
			$groups = ['Default', strtoupper($request->getMethod())];

			if ($request->attributes->has('_route'))
			{
				$groups[] = strtoupper($request->attributes->get('_route'));
			}

			$errors = $this->validator->validate($object, null, $groups);
			$request->attributes->set('validationErrors', $errors);
		}

		$request->attributes->set($configuration->getName(), $object);
	}

	/** {@inheritdoc} */
	public function supports(ParamConverter $configuration)
	{
		$class = $configuration->getClass();

		if ($class)
		{
			return true;
		}
	}
}