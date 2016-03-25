<?php

	if (!isset($argumanlar[0]) || $argumanlar[0] == "")
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Gerekli parametre eksik");
	}
	
	Mysql::sql($emlaknet)
	->select('ilan.id AS kod, ilan.baslik AS ilan')
	->from('ilanlar AS ilan')
	->where()
	->filtre('ilan.baslik', "%{$argumanlar[0]}%", "LIKE")
	->limit(0, 5)
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("sonuclar", $emlaknet->sonuc(true)->alMesaj());
	}
	else
	{
		$cevap->ekleCevap("sonuclar", "");
	}
?>