<?php

	/**
	*	Güvenlikle alakalı genel fonksiyonlar. 
	*	
	*	@copyright (c) 2014 Muhammet Mustafa Çaliskan
	*	
	*/
	
	/**
	*	Şifreyi tuzlar.
	*
	*	@param String $sifre Tuzlanacak şifre
	*	@return String  
	*/
	function tuzla($sifre)
	{
		return md5($sifre . md5($sifre));
	}
	
	/**
	*	Rastgele şifre üretir
	*	
	*	@param Integer $uzunluk Üretilecek şifrenin uzunluğu
	*	@return String
	*/
	function rastgeleSifreUret($uzunluk)
	{
		$karakterUzayi = "abcdefghijkmnopqrstuvwxyz023456789";
		
		srand((double) microtime() * 1000000);
		
		$i = 0;
		$sifre = '';
		
		while ($i <= $uzunluk)
		{
			$rastgelenKarakterKonumu = rand() % strlen($karakterUzayi);
			$rastgelenKarakter = substr($karakterUzayi, $rastgelenKarakterKonumu, 1);
			$sifre = $sifre . $rastgelenKarakter;
			
			$i ++;
		}
		
		return $sifre;
	}
	
	/**
	*   Emailin geçerliligini kontrol eder. Geçerliyse true döndürür.
	*   
	*   @param String $email Geçerliliği kontrol edilecek email
	*   @return Boolean
	*/
	function emailGecerli($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	
	/**
	*    Emailin geçerliligini kontrol eder. Geçersiz ise true döndürür.
	*
	*	@param String $email Geçerliliği kontrol edileceke mail
	*	@return Boolean
	*/
	function emailGecersiz($email)
	{
		return !emailGecerli($email);
	}

?>