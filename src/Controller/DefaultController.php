<?php

namespace App\Controller;

use App\Entity\Horaires;
use App\Entity\Poubelles;
use App\Entity\Produit;
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

	public function scan(string $cb, string $cp,ManagerRegistry $doctrine): Response
	{
		//Get product by codebarre
		$repositoryProduit = $doctrine->getRepository(Produit::class);
		$produit = $repositoryProduit->findBy(["codeBarre" => $cb]);

		//Get City by CP
		$repositoryVille = $doctrine->getRepository(Villes::class);
		$ville = $repositoryVille->findBy(["cp" => $cp]);

		$poubelles = [];
		$produit[0]->createPoubelles();

		foreach ($produit[0]->getCompositions() as $composant )
		{
			$find = false;
			for($i=0; $i< sizeof($ville[0]->getPoubelles()) && !$find ; $i++)
			{
				for($j=0; $j < sizeof($ville[0]->getPoubelles()[$i]->getContenues()) && !$find;$j++ )
				{
					if($ville[0]->getPoubelles()[$i]->getContenues()[$j]->getLabel() == $composant->getMatiere()->getLabel())
					{
						if(!$produit[0]->includePoubelle($produit[0]->getPoubelles(),$ville[0]->getPoubelles()[$i]))
						{
							$produit[0]->addPoubelles($ville[0]->getPoubelles()[$i]);
							$ville[0]->getPoubelles()[$i]->createDechets();
						}
						$ville[0]->getPoubelles()[$i]->addDechets($composant);

						$find = true;
					}
				}
			}
		}

		//Serialize Resultat
		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$normalizer = new GetSetMethodNormalizer($classMetadataFactory);
		$serializer = new Serializer([$normalizer,new DateTimeNormalizer()], $encoders);

		$produit = $serializer->normalize($produit, 'json',['groups' => 'produit']);

		return new JsonResponse([$produit]);
	}

	public function recherche(string $recherche,ManagerRegistry $doctrine): Response
	{
		//Get product by marque or name
		$repositoryProduit = $doctrine->getRepository(Produit::class);
		$produit = $repositoryProduit->findByBrandOrName("%".$recherche."%");


		if(empty($produit))
		{
			$produit = null;
		}
		else
		{
			//Serialize Resultat
			$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
			$encoders = [new XmlEncoder(), new JsonEncoder()];

			$normalizer = new GetSetMethodNormalizer($classMetadataFactory);
			$serializer = new Serializer([$normalizer,new DateTimeNormalizer()], $encoders);

			$produit = $serializer->normalize($produit, 'json',['groups' => 'recherche']);
		}
		return new JsonResponse($produit);
	}
}