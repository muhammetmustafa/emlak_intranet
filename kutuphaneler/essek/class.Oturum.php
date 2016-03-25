<?php
	/**
	*	Oturum (session) işlemlerini yöneten fonksiyonlar mevcuttur. 
	*	
	*	@copyright (c) 2014 Muhammet Mustafa Çalışkan
	*/
	
	require_once 'class.Post.php';
	
	/**
	*	Sisteme giriş yapılmış ise true döndürür.
	*	
	*	@param string $session_kullanici_degisken_adi
	*		Bu paramatreye sessionda kullanıcı id'sini tutmak için hangi string kullanıyorsak o atanır. 
	*		Eğer kullanılamazsa 'uid' olduğu varsayılır.
	*		
	*	@example
	*		giris_yapilmis('kullanici_id')
	*		giris_yapilmis('user_id')
	*
	*	@return boolean
	*/
	function giris_yapilmis($session_kullanici_degisken_adi = 'uid')
	{
		return (	isset($_SESSION[$session_kullanici_degisken_adi]) && 
					$_SESSION[$session_kullanici_degisken_adi] != ""
			   );
	}

	/**
	*	Sisteme giriş yapılmamış ise true döndürür.
	*
	*	@return boolean
	*/
	function giris_yapilmamis()
	{
		return (!(giris_yapilmis()));
	}
	
	/**
	*	Sisteme giriş yapmak için kullanılır.
	*	
	*	@param string $kullanici_id 
	*		Session'a atanması için kullanılacak id.
	*
	*	@param string $session_kullanici_degisken_adi 
	*		Bu paramatreye sessionda kullanıcı id'sini tutmak için hangi string kullanıyorsak o atanır.
	*		Eğer kullanılamazsa 'uid' olduğu varsayılır.
	*
	*	@param boolean $post
	*		Bu parametreye true atanırsa $kullanici_id parametresi POST global değişkeninden alınır.
	*
	*	@example	
	*		giris_yap("zeki342", "kullanici_id")
	*
	*/
	function giris_yap($kullanici_id, $post = false, $session_kullanici_degisken_adi = 'uid')
	{
		if ($post)
		{
			$kullanici_id = Post::al($kullanici_id);
		}
		
		$_SESSION[$session_kullanici_degisken_adi] = $kullanici_id;
	}
	
	/**
	*	Sistemden çıkış yapar.
	*/
	function cikis_yap()
	{
		return session_destroy();
	}

	/** 
	*	Sisteme giriş yapanın id'sini döndürür. 
	*	
	*	@param string $session_kullanici_degisken_adi
	*		Bu paramatreye sessionda kullanıcı id'sini tutmak için hangi string kullanıyorsak o atanır.
	*		Eğer kullanılamazsa 'uid' olduğu varsayılır.
	*		
	*	@example 
	*		giris_yapan('kullanici_id')
	*		giris_yapan('user_id')
	*/
		
	function giris_yapan($session_kullanici_degisken_adi = 'uid')
	{
		return $_SESSION[$session_kullanici_degisken_adi];
	}
	
	/**
	*	Bu sınıf ileride genişletilebilir. Şimdilik 
	*	bazı statik fonksiyonlar yeterli.
	*/
	class Oturum
	{
		/**
		*	Oturum global değişkeninden $anahtar ile belirlenen degeri döndürür.
		*/
		public static function al($anahtar)
		{
			return $_SESSION[$anahtar];
		}
		
		/**
		*	Oturum global değişkenine $anahtar ve $deger ile belirlenen değeri atar.
		*/
		public static function ata($anahtar, $deger)
		{
			$_SESSION[$anahtar] = $deger;
		}	
	}
?>