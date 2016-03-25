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
	
	$sart1 = isset($ilan->id) &&
			 isset($ilan->ilan->baslik) && 
			 isset($ilan->ilan->gayrimenkul_turu) && 
			 isset($ilan->ilan->tur) &&
			 isset($ilan->ilan->oda_turu) &&
			 isset($ilan->ilan->metrekare) &&
			 isset($ilan->ilan->bulundugu_kat) &&
			 isset($ilan->ilan->kac_yillik) &&
			 isset($ilan->ilan->istenilen_fiyat) &&
			 isset($ilan->ilan->il) &&
			 isset($ilan->ilan->ilce) &&
			 isset($ilan->ilan->mahalle) &&
			 isset($ilan->ilan->adres)
			; 
	$sart2 = $ilan->id != "" &&
			 $ilan->ilan->baslik != "" &&
			 $ilan->ilan->gayrimenkul_turu != "" &&
			 $ilan->ilan->tur != "" &&
			 $ilan->ilan->oda_turu != "" &&
			 $ilan->ilan->metrekare != "" &&
			 $ilan->ilan->bulundugu_kat != "" &&
			 $ilan->ilan->kac_yillik != "" &&
			 $ilan->ilan->istenilen_fiyat != "" &&
			 $ilan->ilan->il != "" &&
			 $ilan->ilan->ilce != "" &&
			 $ilan->ilan->mahalle != "" &&
			 $ilan->ilan->adres != ""
			;
	
	if (!($sart1 && $sart2))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	}
	
	$emlaknet = new Mysql("emlaknet");

	Mysql::sql($emlaknet)
	->update('ilanlar')
	->set( array(
		'gayrimenkul_tur_id' => $ilan->ilan->gayrimenkul_turu,
		'mahalle_id' => $ilan->ilan->mahalle,
		'baslik' => $ilan->ilan->baslik,
		'aciklama' => $ilan->ilan->aciklama,
		'fiyat' => $ilan->ilan->istenilen_fiyat,
		'oda_turu' => $ilan->ilan->oda_turu,
		'metrekare' => $ilan->ilan->metrekare,
		'bulundugu_kat' => $ilan->ilan->bulundugu_kat,
		'kac_yillik' => $ilan->ilan->kac_yillik,
		'adres' => $ilan->ilan->adres
	))
	->where()
	->filtre('id', $ilan->id)
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