<?php

class Compare
{

	static $ObjectToCompare = null;
	static $ArrayErr = array(
		"Nome de guerra" => array());

	public function __construct($Object1, $Object2)
	{	
		self::setCompareDatasheets(array($Object1, $Object2));
	}

	public function getCompareDatasheets(){
		return self::$ObjectToCompare;
	}
	public function getArrayErr()
	{
		return self::$ArrayErr;
	}

	public function setCompareDatasheets($newObject)
	{
		self::$ObjectToCompare = $newObject;
	}

	public function loadSheetsTemplate($Datasheet){

		$filterSubset = new MyReadFilter();
		$objReader = PHPExcel_IOFactory::createReader($Datasheet["ExcelFileType"]);
		$objReader->setLoadSheetsOnly($Datasheet["worksheetName"])->setReadFilter($filterSubset);
		$objPHPExcel = $objReader->load($Datasheet["tmp_name"]);

		return $objPHPExcel->getActiveSheet()->toArray();

	}

	public function start_compare(){

		$Datasheet = self::getCompareDatasheets();
		
		// Construct data
		$sheetData = array(self::loadSheetsTemplate($Datasheet[0]), self::loadSheetsTemplate($Datasheet[1]));
		$listaRefeicaoQuarta = array( 0 => array(), 1 => array());
		$listaNomes = array( 0 => array(), 1 => array());

		foreach ($sheetData[0] as $key => $value) {
			array_push($listaNomes[0], $value[4]);
			array_push($listaRefeicaoQuarta[0], $value[7]);
		}
		foreach ($sheetData[1] as $key => $value) {
			// var_dump($value);
			array_push($listaNomes[1], $value[2]);
			array_push($listaRefeicaoQuarta[1], $value[3]);
		}

		// var_dump($sheetData[1]);

		var_dump($listaRefeicaoQuarta);
		var_dump($listaNomes);

		// foreach ($listaNomes as $key => $value) {
			
		// }

	}

}

?>