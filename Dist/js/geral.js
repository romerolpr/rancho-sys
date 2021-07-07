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
 
$(window).bind("popstate", function(e) {
  $('.main').load(e.state.url);
});

var click_btn = false;
$(".btn_expand").click(function(e){ 
	e.preventDefault();
	if (click_btn === false){
			$(this).addClass("btn_active");
			$(".box-table").addClass("window_fixed");
			click_btn = true;
			localStorage.setItem('window', true);
	} else {
		$(".box-table").removeClass("window_fixed");
		$(this).removeClass("btn_active"); 
		localStorage.setItem('window', false);
		click_btn = false;
	}
});

document.addEventListener('keydown', function (event) {
    if (event.keyCode == 27){
     	$(".box-table").removeClass("window_fixed");
   		$(".btn_expand").removeClass("btn_active"); 
   		localStorage.setItem('window', false);
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

function getMoreItems(){
	var divLoading = $(".bg-loading"),
		 	newLimit = divLoading.attr("data-limit"),
		 	maxElem = divLoading.attr("data-maxElem"),
			countElem = $("#table-filter tr:not(.bar-table)"),
			$body = $('body');
			request = $.ajax({
		    url: "Inc/get.inc.php",
		    type: "POST",
		    data: "newLimit=" + newLimit + "&countElem=" + countElem.length,
		    dataType: "html"
		});

	divLoading.hide();

	if (countElem.length >= maxElem){
		$("body").removeClass("loading");
	} else {
		$("body").addClass("loading");
	}

	// console.log(countElem.length);

	request.done(function(data){
		if (countElem.length < maxElem){

			$("body").removeClass("loading");

			$('#table-filter').append(data);

			InputChange(exbAll);
		}
	});
	request.fail(function(jqXHR, textStatus) {
	    console.log("Request failed: " + textStatus);
	    $(".modal").append("<span class=\"message\">Request failed: "+textStatus+"</span>");
	    setTimeout(function(){
	    	$body.removeClass("loading");
	    }, 2500);
	});

	searchOnTable();
}

$('.box-table:not(.exbAll)').bind('scroll', function() {
	if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
      getMoreItems();
  }
});

function getInputsAll(){
	let input = [];
	$(".input_checked").each(function(){
		input.push($(this));
	});
	return input;
}

function applyEffect(input){
	for (var i = 0; i < input.length; i++) {
		if (input[i][0].checked == false) {
			input[i].parent().parent().parent().show();
		} else {
			input[i].parent().parent().parent().hide();
		}''
	}
}

function removeEffect(input){
	$("#table-filter tr").show().removeClass("hide");
}

let X = false;
$(".btn_square").on("click", function(e){
	e.preventDefault();
	var input = getInputsAll(),
			$body = $("body");

	$body.addClass("loading");
	

	if (X !== true) {
		$(this).addClass("btn_active").text("Mostrar tudo");
		applyEffect(input);
		X = true;
	} else {
		$(this).removeClass("btn_active").text("Caixas vazias");
		removeEffect(input);
		X = false;
	}

	// $(".btn_restore").removeClass("none");

	setTimeout(function(){
		$body.removeClass("loading");
	}, 250);

});

InputChange(exbAll);

$(".td-button span[data-filter=\"posto_graduacao\"], .td-button span[data-filter=\"organizacao_militar\"]").trigger("click");