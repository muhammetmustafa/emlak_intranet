<?php

/**
*	MySQL veritabanı yöneticisi. 
*	
*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
*	
*/

require_once 'abstract.Surucu.php';
require_once 'class.SQL.php';
require_once str_replace('\\', '/', dirname(__DIR__)) . '/class.Mesaj.php';

class Mysql extends Surucu
{
	/**
	*	Sorgu yapılacak veritabanı. Her nesne için belirtilmeli.
	*	
	*	@var String
	*/
	public $veritabani = "";
	
	/**
	*	Bağlanılacak MySQL hostu. Atanmazsa "localhost" olarak devam edilecek.
	*	
	*	@var String
	*/
	public $host = "localhost";
	
	/**
	*	Bağlanılacak MySQL kullanıcısı. Atanmazsa "root" olarak devam edilecek.
	*	
	*	@var String
	*/
	public $kullanici = "root";
	
	/**
	*	Bağlanılacak MySQL kullanıcısı için şifre. Atanmazsa "" olarak devam edilecek.
	*	
	*	@var String
	*/
	public $sifre = "";
	
	/**
	*	MySQL'e başarılı bağlandıktan sonra kullanılacak nesne.
	*	
	*	@var MySQLi Nesnesi
	*/
	public $mysqli = null;
	
	/**
	*	Sorgu sonucunun tutulduğu değişken.
	*
	*	@var MySQLi_Result
	*/
	public $mysqliSonuc = null;
	
	/**
	*	Gerçekleştirilen sorgunun sonuç döndürüp döndürmediği.
	*
	*	@var Boolean
	*/
	public $mysqliSonucMevcutmu = false;
	
	/**
	*	Gerçekleştirilen sorgunun kaç adet satırı etkilediği.
	*
	*	@var Integer
	*/
	public $etkilenenSatirSayisi = - 1;
	
	/**
	*	En son INSERT sorgusunda eklenen satırın id'si.
	*
	*	@var Integer
	*/
	public $sonEklenen = - 1;

	
	/**
	* 	Çalıştırılan en son sorgu
	* 
	*   @var String
	*/
	public $sonSorgu = "";
	
	public function __construct($veritabani)
	{
		$this->veritabani = $veritabani;
	}

	public static function sql($surucu)
	{
		return new SQL($surucu);
	}

	public function calistir($sorgu)
	{
		if ($sorgu == "")
		{
			throw new exSorguBos();
		}
		
		//Eğer veritabanına bağlı değilsek
		if ($this->mysqli == null)
		{
			$this->baglan($this->veritabani);
			$this->ilkSorgulariGerceklestir();
		}
		
		//Her sorgu çalıştırmadan önce etkilenen satır sayısı değerini sıfırla.
		//Ancak eğer çalıştırılacak sorgu aykırı durum üretirse diye
		//catch bloğunda eski değerini iade etmek için geçici bir değişkende sakla
		$gcc_etkilenenSatirSayisi = $this->etkilenenSatirSayisi;
		$this->etkilenenSatirSayisi = 0;
		
		try
		{
			$sonuc = $this->mysqli->query($sorgu);
			
			//Sorgu çalıştıysa (ki aykırı durum üretseydi buraya gelemezdik)
			//en son sorguyu gösteren özelliğimize bu değeri ata.
			$this->sonSorgu = $sorgu;
			
			if ($sonuc)
			{
				if ($sonuc instanceof MySQLi_Result)
				{
					//SELECT
					//Eğer sorgu herhangibir sonuç döndürdüyse
					$this->mysqliSonuc = $sonuc;
					$this->mysqliSonucMevcutmu = true;
					$this->etkilenenSatirSayisi = $this->mysqli->affected_rows;
				}
				else
				{
					//INSERT, UPDATE, DELETE
					//Sorgu hatasız ise sınıfımızın bazı üyelerini atayalım.
					$this->etkilenenSatirSayisi = $this->mysqli->affected_rows;
					$this->sonEklenen = $this->mysqli->insert_id;
				}
			}
			else
			{
				//Eğer sorgu hatalıysa
				throw new exSorguHatasi($sorgu, $this->mysqli->errno, $this->mysqli->error);
			}
		}
		catch (mysqli_sql_exception $hata)
		{
			//Aykırı durum fırlatmadan önce bazı işlerimizi halledelim.
			$this->etkilenenSatirSayisi = $gcc_etkilenenSatirSayisi;
			
			throw new exSorguHatasi($sorgu, $hata->getCode(), $hata->getMessage());
		}
	}
	
	/**
	 *	Transaction begin 
	 */
	public function transaBasla()
	{
		//Eğer veritabanına bağlı değilsek
		if ($this->mysqli == null)
		{
			$this->baglan($this->veritabani);
			$this->ilkSorgulariGerceklestir();
		}
		
		try
		{
			$this->mysqli->autocommit(false);
			$this->mysqli->begin_transaction();
		} 
		catch (mysqli_sql_exception $hata) 
		{
			throw new exTransactionBaslatmaHatasi($hata->getCode(), $hata->getMessage()); 
		}
	}
	
	/**
	 *	Rollback
	 */
	public function gerial()
	{
		//Eğer veritabanına bağlı değilsek
		if ($this->mysqli == null)
		{
			$this->baglan($this->veritabani);
			$this->ilkSorgulariGerceklestir();
		}
	
		try
		{
			$this->mysqli->rollback();
		}
		catch (mysqli_sql_exception $hata)
		{
			throw new exGerialHatasi($hata->getCode(), $hata->getMessage());
		}
	}
	
