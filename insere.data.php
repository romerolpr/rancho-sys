<?php 

$obj = array(
	'id' => null,
	'hash_id' => $value["hash"],
	'data_json' => json_encode($JSONData, true)
);


// if($value["hash"] == $items["hash_id"]):
// 	$sql = "UPDATE `tb_conf` SET `data_json`=:data_json WHERE `hash_id`=:hash_id";
// 	$stmt = $db->prepare($sql);
// 	$stmt->execute($objUpdate);	
// else:
	$sql = "INSERT INTO `tb_conf` (`id`, `hash_id`, `data_json`) VALUES (:id ,:hash_id, :data_json)";
	$stmt = $db->prepare($sql);
	$stmt->execute($obj);	
endif;


?>