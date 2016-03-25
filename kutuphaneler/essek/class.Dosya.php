<?php
	/**
	*	Dosya işlemlerini gerçekleştiren statik ve genel metodları kapsayan sınıfı ve 
	*	bu sınıftan fırlatılabilece Exception'ları içerir.
	*
	*		
	*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
	*	
	*/
	
	require_once 'class.Mesaj.php';
	
	class Dosya
	{
	
		/**
		*	Belirtilen konumdaki dosyanın içeriğini okuyup string olarak dönderir.
		* 	Hata olması durumunda Mesaj türünden cevap dönderir.
		* 
		* 	@param String $dosya Okunacak dosyanın yolu
		* 	@return Mesaj (class.Mesaj.php)
		*/
		public static function oku($dosya)
		{
			$mesaj = new Mesaj();
			
			if (!file_exists($dosya)) //Dosya mevcut değilse
			{
				throw new exDosyaMevcutDegil($dosya);
			}
			
			$dt = fopen($dosya, "rb");
			
			if ($dt)//Dosya açma başarılıysa
			{
				$icerik = fread($dt, filesize($dosya));
				
				if ($icerik) //Okuma başarılıysa
				{
					//Açılan dosya kapatmada hata oluştuysa
					//Bu hata sadece bilgilendirmeye tabidir. Fonksiyonun işlevinde bir aksamaya sebep olmaz.
					if (!fclose($dt))
					{
						$mesaj->hataEkle(sprintf("Açılan %s dosyası kapatılamadı", $dosya));
					}
					
					$mesaj->ataMesaj($icerik);
				}
				else //Okuma hatalıysa
				{
					throw new exDosyaOkumaHatasi($dosya);
				}
			}
			else //Dosya açma hatalıysa
			{
				throw new exDosyaAcmaHatasi($dosya);
			}
			
			return $mesaj;
		}
		
		/**
		 * 	Dosya taşıma işlemini gerçekleştirir.
		 * 
		 * @param String $kaynak Taşınacak dosya
		 * @param String $hedef	 Dosyanın yeni konumu
		 * @throws exDosyaMevcutDegil
		 * @throws exDosyaTasimaHatasi
		 * @return Mesaj (class.Mesaj.php)
		 */
		public static function tasi($kaynak, $hedef)
		{
			$mesaj = new Mesaj();
			
			if (!file_exists($kaynak))
			{
				throw new exDosyaMevcutDegil($kaynak);
			}
			
			if (file_exists($hedef))
			{
				$mesaj->hataEkle(sprintf("'%s' hedef dosyası mevcut. Üzerine yazılacak", $hedef));
			}
			
			//Dosya taşıma başarılı değilse
			if (!rename($kaynak, $hedef))
			{
				throw new exDosyaTasimaHatasi($kaynak, $hedef);
			}
			
			return $mesaj;
		}
		
		/**
		 * 	Dosya silme işlemini gerçekleştirir
		 * 
		 * @param String $dosya Silinecek dosya
		 * @throws exDosyaMevcutDegil
		 * @throws exDosyaSilmeHatasi
		 * @return Mesaj
		 */
		public static function sil($dosya)
		{
			if (!file_exists($dosya))
			{
				throw new exDosyaMevcutDegil($dosya);
			}
			
			//Dosya silme başarılı değilse
			if (!unlink($dosya))
			{
				throw new exDosyaSilmeHatasi($dosya);
			}
			
			return Mesaj::Hatasiz();
		}
	}
	
	/**
	*	Dosya yönetimiyle ilgili ebeveyn Exception sınıfı.
	*	Orjinal excepition sınıfından tek farkı exception'a sebep olan
	*	dosya yolunu belirtebileceğimiz bir değişkenin olması ve hata gösteriminin bu dosyayı içerecek şekilde
	*	yapılandırılmış olması.
	*
	*/
	class exDosya extends Exception
	{
		/**
		*	Hataya sebep olan dosya.
		*
		*	@var String 
		*/
		protected $dosya;
		
		public function __construct($mesaj, $dosya, $kod = 0, Exception $oncekiHata = null) 
		{
			parent::__construct($mesaj, $kod, $oncekiHata);
			$this->dosya = $dosya;
		}
		
		public function __toString() 
		{
			return "DOSYA HATASI: [{$this->code}]: '{$this->dosya}' {$this->message}.";
		}
	}
	
	class exDosyaMevcutDegil extends exDosya
	{
		public function __construct($dosya) 
		{
			parent::__construct("mevcut değil", $dosya, 1); //Mesaj basılınca "'C:/img.jpeg' mevcut değil." şeklinde şıkacak
		}
	}
	
	class exDosyaMevcut extends exDosya
	{
		public function __construct($dosya)
		{
			parent::__construct(" zaten mevcut", $dosya, 6); //Mesaj basılınca "'C:/img.jpeg' zaten mevcut." şeklinde şıkacak
		}
	}
	
	class exDosyaAcmaHatasi extends exDosya
	{
		public function __construct($dosya) 
		{
			parent::__construct("açılamadı", $dosya, 2);//Mesaj basılınca "'C:/img.jpeg' açılamadı." şeklinde şıkacak
		}
	}
	
	class exDosyaOkumaHatasi extends exDosya
	{
		public function __construct($dosya) 
		{
			parent::__construct("okunamadı", $dosya, 3);//Mesaj basılınca "'C:/img.jpeg' okunamadı." şeklinde şıkacak
		}
	}
	
	class exDosyaSilmeHatasi extends exDosya
	{
		public function __construct($dosya) 
		{
			parent::__construct("silinemedi", $dosya, 4);//Mesaj basılınca "'C:/img.jpeg' silinemedi." şeklinde şıkacak
		}
	}
	
	class exDosyaTasimaHatasi extends exDosya
	{
		private $hedef;
		
		public function __construct($kaynak, $hedef) 
		{
			parent::__construct("Dosya taşınamadı", $kaynak, 5);
			$this->hedef = $hedef;
		}
		
		public function __toString()
		{
			return "HATA: [{$this->code}]: {$this->message}. Kaynak Dosya: {$this->kaynak}, Hedef Dosya: {$this->hedef}}\n";
		}
	}
	
?>