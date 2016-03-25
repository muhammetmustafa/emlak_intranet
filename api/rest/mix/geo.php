<?php
session_start();

$kok_dizin = str_replace('\\', '/', dirname(__DIR__));

require_once "$kok_dizin/essek/class.Oturum.php";
require_once "$kok_dizin/essek/class.Post.php";
require_once "$kok_dizin/essek/class.Get.php";
require_once "$kok_dizin/essek/sql/class.Mysql.php";
require_once "$kok_dizin/essek/class.Cevap.php";

try
{
	$cevap = new Cevap();
	
	if (giris_yapilmamis())
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş yapılmamış");
	}
	
	/*
	 * 'c' = Cevap
	 * 'l' = Liste
	 * 'd' = Değer
	 * 't' = Tablo
	 * 'il' = İller Tablosu
	 * 'ic' = İlceler Tablosu
	 * 'mh' = Mahalleler Tablosu
	 * 
	 */
	if (GET::kontrol_ajax('c') && GET::kontrol_liste('c', array('l', 'd')) &&
		GET::kontrol_ajax('t') && GET::kontrol_liste('t', array('il', 'ic', 'mh')) &&
		GET::kontrol_ajax('id')
	   )
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Gerekli parametre belirtilmemiş");
	}
	
	$cevap_turu = GET::al('c');
	$tablo = GET::al('t');
	$id = GET::al('id');
	
	$geo = new Mysql("geo");
	
	switch ($tablo)
	{
		case 'il': //İl
			{
				$sorgu = Mysql::sql($geo)
				->select($cevap_turu == 'l' ? 'id as kod,il as ad' : 'il AS ad')
				->from('iller');
				
				if ($id > 0)
				{
					$sorgu = $sorgu
					->where()
					->filtre('id', $id);
				}
				
				$sorgu->calistir();
				
				if ($geo->etkilenenSatirSayisi > 0)
				{
					$cevap->ekleCevap("c", $geo->sonuc($id <= 0)->alMesaj());
				}
				else
				{
					$cevap->ekleCevap("c", "");
				}
				
			}
			break;
		case 'ic': //İlçe
			{
				Mysql::sql($geo)
				->select($cevap_turu == 'l' ? 'id as kod,ilce as ad' : 'ilce AS ad')
				->from('ilceler')
				->where()
				->filtre( $cevap_turu == 'l' ? 'il_id' : 'id', $id)
				->calistir();
				
				if ($geo->etkilenenSatirSayisi > 0)
				{
					$cevap->ekleCevap("c", $geo->sonuc($cevap_turu == 'l')->alMesaj());
				}
				else
				{
					$cevap->ekleCevap("c", "");
				}	
			}
			break;
		case 'sm': //Semt
			{
				Mysql::sql($geo)
				->select($cevap_turu == 'l' ? 'id as kod,semt as ad' : 'semt AS ad')
				->from('semtler')
				->where()
				->filtre( $cevap_turu == 'l' ? 'ilce_id' : 'id', $id)
				->calistir();
		
				if ($geo->etkilenenSatirSayisi > 0)
				{
					$cevap->ekleCevap("c", $geo->sonuc($cevap_turu == 'l')->alMesaj());
				}
				else
				{
					$cevap->ekleCevap("c", "");
				}
			}
			break;
		case 'mh': //Mahalle
			{
				Mysql::sql($geo)
				->select($cevap_turu == 'l' ? 'id as kod,mahalle as ad' : 'mahalle AS ad')
				->from('mahalleler')
				->where()
				->filtre( $cevap_turu == 'l' ? 'semt_id' : 'id', $id)
				->calistir();
				
				if ($geo->etkilenenSatirSayisi > 0)
				{
					$cevap->ekleCevap("c", $geo->sonuc($cevap_turu == 'l')->alMesaj());
				}
				else
				{
					$cevap->ekleCevap("c", "");
				}
			}
			break;
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