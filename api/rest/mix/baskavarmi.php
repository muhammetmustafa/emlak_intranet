<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Get.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";
require_once "$kok_dizin/essek/kutuphane.tarih.php";

define('MAKS_DEVIR_SAYISI', 5);
define('MAKS_MESAJ_SAYISI', 10);

try
{
	$cevap = new Cevap();
	
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	$emlaknet = new Mysql("emlaknet");
	
	//önce onayı alınmamış devir isteklerini alalım.
	Mysql::sql($emlaknet)
	->select( array(
						'di.id', 
						'ilan.id AS ilan_kod', 
						'ilan.baslik AS ilan_baslik', 
						'concat(em.ad, " ", em.soyad) AS istek_yapan'
				 ))
	->from( array(
						'_devir_istekleri AS di', 
						'emlakcilar AS em', 
						'ilanlar AS ilan'
			))
	->where()
	->filtre('di.istek_hedef_id', giris_yapan())->ve()
	->filtreString('di.istek_yapan_id = em.id')->ve()
	->filtreString('di.ilan_id = ilan.id')->ve()
	->filtreString('di.istek_durum_id = 3')
	->limit(0, MAKS_DEVIR_SAYISI)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$sonuc = $emlaknet->sonuc(true)->alMesaj();
		$cevap->ekleCevap("devirler", is_array($sonuc) ? $sonuc : array($sonuc));
	}
	else
	{
		$cevap->ekleCevap("devirler", array());
	}
	
	//Daha sonra okunmamış mesajları alalım.
	Mysql::sql($emlaknet)
	->select('mesaj.konu, mesaj.id AS mesaj_kodu')
	->from('mesajlar as mesaj')
	->where()
	->filtre('mesaj.kime_id', giris_yapan())->ve()
	->filtreString('mesaj.durum_id = 1')
	->limit(0, MAKS_MESAJ_SAYISI)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$sonuc = $emlaknet->sonuc(true)->alMesaj();
		$cevap->ekleCevap("mesajlar", is_array($sonuc) ? $sonuc : array($sonuc));
	}
	else
	{
		$cevap->ekleCevap("mesajlar", array());
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