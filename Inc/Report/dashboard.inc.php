<?php

$arrayNum = array(

	"organizacao_militar" =>
		array(
			"b_adm_ap_ibirapuera" => array(0, 0),
			"8_bpe" => array(0, 0),
			"apoio_direto" => array(0, 0),
			"agsp" => array(0, 0)
		),

	"posto_graduacao" =>
		array(
			"of_capten" => array(0, 0),
			"1_sgt" => array(0, 0),
			"2_sgt" => array(0, 0),
			"st" => array(0, 0),
			"of_sup" => array(0, 0),
			"3_sgt" => array(0, 0),
			"civil" => array(0, 0)
		),

	"total" => (count($listPendent) + count($listComplete))
);


foreach ($Resp as $key => $value):

	$omreal = strtolower(preg_replace(array("/(º|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_decode(trim($value["organizacao_militar"])))));
	$pgreal = strtolower(preg_replace(array("/(º|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_decode(trim($value["posto_graduacao"])))));

	$arrayNum["organizacao_militar"][$omreal][0] += 1;
	$arrayNum["total"] += 1;
	$arrayNum["organizacao_militar"][$omreal][1] = round(($arrayNum["organizacao_militar"][$omreal][0] * 100) / $arrayNum["total"]);

	$arrayNum["posto_graduacao"][$pgreal][0] += 1;
	$arrayNum["posto_graduacao"][$pgreal][1] = round(($arrayNum["posto_graduacao"][$pgreal][0] * 100) / $arrayNum["total"]);
endforeach;

$arranc = array(
	"a" => array(),
	"b" => array()
);

$pendente = array(
	'Café' 		=> array(0, 0),
	'Almoço' 	=> array(0, 0),
	'Jantar' 	=> array(0, 0),
);

$completo = array(
	'Café' 		=> array(0, 0),
	'Almoço' 	=> array(0, 0),
	'Jantar' 	=> array(0, 0),
);

$maxValues = array(
	"organizacao_militar" => max($arrayNum["organizacao_militar"]),
	"posto_graduacao" => max($arrayNum["posto_graduacao"]),
	"completo" => max($completo),
	"pendente" => max($pendente)
);


foreach ($listPendent as $key => $value):

	foreach ($value[1] as $valkey => $item):
		array_push($arranc["a"], explode(";", $item));
	endforeach;

endforeach;

foreach ($Conf as $key => $value):

	$data_json = json_decode($value["data_json"], true);

	foreach ($data_json[0]["values"] as $key => $value) {
		$data = explode(";", $value);
		// var_dump($data);
		array_push($arranc["b"], $data);
	}

endforeach;

// Pendentes
foreach ($arranc["a"] as $key => $value) {
	$pendente[$value[0]][0] += 1;
}

// Completos
foreach ($arranc["b"] as $key => $value) {
	$completo[$value[0]][0] += 1;
}

// foreach ($pendente as $key => $value):
// 	var_dump($value[0]);
// endforeach;

$data = array(
 array("B ADM AP IBIRAPUERA", $arrayNum["organizacao_militar"]["b_adm_ap_ibirapuera"]), 
 array('8º BPE', $arrayNum["organizacao_militar"]["8_bpe"]),
 array('Apoio Direto', $arrayNum["organizacao_militar"]["apoio_direto"]),
 array('AGSP', $arrayNum["organizacao_militar"]["agsp"]),
 );     

// var_dump($arrayNum);

$backgroundColors = array(
	'rgb(140, 212, 148)',
	'rgb(140, 169, 212)',
	'rgb(220, 151, 102)',
	'rgb(186, 102, 220)',
	'rgb(220, 178, 102)',
	'rgb(220, 102, 102)',
	'rgb(98, 193, 74)',
	'rgb(186, 193, 74)',
	'rgb(193, 74, 74)',
);

// var_dump($maxValues);

?>

<!-- <p class="fleft d-center-items sticky">
	<span><strong>Básico geral</strong></span>
</p> -->

<div class="box-bg-board">

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_posto_graduacao" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_organizacao_militar" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_comparations" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_max" width="400" height="400"></canvas>
		</div>
	</div>

</div>


<script>

	var ctx = [
		document.getElementById('chart_organizacao_militar'), 
		document.getElementById('chart_posto_graduacao'),
		document.getElementById('chart_comparations'),
		document.getElementById('chart_max'),
	];

	var labels = {
		'organizacao_militar': [
			'B ADM AP IBIRAPUERA',
			'8º BPE',
			'Apoio Direto',
			'AGSP'
		], 

		'posto_graduacao': [
			'Of cap/ten',
			'1º sgt',
			'2º sgt',
			'St',
			'Of sup',
			'3º sgt',
			'Civil'
		]
	}

	var labelsComparations = [];

	for (var i = 0; i < labels["organizacao_militar"].length; i++) 
		labelsComparations.push(labels["organizacao_militar"][i]);

	for (var i = 0; i < labels["posto_graduacao"].length; i++) 
		labelsComparations.push(labels["posto_graduacao"][i]);


	// console.log(labelsComparations);

	const options = [
	{
        plugins: {
            title: {
                display: true,
                text: 'Organização militar'
            }
        }
    },
	{
        plugins: {
            title: {
                display: true,
                text: 'Posto/ Graduação'
            }
        }
    },
	{
		responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Arranchamentos Realizados x Conferidos'
            },
        }
    },
	{
        plugins: {
            title: {
                display: true,
                text: 'Maior/ Menor valor por Arranchamento'
            },
        }
    },
    ]

	const data_om = {
	  labels: labels['organizacao_militar'],
	  datasets: [{
	    label: 'Arranchados',
	    data: [
		<?php 
			foreach ($arrayNum['organizacao_militar'] as $key => $value) echo $value[1] . ",\n";
		?>
	    ],
	    backgroundColor: [
	      <?php 
	      sort($backgroundColors);
	      for ($i=0; $i <= $arrayNum['posto_graduacao'] ; $i++):
	      	if (isset($backgroundColors[$i])):
	      		echo "\"$backgroundColors[$i]\",\n";
	      	else:
	      		echo '"rgb(238, 238, 238)",';
	      		break;
	      	endif;
	      endfor;
	      ?>
	    ],
	    hoverOffset: 4
	  }]
	};

	const data_comparation= {
	  labels: ["Café", "Almoço", "Jantar"],
	  datasets: [
	  {
	    label: 'Realizados',
	    data: [
		<?php 
			foreach ($pendente as $key => $value) echo $value[0] . ",\n";
		?>
	    ],
	    backgroundColor: "rgb(140, 169, 212)",
	    borderColor: "rgb(140, 169, 212, 0.5)",
	    hoverOffset: 4
	  },
	  {
  	    label: 'Conferidos',
  	    data: [
  		<?php 
  			foreach ($completo as $key => $value) echo $value[0] . ",\n";
  		?>
  	    ],
  	    backgroundColor: "rgb(140, 212, 148)",
  	    borderColor: "rgb(140, 212, 148, 0.5)",
  	    hoverOffset: 4
	  }]
	};

	const data_pg = {
	  labels: labels['posto_graduacao'],
	  datasets: [{
	    label: 'Arranchados',
	    data: [
	    <?php 
	    	foreach ($arrayNum['posto_graduacao'] as $key => $value) echo $value[1] . ",\n";
	    ?>
	    ],
	    backgroundColor: [
	      <?php 
	      sort($backgroundColors);
	      for ($i=0; $i <= $arrayNum['posto_graduacao'] ; $i++):
	      	if (isset($backgroundColors[$i])):
	      		echo "\"$backgroundColors[$i]\",\n";
	      	else:
	      		echo '"rgb(238, 238, 238)",';
	      		break;
	      	endif;
	      endfor;
	      ?>
	    ],
	    hoverOffset: 4
	  }]
	};

	const data_max = {
	  labels: ["Organização Militar", "Posto/ Graduação"],
	  datasets: [
	  {
	    label: 'Máximo',
	    data: [
	    <?php 
	    	foreach ($maxValues as $key => $value) echo $value[0] . ",\n";
	    ?>
	    ],
	   	backgroundColor: "rgb(140, 169, 212)",
	   	borderColor: "rgb(140, 169, 212, 0.5)",
	    hoverOffset: 4
	  },
	  {
	    label: 'Mínimo',
	    data: [
	    <?php 
	    	foreach ($maxValues as $key => $value) echo $value[1] . ",\n";
	    ?>
	    ],
	    backgroundColor: "rgb(140, 212, 148)",
	    borderColor: "rgb(140, 212, 148, 0.5)",
	    hoverOffset: 4
	  },
	  ]
	};

	new Chart(ctx[0], {
	    type: 'pie',
	    data: data_om,
	    options: options[0]
	});

	new Chart(ctx[1], {
	    type: 'pie',
	    data: data_pg,
	    options: options[1]
	});

	new Chart(ctx[2], {
	    type: 'bar',
	    data: data_comparation,
	    options: options[2],

	});

	new Chart(ctx[3], {
	    type: 'bar',
	    data: data_max,
	    options: options[3],

	});

	var el = document.querySelector(".graph"); // get canvas

</script>