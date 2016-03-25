<?php

/**
*	Yükleme (upload) islemlerini yönetebilecegimiz ve $_FILES global degiskenine ulasimi 
*	kolaylastiran sinifi içerir.
*	
*	@copyright (c) 2014 Muhammet Mustafa Çaliskan
*	
*/

require_once 'class.Resim.php';
require_once 'kutuphane.dizi.php';
require_once 'class.Dosya.php';

class Yukleme
{
	/**
	*	Yuklenecek dosya için kabul edilebilecek uzantilar dizisi. Sadece uzantilar mevcut. Noktasiz halleri.
	*	jpg, gif, exe v.b.
	*
	*/
	private $uzantilar = array();
	
	/**
	*	Yuklenecek dosya için kabul edilebilecek dosya türleri dizisi. 
	*   image/gif, image/jpeg v.b.
	*
	*/
	private $mime = array();
	
	/**
	*	Yüklemenin byte cinsinden izin verilen en yüksek boyutu.
	*	
	*
	*/
	private $enYuksekBoyut;
	
	/**
	 * Yüklenen resmin yüksekliği
	 * 
	 * @var integer
	 */
	private $yukseklik = 0;
	
	/**
	 * Yüklenen resmin genişliği
	 * 
	 * @var integer
	 */
	private $genislik = 0;
	
	/**
	* 	Dosya gönderiminin yapildigi html formundaki dosya etikekitin "name" degeri.
	*
	*	Örnek: <input type="file" name="resimdosyasi" /> ... Burdaki "resimdosyasi"
	*
	*/
	private $inputName = "";
	
	/**
	*	Alınan dosyanin gönderilecegi klasör. 
	*
	*
	*/
	private $hedefKlasor = "";

	public function __construct($inputName)
	{
		$this->inputName = $inputName;
		$this->enYuksekBoyut = 20 * 1024 * 1024; //20 MB
	}
	
	public static function Yeni($_fileAnahtar)
	{
		return new Yukleme($_fileAnahtar);
	}
	
	/**
	 * Yüklenen dosyanın kriterlere uygun olup olmadığını denetler
	 * 
	 * @throws exYuklemeDosyasiCokBuyuk
	 * @throws exYuklemeDosyasiKismenYuklendi
	 * @throws exYuklemeYapilmadi
	 * @throws exYuklemeninGeciciKlasoruYok
	 * @throws exYuklemeYazmaHatasi
	 * @throws exYuklemeEklentiTarafindanEngelledi
	 * @throws exYuklemeIllegalDosyaTuru
	 * @throws exYuklemeIllegalUzanti
	 */
	private function yuklemeDenetimi()
	{
		switch ($this->alHataKodu())
		{
			case UPLOAD_ERR_OK :
				break;
			case UPLOAD_ERR_INI_SIZE :
				{
					throw new exYuklemeDosyasiCokBuyuk($this->alAd());
				}
			case UPLOAD_ERR_FORM_SIZE :
				break;
			case UPLOAD_ERR_PARTIAL :
				{
					throw new exYuklemeDosyasiKismenYuklendi($this->alAd());
				}
			case UPLOAD_ERR_NO_FILE :
				{
					throw new exYuklemeYapilmadi();
				}
			case UPLOAD_ERR_NO_TMP_DIR :
				{
					throw new exYuklemeninGeciciKlasoruYok();
				}
			case UPLOAD_ERR_CANT_WRITE :
				{
					throw new exYuklemeYazmaHatasi();
				}
			case UPLOAD_ERR_EXTENSION :
				{
					throw new exYuklemeEklentiTarafindanEngelledi();
				}
		}
		
		//Dosyanın türü izin verilen türlerden değilse
		if (!biriEsitmi($this->alTur(), $this->mime))
		{
			throw new exYuklemeIllegalDosyaTuru($this->alTur());
		}
		
		//Dosyanın boyutu izin verilen boyuttan büyükse
		if ($this->alBoyut() > $this->enYuksekBoyut)
		{
			throw new exYuklemeDosyasiCokBuyuk($this->alAd());
		}
		
		//Dosyanın uzantısı izin verilen uzantılar arasındaysa
		if (!in_array($this->alUzanti(), $this->uzantilar))
		{
			throw new exYuklemeIllegalUzanti($this->alUzanti());
		}
	}

	
	/**
	 * 	Sınıf yapılandırmasına uygun olarak yükleme yapar.
	 * 
	 * @throws exDosyaMevcut
	 * @return boolean
	 */
	public function yuklemeyiTamamla()
	{
		$this->yuklemeDenetimi();
		
		if (file_exists($this->alHedefDosyaYolu()))
		{
			throw new exDosyaMevcut($this->alGeciciAd());
		}
		else
		{
			$boyut = getimagesize($this->alGeciciAd());
			
			if (!$boyut)
			{
				throw new exResimBoyutuAlmaHatasi($this->alGeciciAd());
			}
			
			list($this->genislik, $this->yukseklik) = $boyut;
			
			if (!move_uploaded_file($this->alGeciciAd(), $this->alHedefDosyaYolu()))
			{
				throw new exYuklemeTasimaHatasi($this->alGeciciAd(), $this->alHedefDosyaYolu());
			}
		}
	}
	
	
	/**
	 * 	$_FILES global degiskeniyle gelen dosyanin ismini döndürür.
	 * 
	 * @return string
	 */
	public function alAd()
	{	
		return $_FILES[$this->inputName]["name"];
	}

