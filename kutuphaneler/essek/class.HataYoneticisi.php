<?php
/**
*	Hataların ortak olarak ifade edilmesi için kullanılır..
*	
*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
*	
*/
class HataYoneticisi
{
	/**
	*	Oluşan hatalar
	*
	*	@var array
	*/
	private $hatalar = array();
	
	/**
	*	Oluşan hataların miktarı.
	*	Bunu tanımlamamın sebebi (count($hatalar)) kullanmak yerine eklenecek bazı hataların, hata değil de uyarı durumunda olması. 
	*
	*	@var integer
	*/
	private $hataMiktari = 0;

	public function ekleHata($hataTuru, $hataMesaji, $ekBilgiler = null, $uyari = false)
	{
		if (is_array($hataMesaji) && (count($hataMesaji) == 0))
		{
			return;
		}
		
		//Eklenecek hata uyarı amaçlıysa cevabın başarısına etki etmesin
		if (!$uyari)
		{
			$this->hataMiktari++;
		}
		
		if ($ekBilgiler != null)
		{
			$hata[$hataMesaji] = $ekBilgiler;
		}
		else
		{
			$hata[] = $hataMesaji;
		}
		
		$this->hatalar = array_merge_recursive($this->hatalar, array(
				$hataTuru => $hata 
		));
	}
	
	/**
	 * 	Dönüş değeri: Hata sınıfı (class.Cevap.php sınıfında)
	 * 	
	 * 	@param string $hataTuru
	 * 	
	 * 	@return array, null
	 */
	public function alHatalar($hataTuru = null)
	{
		//Özel bir hata istiyormusun
		if ($hataTuru != null)
		{
			//İstediğin özel hata bizdeki hatalarda mevcutmu
			if (key_exists($hataTuru, $this->hatalar))
			{
				return $this->hatalar[$hataTuru];
			}
			else
			{
				return null;
			}
		}
		else
		{
			if (count($this->hatalar) >= 1)
			{
				return $this->hatalar;
			}
			else
			{
				return null;
			}
		}
	}

	public function alHataMiktari()
	{
		return $this->hataMiktari;
	}
}
?>