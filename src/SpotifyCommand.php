<?php

namespace smalu\SpotifyDBus;

use AdamBrett\ShellWrapper\Command;
use AdamBrett\ShellWrapper\Command\SubCommand;
use AdamBrett\ShellWrapper\Runners\Exec;


class SpotifyCommand {
	
	
	const DBUS_COMMAND		= 'timeout 1 dbus-send';
	const DBUS_DEST_PLAYER	= 'org.mpris.MediaPlayer2.Player';
	const DBUS_DEST_SPOTIFY	= 'org.mpris.MediaPlayer2.spotify';
	const DBUS_PATH			= '/org/mpris/MediaPlayer2';
	
	private $shell;
	private $command;
	
	public function __construct() {
		
		$this->shell	= new Exec();
		$this->command	= new Command(self::DBUS_COMMAND);
	
		$this->command->addSubCommand(new SubCommand('--print-reply'));
		$this->command->addSubCommand(new SubCommand('--dest=' . self::DBUS_DEST_SPOTIFY));
		$this->command->addSubCommand(new SubCommand(self::DBUS_PATH));
		
	}
	
	
	public function create(){
			
		return new SpotifyCommand();
	
	}
	
	public function play(){
		
		$this->command->addSubCommand(new SubCommand('org.mpris.MediaPlayer2.Player.Play'));
	
		$this->run();
		
	}
	
	public function pause(){
		
		$this->command->addSubCommand(new SubCommand('org.mpris.MediaPlayer2.Player.Pause'));
	
		$this->run();
		
	}
	
	
	public function playPause(){
		
		$this->command->addSubCommand(new SubCommand('org.mpris.MediaPlayer2.Player.PlayPause'));
	
		$this->run();
	}
	
	public function playUri($uri){
		
		if($uri === NULL){
			
			throw new \InvalidArgumentException('No spotify URI provided');
			
		}
		
		$this->command->addSubCommand(new SubCommand('org.mpris.MediaPlayer2.Player.OpenUri'));
		$this->command->addSubCommand(new SubCommand('string:' . $uri));
		
		$this->run();

	}
	
	public function getMetadata(){
		
		$this->command->addSubCommand(new SubCommand('org.freedesktop.DBus.Properties.Get'));
		$this->command->addSubCommand(new SubCommand('string:"' . self::DBUS_DEST_PLAYER . '"'));
		$this->command->addSubCommand(new SubCommand('string:"Metadata"'));

		$this->run();
		
		return DBusResponse::create($this->shell->getOutput())->asArray();
		
	}
	
	private function run(){
		
		$this->shell->run($this->command);
		
		
		if($this->shell->getReturnValue() === 1){
			
			throw new \Exception('Could not connect to Spotify via DBus');
		
		}else if($this->shell->getReturnValue() === 124){
			
			// GNU timeout default exit code for timeouted scripts
			
			throw new \Exception('Could not connect to Spotify via DBus - timeout');
		
		}
	}
	
	
	
}
