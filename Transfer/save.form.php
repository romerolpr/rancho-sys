<?php

require_once '../Inc/Classes/ObjectDB.class.php';
require_once '../Inc/define.inc.php';

$Db = new ObjectDB();
$Db->setter(HOST, USER, PASS, DBNAME);
$db = $Db->connect_db();

$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$myObject = json_decode($post["values"], true);

$getfromDb = $Db->return_query($db, TB_CONF);

$JSONData = array();

// print_r($getfromDb);

foreach ($myObject as $key => $value):
	array_push($JSONData, array('hash' => $value['hash'], array('timestamp' => $value["timestamp"], 'values' => $value["value"])));
endforeach;

function insertOn($db, $hash, $JSONData){
	$obj = array(
		'id' => null,
		'hash_id' => $hash,
		'data_json' => json_encode($JSONData, true),
	);

	array_unique($JSONData);

	$sql = "INSERT INTO `tb_conf` (`id`, `hash_id`, `data_json`) VALUES (:id ,:hash_id, :data_json)";
	$stmt = $db->prepare($sql);
	$stmt->execute($obj);	
	
}

function updateOn($Db, $db, $hash, $JSONData){
	$obj = array(
		'hash_id' => $hash,
		'data_json' => json_encode($JSONData, true)
	);

	$sql = "UPDATE `tb_conf` SET `data_json`=:data_json WHERE `hash_id`=:hash_id";
	$stmt = $db->prepare($sql);
	$stmt->execute($obj);	
}

if (!empty($getfromDb)):
	foreach ($getfromDb as $key => $items):

		//Inserting or changing data
		foreach ($JSONData as $i => $value):

			if($items["hash_id"] == $value["hash"]):
				updateOn($Db, $db, $value["hash"], $value);
			else:
				// insertOn($db, $value["hash"], $value);
			endif;

		endforeach;
			

	endforeach;
else:


	//Inserting or changing data
	foreach ($JSONData as $i => $value):

		insertOn($db, $value["hash"], $value);

	endforeach;

endif;

// print_r($JSONData);
