<?php

namespace App\DataFixtures\ORM;

//use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Song;

use App\Utils\TokenGenerator;


class LoadArtistData implements ORMFixtureInterface{ //FixtureInterface

	public function load(ObjectManager $manager)
    {

    	// get the file contents from the URL
    	$content = file_get_contents('https://gist.githubusercontent.com/fightbulc/9b8df4e22c2da963cf8ccf96422437fe/raw/8d61579f7d0b32ba128ffbf1481e03f4f6722e17/artist-albums.json');
		$json = json_decode($content, true);

		//var_dump($json);


		$artistids = array();
		$albumids = array();

		foreach ($json as $key ) {
			$new_aid = TokenGenerator::generate(6);
			array_push($artistids, $new_aid);

			// track uniqueness of artist tokens
			while( in_array($new_aid, $artistids)){
				$new_aid = TokenGenerator::generate(6);
			}
			
			// Import Artist Data
			$artist = (new Artist())
	            ->setToken($new_aid)
	            ->setName($key['name'])
	        ;
	        $manager->persist($artist);
	        $manager->flush();

	        $albums = $key['albums'];

	        foreach ($albums as $alb ) {

	        	$new_albid = TokenGenerator::generate(6);
				array_push($albumids, $new_albid);

				// track uniqueness of album tokens
				while( in_array($new_albid, $albumids)){
					$new_albid = TokenGenerator::generate(6);
				}

	        	// Import Album Data
		        $album = (new Album())
		            ->setToken($new_albid)
		            ->setTitle($alb['title'])
		            ->setCover($alb['cover'])
		            ->setDescription($alb['description'])
		            ->setArtist($new_aid)
		        ;
		        $manager->persist($album);
		        $manager->flush();

		        $songs = $alb['songs'];
		        foreach ($songs as $sng ) {
		        	// Import Song Data

		        	$time_seconds = Song::minstrToSec($sng['length']);
					
			        $song = (new Song())
			            ->setTitle($sng['title'])
			            ->setLength($time_seconds)
			            ->setAlbum($new_albid)
			        ;
			        $manager->persist($song);
			        $manager->flush();
			    }
	        }
	        


		}

        

    }

}