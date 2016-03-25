<?php
	/*
	 * 	Giriş yapana ait bir ilan görüntülendiğinde o ilana ait istekleri sorgular.
	 * 
	 */

	$bildirim_miktari = 0;
	
	//İstek yapılmış ama ilan sahibi tarafından silinmiş ilanlara bak.
	Mysql::sql($emlaknet)
	->select('ilan.baslik AS ilan, DATE_FORMAT(istek.istek_tarihi, "%d-%m-%Y %T") AS tarih')
	->from('_devir_istekleri as istek, ilanlar as ilan')
	->where()
	->filtre("istek.istek_yapan_id", giris_yapan())->ve()
	->filtreString("ilan.durum_id = 3")->ve()
	->filtreString("istek.istek_durum_id = 5")->ve()
	->filtreString("istek.ilan_id = ilan.id")
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("bildirimlerim", array("silinmis" => $emlaknet->sonuc(true)->alMesaj()));
		$bildirim_miktari += $emlaknet->etkilenenSatirSayisi;
	}
	
	//İncelenmiş ilanlar
	Mysql::sql($emlaknet)
	->select('ilan.baslik AS ilan, DATE_FORMAT(istek.istek_tarihi, "%d-%m-%Y %T") AS tarih')
	->from('_devir_istekleri as istek, ilanlar as ilan')
	->where()
	->filtreString("ilan.durum_id != 3")->ve()
	->filtre("istek.istek_yapan_id", giris_yapan())->ve()
	->filtreString("istek.ziyaret = 1")->ve()
	->filtreString("istek.ilan_id = ilan.id")
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$cevap->ekleCevap("bildirimlerim", array("incelenmis" => $emlaknet->sonuc(true)->alMesaj()));
		$bildirim_miktari += $emlaknet->etkilenenSatirSayisi;
	}
	
	$cevap->ekleCevap("bildirimlerim", array("miktar" => $bildirim_miktari));

?>