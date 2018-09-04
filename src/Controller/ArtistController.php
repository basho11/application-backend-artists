<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Artist;

class ArtistController extends Controller
{

  // return all artists
  public function index()
  {

  	$artists = $this->getDoctrine()
        ->getRepository(Artist::class)
        ->findAll();

    //var_dump($artists[0]->getToken());

    $artists_arr = array();
    $count = 0;
    foreach ($artists as $art) {
    	$artists_arr[$count]['token'] = $art->getToken();
    	$artists_arr[$count]['name'] = $art->getName();
    	$count++;
    }

    $response = new JsonResponse($artists_arr);
    return $response;
  }


  // return artist by token
  public function getOne($token)
  {

  	$artists = $this->getDoctrine()
        ->getRepository(Artist::class)
        ->find($token);

    //var_dump($artists[0]->getToken());

    $artists_arr = array();
    if($artists){
    	$artists_arr = array();
    	$artists_arr['token'] = $artists->getToken();
    	$artists_arr['name'] = $artists->getName();
    }

    $response = new JsonResponse($artists_arr);
    return $response;

    }
}