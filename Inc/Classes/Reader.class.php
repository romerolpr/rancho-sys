<?php

/**
	Class for building and construct
**/
class Render
{

	static 
	$TableName,
	$DatasheetName,
	$SheetData,
	$unset,
	$Object,
	$PregUrl,
	$Status,
	$Filter,

	$worksheetNameDefault = false,
	$InsereData = false,
	$CountElem = false;
	
	/** 
	Getter and Setter 
	**/

	public function __construct()
	{	
		if (!isset(self::$Status))
			self::setStatus(false);
		// self::setFilter(self::getFilterDefault());

		if (!isset(self::$InsereData) or is_null(self::$InsereData))
			self::powerOffInsereData(true);
		# Unseting the first element [always null]
		if (self::$unset)
			unset(self::$SheetData[0]);
		if (!isset(self::$PregUrl) or is_null(self::$PregUrl))
			self::setPregUrl("!");
	}

	public function getTableName(){
		return self::$TableName;
	}
	public function getCountElem(){
		return self::$CountElem;
	}
	public function getInsereOnDb(){
		return self::$InsereOnDb;
	}
	public function getFilter(){
		return self::$Filter;
	}
	public function getWorksheetNameDefault(){
		return self::$worksheetNameDefault;
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
	public function getPregUrl(){
		return self::$PregUrl;
	}
	public function getInsereData(){
		return self::$InsereData;
	}
	public function getStatus(){
		return self::$Status;
	}
	public function setSheetData($newSheetData){
		self::$SheetData = $newSheetData;
	}
	public function setWorksheetNameDefault($newWorksheetNameDefault){
		self::$worksheetNameDefault = $newWorksheetNameDefault;
	}
	public function setTableName($newtableName){
		self::$TableName = $newtableName;
	}
	public function setUnset($newUnset){
		self::$unset = $newUnset;
	}
	public function setCountElem($newCountElem){
		self::$CountElem = $newCountElem;
	}
	public function setInsereOnDb($newInsereOnDb){
		self::$InsereOnDb = $newInsereOnDb;
	}
	public function setDatasheetName($newDatasheetName){
		self::$DatasheetName = $newDatasheetName;
	}
	public function setObject($newObject){
		self::$Object = $newObject;
	}
	public function setPregUrl($newPregUrl){
		self::$PregUrl = $newPregUrl;
	}
	public function powerOffInsereData($newData){
		self::$InsereData = $newData; 
	}
	public function setStatus($newStatus){
		self::$Status = $newStatus; 
	}
	public function setFilter($newFilter, $default = false){
		self::$Filter = ($default !== true) ? $newFilter : self::getFilterDefault(); 
	}

	public function getFilterDefault(){
		return array(
			"sort" => array(),
			"hide" => array(),
			"filter" => array()
		);
	}

	public function manipuleFilter(){
		
	}

	public function constructTable(){
		try {

			$current_url = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=="on") ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];

			$buttons = array(
				'<div class="box-table '. (isset($_GET["window"]) && $_GET["window"] == "expanded" ? "window_fixed" : null) .'"><p class="fleft d-center-items">',
				# start buttons
					'<span class="fleft"><strong>' .  self::getDatasheetName() . '</strong></span>',
					'<span class="fright head_table">',
						'<a href="index.php?worksheetName=' .  urlencode(self::getDatasheetName()) . '" class="btn btn_link btn_manage" title="Restaurar tabela"><i class="fas fa-undo-alt"></i></a>',
						(isset($_GET["window"]) && !empty($_GET["window"]) ? '<a href="'.str_replace("&window=expanded", "", $current_url).'" class="btn btn_link btn_manage btn_expand btn_active" title="Desativar modo: Expandir tabela"><i class="fas fa-expand"></i></a>' : '<a href="'.$current_url.'&window=expanded" class="btn btn_link btn_manage btn_expand" title="Expandir tabela"><i class="fas fa-expand"></i></a>'),
						'<a href="index.php?worksheetName=None" class="btn btn_link btn_manage" title="Alterar Datasheet"><i class="fas fa-exchange-alt"></i></a>',
						(!isset($_GET["method"]) ? '<a href="'.str_replace("&method=exb_all", "", $current_url).'&method=exb_all" class="btn btn_link btn_manage" title="Todas as colunas"><i class="fas fa-globe"></i></a>' : '<a href="'.str_replace("&method=exb_all", "", $current_url).'" class="btn btn_link btn_manage btn_active" title="Desativar modo: Todas as colunas"><i class="fas fa-globe"></i></a>'),
						'<a href="index.php?exit=session_obj" class="btn btn_link btn_manage btn_click_consult" title="Fechar arquivo" data-action="fechar"><i class="fas fa-power-off"></i></a>',
					'</span>',
				# end buttons
				'</p>'
			);

			$returnTable = implode("", $buttons) . '<table><tr id="hide-td">';
			$getItem = (isset($_GET["hideItem"]) ? strval($_GET["hideItem"]) : null);

			$returnTable .= '</tr>';

			# Create [td] and values
			foreach (self::$SheetData as $key => $value):
				$s = 5;

				$date = new DateTime();
				$today = $date->format("d/m/Y");

				$returnTable .= "<tr ".($key == 0 ? 'class="bar-table"' : null).">";

				for ($i=0; $i < count(self::$SheetData[0]) ; $i++):
					$valor = (isset($value[$i]) ? $value[$i] : "-");

					if (isset($_GET["hideItem"]) && in_array($i, explode(self::$PregUrl, $_GET["hideItem"])) && !empty($getItem))
						$valor = null;
						$bgnone = $i;

					if (!in_array($i, array(5,6,7,8,9,10,11))):
						$returnTable .= "<td ". (is_null($valor) ? "class=\"hide-td\"" : null) ." data-tr=\"". $key ."\" data-td=\"". $i ."\">" . $valor . "</td>";
					else:
						
						// var_dump($array);
						$array = ($key != 0 ? explode(",", self::$SheetData[$key][$s]) : $valor);
						$myScratch = array(
							0 => (isset($array[0]) && !is_null($array[0]) ? $array[0] : null),
							1 => (isset($array[1]) && !is_null($array[1]) ? $array[1] : null),
							2 => (isset($array[2]) && !is_null($array[2]) ? $array[2] : null)
						);

						if (isset($_GET["method"]) && $_GET["method"] == "exb_all"):
							$returnTable .= "<td ". (is_null($valor) ? "class=\"hide-td\"" : null) ." data-tr=\"". $key ."\" data-td=\"". $i ."\">" . (is_array($array) ? $myScratch[0] . "; " . $myScratch[1] . "; " . $myScratch[2] : $array) ."</td>";
						else:
							if (mb_strpos($valor, $today) !== false) 
								$saveKey = $i;
							if (isset($saveKey) && $i === $saveKey)
								$returnTable .= "<td ". (is_null($valor) ? "class=\"hide-td\"" : null) ." data-tr=\"". $key ."\" data-td=\"". $i ."\">" . (is_array($array) ? $myScratch[0] . "; " . $myScratch[1] . "; " . $myScratch[2] : $array) ."</td>";
						endif;
						$s++;

					endif;

				endfor;
				$returnTable .= "</tr>";

			endforeach;

			$returnTable .= "</div></table>";

			// Define Object
			self::setObject(self::$SheetData);

			echo $returnTable;

		} catch (Exception $e) {
			echo 'Exceção capturada: ',  $e->getMessage(), "\n";
		}

	}

	public function testItem($value, $key){
		$n = array();
		for ($i=0; $i < 3; $i++):
			$item = explode(",", $value[$key]);
			if (isset($item[$i])):
				array_push($n, true);
			else:
				array_push($n, false);
			endif;
		endfor;

		return $n;
	}

	public function cut($string, $i){
		try {
			$newString = strtolower(str_replace("-", "_", preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/", "/Ç/", "/ç/"), explode(" ","a A e E i I o O u U n N c C"),$string[$i])));
			preg_match("/\[(.*)\]/", $newString, $match);
			$match = explode(" ", end($match));
			return end($match);
		} catch (Exception $e) {
			return false;
		}
	}

	public function pushData(){

		// try {

			$Db = new ObjectDB();
			$Db->setter(HOST, USER, PASS, DBNAME);
			$Data = self::getSheetData();

			// var_dump($Data);

			$Items = array();
			$timestamp = new DateTime();

			// Save header table
			$cabecalho = $Data[0];
			unset($Data[0]); // Remove from var after save

			foreach ($Data as $key => $value):
				$carimboData = new DateTime($value[0]);
				$myObject = array(
					"id" => null,
					"hash" => md5($carimboData->format("Y-m-d H:i:s") . "_" . utf8_encode($value[1])),
					"carimbo" => $carimboData->format("Y-m-d H:i:s"),
					"email" => (isset($value[1]) ? trim($value[1]) : null),
					"posto_graduacao" => (isset($value[3]) ? utf8_encode($value[3]) : null ),
					"nome" => (isset($value[4]) ? utf8_encode($value[4]) : null),
					"organizacao_militar" => (isset($value[2]) ? utf8_encode($value[2]) : null),

					"segunda_feira" => 
					(isset($value[5]) ? utf8_encode($value[5]) . "&&" . self::cut($cabecalho, 5) : "&&" . self::cut($cabecalho, 5) ),

					"terca_feira" => 
					(isset($value[6]) ? utf8_encode($value[6]) . "&&" . self::cut($cabecalho, 6) : "&&" . self::cut($cabecalho, 6) ),

					"quarta_feira" => 
					(isset($value[7]) ? utf8_encode($value[7]) . "&&" . self::cut($cabecalho, 7) : "&&" . self::cut($cabecalho, 7) ),

					"quinta_feira" => 
					(isset($value[8]) ? utf8_encode($value[8]) . "&&" . self::cut($cabecalho, 8) : "&&" . self::cut($cabecalho, 8) ),

					"sexta_feira" => 
					(isset($value[9]) ? utf8_encode($value[9]) . "&&" . self::cut($cabecalho, 9) : "&&" . self::cut($cabecalho, 9) ),

					"sabado" => 
					(isset($value[10]) ? utf8_encode($value[10]) . "&&" . self::cut($cabecalho, 10) : "&&" . self::cut($cabecalho, 10) ),

					"domingo" => 
					(isset($value[11]) ? utf8_encode($value[11]) . "&&" . self::cut($cabecalho, 11) : "&&" . self::cut($cabecalho, 11) ),

					"datasheet_name" => self::getTableName(),
				);
				array_push($Items, $myObject);
			endforeach;

			// var_dump($Items);

			if (self::getInsereData()):
				$sheet = TB_SHEET;
				$insert = array(false, false);
				foreach ($Items as $key => $value):

					$Db->insert_query($Db->connect_db(), TB_RESP, $value);
					$db = $Db->connect_db();
					// $sql = $Db->return_query(, TB_CONF, null, true);
					$table = TB_CONF;
					$datasheet_name = self::getTableName();
					$hash = $value["hash"];

					$stt = $db->prepare('SELECT * FROM '.$table.' WHERE datasheet="'.$datasheet_name.'" AND hash_id="'.$hash.'"');
					$stt->execute();
					$sql = $stt->fetchAll(PDO::FETCH_ASSOC);

					$insert[0] = true;

					// var_dump($sql);
					if (empty($sql)):
						$db = $Db->connect_db();
						$objData = array(
							'id' => null,
							'hash_id' => $value["hash"],
							'data_json' => json_encode(
								array(
						 			'hash' => $value["hash"], 
						 			array(
						 			'timestamp' => $timestamp->getTimestamp(), 
						 			'values' => array()
						 			)
								), true
							),
							'datasheet' => self::getTableName()
						);

						$sql = "INSERT INTO `tb_conf` (`id`, `hash_id`, `data_json`, `datasheet`) VALUES (:id ,:hash_id, :data_json, :datasheet)";
						$stmt = $db->prepare($sql);
						$stmt->execute($objData);	

						$insert[1] = true;

					endif;


				endforeach;

				if ($insert[0] !== false)
					self::consoleLog("`tb_resp`: Inserted \"".count($Items)."\" data on database.");
				if ($insert[1] !== false)
					self::consoleLog("`tb_conf`: Inserted \"".count($sql)."\" data on database.");

				self::powerOffInsereData(false);

			endif;

			self::setStatus(true);
			return true;

		// } catch (Exception $e) {

		// 	self::consoleLog("Request failed: " . $e);

		// 	return false;
		// }
	}

	public function consoleLog($txt){
		echo "<script>console.log('".$txt."')</script>";
	}

}

?>