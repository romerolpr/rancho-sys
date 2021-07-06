<?php

include_once 'DisplayAlert.class.php';

/**
	Connect db class
**/
class ObjectDB
{
	/*
	** Métodos do banco de dados **
	*/
	static $host, // host
	$user, // usuario
	$pass, // senha
	$db, // banco de dados

	$DisplayAlert;

	public function __construct() {
		self::setDisplayAlert();
	}

	public static function setDisplayAlert() {
		self::$DisplayAlert = new DisplayAlert();
	}

	public static function echoAlert($method, $message) {
		self::$DisplayAlert->setConfig($method, $message);
		self::$DisplayAlert->displayEcho();
	}

	public static function connect_db()
	{

		try 
		{
			$connection = new PDO("mysql:dbname=". self::$db .";host=" . self::$host . "", self::$user, self::$pass);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $connection;
		} catch (PDOException $e)
		{
			self::echoAlert("danger", "<strong>Falha: </strong> Não foi possível conectar-se com o banco de dados. Contate o desenvolvedor.");
		}

		

	}

	public static function return_query($db, $table, $where = null, $gettable = false, $limit = null, $wget = false)
	{	
		// try 
		// {	
	
			// var_dump($where);
			if (!isset($gettable) || !$gettable && $wget !== true):
				$stt = $db->prepare('SELECT * FROM ' . $table . (!is_null($where) ? " WHERE " . $where[0][0] . "=\"" . $where[1][$where[0][0]] . "\" AND " . $where[0][1] . "=\"" . $where[1][$where[0][1]] . "\" AND " . $where[0][2] . "=\"" . $where[1][$where[0][2]] . "\" AND " . $where[0][3] . "=\"" . $where[1][$where[0][3]] . "\""  : null) . (!is_array($limit) ? (!is_null($limit) ? ' LIMIT ' . $limit : null) : ( ' LIMIT ' . $limit[0] . ', ' . $limit[0] )));
				// var_dump($stt);
			else:
				if (!is_null($where)):
					$stt = $db->prepare('SELECT * FROM '.$table.' WHERE datasheet="'.$where.'"');
					// var_dump($stt);
				else:
					
					$stt = $db->prepare('SELECT * FROM ' . $table);
				endif;
			endif;

			$stt->execute();
			return $stt->fetchAll(PDO::FETCH_ASSOC);

		// } catch (PDOException $e)
		// {
		// 	self::echoAlert("warning", "<strong>Aviso: </strong> Não foi possível recuperar os registros da tabela.");
		// }

	}

	public static function insert_query($db, $table, $obj)
	{
		try 
		{	
		 	$is_null = ( is_null($obj["email"]) || is_null($obj["posto_graduacao"]) || is_null($obj["nome"]) ) ? true : false;

		 	if ($is_null !== true):;

			 	$hash = $obj["hash"];
			 	$stt = $db->prepare('SELECT * FROM '.$table.' WHERE datasheet="'.$obj["datasheet"].'" AND hash="'.$hash.'"');
			 	$stt->execute();
			 	$sql = $stt->fetchAll(PDO::FETCH_ASSOC);

				if(empty($sql)):
					$sql = "INSERT INTO 
					$table (
					`id`,
					`hash`,
					`carimbo`,
					`email`,
					`posto_graduacao`,
					`nome`,
					`organizacao_militar`,
					`segunda_feira`,
					`terca_feira`,
					`quarta_feira`,
					`quinta_feira`,
					`sexta_feira`,
					`sabado`,
					`domingo`,
					`datasheet`)
					VALUES (";
					$sql .= "'" . $obj["id"] . "',";
					$sql .= "'" . $obj["hash"] . "',";
					$sql .= "'" . $obj["carimbo"] . "',";
					$sql .= "'" . $obj["email"] . "',";
					$sql .= "'" . $obj["posto_graduacao"] . "',";
					$sql .= "'" . $obj["nome"] . "',";
					$sql .= "'" . $obj["organizacao_militar"] . "',";
					$sql .= "'" . $obj["segunda_feira"] . "',";
					$sql .= "'" . $obj["terca_feira"] . "',";
					$sql .= "'" . $obj["quarta_feira"] . "',";
					$sql .= "'" . $obj["quinta_feira"] . "',";
					$sql .= "'" . $obj["sexta_feira"] . "',";
					$sql .= "'" . $obj["sabado"] . "',";
					$sql .= "'" . $obj["domingo"] . "',";
					$sql .= "'" . $obj["datasheet"] . "'";
					$sql .= ")";
					$stmt = $db->prepare($sql);
					$stmt->execute($obj);
				endif;

			endif;

		} catch (PDOException $e)
		{	
			self::echoAlert("danger", "<strong>Falha: </strong> Não foi possível inserir os novos registros no banco de dados.");
			return false;
		}
	}

	public static function insert_data($db, $table, $obj)
	{
		try 
		{	
		 	

			$sql = "INSERT INTO $table (`id`, `hash_id`, `data_json`) VALUES (:id, :hash_id, :data_json)";
			$stmt = $db->prepare($sql);
			$stmt->execute($obj);


		} catch (PDOException $e)
		{
			return "Impossível inserir novo registro";
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
			return "We can't insert a new data on db.";
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
			return "We can't insert a new data on db.";
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