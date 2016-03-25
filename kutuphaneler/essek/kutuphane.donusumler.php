<?php
	
	/*
		Genel dönüsüm fonksiyonlarini içerir.. 
		
		Muhammet Mustafa Çaliskan
		2014 
	*/
	
	
	/*
		- Bayt cinsinden verilen dosya boyutunu büyüklügüne göre uygun
		  türde geri döndürür.
		  
		  Eger dosya boyutu terabaytla ifade edilebilecek kadar büyükse TB olarak,
		  gigabyte ile ifade edilebilecek kadar büyükse GB olarak vb. döndürür.
	*/
	function dosyaBoyutunuDonustur($boyut)
	{
		if (is_numeric ( $boyut )) 
		{
			$s = array ('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
			$e = floor ( log ( $boyut ) / log ( 1024 ) );
			
			return sprintf ( '%.2f ' . $s [$e], @($boyut / pow ( 1024, floor ( $e ) )) );
		} 
		else 
		{
			$boyut = "Bilinmiyor";
		}
		
		return $boyut;
	}
	
	/*
		- Geçen toplam saniyeyi, bir dizide saat dakika saniye olarak döndürür.
		
		- Dönüs:
			array('saat'=>2,'dakika'=>34,'saniye'=>23)
	*/
	function saniyeyiZamanaDonustur($toplamSaniye) 
	{
		$saat = round ( $toplamSaniye / 3600, 2 );
		
		if ($saat >= 1) {
			$saat = floor ( $saat );
			$toplamSaniye -= $saat * 3600;
		}
		
		$dakika = round ( $toplamSaniye / 60, 2 );
		
		if ($dakika >= 1) {
			$dakika = floor ( $dakika );
			$toplamSaniye -= $dakika * 60;
		}
		
		$saniye = $toplamSaniye;
		
		
		return array('saat' => $saat, 'dakika' => $dakika, 'saniye' => $saniye);
	}	
?>