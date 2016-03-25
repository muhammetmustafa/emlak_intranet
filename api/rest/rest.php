<?php
	session_start();
	
	require_once '../../kutuphaneler/essek/sql/class.Mysql.php';
	require_once '../../kutuphaneler/essek/class.Oturum.php';
	require_once '../../kutuphaneler/essek/class.Istek.php';
	require_once '../../kutuphaneler/essek/class.Cevap.php';
	
	define("YETKI_GIRIS_YAPMAYANLAR", 1);
	define("YETKI_GIRIS_YAPANLAR", 2);
	define("YETKI_HERKES", 3);
	
	$cevap = new Cevap();
	$istek = new Istek(str_replace("/emlak_intranet/api/rest/", "", $_SERVER['REQUEST_URI']));
	
	try 
	{
		//işlem listesini yükleyelim.
		require_once 'islem_listesi.php';
			
		//İstenilen işlem listede yoksa hata üret.
		if (!array_key_exists($istek->alIslem(), $islemler))
		{
			throw new exCevap("400", "Bad request");
		}
			
		//İşlem bilgilerini al
		$islem = $islemler[$istek->alIslem()];
			
		//Yetki kontrolü
		//İstenilen işlemi sadece giriş yapmayanlar gerçekleştirebiliyorsa ve
		//sisteme giriş yapılmışsa
		if ($islem["yetki"] == YETKI_GIRIS_YAPMAYANLAR && giris_yapilmis())
		{
			throw new exCevap("400", "Bad request");
		}
		//İstenilen işlemi sadece giriş yapanlar gerçekleştirebiliyorsa ve
		//sisteme giriş yapılmamışsa
		if ($islem["yetki"] == YETKI_GIRIS_YAPANLAR && giris_yapilmamis())
		{
			throw new exCevap("400", "Bad request");
		}
		
		//Veritabanı bağlantısını gerçekleştir.
		$emlaknet = new Mysql("emlaknet");
			
		//Script dosyalarında kullanılmak üzere post la gelen veriyi, argümanları ata.
		$veri = $istek->alVeri();
		$argumanlar = $istek->alArgumanlar();
		
		//Script dosyasını yükle.
		require $islem["script"];
	}
	catch (exCevap $hata)
	{
		$cevap->ekleHata($hata->alTur(), $hata->alMesaj());
	}
	catch (Exception $e) 
	{
		$cevap->ekleHata("500", $e->getMessage());
	}		
	
	echo $cevap->alJSON();
?>