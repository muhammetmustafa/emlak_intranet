<?php

/**
 * SQL Sorgularını yapılandırmak için kullanılır
 * 
 * @copyright (c) 2014 Muhammet Mustafa Çalışkan
 *
 */

require_once str_replace("\\", "/", dirname(__DIR__)) . '/class.Post.php';
require_once str_replace("\\", "/", dirname(__DIR__)) . '/class.Aykiri.php';

class SQL
{
	
	/**
	 * Sorgu metni
	 * 
	 * @var String
	 */
	public $sorgu;
	
	/**
	 * @aciklama Kullanılacak veritabanı sürücüsü (driver)
	 * 
	 * @var Surucu (interface.Surucu.php)
	 */
	private $surucu;

	public function __construct($surucu)
	{
		$this->sorgu = "";
		$this->surucu = $surucu;
	}

	public static function sql($surucu)
	{
		return new SQL($surucu);
	}

	public function calistir()
	{
		if ($this->surucu != null)
		{
			$this->surucu->calistir($this->sorgu);
		}
	}

	/*******************************************************
	*
	*	Temel işlemler: SELECT, INSERT, UPDATE, DELETE
	*
	*******************************************************/
	
	public function select($sutunlar = null)
	{
		if ($sutunlar == "" || $sutunlar == null)
		{
			$sutunlar = "*";
		}
		
		if (is_string($sutunlar))
		{
			$sutunlar = array(
					$sutunlar 
			);
		}
		
		$this->sorgu = sprintf("SELECT %s", implode(", ", $sutunlar));
		
		return $this;
	}

	public function insert($tablo)
	{
		$this->sorgu = sprintf("INSERT INTO %s", $tablo);
		
		return $this;
	}

	public function update($tablo)
	{
		$this->sorgu = sprintf("UPDATE %s", $tablo);
		
		return $this;
	}

	public function delete()
	{
		$this->sorgu = "DELETE";
		
		return $this;
	}

	/*******************************************************************
	*
	*	Sorgulara yan sorgular eklemek için kullanılan metodlar.
	*
	*	from(), 
	*	sutunlar() (columns), 
	*	degerler() (values),
	*	set(),
	*	orderby(),
	*	limit()
	*
	*********************************************************************/
	
	/**
	*	SELECT, DELETE sorgularını oluşturmada kullanılır.
	*
	*/
	public function from($tablolar)
	{
		if (is_string($tablolar))
		{
			$tablolar = (array) $tablolar;
		}
		
		$this->sorgu .= sprintf(" FROM %s", implode(", ", $tablolar));
		
		return $this;
	}

	/**
	*	INSERT sorgusunu oluşturmada kullanılır.
	*
	*/
	public function sutunlar($sutunlar)
	{
		if (is_string($sutunlar))
		{
			$sutunlar = (array) $sutunlar;
		}
		
		$this->sorgu .= sprintf(" (%s)", implode(", ", $sutunlar));
		
		return $this;
	}

	/**
	*	INSERT sorgusunu oluşturmada kullanılır.
	*
	*	@param Array $degerler Eklenecek değerler.
	*	@param Booelan $post 
	*/
	public function degerler($degerler, $post = false)
	{
		if (is_string($degerler))
		{
			$degerler = (array) $degerler;
		}
		
		$degerler = array_map("SQL::degerPOST", $degerler, array_fill(0, count($degerler), $post));
		$degerler = array_map("SQL::tirnakla", array_map("SQL::sorguyaHazirla", $degerler));
		
		$this->sorgu .= sprintf(" VALUES (%s)", implode(", ", $degerler));
		
		return $this;
	}
	
	/**
	 * 	 INSERT sorgusunda eklenecek sutunlar ve değerler bir dizi içinde key => value ilişkisi ile belirtilebilir.
	 * 
	 * @param unknown $sutunlarDegerler
	 * @param string $post
	 */
	public function eklemeler($sutunlarDegerler, $post = false)
	{
		if (!is_array($sutunlarDegerler))
		{
			return $this;
		}
		
		$sutunlar = array_keys($sutunlarDegerler);
		$degerler = array_values($sutunlarDegerler);
		
		return $this->sutunlar($sutunlar)->degerler($degerler, $post);
	}
	
	/**
	*	UPDATE sorgusunu oluşturmada kullanılır.
	*
	*/
	public function set($sutunlarDegerler, $post = false)
	{
		$gecici = " SET";
		
		foreach ($sutunlarDegerler as $sutun => $deger)
		{
			$deger = $this->degerPOST($deger, $post);
			$deger = $this->degerSorgu($deger);
			
			$gecici .= sprintf(" %s = %s, ", $sutun, $deger);
		}
		
		$this->sorgu .= substr($gecici, 0, - 2); //Sondaki virgülü ve boşluğu kaldır
		

		return $this;
	}

	/**
	 * Sıralanacak sütunu ekler
	 * 
	 * @param String $sutun
	 * @return SQL
	 */
	public function orderby($sutun)
	{
		if (is_array($sutun))
		{
			$sutun = $sutun[0];
		}
		
		$this->sorgu .= sprintf(" ORDER BY %s", $sutun);
		
		return $this;
	}

	/**
	 * Sorguya limit ekler
	 * 
	 * @param integer $sayfaNo
	 * @param integer $sonucAdedi
	 * @return SQL
	 */
	public function limit($sayfaNo, $sonucAdedi)
	{
		$this->sorgu .= sprintf(" LIMIT %d, %d", $sayfaNo, $sonucAdedi);
		
		return $this;
	}

