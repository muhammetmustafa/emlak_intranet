<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Post.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";

try
{
	$cevap = new Cevap();
	
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	$ilan = json_decode(file_get_contents('php://input'));
	if ($ilan == false)
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "İstek işlemede hata.");
	}
	
	$sart1 = isset($ilan->baslik) && 
			 isset($ilan->gayrimenkul_turu) && 
			 isset($ilan->tur) &&
			 isset($ilan->oda_turu) &&
			 isset($ilan->metrekare) &&
			 isset($ilan->bulundugu_kat) &&
			 isset($ilan->kac_yillik) &&
			 isset($ilan->istenilen_fiyat) &&
			 isset($ilan->il) &&
			 isset($ilan->ilce) &&
			 isset($ilan->mahalle) &&
			 isset($ilan->adres)
			; 
	$sart2 = $ilan->baslik != "" && is_string($ilan->baslik) &&
			 $ilan->gayrimenkul_turu != "" && is_numeric($ilan->gayrimenkul_turu) &&
			 $ilan->tur != "" && is_numeric($ilan->tur) &&
			 $ilan->oda_turu != "" && is_string($ilan->oda_turu) &&
			 $ilan->metrekare != "" && is_numeric($ilan->metrekare) &&
			 $ilan->bulundugu_kat != "" && is_numeric($ilan->bulundugu_kat) &&
			 $ilan->kac_yillik != "" && is_numeric($ilan->kac_yillik) &&
			 $ilan->istenilen_fiyat != "" && is_numeric($ilan->istenilen_fiyat) &&
			 $ilan->il != "" && is_numeric($ilan->il) &&
			 $ilan->ilce != "" && is_numeric($ilan->ilce) &&
			 $ilan->mahalle != "" && is_numeric($ilan->mahalle) &&
			 $ilan->adres != "" && is_string($ilan->adres)
			;
	
	if (!($sart1 && $sart2))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	}
	
	$emlaknet = new Mysql("emlaknet");

	Mysql::sql($emlaknet)
	->insert('ilanlar')
	->eklemeler(array(
		'emlakci_id' => giris_yapan(),
		'gayrimenkul_tur_id' => $ilan->gayrimenkul_turu,
		'ilan_tur_id' => $ilan->tur,
		'mahalle_id' => $ilan->mahalle,
		'baslik' => $ilan->baslik,
		'aciklama' => isset($ilan->aciklama) ? $ilan->aciklama : "",
		'fiyat' => $ilan->istenilen_fiyat,
		'oda_turu' => $ilan->oda_turu,
		'metrekare' => $ilan->metrekare,
		'bulundugu_kat' => $ilan->bulundugu_kat,
		'kac_yillik' => $ilan->kac_yillik,
		'adres' => $ilan->adres,
		'durum_id' => 1
	))
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0) 
	{
		$cevap->ekleCevap("DURUM", "1");
	}
	else 
	{
		$cevap->ekleCevap("DURUM", "0");
	}
}
catch (exVeritabani $hata)
{
	$cevap->ekleHata("VERITABANI_HATASI", $hata->__toString());
}
catch (exCevap $hata)
{
	$cevap->ekleHata($hata->alTur(), $hata->alMesaj());
}

echo $cevap->alJSON();
?>