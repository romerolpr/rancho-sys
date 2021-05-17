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

	public function start_compare(){

		$Datasheet = self::getCompareDatasheets();
		$filterSubset = new MyReadFilter();

		// Construct data
		$objReader = array(PHPExcel_IOFactory::createReader($Datasheet[0]["ExcelFileType"]), PHPExcel_IOFactory::createReader($Datasheet[1]["ExcelFileType"]));
		$objReader[0]->setLoadSheetsOnly($Datasheet[0]["worksheetName"])->setReadFilter($filterSubset);
		$objReader[1]->setLoadSheetsOnly($Datasheet[1]["worksheetName"])->setReadFilter($filterSubset);
		$objPHPExcel = array($objReader[0]->load($Datasheet[0]["tmp_name"]), $objReader[1]->load($Datasheet[1]["tmp_name"]));
		$sheetData = array($objPHPExcel[0]->getActiveSheet()->toArray(), $objPHPExcel[1]->getActiveSheet()->toArray());

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
	


	public function construct_object()
	{

		function add($SheetData, $obj, $indice)
		{

			switch ($indice) {
				case 0:
					$itemKey = "Carimbo de data/hora";
					break;
				case 1:
					$itemKey = "Endereço de e-mail";
					break;
				case 2:
					$itemKey = "Posto/Graduação";
					break;
				case 3:
					$itemKey = "Nome de guerra";
					break;
				case 4:
					$itemKey = "Organização Militar";
					break;
				case 5:
					$itemKey = "Segunda-feira";
					break;
				case 6:
					$itemKey = "Terça-feira";
					break;
				case 7:
					$itemKey = "Quarta-feira";
					break;
				case 8:
					$itemKey = "Quinta-feira";
					break;
				case 9:
					$itemKey = "Sexta-feira";
					break;
				case 10:
					$itemKey = "Sábado";
					break;
				case 11:
					$itemKey = "Domingo";
					break;
				
				default:
					$itemKey = "Carimbo de data/hora";
					break;
			}

			foreach ($SheetData as $key => $value):
				$return = $SheetData[$key][$indice];
				if ($SheetData[$key][$indice]):
					if ($SheetData[$key][0][0] != 0):
						array_push($obj[$indice][$itemKey], $return);
					endif;
				// else:
					// var_dump($obj[$indice][$itemKey]);
				// 	array_push($obj[$indice][$itemKey], $return);
				endif;
			endforeach;


			return $obj;
		}

		$ObjArray = array();
		$maxHeight = count(self::$SheetData[0])/2;

		for ($i=0; $i <= $maxHeight; $i++) { 
			$newarray = self::$SheetData[0][$i];
			$newarray = array(
				$newarray => array()
			);
			array_push($ObjArray, $newarray);
		}

		for ($i=0; $i < count(self::$SheetData[0]); $i++) { 
			# code...
			$ObjArray = add(self::$SheetData, $ObjArray, $i);
		}


		// var_dump($ObjArray);

	}

	public function construct_table(){

		$return = '<p>Datasheet: <strong>' . self::getDatasheetName() . '</strong><p><table>';

		foreach (self::$SheetData as $key => $value):

			$return .= "<tr ".($key == 0 ? 'class="bar-table"' : null).">";
			$return .= "<td>" . (isset($value[0]) ? $value[0] : "-") . "</td>";
			$return .= "<td>" . (isset($value[1]) ? $value[1] : "-") . "</td>";
			$return .= "<td>" . (isset($value[2]) ? $value[2] : "-") . "</td>";
			$return .= "<td>" . (isset($value[3]) ? $value[3] : "-") . "</td>";
			$return .= "<td>" . (isset($value[4]) ? $value[4] : "-") . "</td>";
			$return .= "<td>" . (isset($value[5]) ? $value[5] : "-") . "</td>";
			$return .= "<td>" . (isset($value[6]) ? $value[6] : "-") . "</td>";
			$return .= "<td>" . (isset($value[7]) ? $value[7] : "-") . "</td>";
			$return .= "<td>" . (isset($value[8]) ? $value[8] : "-") . "</td>";
			$return .= "<td>" . (isset($value[9]) ? $value[9] : "-") . "</td>";
			$return .= "<td>" . (isset($value[10]) ? $value[10] : "-") . "</td>";
			$return .= "<td>" . (isset($value[10]) ? $value[11] : "-") . "</td>";
			$return .= "</tr>";

		endforeach;

		$return .= "</table>";

		// Define Object
		self::setObject(self::$SheetData);

		echo $return;

	}

}

/**
	Connect db class
**/
class ObjectDB
{
	/*
	** Métodos do banco de dados **
	*/
	static $host; // host
	static $user; // usuario
	static $pass; // senha
	static $db; // banco de dados

	public static function connect_db()
	{

		try 
		{
			$connection = new PDO("mysql:dbname=". self::$db .";host=" . self::$host . "", self::$user, self::$pass);
		} catch (PDOException $e)
		{
			echo ("We could't connect db.");
		}

		return $connection;

	}

	public static function return_query($db, $table)
	{	
		try 
		{
			$stt = $db->prepare('SELECT * FROM ' . $table);
			$stt->execute();

		} catch (PDOException $e)
		{
			echo ("You don't have anything to return.");
		}

		return $stt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function insert_query($db, $table, $obj)
	{
		try 
		{	
			switch ($table) {
				case 'cancel':
					$sql = "INSERT INTO $table (`id`, `nome`, `posto_graduacao`, `data_time`, `solicitacao`, `motivo`, `justificativa`) VALUES (:id, :nome, :posto_graduacao, :data_time, :solicitacao, :motivo, :justificativa)";
					break;
				
				default:
					$sql = "INSERT INTO $table (`id`, `nome`, `idade`, `sexo`, `posto_graduacao`, `data_time`) VALUES (:id, :nome, :idade, :sexo, :posto_graduacao, :data_time)";
					break;
			}
				
			$stmt = $db->prepare($sql);
			$stmt->execute($obj);

		} catch (PDOException $e)
		{
			echo ("We can't insert a new data on db.");
		}
	}

	public static function update_query($db, $table, $obj, $metodo, $x = null, $replace = "&h=Cancelamentos")
	{
		try 
		{	
			switch ($x) {
				case true:
					$sql = "UPDATE $table SET status=$metodo WHERE nome=$obj";
					break;
				
				default:
					$sql = "UPDATE $table SET status=$metodo WHERE id=$obj";
					break;
			}	
				
			$stmt = $db->prepare($sql);
			$stmt->execute();

			echo "<script>document.location.replace('?action=Consultar".$replace."')</script>";

		} catch (PDOException $e)
		{
			echo ("We can't insert a new data on db.");
		}
	}

	public static function delete_query($db, $table, $obj)
	{
		try 
		{	
			$sql = "DELETE FROM $table WHERE id=$obj";
				
			$stmt = $db->prepare($sql);
			$stmt->execute();

		} catch (PDOException $e)
		{
			echo ("We can't insert a new data on db.");
		}
	}

	public static function setter($host, $user, $pass, $dbname)
	{
		self::$host = $host;
		self::$user = $user;
		self::$pass = $pass;
		self::$db = $dbname;
	}

}

?>