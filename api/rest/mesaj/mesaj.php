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

	if (GET::kontrol_ajax('id'))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Gerekli parametre eksik");
	}
	
	$mesaj_id = GET::al('id');
	$emlaknet = new Mysql("emlaknet");
	$cevap = new Cevap();
		
	//Okunmamış mesajları alalım.
	Mysql::sql($emlaknet)
	->select( array(
		'em.id as mesaj_gonderen_kod', 
		'concat(em.ad, " ", em.soyad) as mesaj_gonderen_ad',
		'mesaj.konu',
		'mesaj.mesaj',
		'DATE_FORMAT(mesaj.tarih, "%d-%m-%Y %T") AS tarih'
		))
	->from('mesajlar as mesaj, emlakcilar as em')
	->where()
	->filtre('mesaj.id', $mesaj_id)->ve()
	->filtre('mesaj.kime_id', giris_yapan())->ve()
	->filtreString('mesaj.durum_id != 3')->ve()
	->filtreString('mesaj.kimden_id = em.id')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("mesaj", $emlaknet->sonuc()->alMesaj());
		
		//mesajı okundu diye güncelleyelim.
		Mysql::sql($emlaknet)
		->update('mesajlar')
		->set(array('durum_id' => 2))
		->where()
		->filtre('id', $mesaj_id)->ve()
		->filtre('kime_id', giris_yapan())
		->calistir();
	}
	else
	{
		$cevap->ekleCevap("mesaj", array());
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