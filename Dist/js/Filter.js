// Object filter
var myFilter = {
	load: false,
	hide: [],
	filter : {
		'carimbo': [],
		'email': [],
		'organizacao_militar': [],
		'posto_graduacao': [],
		'nome': []
	},
	apply: []
}, 

click = {
	"carimbo": 0,
	"email": 0,
	"posto_graduacao": 0,
	"organizacao_militar": 0,
	"nome": 0
}

function getTarget(event) {
    var el = event.target || event.srcElement;
    return el.nodeType == 1? el : el.parentNode;
}

function alocateArray(array) {
	var myJSON = array;
	// JSON.parse(myJSON);
	localStorage.setItem("myFilterJson", jQuery.parseJSON(JSON.stringify(myJSON)));

	return localStorage.getItem("myFilterJson");
	// return myJSON;
}

function selectAllInputs(content){
	$('.input_checked_drop').each(function(){
		$(this).prop("checked", true);
	});
}

function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}

function pushFilter(content, returnAll = false){
	var inputAll = returnAll == false ? $(".input_checked_drop[data-content="+content+"]") : $(".input_checked_drop");

	for (var i = inputAll.length - 1; i >= 0; i--) {
		if (inputAll[i].checked === true && !myFilter.filter[content].includes(inputAll[i])) {
			myFilter.filter[content].push(inputAll[i]);
		} else if (inputAll[i].checked !== true && myFilter.filter[content].includes(inputAll[i])) {
			myFilter.filter[content].splice(myFilter.filter[content].indexOf(inputAll[i]), 1);
		}
	}

}

function clearArray(array){
	for (var i = array.length - 1; i >= 0; i--) {
		if (array[i].checked !== true)
			array.splice(array.indexOf(array[i]), 1);
	}
}

function loadFilter(content, $this){

	var result, 
	filtering = {
		1: [],
		2: [],
		3: [],
		4: [],
		5: [],
	};

	for (var ia = myFilter.filter["carimbo"].length - 1; ia >= 0; ia--) {

		if ($this.text().toLowerCase().indexOf($("label[for="+myFilter.filter["carimbo"][ia].id+"]").text().toLowerCase()) > -1){
			filtering[1].push($this);
			break;
		} 

	}

	for (var ie = myFilter.filter["email"].length - 1; ie >= 0; ie--) {

		if ($this.text().toLowerCase().indexOf($("label[for="+myFilter.filter["email"][ie].id+"]").text().toLowerCase()) > -1){
			filtering[2].push($this);
			break;

		} 

	}

	for (var ii = myFilter.filter["organizacao_militar"].length - 1; ii >= 0; ii--) {

		if ($this.text().toLowerCase().indexOf($("label[for="+myFilter.filter["organizacao_militar"][ii].id+"]").text().toLowerCase()) > -1){
			filtering[3].push($this);
			break;

		} 

	}

	for (var io = myFilter.filter["posto_graduacao"].length - 1; io >= 0; io--) {

		if ($this.text().toLowerCase().indexOf($("label[for="+myFilter.filter["posto_graduacao"][io].id+"]").text().toLowerCase()) > -1){
			filtering[4].push($this);
			break;

		} 

	}

	for (var iu = myFilter.filter["nome"].length - 1; iu >= 0; iu--) {

		if ($this.text().toLowerCase().indexOf($("label[for="+myFilter.filter["nome"][iu].id+"]").text().toLowerCase()) > -1){
			filtering[5].push($this);
			break;

		} 

	}

	return filtering;

}

function showHideColumn(content){
	if (myFilter.hide.includes(content)){
		$("a[data-exec=hide]").removeClass("selected");
		$("td[data-content="+content+"], td[data-content-children="+content+"]").removeClass("hide-td");
		// $("td[data-content-children="+content+"]").show();
		// $("td[data-content-children="+content+"] span.title").show();
		myFilter.hide.splice(myFilter.hide.indexOf(content), 1);
	} else {
		$("a[data-exec=hide]").addClass("selected");
		// $("td[data-content-children="+content+"]").hide();
		// $("td[data-content-children="+content+"] span.title").hide();
		$("td[data-content="+content+"], td[data-content-children="+content+"]").addClass("hide-td");
		myFilter.hide.push(content);
	}
}

function selectAll(content){

	$(".input_checked_drop_all[data-content="+content+"]").on("change", function (e){
		if ($(this).is(":checked")){
			$(".input_checked_drop[data-content="+content+"]").prop("checked", true);
		} else {
			$(".input_checked_drop[data-content="+content+"]").prop("checked", false);
		}
		pushFilter(content);
	});

	$(".input_checked_drop[data-content="+content+"]").on("change", function (e){ pushFilter(content) });

}

