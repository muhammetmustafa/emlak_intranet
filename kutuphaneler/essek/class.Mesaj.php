<?php
/**
*	Bu sınıfın amacı fonksiyonlar arasındaki mesajlaşmayı sağlamaktır.
*	Hataları ve ve bu hataların miktarını; hata yoksa fonksiyonun dönüş değerini
*	belirtir.
*	
*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
*/
class Mesaj
{
	/**
	*	İletilecek mesaj. Bu genelde fonksiyonun dönderdiği değerdir.
	*
	*	@var object
	*/
	private $mesaj;
	
	/**
	*	İletilecek hatalar
	*
	*	@var array
	*/
	private $hatalar;

	public function __construct()
	{
		$this->hatalar = array();
		$this->mesaj = true;
	}

	public static function YeniMesaj($hatalar, $mesaj)
	{
		$mesaj = new Mesaj();
		$mesaj->HataEkle($hatalar);
		$mesaj->ataMesaj($mesaj);
		return $mesaj;
	}

	public static function Hatasiz()
	{
		return Mesaj::YeniMesaj(array(), true);
	}

	public function hataEkle($hata)
	{
		if (is_string($hata))
		{
			$hata = (array)$hata;
		}
		
		$this->hatalar = array_merge($this->hatalar, $hata);
	}

	public function ataMesaj($mesaj)
	{
		$this->mesaj = $mesaj;
	}

	public function alMesaj()
	{
		return $this->mesaj;
	}

	public function alHatalar($anahtar = null)
	{
		//Özel bir hata istiyormusun
		if ($anahtar != null)
		{
			//İstediğin özel hata bizdeki hatalarda mevcutmu
			if (key_exists($anahtar, $this->hatalar))
			{
				return $this->hatalar[$anahtar];
			}
			else
			{
				return null;
			}
		}
		else
		{
			//Eğer hatalar 1'den fazlaysa dizi olarak gönder, 
			//1 taneyse string olarak gönder, 
			//0 ise null değeri gönder.
			if (count($this->hatalar) > 1)
			{
				return $this->hatalar;
			}
			else if (count($this->hatalar) == 1)
			{
				return $this->hatalar[0];
			}
			else
			{
				return null;
			}
		}
	}

	public function alHataMiktari()
	{
		return count($this->hatalar);
	}
}
?>