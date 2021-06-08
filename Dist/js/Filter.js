// Object filter
var myFilter = {
	load: false,
	hide: [],
	sort: [],
	filter : {
		'carimbo': [],
		'email': [],
		'organizacao_militar': [],
		'posto_graduacao': [],
		'nome': []
	},
	apply: []
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

function search(value, targetSelector){
	// $(targetSelector).show();
	// $(targetSelector+':not(:contains("'+ value +'"))').parent("tr").hide();

	$("tr[data-hash]").filter(function() {
		$(this).hide();
    if ($(this).text().toLowerCase().indexOf(value) > -1){
    	$(this).show();
    }
  });
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

function applyFilter(content){

	var label = [];

	$("body").addClass("loading");

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

function initializeFilter(content){

	myFilter.load = true;
	selectAll(content);

	$(".btn_apply").on("click", function(){
		var content = $(this).attr("data-content");
		
		applyFilter(content);

	});

	$("a[data-exec=hide]").on("click", function(e){
		e.preventDefault();
		showHideColumn($(this).attr("data-content"));
	});

	if (myFilter.load !== true)
		console.log("Filter initialized");

	$("body").removeClass("loading");

	// console.log(myFilter.filter);
}