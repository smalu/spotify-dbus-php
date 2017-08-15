<?php


require_once __DIR__ . '/../vendor/autoload.php';

use smalu\SpotifyDBus;

while(true){

	SpotifyDBus\SpotifyCommand::create()->playPause();
	sleep(1);

}