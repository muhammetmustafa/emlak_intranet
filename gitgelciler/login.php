<?php
	
	$js = array(
		"js/angular/angular.min.js",
		"js/login.js"
	);
	
	$css = array(
		"css/bootstrap/css/bootstrap.min.css",
		"css/emlak.css"
	);

	$smarti
	->assign("baslik", "Giriş - emlakINTRANET")
	->assign("uygulama_adi", "Login")
	->assign("js", $js)
	->assign("css", $css)
	->assign("sayfa", "login")
	->display("sablon.tpl");

?>