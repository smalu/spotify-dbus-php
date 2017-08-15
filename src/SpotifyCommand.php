<?php

namespace smalu\SpotifyDBus;

use AdamBrett\ShellWrapper\Command;
use AdamBrett\ShellWrapper\Command\SubCommand;
use AdamBrett\ShellWrapper\Command\Param;
use AdamBrett\ShellWrapper\Command\Argument;
use AdamBrett\ShellWrapper\Runners\Exec;
use AdamBrett\ShellWrapper\Runners\DryRunner;


class SpotifyCommand {
	
	
	const DBUS_COMMAND		= 'dbus-send';
	const DBUS_DEST_PLAYER	= 'org.mpris.MediaPlayer2.Player';
	const DBUS_DEST_SPOTIFY	= 'org.mpris.MediaPlayer2.spotify';
	const DBUS_PATH			= '/org/mpris/MediaPlayer2';
	
	private $shell;
	private $command;
	
	public function __construct() {
		
		$this->shell	= new Exec();
		$this->command	= new Command(self::DBUS_COMMAND);
		
	}
	
	
	public function create(){
			
		return new SpotifyCommand();
	
	}
	
	public function getMetadata(){
		
		$this->command->addSubCommand(new SubCommand('--print-reply'));
		$this->command->addSubCommand(new SubCommand('--dest=' . self::DBUS_DEST_SPOTIFY));
		$this->command->addSubCommand(new SubCommand(self::DBUS_PATH));
		$this->command->addSubCommand(new SubCommand('org.freedesktop.DBus.Properties.Get'));
		$this->command->addSubCommand(new SubCommand('string:"' . self::DBUS_DEST_PLAYER . '"'));
		$this->command->addSubCommand(new SubCommand('string:"Metadata"'));

		$this->shell->run($this->command);
		
		if($this->shell->getReturnValue() === 1){
			
			throw new \Exception('Could not connect do Spotify via DBus');
		
		}
		
		return DBusResponse::create($this->shell->getOutput())->asArray();
		
	}
	
	
	
	
	
}