	/****************************************************************************************
	*
	*	WHERE sorgusunun fonksiyonları
	*
	*	
	*	Baglaçlar:		ve(), veya(), xor(), 
	*	
	*****************************************************************************************/
	public function where($sutunlarDegerler = null, $post = false)
	{
		$gecici = " WHERE";
		
		if ($sutunlarDegerler != null)
		{
			foreach ($sutunlarDegerler as $sutun => $deger)
			{
				$deger = $this->degerPOST($deger, $post);
				$deger = $this->degerSorgu($deger);
				
				$gecici .= sprintf(" %s = %s AND ", $sutun, $deger);
			}
			
			$gecici = substr($gecici, 0, - 4); //Sondaki "AND" yazısını ve boşluğu kaldır
		}
		
		$this->sorgu .= $gecici;
		
		return $this;
	}

	public function filtre($sutun, $deger, $operator = null,  $post = false)
	{
		if ($operator == null || $operator == "")
		{
			$operator = "=";
		}
		
		$deger = $this->degerPOST($deger, $post);
		$deger = $this->degerSorgu($deger);
		
		$this->sorgu .= " " . $sutun . " " . $operator . " " . $deger;
		
		return $this;
	}

	public function filtreString($filtre)
	{	
		if (is_array($filtre))
		{
			$filtre = $filtre[0];
		}
		
		$this->sorgu .= " ".$filtre;
		
		return $this;
	}

	public function sutun($sutun)
	{
		$this->sorgu .= " " . $sutun;
		
		return $this;
	}

	public function deger($deger, $post = false)
	{
		$deger = $this->degerPOST($deger, $post);
		$deger = $this->degerSorgu($deger);
		
		$this->sorgu .= " " . $deger;
		
		return $this;
	}

	public function op($operator = null)
	{
		if ($operator == null || $operator == "")
		{
			$operator = "=";
		}
		
		$this->sorgu .= " " . $operator . " ";
		
		return $this;
	}

	public function ve()
	{
		$this->sorgu .= " AND ";
		
		return $this;
	}

	public function veya()
	{
		$this->sorgu .= " OR ";
		
		return $this;
	}

	public function ixor()
	{
		$this->sorgu .= " XOR ";
		
		return $this;
	}
	
	public function artan()
	{
		$this->sorgu .= " ASC ";
	
		return $this;
	}
	
	public function azalan()
	{
		$this->sorgu .= " DESC ";
	
		return $this;
	}
	/****************************************************************************************
	*
	*	Veritabanında sorgu yaparken kontrol edilen değerlerin sorguya güvenli bir şekilde
	*	yerleştirilmesini sağlamak için kullanılabilecek metodlar.
	*
	*****************************************************************************************/
	
	/**
	*	Değerleri POST'dan alacağımız belirtidiğimiz halde
	*	bütün değerleri POST'dan almak istemeyebiliriz. Bu türdeki değerleri
	*	dizi olarak atarsak burada POST'dan alınmaz.
	*/
	private function degerPOST($deger, $post)
	{
		if ($post)
		{
			if (is_string($deger))
			{
				if (Post::kontrol($deger))
				{
					$deger = Post::al($deger);
				}
				else
				{
					throw new exIndisMevcutDegil('$_POST', $deger, __FUNCTION__, __LINE__);
				}
			}
			else if (is_array($deger))
			{
				if (in_array(0, $deger))
				{
					$deger = $deger[0];
				}
				else
				{
					throw new exIndisMevcutDegil('$deger', 0, __FUNCTION__, __LINE__);
				}
			}
		}
		
		return $deger;
	}
	
	
	/**
	 * Değer sutun ise tırnak içine almaz diğer türlü değeri gönderir
	 * 
	 * @param string $deger Sütun olup olmadığı kontrol edilecek değer.
	 * @return string
	 */
	private function degerSorgu($deger)
	{
		if (is_object($deger))
		{
			$deger = $this->sorguyaHazirla($deger->scalar);
		}
		else if (is_string($deger))
		{
			$deger = $this->tirnakla($this->sorguyaHazirla($deger));
		}
		
		return $deger;
	}

	/**
	* 	$deger sayı (numeric) ise hiç değişiklik yapmak aynısını gönderir.
	*   $deger metin (string) ise tek tırnak içerisine alır.
	*
	*	@param $deger (String, String Array)
	*	@return String, String Array, null
	*/
	private function tirnakla($deger)
	{
		if (is_numeric($deger))
		{
			return $deger;
		}
		else if (is_string($deger))
		{
			return sprintf("'%s'", $deger);
		}
		else if (is_array($deger))
		{
			return array_map("SQL::tirnakla", $deger);
		}
		else
		{
			return null;
		}
	}

	/**
	*	Değerleri veritabanı sorgusu için hazırlar.
	*
	*	@param $deger (String, String Array)
	*	@return String, String Array, null
	*/
	private function sorguyaHazirla($deger)
	{
		if (is_numeric($deger))
		{
			return $deger;
		}
		else if (is_string($deger))
		{
			$kacisTablosu = array(
					"\0" => "",
					"'" => "\'",
					"\\" => "\\\\",
					"<" => "&lt;",
					">" => "&gt;",
					"\n" => "\\\\n",
					"\r" => "\\\\r" 
			);
			
			return strtr($deger, $kacisTablosu);
		}
		else if (is_array($deger))
		{
			return array_map("SQL::sorguyaHazirla", $deger);
		}
		else
		{
			return null;
		}
	}
}
?>