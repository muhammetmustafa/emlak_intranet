<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Get.php";
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
	
	//t = Tazelik
	if (GET::kontrol_ajax('t'))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Parametre eksik.");
	}
	
	$tazelik = GET::al('t');

	$emlaknet = new Mysql("emlaknet");
	
	//önce giriş yapan kullanıcıya gönderilen onayı alınmamış isteklerin sayısını bulalım.
	Mysql::sql($emlaknet)
	->select('count(id) AS gelen')
	->from('_devir_istekleri AS di')
	->where()
	->filtre('di.istek_hedef_id', giris_yapan())->ve()
	->filtreString('di.istek_durum_id = 3')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$sonuc = $emlaknet->sonuc()->alMesaj();
		$cevap->ekleCevap("istekler", $sonuc);
	}
	else
	{
		$cevap->ekleCevap("istekler", 0);
	}
	
	//Daha sonra giriş yapan kullanıcının yaptığı onayı alınmamış isteklerin sayısını bulalım.
	Mysql::sql($emlaknet)
	->select('count(id) AS giden')
	->from('_devir_istekleri AS di')
	->where()
	->filtre('di.istek_yapan_id', giris_yapan())->ve()
	->filtreString('di.istek_durum_id = 3')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$sonuc = $emlaknet->sonuc()->alMesaj();
		$cevap->ekleCevap("istekler", $sonuc);
	}
	else
	{
		$cevap->ekleCevap("istekler", 0);
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

echo $cevap->alJSON(JSON_NUMERIC_CHECK);
?>