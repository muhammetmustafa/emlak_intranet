<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
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
	
	$mesaj = json_decode(file_get_contents('php://input'));
	if ($mesaj == false)
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "İstek işlemede hata.");
	}
	
	if (isset($mesaj->icerik->baslik) && ($mesaj->icerik->baslik == "") && 
		isset($mesaj->icerik->mesaj) && ($mesaj->icerik->mesaj == "") &&
		isset($mesaj->ilan_id) && ($mesaj->ilan_id == ""))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	}
	
	$emlaknet = new Mysql("emlaknet");

	Mysql::sql($emlaknet)
	->select('emlakci_id')
	->from('ilanlar')
	->where()
	->filtre('id', $mesaj->ilan_id)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$kime_id = $emlaknet->sonuc()->alMesaj()['emlakci_id'];
	
		Mysql::sql($emlaknet)
		->insert('mesajlar')
		->eklemeler(array(
		'kimden_id' => giris_yapan(),
		'kime_id' => $kime_id,
		'durum_id' => 1,
		'konu' => $mesaj->icerik->konu,
		'mesaj' => $mesaj->icerik->mesaj,
		'tarih' => veritabaniSimdi(),	
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