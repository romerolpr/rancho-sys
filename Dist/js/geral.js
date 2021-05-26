var clicked, popup;

function confirm_clicked(url, action) {
	if (confirm("Você tem certeza que deseja "+action+" este item?")) {
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