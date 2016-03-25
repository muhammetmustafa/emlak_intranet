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
	
	if (GET::kontrol_ajax('istek_kod'))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Gerekli parametre eksik");
	}
	
	$istek_kod = GET::al('istek_kod');
	$emlaknet = new Mysql("emlaknet");
	
	//Belirtilen kodlu isteğin bilgilerini isteyen isteğin hedefi ise
	Mysql::sql($emlaknet)
	->select( array('em.id as emlakciKod', 
					'CONCAT(em.ad, " ", em.soyad) as emlakciAd', 
					'DATE_FORMAT(istek.istek_tarihi, "%d-%m-%Y %T") as tarih',
					'istek.ek',
					'_durum.aciklama AS durum',
					'_durum.id AS yetki',
					'ilan.id as ilanKod',
					'ilan.baslik AS ilanBaslik'  
					))
	->from('_devir_istekleri as istek, emlakcilar as em, ilanlar as ilan, istek_durumlari AS _durum')
	->where()
	->filtre('istek.istek_hedef_id', giris_yapan())->ve()
	->filtre('istek.id', $istek_kod)->ve()
	->filtreString('istek.istek_yapan_id = em.id')->ve()
	->filtreString('istek.ilan_id = ilan.id')->ve()
	->filtreString('ilan.durum_id != 3')->ve()
	->filtreString('istek.istek_durum_id = _durum.id')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("istek", array("veri" => $emlaknet->sonuc()->alMesaj()));
		$cevap->ekleCevap("istek", array("gorunum" => "hedef"));
		
		//isteği incelediğimize dair istekler tablosunu güncelleyelim.
		Mysql::sql($emlaknet)
		->update('_devir_istekleri AS istek')
		->set(array('istek.ziyaret' => 1))
		->where()
		->filtre("istek.id", $istek_kod)->ve()
		->filtre("istek.istek_hedef_id", giris_yapan())
		->calistir();
	}
	else
	{
		//Belirtilen kodlu isteğin bilgilerini isteyen isteği yapan ise
		Mysql::sql($emlaknet)
		->select( array('em.id as emlakciKod', 
					'CONCAT(em.ad, " ", em.soyad) as emlakciAd', 
					'DATE_FORMAT(istek.istek_tarihi, "%d-%m-%Y %T") as tarih',
					'istek.ek',
					'_durum.aciklama AS durum',
					'_durum.id AS yetki',
					'ilan.id as ilanKod',
					'ilan.baslik AS ilanBaslik'  
					))
		->from('_devir_istekleri as istek, emlakcilar as em, ilanlar as ilan, istek_durumlari AS _durum')
		->where()
		->filtre('istek.istek_yapan_id', giris_yapan())->ve()
		->filtre('istek.id', $istek_kod)->ve()
		->filtreString('istek.istek_hedef_id = em.id')->ve()
		->filtreString('istek.ilan_id = ilan.id')->ve()
		->filtreString('istek.istek_durum_id = _durum.id')->ve()
		->filtreString('ilan.durum_id != 3')
		->calistir();
		
		if ($emlaknet->etkilenenSatirSayisi > 0)
		{
			
			$cevap->ekleCevap("istek", array("veri" => $emlaknet->sonuc()->alMesaj()));
			$cevap->ekleCevap("istek", array("gorunum" => "yapan"));
		}
		else
		{
			$cevap->ekleCevap("istek", array());
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