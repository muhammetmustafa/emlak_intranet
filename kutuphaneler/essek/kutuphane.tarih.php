<?php
	/**
	*	Tarih ve zaman için genel fonksiyonlar. 
	*	
	*	Muhammet Mustafa Çaliskan
	*	2014 
	*/
	

	/**
	*	Belli formatta tarih dönderir.
	*	
	*	Örn: 1990-05-21 07:02:04
	*/
	function veritabaniSimdi()
	{
		$tarih = new DateTime();
		
		return $tarih->format("Y-m-d H:i:s");
	}
	
	/**
	*	Sunucuya eklenen dosya için tarih ve saatin birleşimini döndürür.
	*
	*/
	function dosyaSimdi()
	{
		$tarih = new DateTime();
		
		return $tarih->format("dmYHis");
	}
?>