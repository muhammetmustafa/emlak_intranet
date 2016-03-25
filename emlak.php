<?php
	session_start();
	
	require_once 'kutuphaneler/essek/sql/class.Mysql.php';
	require_once 'kutuphaneler/essek/class.Oturum.php';
	require_once 'kutuphaneler/smarty/libs/Smarty.class.php';
	
	$smarti = new Smarty();
	$smarti->setTemplateDir("sablonlar");
	$smarti->setCompileDir("sablonlar/compile");
	$smarti->setCompileDir("sablonlar/cache");
	
	if (giris_yapilmis())
	{
		$istek = str_replace("/emlak_intranet/", "", $_SERVER['REQUEST_URI']);
		$istek = explode("/", rtrim($istek, "/"));
		$emlaknet = new Mysql("emlaknet ");
		
		if (isset($istek[0]))
		{
			if ($istek[0] == "suretler" && isset($istek[1]))
			{
				$suret = $istek[1].".phtml";
				
				if (file_exists("suretler/$suret"))
				{
					ob_start();
					require "suretler/$suret";
					$suret_html = ob_get_clean();
					
					echo $suret_html;
					exit;
				}
				else
				{
					ob_start();
					require "suretler/hata.phtml";
					$suret_html = ob_get_clean();
					
					echo $suret_html;
					exit;
				}
			}
		}
		
		require 'gitgelciler/home.php';
	}
	else 
	{
		require 'gitgelciler/login.php';
	}
?>