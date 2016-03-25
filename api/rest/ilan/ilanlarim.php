<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Post.php";
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
		
	Mysql::sql($emlaknet)
	->select('i.id, baslik, fiyat, it.tur AS tur, id.durum AS durum, gt.tur AS gayrimenkul_turu')
	->from('ilanlar AS i, ilan_turleri AS it, gayrimenkul_turleri as gt, ilan_durumlari AS id')
	->where()
	->filtreString('emlakci_id='.giris_yapan())->ve()
	->filtreString('it.id = i.ilan_tur_id')->ve()
	->filtreString('id.id = i.durum_id')->ve()
	->filtreString('id.id != 3')->ve()
	->filtreString('gt.id = i.gayrimenkul_tur_id')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("ilanlarim", $emlaknet->sonuc(true)->alMesaj());
	}
	else
	{
		$cevap->ekleCevap("ilanlarim", "");
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