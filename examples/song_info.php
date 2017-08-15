<?php


require_once __DIR__ . '/../vendor/autoload.php';

use smalu\SpotifyDBus;

$data = SpotifyDBus\SpotifyCommand::create()->getMetadata();

var_dump($data);