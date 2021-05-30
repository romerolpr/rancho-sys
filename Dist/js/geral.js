var clicked, popup;

function confirm_clicked(url, action) {
	msg = action != "end" ? "Você tem certeza que deseja " + action + " este item?" : "Você tem certeza que deseja encerrar a sessão?";
	if (confirm(msg)) {
		document.location = url;
	}
}
$(".btn_click_consult").on("click", function(e){
	var url = $(this).attr("href"), action = $(this).attr("data-action");
	e.preventDefault();
	confirm_clicked(url, action);
});

$(".btn_expand").on("click", function(e){
	e.preventDefault();
	// if (!clicked){
		$(".box-table").addClass("window_fixed");
		window.history.pushState({url: "" + $(this).attr('href') + ""}, $(this).attr('title') , $(this).attr('href'));
		// clicked = true;
	//}
});

document.addEventListener('keydown', function (event) {
    if (event.keyCode == 27){
     	$(".box-table").removeClass("window_fixed");
     	window.history.pushState({url: window.location.href}, "Minimizar" , window.location.href.replace("&window=expanded", ""));
     	clicked = false;	
    }
});
 
$(window).bind("popstate", function(e) {
  $('.main').load(e.state.url);
});

$(".btn_expand").click(function(){ $(this).addClass("btn_active"); });

var itemsBox = [],
	is_empty = [];

function tryStatus(){
	if (is_empty.length > 0){
		for (var i = is_empty.length - 1; i >= 0; i--) {
			if (is_empty[i].checked !== false){
				delete is_empty[i];
			}
		}
	}
}

$('.input_checked').on('change', function () {

	let elem = $(this).parent(),
		hash = elem.attr("data-hash-id")
		label = $("td[data-hash-id=" + hash + "]").children("input");
 	var	values = {
 		'timestamp': new Date().getTime(),
		'hash': hash,
		'value': []
	}

	for (var i = label.length-1; i >= 0; i--) {
		if (label[i].checked !== false) {
			if( label[i].dataset.date == elem.attr("data-date")) {
				values['value'].push(label[i].value.trim() + ";" + elem.attr("data-date"));
			} else {
				values['value'].push(label[i].value.trim() + ";" + label[i].dataset.date);
			}
		} else {
			is_empty.push(label[i]);
		}
	}

 	itemsBox.push(values);
 	$("button#saveAll").text("Salvar alterações");
 	(itemsBox.length > 0) ? $("button#saveAll").prop("disabled", false).show() : $("button#saveAll").prop("disabled", true).hide();

	console.log(is_empty);
});

$('#saveAll').on("click", function(e){
	e.preventDefault();
	var data = JSON.stringify(itemsBox);
	var request = $.ajax({
	    url: "Transfer/save.form.php",
	    type: "POST",
	    data: "values=" + data,
	    dataType: "html"
	});

	request.done(function(response){
		$("button#saveAll").text("Salvando...");
		console.log(response);
	});
	request.fail(function(jqXHR, textStatus) {
	    console.log("Request failed: " + textStatus);
	});
	request.always(function() {
	    $("button#saveAll").prop("disabled", true).text("Salvo!");
	    console.log("Saved successfully.");
	});

});	


$('#report').on("click", function(e){
	e.preventDefault();
	var url = $(this).attr("href");

	tryStatus();

	if (is_empty.length !== 0){
		if (confirm("Existem campos vazios. Deseja gerar o relatório mesmo assim?")){
			document.location = url;
		}
	} else {
		console.log("Generation report...");
	}
});