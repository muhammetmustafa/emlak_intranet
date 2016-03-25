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
	
	$devir = json_decode(file_get_contents('php://input'));
	if ($devir == false)
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "İstek işlemede hata.");
	}
	
	if (!isset($devir->ilan) && $devir->ilan == "")
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	}
	
	$emlaknet = new Mysql("emlaknet");

	Mysql::sql($emlaknet)
	->select('emlakci_id')
	->from('ilanlar')
	->where()
	->filtre('id', $devir->ilan)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$istek_hedef_id = $emlaknet->sonuc()->alMesaj()['emlakci_id'];
		
		Mysql::sql($emlaknet)
		->insert('_devir_istekleri')
		->eklemeler(array(
		'istek_yapan_id' => giris_yapan(),
		'istek_hedef_id' => $istek_hedef_id,
		'ilan_id' => $devir->ilan,
		'ek' => isset($devir->ek) ? $devir->ek : "",
		'istek_durum_id' => 1,
		'ziyaret' => 0
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