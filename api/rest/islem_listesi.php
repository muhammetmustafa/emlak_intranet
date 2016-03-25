<?php

//İşlemleri ve o işlemleri
//yöneten script dosyalarını eşleştirelim.
$islemler = array(
	'login' => array(
		'yetki' => YETKI_GIRIS_YAPMAYANLAR,
		'script' =>	'mix/login.php'
	),
	'cikis' => array(
		'yetki' => YETKI_GIRIS_YAPANLAR,
		'script' => 'mix/cikis.php'
	),
	'bildirimlerim' => array(
		'yetki' => YETKI_GIRIS_YAPANLAR,
		'script' => 'mix/bildirimlerim.php'
	),
	'ara' => array(
		'yetki' => YETKI_GIRIS_YAPANLAR,
		'script' => 'mix/ara.php'
	),
	'ilan/al' => array(
		'yetki' => YETKI_GIRIS_YAPANLAR,
		'script' => 'mix/ara.php'
	)
);

?>