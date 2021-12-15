<?php

namespace App\Controller;

use App\Entity\Composition;
use App\Entity\Contenu;
use App\Entity\Horaires;
use App\Entity\Notification;
use App\Entity\Poubelles;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Villes;
use Doctrine\Persistence\ManagerRegistry;
use ExpoSDK\ExpoMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

	public function scan(string $cb, string $cp,ManagerRegistry $doctrine,HttpClientInterface $client): Response
	{
		$entityManager = $doctrine->getManager();

		//Get product by codebarre
		$repositoryProduit = $doctrine->getRepository(Produit::class);
		$produit = $repositoryProduit->findBy(["codeBarre" => $cb]);

		//Get City by CP
		$repositoryVille = $doctrine->getRepository(Villes::class);
		$ville = $repositoryVille->findBy(["cp" => $cp]);

		//Serializer
		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$normalizer = new GetSetMethodNormalizer($classMetadataFactory);
		$serializer = new Serializer([$normalizer,new DateTimeNormalizer()], $encoders);

		if($produit == null)
		{
			$produitOFF = $client->request("GET","https://world.openfoodfacts.org/api/v0/product/".$cb.".json");
			$produitOFF = json_decode($produitOFF->getContent())->product;
			$produit = new Produit();
			$produit->setCodeBarre($produitOFF->_id);
			$produit->setLabel($produitOFF->generic_name_fr);
			$produit->setMarque($produitOFF->brands_tags[0]);
			if(property_exists($produitOFF, "packaging_text_fr"))
			{
				$packagings = $produitOFF->packaging_text_fr;
				if($packagings != "")
				{
					$explodes = explode("\r\n",$packagings);
					foreach ($explodes as $explode)
					{
						$words = explode(" ", $explode);
						$repositoryContenu = $doctrine->getRepository(Contenu::class);
						if(strlen(str_replace($words[3],""," ")) < 2)
						{
							$contenu = $repositoryContenu->findOneBy(["label"=> $words[2]]);
						}
						else
						{
							$contenu = $repositoryContenu->findOneBy(["label"=> $words[3]]);

						}
						if($contenu == null)
						{
							$contenu = new Contenu();
							$contenu->setLabel($words[3]);
							$entityManager->persist($contenu);
						}
						$composition = new Composition();
						$composition->setLabel($words[1]);
						$composition->setMatiere($contenu);
						$entityManager->persist($composition);

						$produit->addComposition($composition);
					}
				}
			}

		}
		else
		{
			$produit = $produit[0];
		}

		$produit->createPoubelles();

		foreach ($produit->getCompositions() as $composant )
		{
			$find = false;
			for($i=0; $i< sizeof($ville[0]->getPoubelles()) && !$find ; $i++)
			{
				for($j=0; $j < sizeof($ville[0]->getPoubelles()[$i]->getContenues()) && !$find;$j++ )
				{
					if($ville[0]->getPoubelles()[$i]->getContenues()[$j]->getLabel() == $composant->getMatiere()->getLabel())
					{
						if(!$produit->includePoubelle($produit->getPoubelles(),$ville[0]->getPoubelles()[$i]))
						{
							$produit->addPoubelles($ville[0]->getPoubelles()[$i]);
							$ville[0]->getPoubelles()[$i]->createDechets();
						}
						$ville[0]->getPoubelles()[$i]->addDechets($composant);

						$find = true;
					}
				}
			}
		}
		$entityManager->persist($produit);
		$entityManager->flush();
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

	public function notifications(Request $request,ManagerRegistry $doctrine)
	{
//		//Provisoire
//		$request = Request::create(
//			'/notifications/settings',
//			'POST',
//			[
//				'token' => 'ExponentPushToken[xxxx-xxxx-xxxx]',
//				'notifications' => [1, 2],
//			]
//		);

		$entityManager = $doctrine->getManager();


		$token = $request->request->get('token');
		$poubelles = $request->request->get('notifications');

		if($token == null)
		{
			throw new \Exception("Le token doit être fourni");
		}

		// Create user or search one

		$repositoryUser = $doctrine->getRepository(User::class);
		$user = $repositoryUser->findOneBy(["token" => $token]);

		if($user == null)
		{
			$user = new User();
			$user->setToken($token);
			$entityManager->persist($user);
		}


		$repositoryPoubelle = $doctrine->getRepository(Poubelles::class);
		foreach ($poubelles as $poubelleNotif)
		{
			$poubelle = $repositoryPoubelle->find($poubelleNotif);

			//Create Notification entity with poubelle and user

			$notification = new Notification();
			$notification->setPoubelle($poubelle);
			$notification->setUser($user);
			$notification->setTimeNotif(3600);
			$entityManager->persist($notification);
		}

		$entityManager->flush();

		return new JsonResponse();
	}
}