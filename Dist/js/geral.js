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
   		$(".btn_expand").removeClass("btn_active"); 
    }
});
 
$(window).bind("popstate", function(e) {
  $('.main').load(e.state.url);
});

var click_btn = false;
$(".btn_expand").click(function(){ 
	if (click_btn === false){
		$(this).addClass("btn_active");
			click_btn = true;
	} else {
		$(".box-table").removeClass("window_fixed");
		$(this).removeClass("btn_active"); 
		click_btn = false;
	}

});

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

function mescleItems(){
	
	var mescleItemsVar = {
		checked	: [],
		empty 	: []
	};

	let elem  = $(".input_checked").parent().parent(),
			trdad   = elem.parent(),
			input = $("td").children("div").children("input");

	for (var i = input.length-1; i >= 0; i--) {
		if (input[i].checked !== false) {
			mescleItemsVar['checked'].push(input[i].id);
		} else {
			mescleItemsVar['empty'].push(input[i].id);
		}
	}

	return [mescleItemsVar, trdad];
}


$('.input_checked').on('change', function () {

	let elem = $(this).parent().parent(),
		hash = elem.attr("data-hash-id")
		label = $("td[data-hash-id=" + hash + "]").children("div").children("input");

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

	// console.log(is_empty);
	// console.log(label);
});

$('#saveAll').on("click", function(e){
	e.preventDefault();
	var data = JSON.stringify(itemsBox),
		$body = $("body"),
		request = $.ajax({
	    url: "Transfer/save.form.php",
	    type: "POST",
	    data: "values=" + data,
	    dataType: "html"
	});

	$body.addClass("loading");
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
	    $body.removeClass("loading");
	});

});	

$('#report').on("click", function(e){
	e.preventDefault();
	var url = $(this).attr("href");

	tryStatus();
	if (is_empty.length !== 0){
		if (confirm("Existem campos vazios. Deseja gerar o relatório mesmo assim?")){
			window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=150,width=800,height=800")
		}
	} else {

		// document.location = url;
		window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=150,width=800,height=800")
	}
});

$(".td-button span[data-filter]").on("click", function(){
	// e.preventDefault();

	var divdrop = $(this).children("div.sub-dropdown"),
		request = $.ajax({
		    url: "Inc/load.drop.php",
		    type: "POST",
		    data: "content=" + $(this).attr("data-filter"),
		    dataType: "html"
		});

	$("div.sub-dropdown").hide();
	divdrop.show();

	// divdrop.addClass("loading");
	request.done(function(data){
		if (divdrop.html().length <= 0){
			divdrop.append(data);
		}
	});
	request.fail(function(jqXHR, textStatus) {
	    console.log("Request failed: " + textStatus);
	});
	request.always(function() {
	    console.log("Load successfully.");
	    divdrop.css({"background":"#fff"});
	    // divdrop.removeClass("loading");
	});

	// return false;

});

$('.input_checked_drop').each(function(){

	var myValue = $(this).val();

	if (myValue.split("_")[1] == "all"){
		
	}
});