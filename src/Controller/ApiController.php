<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/regions/api", name="api_add_region_api", methods={"GET"})
     */
    public function addRegionByApi(SerializerInterface $serializer)
    {
        $regionJson=file_get_contents("https://geo.api.gouv.fr/regions");

        // First method Decode then Denormalize

        /* // Frist step Decode JSON to Array
        $regionTab=$serializer->decode($regionJson,"json");
        // Second step Denormalization Array to Objet or Object Array
        $regionObject = $serializer->denormalize($regionTab, 'App\Entity\Region[]'); */

        // Second method Deserialize JSON to Object or Object Array
        $regionObject = $serializer->deserialize($regionJson,'App\Entity\Region[]','json');

        // Insert Deserialised data into dataBase
        $entityManager = $this->getDoctrine()->getManager();
        
        foreach($regionObject as $region){
           $entityManager->persist($region);
        }
        $entityManager->flush();
        
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);

    }

    /**
     * @Route("/api/regions", name="api_show_region", methods={"GET"})
     */
    public function showRegion(SerializerInterface $serializer, RegionRepository $repo)
    {
        // Serialize Object or Array Object to JSON 

        $regionsObject=$repo->findAll();
        $regionsJson =$serializer->serialize($regionsObject,"json",[
            "groups"=>["listRegionSimple"]           
        ]);
        return new JsonResponse($regionsJson,Response::HTTP_OK,[],true);

    }

    /**
     * @Route("/api/regions", name="api_add_region_api", methods={"POST"})
     */
    public function addRegion(SerializerInterface $serializer,ValidatorInterface $validator, Request $request)
    {

        // Get Body content of the Request
        $regionJson = $request->getContent();
        // Deserialize and insert into dataBase
        $region = $serializer->deserialize($regionJson, Region::class,'json');

        // Data Validation
        $errors = $validator->validate($region);
        if (count($errors)>0) {
            $errorsString =$serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($region);
        $entityManager->flush();
        return new JsonResponse("Cool neu GAYN",Response::HTTP_CREATED,[],true);

    }
    
}
