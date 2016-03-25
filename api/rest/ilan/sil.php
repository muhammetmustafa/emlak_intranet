<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Post.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";

try
{
	$cevap = new Cevap();
	
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	$id = json_decode(file_get_contents('php://input'));
	if ($id == false)
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "İstek işlemede hata.");
	}
	
	$emlaknet = new Mysql('emlaknet');
	
	//önce silinecek ilan varmı kontrol edelim ve bilgilerini alalım
	Mysql::sql($emlaknet)
	->select("id")
	->from("ilanlar")
	->where()
	->filtre("id", $id)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi <= 0)
	{
		throw new exCevap("DURUM", "0");
	}
	
	//Daha sonra ilanı silelim
	Mysql::sql($emlaknet)
	->update("ilanlar")
	->set(array("durum_id" => 3))
	->where()
	->filtre('id', $id)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi <= 0)
	{
		throw new exCevap("DURUM", "0");
	}
	
	//Daha sonra silinen ilanla alakalı istekleri durumlarını güncelleyelim.
	Mysql::sql($emlaknet)
	->update('_devir_istekleri AS istek')
	->set(array('istek.istek_durum_id' => 5))
	->where()
	->filtre("istek.ilan_id", $id)->ve()
	->filtreString('(istek.istek_durum_id = 1 OR istek.istek_durum_id = 2)')
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

echo $cevap->alJSON(JSON_NUMERIC_CHECK);
?>