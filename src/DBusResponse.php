<?php

namespace smalu\SpotifyDBus;

/**
 *  DBusResponse
 *
 * @author smalu
 */
class DBusResponse {
	
	protected $rawResponse;
	
	public function __construct($rawResponse) {
		$this->rawResponse = $rawResponse;
	}
	
	public static function create($rawResponse){
		
		return new DBusResponse($rawResponse);
		
	}
	
	public function asArray(){
	
		$response	= [];
		
		$pattern_key	= '/(?:string ")([A-z\:]*)/';
		$pattern_val	= '/(variant|int32|double|uint64|"| string ")/';
		
		for ($i = 0; $i < count($this->rawResponse); $i++){
			
			if(preg_match($pattern_key, $this->rawResponse[$i], $matches)){
				
				$key = $matches[1];
				$key = str_replace(['mpris:', 'xesam:'], '', $key); // fliter out unnecessary key namespaces
				$i++;
				
				if(strpos($this->rawResponse[$i], 'array [')){
					
					// value containts an dbus array, so we grab an first item
					
					$i++;
					$val = $this->rawResponse[$i];
					$i++;
					
				} else {
					
					// simple string value
					
					$val = $this->rawResponse[$i];
				
				}
				
				$i++;
				
				$val = preg_replace($pattern_val, '', $val);
				$val = ltrim($val);
				$val = rtrim($val, "\"");
				
				$response[$key] = $val;
						
			}
			
		}
		
		return $response;
		
	}
	
	
}
