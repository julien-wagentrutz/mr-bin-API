<?php

namespace App\Controller;

use App\Entity\Horaires;
use App\Entity\Villes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class DefaultController extends AbstractController
{
	public function index(): Response
	{
		$number = random_int(0, 100);

		return new Response(
			'<html><body>Lucky number: '.$number.'</body></html>'
		);
	}

	public function horaires(string $cp,ManagerRegistry $doctrine): Response
	{
		$repository = $doctrine->getRepository(Horaires::class);
		$horaires = $repository->findByVille($cp);

		// all callback parameters are optional (you can omit the ones you don't use)
		$dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
			return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
		};

		$defaultContext = [
			AbstractNormalizer::CALLBACKS => [
				'passage' => $dateCallback,
			],
		];

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$normalizer = new GetSetMethodNormalizer($classMetadataFactory,null, null, null, null,$defaultContext);
		$serializer = new Serializer([$normalizer,new DateTimeNormalizer()], $encoders);

		$data = $serializer->normalize($horaires, 'json',['groups' => 'horaires']);

		return new JsonResponse($data);
	}
}