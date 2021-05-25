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