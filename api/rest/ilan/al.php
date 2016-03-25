<?php

	/*
	 * a = Amaç
	 * g = Gösterim
	 * d = Düzenleme
	 */
	if (GET::kontrol_ajax('id') && GET::kontrol_ajax('a') && GET::kontrol_liste('a', array('g','d')))
	{
		throw new exCevap("AJAX_ISTEK_HATASI", "Giriş parametreleri alınamadı.");
	} 
	
	$id = GET::al('id');
	$amac = GET::al('a');
	
	$emlaknet = new Mysql("emlaknet");
	
	//İstenilen id ile ilan var mı kontrol et
	Mysql::sql($emlaknet)
	->select('id')
	->from('ilanlar')
	->where()
	->filtre('id', $id)->ve()
	->filtreString('durum_id != 3')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi <= 0)
	{
		//İlan bizim değil
		throw new exCevap('AJAX_ISTEK_HATASI', 'İlan yok veya silinmiş!');
	}
	
	//Amaç düzenlemekse
	if ($amac == 'd')
	{
		//Önce ilan düzenleme için yetkisi var mı kontrol edelim. 
		//Yani düzenlenmek istenen ilan giris_yapan() kisinin mi?
		Mysql::sql($emlaknet)
		->select('id')
		->from('ilanlar')
		->where()
		->filtre('emlakci_id', giris_yapan())->ve()
		->filtre('id', $id)
		->calistir();
		
		if ($emlaknet->etkilenenSatirSayisi <= 0)
		{
			//İlan bizim değil
			throw new exCevap('AJAX_ISTEK_HATASI', 'İlan değiştirmeye yetkiniz yok!');
		}
	}
	
	//İlan bilgilerini alalım.
	//Gösterim ve düzenleme olmak üzere 2 farklı versiyonu var.
	
	$select = array(
				'concat(em.ad," ",em.soyad) AS emad',
				'ilan.emlakci_id', 
				'ilan.baslik', 
				'ilan.aciklama',
			  	'ilan.fiyat AS istenilen_fiyat', 
				'ilan.oda_turu', 
				'ilan.metrekare', 
				'ilan.bulundugu_kat', 
				'ilan.kac_yillik', 
				'ilan.adres');
	
	if ($amac == 'g') //Gösterim
	{
		$select = array_merge($select, array(
				'it.tur AS tur', 
				'gt.tur AS gayrimenkul_turu', 
				'il.il AS il', 
				'ic.ilce AS ilce',
				'sm.semt AS semt',
				'mh.mahalle AS mahalle'));
	}
	else //Düzenleme
	{
		$select = array_merge($select, array(
				'it.id AS tur', 
				'gt.id AS gayrimenkul_turu', 
				'ic.il_id AS il', 
				'ic.id AS ilce', 
				'sm.id AS semt', 
				'mh.id AS mahalle'));
	}
	
	Mysql::sql($emlaknet)
	->select($select)
	->from(array(
		'ilanlar AS ilan', 
		'ilan_turleri AS it', 
		'gayrimenkul_turleri AS gt', 
		'geo.mahalleler AS mh',
		'geo.semtler AS sm', 
		'geo.ilceler AS ic', 
		'geo.iller AS il', 
		'emlakcilar AS em'))
	->where()
	->filtre('ilan.id', $id)->ve()
	->filtreString('ilan.gayrimenkul_tur_id = gt.id')->ve()
	->filtreString('ilan.emlakci_id = em.id')->ve()
	->filtreString('ilan.ilan_tur_id = it.id')->ve()
	->filtreString('ilan.mahalle_id = mh.id')->ve()
	->filtreString('mh.semt_id = sm.id')->ve()
	->filtreString('sm.ilce_id = ic.id')->ve()
	->filtreString('ic.il_id = il.id')	
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0) 
	{
		$sonuc = $emlaknet->sonuc()->alMesaj();
		
		$cevap->ekleCevap('ilan', array('benimmi' => $sonuc['emlakci_id'] != giris_yapan() ? 0 : 1));
		
		if ($sonuc['emlakci_id'] == giris_yapan())
		{
			$sonuc['emad'] = "Ben";	
		}
		
		unset($sonuc['emlakci_id']);
		$cevap->ekleCevap('ilan', array('veri' => $sonuc));
	}
	else 
	{
		$cevap->ekleCevap('ilan', '');
	}
	
	//Daha sonra ilan için devir isteğinde bulunmuşmuyuz onu kontrol edelim
	
	Mysql::sql($emlaknet)
	->select('istek.id, _durum.durum')
	->from('_devir_istekleri AS istek, istek_durumlari AS _durum')
	->where()
	->filtre('istek.istek_yapan_id', giris_yapan())->ve()
	->filtre('istek.ilan_id', $id)->ve()
	->filtreString('istek.istek_durum_id = _durum.id')->ve()
	->filtreString('istek.istek_durum_id = 1')
	->calistir();
	
	if ($emlaknet->etkilenenSatirSayisi > 0)
	{
		$sonuc = $emlaknet->sonuc()->alMesaj();
	
		$cevap->ekleCevap("ilan", array('istek' => 1));
	}
	else
	{
		$cevap->ekleCevap("ilan", array('istek' => 0));
	}

?>