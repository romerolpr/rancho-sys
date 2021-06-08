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
	$arrayNum["organizacao_militar"][$omreal][1] = round(($arrayNum["organizacao_militar"][$omreal][0] * 100) / $arrayNum["total"]);

	$arrayNum["posto_graduacao"][$pgreal][0] += 1;
	$arrayNum["posto_graduacao"][$pgreal][1] = round(($arrayNum["posto_graduacao"][$pgreal][0] * 100) / $arrayNum["total"]);
endforeach;

$arranc = array();

$pendente = array(
	'Café' 		=> array(0, 0),
	'Almoço' 	=> array(0, 0),
	'Jantar' 	=> array(0, 0),
);

foreach ($listPendent as $key => $value):

	foreach ($value[1] as $valkey => $item) array_push($arranc, explode(";", $item));

endforeach;



foreach ($arranc as $key => $value):

	$pendente[trim($value[0])] += 1;

endforeach;



$data = array(
 array("B ADM AP IBIRAPUERA", $arrayNum["organizacao_militar"]["b_adm_ap_ibirapuera"]), 
 array('8º BPE', $arrayNum["organizacao_militar"]["8_bpe"]),
 array('Apoio Direto', $arrayNum["organizacao_militar"]["apoio_direto"]),
 array('AGSP', $arrayNum["organizacao_militar"]["agsp"]),
 );     

// var_dump($arrayNum);

$backgroundColors = array(
	'rgb(255, 99, 132)',
	'rgb(54, 162, 235)',
	'rgb(255, 205, 86)',
	'rgb(96, 255, 86)',
	'rgb(96, 255, 86)',
	'rgb(235, 221, 54)',
	'rgb(235, 54, 54)',
	'rgb(40, 224, 139)',
	'rgb(187, 61, 172)',
);

?>

<!-- <p class="fleft d-center-items sticky">
	<span><strong>Básico geral</strong></span>
</p> -->

<div class="box-bg-board">

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_posto_graduacao" width="400" height="400"></canvas>
		</div>
		<!-- <div class="no-bg">

			<?php foreach ($arrayNum['posto_graduacao'] as $key => $value): ?>
				<div class="chart graph_<?php echo $key?>" data-percent="<?php echo $value[1]?>"></div>
			<?php endforeach; ?>
		
		</div> -->
	</div>

	<div class="box-board">
		<div>
			<canvas id="chart_organizacao_militar" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_pendentes" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="box-board">
		<div>
			<canvas id="chart_concluidos" width="400" height="400"></canvas>
		</div>
	</div>

</div>


<script>

	var ctx = [document.getElementById('chart_organizacao_militar'), document.getElementById('chart_posto_graduacao')];

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

	const options = [
	{
        plugins: {
            title: {
                display: true,
                text: 'Arranchamento por Organização militar'
            }
        }
    },
	{
        plugins: {
            title: {
                display: true,
                text: 'Arranchamento por Posto/ Graduação'
            }
        }
    },
    ]

	const data_om = {
	  labels: labels['organizacao_militar'],
	  datasets: [{
	    label: 'Organização Militar',
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

	const data_pg = {
	  labels: labels['posto_graduacao'],
	  datasets: [{
	    label: 'Posto/ Graduação',
	    data: [
	    <?php 
	    	foreach ($arrayNum['posto_graduacao'] as $key => $value) echo $value[1] . ",\n";
	    ?>
	    ],
	    backgroundColor: [
	      <?php 
	      asort($backgroundColors);
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

	new Chart(ctx[0], {
	    type: 'bar',
	    data: data_om,
	    options: options[0]
	});

	new Chart(ctx[1], {
	    type: 'bar',
	    data: data_pg,
	    options: options[1]
	});


	var el = document.querySelector(".graph"); // get canvas


	function createCanvas(el, colorPrimary){

		var opt = {
		    percent:  el.getAttribute('data-percent') || 25,
		    size: el.getAttribute('data-size') || 220,
		    lineWidth: el.getAttribute('data-line') || 20,
		    rotate: el.getAttribute('data-rotate') || 0
		}

		var canvas = document.createElement('canvas');
		var span = document.createElement('span');

		canvas.className = "canvas-circle";
		span.className = "span-circle";
		span.textContent = opt.percent + '%';
		    
		if (typeof(G_vmlCanvasManager) !== 'undefined') {
		    G_vmlCanvasManager.initElement(canvas);
		}

		var ctxCircle = canvas.getContext('2d');
		canvas.width = canvas.height = opt.size;

		el.appendChild(span);
		el.appendChild(canvas);

		ctxCircle.translate(opt.size / 2, opt.size / 2); // change center
		ctxCircle.rotate((-1 / 2 + opt.rotate / 180) * Math.PI); // rotate -90 deg

		//imd = ctxCircle.getImageData(0, 0, 240, 240);
		var radius = (opt.size - opt.lineWidth) / 2;

		var drawCircle = function(color, lineWidth, percent) {
				percent = Math.min(Math.max(0, percent || 1), 1);
				ctxCircle.beginPath();
				ctxCircle.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
				ctxCircle.strokeStyle = color;
		        ctxCircle.lineCap = 'round'; // butt, round or square
				ctxCircle.lineWidth = lineWidth
				ctxCircle.stroke();
		};

		drawCircle("#efefef", opt.lineWidth, 100 / 100);
		drawCircle("#555555", opt.lineWidth, opt.percent / 100);
	}

	<?php foreach ($arrayNum['posto_graduacao'] as $key => $value): ?>
		createCanvas(document.querySelector('.graph_<?php echo $key?>', '<?php echo $backgroundColors[array_search($key, $arrayNum['posto_graduacao'])]?>'));
	<?php endforeach; ?>

	<?php foreach ($arrayNum['organizacao_militar'] as $key => $value): ?>
		createCanvas(document.querySelector('.graph_<?php echo $key?>'), '<?php echo $backgroundColors[array_search($key, $arrayNum['organizacao_militar'])]?>');
	<?php endforeach; ?>

</script>


<?php echo "<br>"; var_dump($pendente);
?>