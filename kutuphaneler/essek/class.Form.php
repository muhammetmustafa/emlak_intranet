<?php
	/**
	*	Form doğrulama/kontrol işlemlerinin yapıldığı sınıf.
	*	
	*	Muhammet Mustafa Çalışkan
	*	2014 
	*/
	class Form
	{
		/**
		 * Form nesnesinin yaşamı boyunca, nesneye yaptırılan tüm kontrollerin hata miktar toplamıdır.
		 * 
		 * @var integer
		 */
		public $toplamHataMiktari = 0;
		
		/**
		*	Sisteme post ile gönderilen değişkenlerin boş olup olmadığını kontrol eder.
		*	
		*	@param array $postDegerleri: 
		*			Bu değişkenle kontrol edilmesini istediğiniz post değişkenleri, etiketleri ile
		*			dizi olarak gönderilir.
		*	
		*	@return array
		*		Fonksiyon dönüş olarak her değişkene özel etiket + "boş bırakılamaz" mesajlarının oluştuğu diziyi 
		*		döndürür.
		*		
		*	@example
		*		bos_kontrol(array('uid' => 'Kullanıcı Adı', 'password' => 'Şifre')
		*		array('uid' => 'Kullanıcı Adı boş bırakılamaz') Bu örnekte gelen dizide tek boş olan uid imiş.
		*/
		function kontrolBos($postDegerleri)
		{
			$uyarilar = array();
			
			foreach($postDegerleri as $postAnahtar => $postDeger)
			{
				if (isset($_POST[$postAnahtar]) && $_POST[$postAnahtar] == "")
				{
					$uyarilar[$postAnahtar] = $postDeger. " boş bırakılamaz";
					
					$this->toplamHataMiktari++;
				}
				else
				{
					$uyarilar[$postAnahtar] = "";
				}
			}
			
			return $uyarilar;
		}
		
		/**
		*	Ziyaret edilen sayfaya ilk defa girilip girilmediğini bulur.
		*	
		*	@param array $postDegerleri kontrol edilmesini istediğiniz post değişkenleri.
		*	@return boolean
		*/
		function ilkZiyaret($postDegerleri)
		{	
			$setOlmayanKontrolSayisi = 0;
			
			foreach($postDegerleri as $postDegeri)
			{
				if (!isset($_POST[$postDegeri]))
				{
					$setOlmayanKontrolSayisi++;
					
					$this->toplamHataMiktari++;
				}
			}
			
			return ($setOlmayanKontrolSayisi > 0);
		}
		
		function hataArtir()
		{
			$this->toplamHataMiktari++;
		}
	}
?>