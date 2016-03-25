<?php
	/*
	*	Bu sınıf isteklere gönderilen cevapların yönetiminde kullanılacaktır.
	*	
	*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
	*	
	*/
	
	require_once 'class.HataYoneticisi.php';
	
	class Cevap
	{	
		/**
		*	Dönecek cevaplar. 
		*
		*	@var mixed. (String veya Array)
		*/
		private $cevaplar = array();
		
		/**
		*	Oluşan hatalar
		*
		*	Tür:		Hata (class.Hata.php dosyasında)
		*	İlkdeğer:	Boş Hata Nesnesi
		*/
		private $hatalar;
	
		public function __construct()
		{
			$this->hatalar = new HataYoneticisi();
		}
		
		public function ekleHata($hataTuru, $hataMesaji, $ekBilgiler = null, $uyari = false)
		{
			$this->hatalar->ekleHata($hataTuru, $hataMesaji, $ekBilgiler, $uyari);
		}
		
		public function ekleCevap($cevap, $deger)
		{
			$this->cevaplar = array_merge_recursive($this->cevaplar, array($cevap => $deger));
		}
		
		public function alJSON($durum_kodu = 200, $secenekler = null)
		{	
			header("Content-type: application/json");
			header("HTTP/1.1 " . $durum_kodu . " " . $this->alIstekDurumMesaji($durum_kodu));
			
			return json_encode($this->alDizi(), $secenekler);
		}
		
		public function alHTML($durum_kodu = 200)
		{
			header("Content-type: text/html");
			header("HTTP/1.1 " . $durum_kodu . " " . $this->alIstekDurumMesaji($durum_kodu));
			
			return $this->alCevap("html");
		}
		
		public function alDizi()
		{
			$cevap = array();
			
			if (count($this->cevaplar) > 0)
			{
				$cevap["cevap"] = $this->cevaplar;
			}
			
			if ($this->hatalar->alHataMiktari() > 0)
			{
				$cevap["hatalar"] = $this->hatalar->alHatalar();
			}
			
			$cevap["hatamiktari"] = $this->hatalar->alHataMiktari();
			
			return $cevap;
		}
		
		public function alCevap($anahtar = null)
		{
			if ($anahtar != null)
			{
				if (!is_string($anahtar))
				{
					return null;
				}
				if (key_exists($anahtar, $this->cevaplar))
				{
					return $this->cevaplar[$anahtar];
				}
				else
				{
					return null;
				}
			}
			else
			{
				if (count($this->cevaplar) >= 1)
				{
					return $this->cevaplar;
				}
				else
				{
					return null;
				}
			}
		}
		
		//Dönüş değeri: Hata sınıfı (class.Hata.php sınıfında)
		public function alHatalar($hataTuru = null)
		{
			return $this->hatalar->alHatalar($hataTuru);
		}
		
		public function alHataMiktari()
		{
			return $this->hatalar->alHataMiktari();
		}
		
		private static function alIstekDurumMesaji($durum_kodu)
		{
			$durumlar = Array(
					100 => 'Continue',
					101 => 'Switching Protocols',
					200 => 'OK',
					201 => 'Created',
					202 => 'Accepted',
					203 => 'Non-Authoritative Information',
					204 => 'No Content',
					205 => 'Reset Content',
					206 => 'Partial Content',
					300 => 'Multiple Choices',
					301 => 'Moved Permanently',
					302 => 'Found',
					303 => 'See Other',
					304 => 'Not Modified',
					305 => 'Use Proxy',
					306 => '(Unused)',
					307 => 'Temporary Redirect',
					400 => 'Bad Request',
					401 => 'Unauthorized',
					402 => 'Payment Required',
					403 => 'Forbidden',
					404 => 'Not Found',
					405 => 'Method Not Allowed',
					406 => 'Not Acceptable',
					407 => 'Proxy Authentication Required',
					408 => 'Request Timeout',
					409 => 'Conflict',
					410 => 'Gone',
					411 => 'Length Required',
					412 => 'Precondition Failed',
					413 => 'Request Entity Too Large',
					414 => 'Request-URI Too Long',
					415 => 'Unsupported Media Type',
					416 => 'Requested Range Not Satisfiable',
					417 => 'Expectation Failed',
					500 => 'Internal Server Error',
					501 => 'Not Implemented',
					502 => 'Bad Gateway',
					503 => 'Service Unavailable',
					504 => 'Gateway Timeout',
					505 => 'HTTP Version Not Supported'
			);
		
			return (isset($durumlar[$durum_kodu])) ? $durumlar[$durum_kodu] : '';
		}
	}
	
	class exCevap extends Exception
	{
		private $tur;
		private $mesaj;
		
		public function __construct($tur, $mesaj)
		{
			$this->tur = $tur;
			$this->mesaj = $mesaj;
		}
		
		public function alTur()
		{
			return $this->tur;
		}
		
		public function alMesaj()
		{
			return $this->mesaj;
		}
	}
?>