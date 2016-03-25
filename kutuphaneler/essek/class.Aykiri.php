<?php
	/**
	*	Genel geçer aykırı durumlar (exception) toplu olarak burada bulunuyor. 
	*		
	*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
	*	
	*/
	
	/**
	*	Dizilerin olmayan indislerine ulaşılmaya çalışıldığında fırlatılabilecek aykırı durum.
	*
	*/
	class exIndisMevcutDegil extends Exception
	{
		private $fonksiyon;
		private $satir;
		
		public function __construct($dizi, $indis, $fonksiyon = null, $satir = null) 
		{
			if ($fonksiyon == null) { $this->fonksiyon = ""; } else { $this->fonksiyon = $fonksiyon; }
			if ($satir == null){ $this->satir = ""; } else { $this->satir = $satir; }

			parent::__construct("'$dizi' dizisinde '$indis' indisi mevcut değil.", 0);
		}
		
		public function __toString() 
		{
			return "[HATA_DIZI_{$this->code}]: {$this->message} [METOD:{$this->fonksiyon}][SATIR:{$this->satir}]";
		}
	}
	
	
?>