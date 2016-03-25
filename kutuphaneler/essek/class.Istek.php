<?php

class Istek 
{	
	private $metod;
	private $veri;
	private $islem;
	private $argumanlar = array();
	private $dosya = null;
	
	public function __construct($istek_metni)
	{
		$this->metod = $_SERVER['REQUEST_METHOD'];
		
		if ($this->metod == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) 
		{
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') 
			{
				$this->metod = 'DELETE';
			} 
			else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT')
			{
				$this->metod = 'PUT';
			} 
			else 
			{
				throw new exIstek("Tanınmayan metod");
			}
		}
		
		$this->argumanlar = explode('/', rtrim($istek_metni, '/'));
		$this->islem = array_shift($this->argumanlar);
		
		switch ($this->metod)
		{
			case 'DELETE':
			case 'POST':
				{
					//POST yöntemiyle gönderilmişse ama POST değişkeninde değer yoksa
					//json olarak gelmiş demektir.
					if (count($_POST) == 0)
					{
						//gelen json verisini php dizisine dönüştür.
						$this->veri = json_decode(file_get_contents('php://input'), true);
					}
					else
					{
						$this->veri = $this->temizlikYap($_POST);
					}
					
					break;
				}
			case 'GET':
				{
					$this->veri = $this->temizlikYap($_GET);
					break;
				}
			
			case 'PUT':
				{
					// basically, we read a string from PHP's special input location,
					// and then parse it out into an array via parse_str... per the PHP docs:
					// Parses str  as if it were the query string passed via a URL and sets
					// variables in the current scope.
					parse_str(file_get_contents('php://input'), $this->veri);
					break;
				}
			
		}
		
	}
	
	private function temizlikYap($gelen_veri) 
	{
		$temiz_veri = Array();
		
		if (is_array($gelen_veri)) 
		{
			foreach ($gelen_veri as $k => $v) 
			{
				$temiz_veri[$k] = $this->temizlikYap($v);
			}
		} 
		else 
		{
			$temiz_veri = trim(strip_tags($gelen_veri));
		}
		
		return $temiz_veri;
	}
	
	public function alMetod()
	{
		return $this->metod;
	}
	
	public function alIslem()
	{
		return $this->islem;
	}
	
	public function alVeri()
	{
		return $this->veri;
	}
	
	public function alArgumanlar()
	{
		return $this->argumanlar;
	}
}

class exIstek extends Exception
{
	public function __construct($mesaj) 
	{
		parent::__construct($mesaj, null, null);
	}

	public function __toString()
	{
		return $this->message;
	}
}
?>