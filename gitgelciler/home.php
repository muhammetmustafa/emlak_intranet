<?php
	
	$js = array(
		"js/angular/angular.js",
		"js/angular/angular-ui-router.js",
		"js/angular/angular-resource.js",
		"js/emlak/app.js",
		"js/emlak/controllers.js",
		"js/emlak/services.js",
		"js/emlak/filters.js",
		"js/jquery/jquery-2.1.1.min.js",
		"js/parsley/parsley.js",
		"js/bootstrap/bootstrap.min.js"
	);
	$css = array(
		"css/bootstrap/css/bootstrap.min.css",
		"css/emlak.css"
	);
	
	$smarti
	->assign("baslik", "Home - emlakINTRANET")
	->assign("uygulama_adi", "EmlakNET")
	->assign("js", $js)
	->assign("css", $css)
	->assign("sayfa", "home")
	->display("sablon.tpl");
?>