	/**
	 *	Commit 
	 */
	public function komit()
	{
		//Eğer veritabanına bağlı değilsek
		if ($this->mysqli == null)
		{
			$this->baglan($this->veritabani);
			$this->ilkSorgulariGerceklestir();
		}
		
		try
		{
			$this->mysqli->commit();
		}
		catch (mysqli_sql_exception $hata)
		{
			throw new exCommitHatasi($hata->getCode(), $hata->getMessage());
		}
	}
	
	/* (Gerçekleştirilen sorgu SELECT veya sonuç beklenen bir türdense bu metod
	 * kullanılır)
	 * 
	 * @see Surucu::sonuc()
	 * @param $cokSatirli true olması durumunda sonuç birden fazla satır içerir.
	 * @return Mesaj (class.Mesaj.php)
	 */
	public function sonuc($cokSatirli = false)
	{
		$mesaj = new Mesaj();
		
		//Alınacak sonuç yoksa
		if (! $this->mysqliSonucMevcutmu)
		{
			$mesaj->hataEkle("Hiç bir sonuç mevcut değil");
			
			return $mesaj;
		}
		
		//Eğer sonuç çok satırlı ise 
		if ($cokSatirli)
		{
			$mesaj->ataMesaj($this->mysqliSonuc->fetch_all(MYSQLI_ASSOC));
		}
		else
		{
			$mesaj->ataMesaj($this->mysqliSonuc->fetch_assoc());
		}
		
		return $mesaj;
	}

	/**
	*	Parametreleri önceden belirlenen MySQL sunucusuna bağlanır ve 
	*	$veritabani parametresiyle belirlenen veritabanını seçer.
	*
	*	@param $veritabani Bağlanılacak veritabanı ismi.
	*/
	protected function baglan($veritabani)
	{
		//MySQLi'nin exception atabilmesini sağlayalım.
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		//Bu metod için uyarıları kapatalım. Biz gösteriyoruz zaten.
		error_reporting(E_ALL & ~ E_WARNING);
		
		try
		{
			$this->mysqli = new MySQLi($this->host, $this->kullanici, $this->sifre, $veritabani);
		}
		catch (mysqli_sql_exception $hata)
		{
			throw new exBaglantiHatasi($this->host, $this->kullanici, $hata->getCode(), $hata->getMessage());
		}
	}

	protected function ilkSorgulariGerceklestir()
	{
		$ilkSorgular = array(
				"SET NAMES 'utf8'",
				"SET CHARACTER SET utf8",
				"SET COLLATION_CONNECTION = 'utf8_turkish_ci'" 
		);
		
		foreach ($ilkSorgular as $sorgu)
		{
			if (! $this->mysqli->query($sorgu))
			{
				throw new exSorguHatasi($sorgu, $this->mysqli->errno, $this->mysqli->error);
			}
		}
	}

	/**
	*	MySQL bağlantısını sonlandırır.
	*	
	*
	*/
	protected function sonlandir()
	{
		$this->mysqli->close();
	}
}

/*
*	Veritabanı sorgulama, bağlanma ile ilgili ebeveyn Exception sınıfı.
*	
*/
class exVeritabani extends Exception
{
	public function __construct($mesaj, $kod = 0)
	{
		parent::__construct($mesaj, $kod, null);
	}

	public function __toString()
	{
		return "[VERİTABANI_HATA_KODU:{$this->code}] {$this->message}.";
	}
}
class exSorguBos extends exVeritabani
{
	public function __construct()
	{
		parent::__construct("Sorgu boş", 4);
	}
}
class exBaglantiHatasi extends exVeritabani
{
	public function __construct($host, $kullanici, $hataKodu, $hataMesaji)
	{
		$mesaj = sprintf("'%s' kullanıcısı için '%s' e bağlanılamadı.<br/> [MySQL_HATA_KODU:%d] %s", $kullanici, $host, $hataKodu, $hataMesaji);
		
		parent::__construct($mesaj, 1);
	}
}
class exVeritabaniSecmeHatasi extends exVeritabani
{
	public function __construct($veritabani, $hataKodu, $hataMesaji)
	{
		$mesaj = sprintf("'%s' seçilemedi.<br/> [MySQL_HATA_KODU:%d] %s", $veritabani, $hataKodu, $hataMesaji);
		
		parent::__construct($mesaj, 2);
	}
}
class exSorguHatasi extends exVeritabani
{
	public function __construct($sorgu, $hataKodu, $hataMesaji)
	{
		$mesaj = sprintf("'%s' sorgusunu çalıştırmada hata oluştu.<br/> [MySQL_HATA_KODU:%d] %s", $sorgu, $hataKodu, $hataMesaji);
		
		parent::__construct($mesaj, 3);
	}
}
class exTransactionBaslatmaHatasi extends exVeritabani
{
	public function __construct($hataKodu, $hataMesaji)
	{
		$mesaj = sprintf("Transaction başlatılamadı.<br/> [MySQL_HATA_KODU:%d] %s", "BEGIN TRANSACTION", $hataKodu, $hataMesaji);

		parent::__construct($mesaj, 4);
	}
}
class exCommitHatasi extends exVeritabani
{
	public function __construct($hataKodu, $hataMesaji)
	{
		$mesaj = sprintf("Transaction başlatılamadı.<br/> [MySQL_HATA_KODU:%d] %s", "COMMIT", $hataKodu, $hataMesaji);

		parent::__construct($mesaj, 4);
	}
}
class exGerialHatasi extends exVeritabani
{
	public function __construct($hataKodu, $hataMesaji)
	{
		$mesaj = sprintf("Rollback yapılamadı.<br/> [MySQL_HATA_KODU:%d] %s", "ROLLBACK", $hataKodu, $hataMesaji);

		parent::__construct($mesaj, 4);
	}
}
?>