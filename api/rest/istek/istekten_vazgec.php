<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Post.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";
require_once "$kok_dizin/essek/kutuphane.tarih.php";

try
{
	$cevap = new Cevap();
	
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	$ilan_id = file_get_contents('php://input');
	if ($ilan_id == false)
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "İstek işlemede hata.");
	}
	
	if ($ilan_id == "")
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	}
	
	$emlaknet = new Mysql("emlaknet");
			
	Mysql::sql($emlaknet)
	->update('_devir_istekleri AS istek')
	->set(array('istek_durum_id' => 6))
	->where()
	->filtre("istek.ilan_id", $ilan_id)->ve()
	->filtre("istek.istek_yapan_id", giris_yapan())
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