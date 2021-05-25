<?php

/** 
	Global class of system 
**/
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
	public function readCell($column, $row, $worksheetName = '') {
		// Read rows 1 to 7 and columns A to E only
		if ($row >= 1) {

			if (in_array($column, range('A','Z'))) {
				return true;
			}
		}
		return false;
	}
}


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


/**
	Class for building and construct
**/
class Render
{

	static $TableName;
	static $DatasheetName;
	static $SheetData;
	static $unset;
	static $Object;
	

	/** 
	Getter and Setter 
	**/

	public function __construct()
	{	
		# Unseting the first element [always null]
		if (self::$unset)
			unset(self::$SheetData[0]);
	}

	public function getTableName(){
		return self::$TableName;
	}
	public function getDatasheetName(){
		return self::$DatasheetName;
	}
	public function getSheetData(){
		return self::$SheetData;
	}
	public function getUnset(){
		return self::$unset;
	}
	public function getObject(){
		return self::$Object;
	}
	
	public function setSheetData($newSheetData){
		self::$SheetData = $newSheetData;
	}
	public function setTableName($newtableName){
		self::$TableName = $newtableName;
	}
	public function setUnset($newUnset){
		self::$unset = $newUnset;
	}
	public function setDatasheetName($newDatasheetName){
		self::$DatasheetName = $newDatasheetName;
	}
	public function setObject($newObject){
		self::$Object = $newObject;
	}

	public function construct_table(){
		try {
			$return = '<p class="fleft">Datasheet: <strong>' . self::getDatasheetName() . '</strong> <span class="fright"><a href="index.php?worksheetName=' . self::getDatasheetName() . '" class="btn btn_link btn_expand" title="Expandir"><i class="fas fa-expand"></i></a><a href="index.php?worksheetName=None" class="btn btn_link" title="Alterar Datasheet"><i class="fas fa-pen"></i></a><a href="index.php?compare" class="btn btn_link" title="Comparação"><i class="fas fa-file-upload"></i></a></span><p>';
			$return .= '<div class="box-table"><table>';

			foreach (self::$SheetData as $key => $value):

				// var_dump(self::$SheetData);

				// var_dump($value);
				// break;

				$return .= "<tr ".($key == 0 ? 'class="bar-table"' : null).">";
				for ($i=0; $i < count(self::$SheetData[0]) ; $i++) { 
					# code...
					$return .= "<td data-tr=\"". $key ."\" data-td=\"". $i ."\">" . (isset($value[$i]) ? $value[$i] : "-") . "</td>";
				}
				// $return .= "<td>" . (isset($value[1]) ? $value[1] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[2]) ? $value[2] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[3]) ? $value[3] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[4]) ? $value[4] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[5]) ? $value[5] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[6]) ? $value[6] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[7]) ? $value[7] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[8]) ? $value[8] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[9]) ? $value[9] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[10]) ? $value[10] : "-") . "</td>";
				// $return .= "<td>" . (isset($value[10]) ? $value[11] : "-") . "</td>";
				$return .= "</tr>";

			endforeach;

			$return .= "</div></table>";

			// Define Object
			self::setObject(self::$SheetData);

			echo $return;

		} catch (Exception $e) {
			echo 'Exceção capturada: ',  $e->getMessage(), "\n";
		}

	}

}

?>