<?php

/**
*	POST global değişkeninin yönetimini içeren metodlar bulunur. 
*	
*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
*/
class Post
{

	/**
	*	 Bu fonksiyon _POST global dizisinde $anahtar ile belirtilmiş anahtar olup olmadığını kontrol eder. 
	*	 Varsa o değeri döndürür; yoksa boş bir değer döndürür.
	*
	*/
	public static function al($anahtar)
	{
		if (isset($_POST[$anahtar]))
		{
			return $_POST[$anahtar];
		}
		else
		{
			return null;
		}
	}

	/**
	*	 Bu fonksiyon _POST global dizisinde $anahtar ile belirtilmiş anahtar olup olmadığını kontrol eder. 
	*	 Varsa o değeri döndürür; yoksa boş bir değer döndürür.
	*
	*/
	public static function ata($anahtar, $deger)
	{
		$_POST[$anahtar] = $deger;
	}
	
	
	/**
	 * 	 Verilen $anahtar'ın $_POST dizisinde olup olmadığını kontrol eder.
	 *  
	 * @param string, integer $anahtar
	 * @return boolean
	 */
	public static function kontrol($anahtar)
	{
		return isset($_POST[$anahtar]);
	}
	
	/**
	 *   Verilen $anahtar için şu değeri hesaplar. AJAX istekleri için kullanışlı bir metod.
	 *
	 *   !isset($_POST[$anahtar]) && $_POST($anahtar) == ''
	 *
	 * @param unknown $anahtar
	 */
	public static function kontrolAjax($anahtar)
	{
		return !isset($_POST[$anahtar]) && $_POST[$anahtar] == '';
	}
	
	/**
	*	 Deger fonksiyonun dizi hali
	*
	*/
	public static function degerDizisi($anahtarlar)
	{
		$degerler = array();
		
		foreach ($anahtarlar as $anahtar)
		{
			if (isset($_POST[$anahtar]))
				$degerler[] = $_POST[$anahtar];
			else
				$degerler[] = "";
		}
		
		return $degerler;
	}
}
?>