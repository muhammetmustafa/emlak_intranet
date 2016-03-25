<?php
/*
 * 	Giriş yapana ait bir ilan görüntülendiğinde o ilana ait istekleri sorgular.
 * 
 */

session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Get.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";

try
{
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	if (GET::kontrol_ajax('ilan_id'))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Gerekli parametre eksik");
	}
	
	$ilan_id = GET::al('ilan_id');
	
	$emlaknet = new Mysql("emlaknet");
	$cevap = new Cevap();
		
	Mysql::sql($emlaknet)
	->select( array('istek.id as istek_kod', 
					'CONCAT(em.ad, " ", em.soyad) as istek_yapan', 
					'DATE_FORMAT(istek.istek_tarihi, "%d-%m-%Y %T") AS istek_tarihi'
					))
	->from('_devir_istekleri as istek, emlakcilar as em')
	->where()
	->filtre('istek.istek_hedef_id', giris_yapan())->ve()
	->filtre('istek.ilan_id', $ilan_id)->ve()
	->filtreString('istek.istek_yapan_id = em.id')->ve()
	->filtreString('istek.istek_durum_id = 1')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("isteklerim", $emlaknet->sonuc(true)->alMesaj());
	}
	else
	{
		$cevap->ekleCevap("isteklerim", array());
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