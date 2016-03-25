<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Get.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";

$cevap = new Cevap();

try
{
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	$istek_cevap = json_decode(file_get_contents('php://input'));
	if ($istek_cevap == false)
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "İstek işlemede hata.");
	}
	
	if (!isset($istek_cevap->cevap)  && !isset($istek_cevap->istekKod))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	}
	
	$emlaknet = new Mysql("emlaknet");
	
	Mysql::sql($emlaknet)
	->update('_devir_istekleri AS istek')
	->set(array('istek.istek_durum_id' => ($istek_cevap->cevap == 1 ? 2 : 4)))
	->where()
	->filtre("istek.id", $istek_cevap->istekKod)->ve()
	->filtre("istek.istek_hedef_id", giris_yapan())
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