<?php

/**
*	GET global değişkeninin yönetimini içeren metodlar bulunur. 
*	
*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
*/
class GET
{

	/**
	*	 Bu fonksiyon _POST global dizisinde $anahtar ile belirtilmiş anahtar olup olmadığını kontrol eder. 
	*	 Varsa o değeri döndürür; yoksa boş bir değer döndürür.
	*
	*/
	public static function al($anahtar)
	{
		if (isset($_GET[$anahtar]))
		{
			return $_GET[$anahtar];
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
		$_GET[$anahtar] = $deger;
	}
	
	
	/**
	 * 	 Verilen $anahtar'ın $_GET dizisinde olup olmadığını kontrol eder.
	 *  
	 * @param string, integer $anahtar
	 * @return boolean
	 */
	public static function kontrol($anahtar)
	{
		return isset($_GET[$anahtar]);
	}
	
	/**
	 * 	 $_GET dizisinde $anahtar ile belirlenmiş değeri $liste deki değerlerle karşılaştırır.
	 *   Metodun doğru döndermesi için değerin $liste'nin en az bir elemanıyla eşleşmesi gerekir.
	 *
	 * @param string, integer $anahtar
	 * @param array $liste
	 * @return boolean
	 */
	public static function kontrol_liste($anahtar, $liste)
	{
		$sonuc = false;
		$deger = $_GET[$anahtar];
		
		foreach ($liste AS $eleman)
		{
			$sonuc |= ($_GET[$eleman] == $deger); 
		}
		
		return $sonuc;
	}
	
	/**
	 *   Verilen $anahtar için şu değeri hesaplar. AJAX istekleri için kullanışlı bir metod.
	 *   
	 *   !isset($_GET[$anahtar]) && $_GET($anahtar) == ''
	 *	
	 * @param string $anahtar
	 */
	public static function kontrol_ajax($anahtar)
	{
		return !isset($_GET[$anahtar]) && $_GET[$anahtar] == '';
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
			if (isset($_GET[$anahtar]))
				$degerler[] = $_GET[$anahtar];
			else
				$degerler[] = "";
		}
		
		return $degerler;
	}
}
?>