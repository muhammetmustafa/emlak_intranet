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
	
	/*
	 * t = Tür
	 * gl = Gelen
	 * gd = Giden
	 */
	if (GET::kontrol_ajax('t') && GET::kontrol_liste('t', array('gl, gd')))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Gerekli parametre eksik");
	}
	
	$tur = GET::al('t');
	$emlaknet = new Mysql("emlaknet");
	$cevap = new Cevap();
	
	Mysql::sql($emlaknet)
	->select( array(
						'istek.id as istek_kod', 
						'CONCAT(em.ad, " ", em.soyad) as emlakci', 
						'em.id as emlakci_id', 
						'DATE_FORMAT(istek.istek_tarihi, "%d-%m-%Y %T") AS istek_tarihi', 
						'ilan.baslik AS ilan', 
						'ilan.id as ilan_id', 
						'durum.aciklama AS durum'
					))
	->from('_devir_istekleri as istek, emlakcilar as em, ilanlar as ilan, istek_durumlari as durum')
	->where()
	->filtre($tur == 'gl' ? 'istek.istek_hedef_id' : 'istek.istek_yapan_id', giris_yapan())->ve()
	->filtreString($tur == 'gl' ? 'istek.istek_yapan_id = em.id' : 'istek.istek_hedef_id = em.id')->ve()
	->filtreString('istek.ilan_id = ilan.id')->ve()
	->filtreString('istek.istek_durum_id = durum.id')->ve()
	->filtreString('istek.istek_durum_id = 1')
	->orderby("istek.id")
	->artan()
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("isteklerim", $emlaknet->sonuc(true)->alMesaj());
	}
	else
	{
		$cevap->ekleCevap("isteklerim", "");
	}
$cevap->ekleCevap("sorgu", $emlaknet->sonSorgu);
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