	public function alTur()
	{
		return $_FILES[$this->inputName]["type"];
	}
	
	public function alBoyut()
	{
		return $_FILES[$this->inputName]["size"];
	}

	public function alHataKodu()
	{
		return $_FILES[$this->inputName]["error"];
	}

	public function alGeciciAd()
	{
		return $_FILES[$this->inputName]["tmp_name"];
	}

	public function alHedefDosyaYolu()
	{
		return $this->hedefKlasor . $this->alAd();
	}
	
	public function alUzanti()
	{
		$temp = explode(".", $this->alAd());
		
		return end($temp);
	}

	public function alUzantiNoktali()
	{
		return "." . $this->alUzanti();
	}
	
	public function alHedefKlasor()
	{
		return $this->hedefKlasor;
	}
	
	public function ataUzantilar($uzantilar)
	{
		if (!is_array($uzantilar))
		{
			$uzantilar = (array)$uzantilar;
		}
		
		$this->uzantilar = array_merge($this->uzantilar, $uzantilar);
		
		return $this;
	}

	public function ataMIME($mime)
	{
		if (!is_array($mime))
		{
			$mime = (array)$mime;
		}
		
		$this->mime = array_merge($this->mime, $mime);
		
		return $this;
	}

	public function ataEnYuksekBoyut($boyut)
	{
		$this->enYuksekBoyut = $boyut;
		
		return $this;
	}

	public function ataHedefKlasor($yol)
	{
		$this->hedefKlasor = $yol;
		
		return $this;
	}

	public function ataAd($ad)
	{
		$_FILES[$this->inputName]['name'] = $ad;
		
		return $this;
	}
	
	public function alYukseklik()
	{
		return $this->yukseklik;
	}
	
	public function alGenislik()
	{
		return $this->genislik;
	}
}

/**
 *	Dosya yüklemesi sırasında oluşan PHP hatalarının bir nevi aykırı durum  
 *	karşılıklarıdır.
 *
 */
class exYukleme extends Exception
{
	public function __construct($mesaj, $kod = 0, Exception $oncekiHata = null)
	{
		parent::__construct($mesaj, $kod, $oncekiHata);
	}

	public function __toString()
	{
		return "[YUKLEME_HATASI_{$this->code}]: {$this->message}.";
	}
}
class exYuklemeTasimaHatasi extends exYukleme
{
	public function __construct($kaynak, $hedef)
	{
		parent::__construct("'$kaynak' dosyası '$hedef' olarak taşınırken hata oluştu", 9);
	}
}
class exYuklemeDosyasiCokBuyuk extends exYukleme
{
	public function __construct($dosya)
	{
		parent::__construct("'$dosya' kabul edilebilecek sınırdan daha büyük", 1);
	}
}
class exYuklemeDosyasiKismenYuklendi extends exYukleme
{
	public function __construct($dosya)
	{
		parent::__construct("'$dosya' nın tamamı yüklenemedi", 2);
	}
}
class exYuklemeYapilmadi extends exYukleme
{
	public function __construct()
	{
		parent::__construct("Yüklenen herhangi bir dosya yok", 3);
	}
}
class exYuklemeninGeciciKlasoruYok extends exYukleme
{
	public function __construct()
	{
		parent::__construct("Geçici klasör yok/bulunamadı", 4);
	}
}
class exYuklemeYazmaHatasi extends exYukleme
{
	public function __construct()
	{
		parent::__construct("Yüklenen dosyanın diske yazılması başarısız", 5);
	}
}
class exYuklemeEklentiTarafindanEngelledi extends exYukleme
{
	public function __construct()
	{
		parent::__construct("Bir PHP eklentisi yüklemeyi engelledi", 6);
	}
}
class exYuklemeIllegalDosyaTuru extends exYukleme
{
	public function __construct($tur)
	{
		parent::__construct("'$tur' olan dosyanın türü kabul edilemez.", 7);
	}
}
class exYuklemeIllegalUzanti extends exYukleme
{
	public function __construct($uzanti)
	{
		parent::__construct("'$uzanti' dosya uzantısı kabul edilemez.", 8);
	}
}
?>