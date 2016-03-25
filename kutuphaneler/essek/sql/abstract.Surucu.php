<?php

/**
 * 	Bu sınıf veritabanı işlemlerinde ileride eklenecek 
 * 	veritabanı sürücülerinin tanımlaması gereken metodları içerir. 
 * 
 * @copyright (c) 2014 Muhammet Mustafa Çalışkan
 *
 */

abstract class Surucu
{
	abstract public function __construct($veritabani);

	abstract public static function sql($surucu);

	abstract public function sonuc();
	
	abstract public function calistir($sorgu);

	abstract public function transaBasla();
	
	abstract public function komit();
	
	abstract protected function baglan($veritabani);

	abstract protected function ilkSorgulariGerceklestir();

	abstract protected function sonlandir();
}

?>