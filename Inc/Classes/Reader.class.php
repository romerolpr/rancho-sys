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

	public function constructTable(){
		try {

			$preg = ",";

			$return = '<p class="fleft">Datasheet: <strong>' .  self::getDatasheetName() . '</strong> <span class="fright"><a href="index.php?worksheetName=' . urlencode(self::getDatasheetName()) . '&window=expanded'.(isset($_GET["hideItem"]) ? "&hideItem=" . $_GET["hideItem"] : null).'" class="btn btn_link btn_expand" title="Expandir tabela"><i class="fas fa-expand"></i></a><a href="index.php?worksheetName=None'.(isset($_GET["window"]) && $_GET["window"] == "expanded" ? "&window=expanded" : null).'" class="btn btn_link" title="Alterar Datasheet"><i class="fas fa-exchange-alt"></i></a></span><p>';
			$return .= '<div class="box-table '. (isset($_GET["window"]) && $_GET["window"] == "expanded" ? "window_fixed" : null) .'"><table>';
			$return .= '<tr id="hide-td">';
			for ($i=0; $i < count(self::$SheetData[0]) ; $i++):
				if (isset($_GET["hideItem"])):
					$iconAdd = (!in_array($i, explode($preg, $_GET["hideItem"]))) ? array("Esconder", "fa fa-eye-slash") : array("Mostrar", "fas fa-eye");
				else:
					$iconAdd = array("Esconder", "fa fa-eye-slash");
				endif;
				$return .= "<td><a data-action='".strtolower($iconAdd[0])."' class='btn btn_click_hide ".($iconAdd[0] == "Esconder" ? "red" : null)."' data-td-hide='".$i."' href='index.php?hideItem=";
				if(isset($_GET["hideItem"]) && !in_array($i, explode($preg, $_GET["hideItem"]))):
					$hideItemsUrl = $_GET["hideItem"].$preg.$i;
				else:
					if (isset($_GET["hideItem"])):
						$arrayUrl = explode($preg, $_GET["hideItem"]);
						foreach ($arrayUrl as $key => $value) 
							if ($value == $i) 
								unset($arrayUrl[$key]);

						$hideItemsUrl = implode($preg, $arrayUrl);
					else:
						$hideItemsUrl = $i;
					endif;

				endif;
				$return .= $hideItemsUrl . (isset($_GET["window"]) && $_GET["window"] == "expanded" ? "&window=expanded" : null) . "&worksheetName=". urlencode(self::getDatasheetName());
				$return .= "' title='".$iconAdd[0]."'><i class='".$iconAdd[1]."'></i></a></td>";

			endfor;

			$return .= '</tr>';
			foreach (self::$SheetData as $key => $value):
				$s = 5;
				$return .= "<tr ".($key == 0 ? 'class="bar-table"' : null).">";
				for ($i=0; $i < count(self::$SheetData[0]) ; $i++):
					$valor = (isset($value[$i]) ? $value[$i] : "-");

					if (isset($_GET["hideItem"]) && in_array($i, explode($preg, $_GET["hideItem"])))
						$valor = null;
						$bgnone = $i;

					if (!in_array($i, array(5,6,7,8,9,10,11))):
						$return .= "<td ". (is_null($valor) ? "class=\"hide-td\"" : null) ." data-tr=\"". $key ."\" data-td=\"". $i ."\">" . $valor . "</td>";
					else:
						$array = explode(",", self::$SheetData[$key][$s]);
						$myself = array(
							0 => (isset($array[0]) ? $array[0] : null),
							1 => (isset($array[1]) ? $array[1] : null),
							2 => (isset($array[2]) ? $array[2] : null)
						);
						// var_dump($array);
						$return .= "<td ". (is_null($valor) ? "class=\"hide-td\"" : null) ." data-tr=\"". $key ."\" data-td=\"". $i ."\">" . $myself[0] . "; " . $myself[1] . "; " . $myself[2] ."</td>";
						$s++;
					endif;

				endfor;
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