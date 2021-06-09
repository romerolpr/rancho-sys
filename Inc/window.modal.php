
<?php 


function testListValues($lista1, $lista2){	
	$clearArray = array(
		0 => array(),
		1 => array()
	);
	foreach ($lista1 as $key => $value) 
		array_push($clearArray[0], $value);
	foreach ($lista2 as $key => $value) 
		array_push($clearArray[1], $value);

	return array_diff($clearArray[0], $clearArray[1]);
}

// include_once 'Inc/geral.inc.php';
include 'Classes/DisplayAlert.class.php';
include 'Classes/ObjectDB.class.php';
include 'Classes/Reader.class.php';
include 'define.inc.php';

$Db = new ObjectDB();
$Db->setter(HOST, USER, PASS, DBNAME);

$Conf = $Db->return_query($Db->connect_db(), TB_CONF);
$Resp = $Db->return_query($Db->connect_db(), TB_RESP);

$hash = $_POST["hash"];
$listByHash = json_decode($_POST["listByHash"]);

$myUser = array(
	'nome' => null,
	'email' => null,
	'organizacao_militar' => null,
	'posto_graduacao' => null,
	'carimbo' => null,
	'status' => null,
	"refc" => array(),
	"conf" => array(),
);

$myConf = array();

foreach ($Conf as $key => $value):
	array_push($myConf, json_decode($value["data_json"], true));
endforeach;

foreach ($Resp as $key => $value):

	if ($value["hash"] == $hash):

		$myUser["refc"] = array(
			explode("&&", utf8_decode($value["segunda_feira"])),
			explode("&&", utf8_decode($value["terca_feira"])),
			explode("&&", utf8_decode($value["quarta_feira"])),
			explode("&&", utf8_decode($value["quinta_feira"])),
			explode("&&", utf8_decode($value["sexta_feira"])),
			explode("&&", utf8_decode($value["sabado"])),
			explode("&&", utf8_decode($value["domingo"])),
		);

		$myUser['nome'] = ucfirst(strtolower(trim(utf8_decode($value["nome"]))));
		$myUser['email'] = $value["email"];
		$myUser['organizacao_militar'] = utf8_decode($value["organizacao_militar"]);
		$myUser['posto_graduacao'] = trim(utf8_decode($value["posto_graduacao"]));
		$myUser['carimbo'] = $value["carimbo"];

	endif;

endforeach;

$myUser['status'] = (in_array($hash, $listByHash) ? "Completo" : "Pendente");

?>

<div class="window-user">
	
	<div class="modal-box">

		<div class="head-modal box">
			<span class="fleft title-head"><?php echo $myUser["posto_graduacao"] . " / " . $myUser["nome"] ?></span>
			<p class="fright">
				<span class="btn btn_close_window"><i class="fa fa-times"></i></span>
			</p>
		</div>
		
		<div class="body-modal box">
			<div class="grid-content">
				<div>
					<?php

					// var_dump($myUser);

					$n = array();
					foreach ($myUser["refc"] as $keyresp => $resp):

						$data = explode(",", trim($resp[0]));

						if (!empty($data[0])):
							if (preg_match('/,/', $resp[0])):
								for ($i=0; $i < count($data); $i++):
									array_push($n, array(str_replace("Café da manhã", "Cáfé", trim($data[$i])), $resp[1]));
								endfor;
							else:
								array_push($n, array(trim($resp[0]), $resp[1]));
							endif;
						endif;

					endforeach;

					foreach ($myConf as $keyconf => $conf):

						if ($conf["hash"] == $hash):
							foreach ($conf[0]["values"] as $keyvalues => $val)
								array_push($myUser["conf"], explode(";", $val));
						endif;

					endforeach;

					// var_dump($n);
					// var_dump($myUser["conf"]);

					// foreach ($dados as $key => $value) array_push($myUser["conf"], $value);

					// var_dump($myUser["conf"]);

					?>
					<span class="label">Nome:</span>
					<p><?php echo $myUser["nome"]; ?></p>

					<span class="label">E-mail:</span>
					<p><?php echo $myUser["email"]; ?></p>

					<span class="label">Organização militar:</span>
					<p><?php echo $myUser["organizacao_militar"]; ?></p>	

				</div>
				<div>
					<span class="label">Posto/ Graduação</span>
					<p><?php echo $myUser["posto_graduacao"]; ?></p>

					<span class="label">Carimbo/ Hora</span>
					<p><?php echo $myUser["carimbo"]; ?></p>

					<span class="label">Status</span>
					<p><?php echo $myUser["status"]; ?></p>
				</div>
			</div>
			<div class="grid-content only-div">
				<div>
					<br>
					<span class="label">Arranchamento Individual</span>
					<table>
						
						<tr>
							<td>Dia</td>
							<td>Café</td>
							<td>Almoço</td>
							<td>Jantar</td>
						</tr>

						<?php 

						$returnTable = null;
						$indice = 0;
						foreach (
							array(
								"segunda_feira" => "Segunda",
								"terca_feira" => "Terça",
								"quarta_feira" => "Quarta",
								"quinta_feira" => "Quinta",
								"sexta_feira" => "Sexta",
								"sabado" => "Sábado",
								"domingo" => "Domingo"
							) 
							as $day => $dayValue):
							$indice++;
							$returnTable .= "<tr>";
							$returnTable .= "<td>".$dayValue."</td>";
							for ($i=0; $i <= 2; $i++): 

								$returnTable .= "<td>";
								if ($myUser["status"] != "Pendente"):
									$returnTable .= "<span class=\"btn btn-table\"><i class=\"fa fa-check\"></i>";
								else:
									$returnTable .= "<span class=\"btn btn-table red\"><i class=\"fa fa-times\"></i>";
								endif;
								$returnTable .= "</td>";

							endfor;
							$returnTable .= "</tr>";

						endforeach;

						echo $returnTable;

						?>

					</table>
				</div>
			</div>
		</div>

	</div>

</div>

<script>
	$(".btn_close_window").on("click", function(){
		$(".window-user").remove();
		$("body").css({"overflow":"auto"});
	});
</script>