function applyFilter(content){

	
	var label = [];

	// update current filter
	pushFilter(content);
	for (var x in myFilter.filter) {
	 	for (var i = myFilter.filter[x].length - 1; i >= 0; i--) {
	 		if(myFilter.filter[x][i].checked === true && !myFilter.apply.includes(myFilter.filter[x][i])){
	 			myFilter.apply.push(myFilter.filter[x][i]);
	 		}
	 	}
	}

	clearArray(myFilter.apply);

	$("tr[data-hash]").filter(function() {

			$(this).addClass("hide");

			for (var i = myFilter.apply.length - 1; i >= 0; i--) {
				if (!label.includes($("label[for="+myFilter.apply[i].id+"]").text().toLowerCase())){ 
					label.push($("label[for="+myFilter.apply[i].id+"]").text().toLowerCase());
				}
			}

			// console.log(myFilter.apply.length);

			var loadFilterResult = loadFilter(content, $(this));

			if (loadFilterResult[3].length > 0) {


				for (var i1 = loadFilterResult[3].length - 1; i1 >= 0; i1--) {
					
					if (!loadFilterResult[4].includes(loadFilterResult[3][i1])){

						for (var ind = 1; ind <= 5; ind++) {
							for (var item = 0; item < loadFilterResult[ind].length; item++) {
								loadFilterResult[ind][item].addClass("hide");
							}
						}

						for (var abc = loadFilterResult[4].length - 1; abc >= 0; abc--) {

								loadFilterResult[4][abc].addClass("hide");
								break;
	
						}

						break;
						

					} else if (loadFilterResult[4].includes(loadFilterResult[3][i1])) {

						for (var ind = 1; ind <= 5; ind++) {
							for (var item = 0; item < loadFilterResult[ind].length; item++) {
								loadFilterResult[ind][item].addClass("hide");
							}
						}
						
						for (var abc = loadFilterResult[4].length - 1; abc >= 0; abc--) {

								loadFilterResult[4][abc].removeClass("hide");
								break;
	
						}
							
						break;

					}

					break;


				}	
 

			} 


	});

}

function initializeFilter(content){

	selectAll(content);

	$("a[data-exec=hide]").on("click", function(e){
		e.preventDefault();
		showHideColumn($(this).attr("data-content"));
	});

	console.log("initialized filter...");

	
}

function loadFilterDefault(){

	$(".input_checked_drop_all").each(function(){
		if ($(this)[0].checked !== false) {
			var $content = $(this)[0].dataset.content;
			$(".input_checked_drop[data-content=" + $content + "]").each(function(){
				myFilter.filter[$content].push($(this)[0]);
			});
			
		}
	});

}


function InputChange(exbAll = false){

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
		label.length = 0;
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

$(".td-button span[data-filter]").on("click", function(e){	

	loadFilterDefault();

	// Build dropdown
	var divdrop = $(this).children("div.sub-dropdown"),
			i 			= $(this).children("i.fa-sort-down"),
			request = $.ajax({
		    url: "Inc/load.drop.php",
		    type: "POST",
		    data: "content=" + $(this)[0].dataset.filter + "&exbAll=" + exbAll,
		    dataType: "html"
		}), myselfFilter = {
			filter: $(this)[0].dataset.filter,
			extract: $(this)[0].dataset.filterExtract
		}

		click[myselfFilter.filter] += 1;

		if (click[myselfFilter.filter] >= 2 && [getTarget(e)][0].localName == "span" || [getTarget(e)][0].localName == "i" && click[myselfFilter.filter] == 2) {
			divdrop.hide();
			i.removeClass("rotate180deg");
			click[myselfFilter.filter] = 0;
		} else {
			i.addClass("rotate180deg");
			divdrop.show();
		}

	if (myselfFilter.filterExtract !== undefined){
		// $(this)
	} else {
		request.done(function(data){

			if (divdrop.html().length <= 0){
				divdrop.append(data);
			}
			divdrop.css({"background":"#fff"});

			initializeFilter(myselfFilter.filter);

			$("button.btn_apply").on("click", function(){

				$("#table-filter tr").attr('style', "");
				$(".btn_square").hide();
				$("body").addClass("loading");

				if (myFilter.load !== true) {

					var content = $(this).attr("data-content");

					console.log("loading filter...");

					setTimeout(function(){
						applyFilter(content);
						$("body").removeClass("loading");
						$("div.sub-dropdown").hide();
						// click[$(this)[0].dataset.filter] = 0;

					}, 250);

					myFilter.load = true;

				}

				$(".fa-sort-down").removeClass("rotate180deg");
				
				// myFilter = alocateArray(myFilter);

			});

			myFilter.load = false;

		});
		request.fail(function(jqXHR, textStatus) {
				divdrop.append("<p>Request failed: " + textStatus + "</p>");
		    console.log("Request failed: " + textStatus);
		});
	}

	console.log(myFilter);

});

}
