<?php

require_once 'class.Dosya.php';

/**
*	Resim düzenleme için gereken genel fonksiyonlar. 
*	
*	Muhammet Mustafa Çaliskan
*	2014 
*/
class Resim
{
	/**
	 * İşlem görecek resim dosyasının yolu
	 * 
	 * @var string
	 */
	private $kaynak;
	
	/**
	*	İşlenmiş resmin kaydedileceği dosyanın adı.	
	*
	*	@var String
	*/
	private $ad;
	
	/**
	 * 	İşlenmiş resmin kaydedileceği dosyanın klasörü.
	 * 
	 * @var string
	 */
	private $klasor;
	
	/**
	*	Bu sınıftaki herhangi bir fonksiyonla işlem görmüş kaynak resmin
	*	yeni hali.
	*
	*	@var Image
	*/
	private $islenmisResim;
	
	/**
	*	İşlem görmüş resmin genişliği
	*
	*	@var Integer
	*/
	private $genislik;
	
	/**
	*	İşlem görmüş resmin yüksekliği
	*
	*	@var Integer
	*/
	private $yukseklik;

	public function __construct($kaynak)
	{
		$this->kaynak = $kaynak;
		$this->islenmisResim = null;
		$this->genislik = 0;
		$this->yukseklik = 0;
	}
	
	/**
	*	Resmi, verilen bir dikdörtgene en iyi sığacak şekilde küçültür.  
	*	
	*     
	*/
	public function orantiliKucult($hedefGenislik, $hedefYukseklik)
	{
		if (!file_exists($this->kaynak))
		{
			throw new exDosyaMevcutDegil($this->kaynak);
		}
		
		$boyut = getimagesize($this->kaynak);
		
		if ($boyut) //Boyut alma başarılıysa
		{
			list ($kaynakGenislik, $kaynakYukseklik) = $boyut;
			
			$kaynakBoyutOrani = $kaynakGenislik / $kaynakYukseklik;
			
			if ($hedefGenislik * (1 / $kaynakBoyutOrani) <= $hedefYukseklik)
			{
				$yeniGenislik = $hedefGenislik;
				$yeniYukseklik = $hedefGenislik * (1 / $kaynakBoyutOrani);
			}
			else
			{
				$yeniGenislik = $hedefYukseklik * $kaynakBoyutOrani;
				$yeniYukseklik = $hedefYukseklik;
			}
			
			$hedef = imagecreatetruecolor($yeniGenislik, $yeniYukseklik);
			
			if ($hedef) //hedef resim oluşturulabildiyse
			{
				$kaynak = imagecreatefromjpeg($this->kaynak);
				
				if ($kaynak) //kaynak resim oluşturulabildiyse
				{
					if (imagecopyresampled($hedef, $kaynak, 0, 0, 0, 0, $yeniGenislik, $yeniYukseklik, $kaynakGenislik, $kaynakYukseklik))
					{
						$this->islenmisResim = $hedef;
						$this->genislik = $yeniGenislik;
						$this->yukseklik = $yeniYukseklik;
						
						return $this;
					}
					else
					{
						throw new exResimOlusturmaHatasi();
					}
				}
				else
				{
					throw new exResimOlusturmaHatasi("'{$this->kaynak}' dosyasından resim oluştururken hata oluştu.");
				}
			}
			else
			{
				throw new exResimOlusturmaHatasi("Belirtilen boyutlarda resim oluştururken hata oluştu.");
			}
		}
		else
		{
			throw new exResimBoyutuAlmaHatasi($this->kaynak);
		}
	}

	/**
	*	İşlenmiş resmi hedef klasöre kaydeder.
	*
	*	@param string $hedef Kaydedilecek klasör
	*	@return boolean
	*/
	public function kaydet()
	{
		if (!imagejpeg($this->islenmisResim, $this->klasor . $this->ad))
		{
			throw new exResimKaydetmeHatasi($this->islenmisResim);
		}
	}

	public function alGenislik()
	{
		return $this->genislik;
	}

	public function alYukseklik()
	{
		return $this->yukseklik;
	}
	
	public function alBoyut()
	{
		return $this->alGenislik() . "x" . $this->alYukseklik();
	}
	
	public function ataAd($ad)
	{
		$this->ad = $ad;
		
		return $this;
	}
	
	public function alAd()
	{
		return $this->ad;
	}
	
	public function ataKlasor($klasor)
	{
		$this->klasor = $klasor;
		
		return $this;
	}
}

/**
 *	Resim işleme sırasında oluşan PHP hatalarının bir nevi aykırı durum
 *	karşılıklarıdır.
 *
 */
class exResim extends Exception
{

	public function __construct($mesaj, $kod = 0, Exception $oncekiHata = null)
	{
		parent::__construct($mesaj, $kod, $oncekiHata);
	}

	public function __toString()
	{
		return "[RESIM_HATASI_{$this->code}]: {$this->message}.";
	}
}
class exResimBoyutuAlmaHatasi extends exResim
{
	public function __construct($dosya)
	{
		parent::__construct("'$dosya' resminin boyutları alınırken hata oluştu", 1);
	}
}
class exResimOlusturmaHatasi extends exResim
{
	public function __construct($mesaj = null)
	{
		$mesaj = ( $mesaj != null && $mesaj != "" ) ? $mesaj : "Resim oluşturulurken hata oluştu";  
		
		parent::__construct( $mesaj , 2);
	}
}
class exResimKaydetmeHatasi extends exResim
{
	public function __construct($dosya)
	{
		parent::__construct("'$dosya' dosyası kaydedilirken hata oluştu.", 3);
	}
}
?>