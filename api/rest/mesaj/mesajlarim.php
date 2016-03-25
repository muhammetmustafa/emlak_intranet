<?php
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
	
	$emlaknet = new Mysql("emlaknet");
	$cevap = new Cevap();
		
	//Okunmamış mesajları alalım.
	Mysql::sql($emlaknet)
	->select( array(
		'em.id as mesaj_gonderen_kod', 
		'concat(em.ad, " ", em.soyad) as mesaj_gonderen_ad',
		'mesaj.id as mesaj_kod',
		'mesaj.konu',
		'mesaj.durum_id AS durum',
		'DATE_FORMAT(mesaj.tarih, "%d-%m-%Y %T") AS tarih'
		))
	->from('mesajlar as mesaj, emlakcilar as em')
	->where()
	->filtre('mesaj.kime_id', giris_yapan())->ve()
	->filtreString('mesaj.durum_id != 3')->ve()
	->filtreString('mesaj.kimden_id = em.id')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("mesajlarim", $emlaknet->sonuc(true)->alMesaj());
	}
	else
	{
		$cevap->ekleCevap("mesajlarim", array());
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