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
	// window.history.pushState({url: "" + $(this).attr('href') + ""}, $(this).attr('title') , $(this).attr('href'));
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

InputChange();

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

$('#getMoreItems').on('click', function(){
	// e.preventDefault();

	var newLimit = $(this).attr("data-limit"),
		 	maxElem = $(this).attr("data-maxElem"),
			countElem = $("#table-filter tr:not(.bar-table)"),
			$body = $('body');
			request = $.ajax({
		    url: "Inc/get.inc.php",
		    type: "POST",
		    data: "newLimit=" + newLimit + "&countElem=" + countElem.length,
		    dataType: "html"
		});

	if (countElem.length >= maxElem){
		// $(this).prop("disabled", true);
	}

	console.log(countElem.length);
	$body.addClass("loading");

	request.done(function(data){
		$body.removeClass("loading");
		$('#table-filter').append(data);
		InputChange();
	});
	request.fail(function(jqXHR, textStatus) {
	    console.log("Request failed: " + textStatus);
	    $(".modal").append("<span class=\"message\">Request failed: "+textStatus+"</span>");
	    setTimeout(function(){
	    	$body.removeClass("loading");
	    }, 2500);
	});

	searchOnTable();
});	