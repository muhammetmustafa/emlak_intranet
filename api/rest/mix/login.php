<?php

	//$veri değişkeni rest.php'de tanımlı
	//$emlaknet değişkeni rest.php'de tanımlı
	//$cevap değişkeni rest.php'de tanımlı

	$sart1 = isset($veri["id"]) && isset($veri["sifre"]); 
	$sart2 = $veri["id"] != "" && $veri["sifre"] != "";
	
	if (!($sart1 && $sart2))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	} 
	else
	{
		Mysql::sql($emlaknet)
		->select('id, CONCAT(ad, " ", soyad) AS ad')
		->from('emlakcilar')
		->where()
		->filtre("id", $veri["id"])->ve()
		->filtre("sifre", $veri["sifre"])
		->calistir();
		
		if ($emlaknet->etkilenenSatirSayisi > 0) 
		{
			$sonuc = $emlaknet->sonuc()->alMesaj();
			$cevap->ekleCevap("LOGIN_DURUM", "1");
			giris_yap($sonuc["id"]);
			$_SESSION['kullanici_adi'] = $sonuc["ad"]; 
		}
		else 
		{
			$cevap->ekleCevap("LOGIN_DURUM", "0");
		}
	}
?>