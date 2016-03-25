<?php
	
	/**
	*	Dizilerle alakali genel fonksiyonlar. 
	*	
	*	Muhammet Mustafa Çaliskan
	*	2014 
	*/
	
	
	/**
	*	Bu fonksiyon $degisken'i $dizi'deki tüm elemanlarla karşılaştırır. 
	*	Hepsi ona eşitse true döner.
	*	Bir tane bile eşit olmayan çıkarsa false döner.
	*
	*	@param String $deger Dizinin içinde karşılaştırılacak değer
	*	@param Array $dizi Arama yapılacak dizi
	*	@return Boolean
	*/
	function hepsiEsitmi($deger, $dizi)
	{
		foreach ($dizi as $eleman)
		{
			if ($eleman != $deger)
			{
				return false;
			}
		}
		
		return true;
	}
	
	/**
	*	Bu fonksiyon $degisken'i $dizi'deki tüm elemanlarla karsilastirir. 
	*	Sadece biri ona esitse true döner.
	*	Hiç esit bulamazsa false döner.
	*
	*	@param String $deger Dizinin içinde karşılaştırılacak değer
	*	@param Array $dizi Arama yapılacak dizi
	*	@return Boolean
	*/
	function biriEsitmi($deger, $dizi)
	{
		foreach ($dizi as $eleman)
		{
			if ($eleman == $deger)
			{
				return true;
			}
		}
		
		return false;
	}
?>