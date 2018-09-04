<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;

class AlbumController extends Controller
{


  // return album by token
  public function getOne($token)
  {

  	$albums = $this->getDoctrine()
        ->getRepository(Album::class)
        ->find($token);

    //var_dump($artists[0]->getToken());

    $albums_arr = array();
    if($albums){
    	$albums_arr = array();
    	$albums_arr['token'] = $albums->getToken();
    	$albums_arr['title'] = $albums->getTitle();
      $albums_arr['cover'] = $albums->getCover();
      $albums_arr['description'] = $albums->getDescription();

      // get album's artist
      $artist = $this->getDoctrine()
        ->getRepository(Artist::class)
        ->find($albums->getArtist());
      if($artist){
        $albums_arr['artist']['token'] = $artist->getToken();
        $albums_arr['artist']['name'] = $artist->getName();
      }

      // get album songs
      $songs = $this->getDoctrine()
        ->getRepository(Song::class)
        ->findBy(['album' => $token]);
      $songs_arr = array();
      $count = 0;
      foreach ($songs as $song) {
        $songs_arr[$count]['title'] = $song->getTitle();
        $songs_arr[$count]['length'] = $song->getLength()/60;
        $count++;
      }
      $albums_arr['songs'] = $songs_arr;


    }

    $response = new JsonResponse($albums_arr);
    return $response;

    }
}