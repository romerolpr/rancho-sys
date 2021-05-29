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

$(".btn_expand").on("click", function(){
	// e.preventDefault();
	if (!clicked){
		$(".box-table").addClass("window_fixed");
		window.history.pushState({url: "" + $(this).attr('href') + ""}, $(this).attr('title') , $(this).attr('href'));
		clicked = true;
	}
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

var itemsBox = [];

// $('.input_checked').on('change', function () {

// 	let elem = $(this).parent(),
// 		label = $("label[for=" + $(this).attr('id') + "]").text();
//  	var	values = {
// 		'hash': elem.attr("data-hash-id"),
// 		'value': label
// 	}

//  	itemsBox.push(values);
//  	$("button.btn").text("Salvar alterações");

//  	(itemsBox.length > 0) ? $("button.btn").prop("disabled", false).show() : $("button.btn").prop("disabled", true).hide();

// 	console.log(values);
// });

$('.input_checked').on('change', function () {

	let elem = $(this).parent(),
		hash = elem.attr("data-hash-id")
		label = $("td[data-hash-id=" + elem.attr("data-hash-id") + "]").children("input");
 	var	values = {
 		'timestamp': new Date().getTime(),
		'hash': hash,
		'value': []
	}

	for (var i = label.length-1; i >= 0; i--) {
		if( label[i].dataset.date == elem.attr("data-date") && label[i].checked !== false)
			values['value'].push(label[i].value.trim() + ";" + elem.attr("data-date"));
	}

 	itemsBox.push(values);
 	$("button.btn").text("Salvar alterações");
 	(itemsBox.length > 0) ? $("button.btn").prop("disabled", false).show() : $("button.btn").prop("disabled", true).hide();

	console.log(values);
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
		$("button.btn").text("Salvando...");
		console.log(response);
	});
	request.fail(function(jqXHR, textStatus) {
	    console.log("Request failed: " + textStatus);
	});
	request.always(function() {
	    $("button.btn").prop("disabled", true).text("Salvo!");
	    console.log("Saved successfully.");
	});

});	