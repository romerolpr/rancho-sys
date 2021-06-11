<?php

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
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e)
		{
			var_dump("We could't connect db.");
		}

		return $connection;

	}

	public static function return_query($db, $table, $where = null, $gettable = false, $limit = null)
	{	
		try 
		{
			// var_dump($where);
			if (!isset($gettable) || !$gettable):
				$stt = $db->prepare('SELECT * FROM ' . $table . (!is_null($where) ? " WHERE " . $where[0][0] . "=\"" . $where[1][$where[0][0]] . "\" AND " . $where[0][1] . "=\"" . $where[1][$where[0][1]] . "\" AND " . $where[0][2] . "=\"" . $where[1][$where[0][2]] . "\" AND " . $where[0][3] . "=\"" . $where[1][$where[0][3]] . "\""  : null) . (!is_array($limit) ? (!is_null($limit) ? ' LIMIT ' . $limit : null) : ( ' LIMIT ' . $limit[0] . ', ' . $limit[0] )));
				// var_dump($stt);
			else:
				if (!is_null($where)):
					$stt = $db->prepare('SELECT * FROM $table WHERE datasheet_name="$where"');
				else:
					
					$stt = $db->prepare('SELECT * FROM ' . $table);
				endif;
			endif;

			$stt->execute();

		} catch (PDOException $e)
		{
			return "Não foi possível recuperar os registros da tabela.";
		}

		return $stt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function insert_query($db, $table, $obj)
	{
		// try 
		// {	

			
		 	$sql = self::return_query($db, $table, array(array("nome", "posto_graduacao", "segunda_feira", "domingo"), $obj));

		 	// var_dump($obj);
				
			if(empty($sql)):
				$sql = "INSERT INTO $table (`id`, `hash`, `carimbo`, `email`, `posto_graduacao`, `nome`, `organizacao_militar`, `segunda_feira`, `terca_feira`, `quarta_feira`, `quinta_feira`, `sexta_feira`, `sabado`, `domingo`, `datasheet`) VALUES (:id, :hash, :carimbo, :email, :posto_graduacao, :nome, :organizacao_militar, :segunda_feira, :terca_feira, :quarta_feira, :quinta_feira, :sexta_feira, :sabado, :domingo, :datasheet_name)";
				$stmt = $db->prepare($sql);
				$stmt->execute($obj);

				// echo "Inserido: ",utf8_decode($obj["nome"]),"<br>";
			// else:
				// echo "O registro: <strong>",utf8_decode($obj["nome"]),"</strong> já existe.<br>";
			// echo "<pre>";
			// var_dump($sql);
			// echo "</pre>";
			// var_dump($obj);
			endif;


		// } catch (PDOException $e)
		// {
		// 	return "Impossível inserir novo registro";
		// }
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