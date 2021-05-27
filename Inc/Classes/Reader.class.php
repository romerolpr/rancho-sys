<?php

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
	static $PregUrl;
	static $InsereData;
	
	/** 
	Getter and Setter 
	**/

	public function __construct()
	{	
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
	public function setPregUrl($newPregUrl){
		self::$PregUrl = $newPregUrl;
	}
	public function powerOffInsereData($newData){
		self::$InsereData = $newData; 
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

			# Create [tr], buttons and lines head
			for ($i=0; $i < count(self::$SheetData[0]) ; $i++):

				$iconAdd = (isset($_GET["hideItem"]) && !empty($getItem)) ? (!in_array($i, explode(self::$PregUrl, $_GET["hideItem"]))) ? array("Esconder", "fa fa-eye-slash") : array("Mostrar", "fas fa-eye") : array("Esconder", "fa fa-eye-slash");
				$returnTable .= "<td><a data-action='".strtolower($iconAdd[0])."' class='btn btn_click_hide ".($iconAdd[0] == "Esconder" ? "red" : null)."' data-td-hide='".$i."' href='index.php?hideItem=";

				if(isset($_GET["hideItem"]) && !in_array($i, explode(self::$PregUrl, $_GET["hideItem"]))):
					$hideItemsUrl = $_GET["hideItem"].self::$PregUrl.$i;
				else:

					if (isset($_GET["hideItem"]) && !empty($getItem)):
						$arrayUrl = explode(self::$PregUrl, $_GET["hideItem"]);
						foreach ($arrayUrl as $key => $value) 
							if ($value == $i) 
								unset($arrayUrl[$key]);

						$hideItemsUrl = implode(self::$PregUrl, $arrayUrl);
					else:
						$hideItemsUrl = $i;
					endif;

				endif;

				$returnTable .= $hideItemsUrl . (isset($_GET["window"]) && $_GET["window"] == "expanded" ? "&window=expanded" : null) . "&worksheetName=". urlencode(self::getDatasheetName());
				$returnTable .= "' title='".$iconAdd[0]."'><i class='".$iconAdd[1]."'></i></a></td>";

			endfor;

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

	public function pushAppropriateData(){

		$Data = self::getSheetData();
		$Items = array();
		$Db = new ObjectDB();
		$Db->setter(HOST, USER, PASS, DBNAME);

		unset($Data[0]);
		foreach ($Data as $key => $value):
			$carimboData = new DateTime($value[0]);
			$myObject = array(
				"id" => null,
				"carimbo" => $carimboData->format("Y-m-d H:i:s"),
				"email" => trim($value[1]),
				"posto_graduacao" => utf8_encode($value[3]),
				"nome" => utf8_encode($value[4]),
				"organizacao_militar" => utf8_encode($value[2]),
				"segunda_feira" => utf8_encode($value[5]),
				"terca_feira" => utf8_encode($value[6]),
				"quarta_feira" => utf8_encode($value[7]),
				"quinta_feira" => utf8_encode($value[8]),
				"sexta_feira" => utf8_encode($value[9]),
				"sabado" => utf8_encode($value[10]),
				"domingo" => utf8_encode($value[11]),
				"datasheet_name" => self::getDatasheetName(),
			);
			array_push($Items, $myObject);
		endforeach;

		if (self::getInsereData()):
			foreach ($Items as $key => $value):
				// var_dump($value);
				$Insert = $Db->insert_query($Db->connect_db(), TB_RESP, $value);
			endforeach;
		endif;

	}

}

?>