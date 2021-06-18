<?php

/**
	Class for show errors
	<span class="** class ** ** type **">** message **</span>

**/


class DisplayAlert
{

	static $Class;
	static $Message;
	static $Style = array(
		"display" 		=> "block",
		"box-sizing" 	=> "border-box",
		"padding" 		=> ".6em",
		"border-radius" => "3px",
		"border-width" 	=> "1px",
		"font-weight" 	=> "normal",
		"font-size" 	=> "12px",
		"width" 		=> "100%",
		"margin" 		=> "1em 0",
		"border-style" 	=> "solid",
		"float"			=> "left",
		"border-color" 	=> null,
		"color" 		=> null, 
		"background-color" 	=> null,
	);
	static $Status = true;

	public function __construct()
	{	
		if (isset(self::$Class) && isset(self::$Message)):
			$class = buildSpan(self::$Message);
			return (isset($class) ? $class : "undefined");
		endif;
	}

	/*
	Getting and setter
	*/
	public function setClass($class){
		self::$Class = $class;
	}
	public function setMessage($message){
		self::$Message = $message;
	}
	public function getClass(){
		return self::$Class;
	}
	public function getConfig(){
		return self::$Style;
	}
	public function setConfig($class, $message){

		self::setClass($class);
		self::setMessage($message);
	}
	public static function getMessage(){
		return self::$Message;
	}
	public static function setStyle($class)
	{
		switch ($class):
			case 'warning':
				self::$Style["border-color"] = "#fbf0d0";
				self::$Style["background-color"] = "#fff7df";
				self::$Style["color"] = "#856404";
				break;

			case 'danger':
				self::$Style["border-color"] = "#f5c6cb";
				self::$Style["background-color"] = "#f8d7da";
				self::$Style["color"] = "#721c2";
				break;
			case 'success':
				self::$Style["border-color"] = "#c3e6cb";
				self::$Style["background-color"] = "#c4f5d0";
				self::$Style["color"] = "#155724";
				break;
			
			default:
				self::$Style["border-color"] = "#ddd";
				self::$Style["background-color"] = "#eee";
				self::$Style["color"] = "#444";
				break;
		endswitch;

		$css = null;
		foreach (self::$Style as $attr => $value):
			$css .= $attr . ": " . $value . "; ";
		endforeach;

		return "style=\"". $css ."\"";
	}

	public static function getStyle()
	{
		return self::setStyle(self::$Class);
	}

	public static function buildSpan()
	{
		if (in_array(self::$Class, array("warning", "danger", "success"))):

			switch (self::$Class):
				case 'warning':
					$return = "<span class=\"alert warning\" ". self::getStyle() .">". self::getMessage() ."</span>";
					break;

				case 'danger':
					$return = "<span class=\"alert danger\" ". self::getStyle() .">". self::getMessage() ."</span>";
					break;

				case 'success':
					$return = "<span class=\"alert success\" ". self::getStyle() .">". self::getMessage() ."</span>";
					break;
				
				default:
					$return = "<span class=\"alert default\" ". self::getStyle() .">". self::getMessage() ."</span>";
					break;
			endswitch;

		else:
			return false;
		endif;

		return $return;
	}

	public static function Display()
	{	
		return array(
			"Class" => self::getClass(),
			"Context"  => self::getMessage(),
			"Style"  => self::getConfig()
		);
	}

	public function setStatus($stt)
	{
		self::$Status = $stt;
	}
	public function getStatus()
	{
		return self::$Status;
	}

	public function displayPrint()
	{
		return isset(self::$Status) ? self::buildSpan() : null;
	}

}