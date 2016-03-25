-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 27 Ara 2014, 10:35:20
-- Sunucu sürümü: 5.6.16
-- PHP Sürümü: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `emlaknet`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `emlakcilar`
--

CREATE TABLE IF NOT EXISTS `emlakcilar` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sifre` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `soyad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `mahalle_id` int(5) DEFAULT NULL,
  `katilma_tarihi` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mahalle_id` (`mahalle_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=5 ;

--
-- Tablo döküm verisi `emlakcilar`
--

INSERT INTO `emlakcilar` (`id`, `sifre`, `ad`, `soyad`, `mahalle_id`, `katilma_tarihi`) VALUES
(1, '123', 'Muhammet Mustafa', 'Çalışkan', 7766, ''),
(2, '234', 'Ahmet', 'Mustafa', 1, ''),
(3, '1', 'Ali ', 'Karakaş', 4332, '2014-4-1 12:41:11'),
(4, '234', 'Ali', 'Kaytar', NULL, 'Thu Dec 11 14:38:02 EET 2014');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `form_gayrimenkul_alakasiz`
--

CREATE TABLE IF NOT EXISTS `form_gayrimenkul_alakasiz` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `gayrimenkul_id` int(5) NOT NULL,
  `nitelik_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Hangi gayrimenkul ile hangi niteliğin alakasız olduğunun tanımlandığı tablo.' AUTO_INCREMENT=4 ;

--
-- Tablo döküm verisi `form_gayrimenkul_alakasiz`
--

INSERT INTO `form_gayrimenkul_alakasiz` (`id`, `gayrimenkul_id`, `nitelik_id`) VALUES
(1, 3, 1),
(2, 3, 3),
(3, 3, 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `form_gayrimenkul_nitelikler`
--

CREATE TABLE IF NOT EXISTS `form_gayrimenkul_nitelikler` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'text',
  `class` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `sira` int(5) NOT NULL,
  `name` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ngmodel` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `min_value` int(5) DEFAULT NULL,
  `max_value` int(10) DEFAULT NULL,
  `min_char` int(5) DEFAULT NULL,
  `max_char` int(10) DEFAULT NULL,
  `gerekli` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Gayrimenkulle alakalı istenebilecek soruları içeren tablo.' AUTO_INCREMENT=6 ;

--
-- Tablo döküm verisi `form_gayrimenkul_nitelikler`
--

INSERT INTO `form_gayrimenkul_nitelikler` (`id`, `label`, `type`, `class`, `sira`, `name`, `ngmodel`, `min_value`, `max_value`, `min_char`, `max_char`, `gerekli`) VALUES
(1, 'Oda Türü', 'text', 'form-control', 1, 'oda_turu', 'ilan.oda_turu', NULL, NULL, NULL, NULL, 1),
(2, 'Metrekare', 'number', 'form-control', 2, 'metrekare', 'ilan.metrekare', 1, 100000, NULL, NULL, 1),
(3, 'Bulunduğu Kat', 'number', 'form-control', 3, 'bulundugu_kat', 'ilan.bulundugu_kat', NULL, NULL, NULL, NULL, 1),
(4, 'Kaç Yıllık', 'number', 'form-control', 4, 'kac_yillik', 'ilan.kac_yillik', 1, 100, NULL, NULL, 1),
(5, 'İstenilen Fiyat', 'number', 'form-control', 5, 'istenilen_fiyat', 'ilan.istenilen_fiyat', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gayrimenkuller`
--

CREATE TABLE IF NOT EXISTS `gayrimenkuller` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=10 ;

--
-- Tablo döküm verisi `gayrimenkuller`
--

INSERT INTO `gayrimenkuller` (`id`, `tur`) VALUES
(1, 'Daire'),
(2, 'İşyeri'),
(3, 'Arazi'),
(4, 'Yazlık'),
(5, 'Villa'),
(6, 'Apartman/Bina'),
(7, 'İş Merkezi'),
(8, 'Alışveriş Merkezi'),
(9, 'Restorant/Lokanta');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gayrimenkul_turleri`
--

CREATE TABLE IF NOT EXISTS `gayrimenkul_turleri` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=4 ;

--
-- Tablo döküm verisi `gayrimenkul_turleri`
--

INSERT INTO `gayrimenkul_turleri` (`id`, `tur`) VALUES
(1, 'Daire'),
(2, 'İşyeri'),
(3, 'Arazi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ilanlar`
--

CREATE TABLE IF NOT EXISTS `ilanlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emlakci_id` int(11) DEFAULT NULL,
  `gayrimenkul_tur_id` int(11) DEFAULT NULL,
  `ilan_tur_id` int(11) DEFAULT NULL,
  `mahalle_id` int(11) DEFAULT NULL,
  `baslik` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `aciklama` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
  `fiyat` bigint(20) NOT NULL,
  `oda_turu` varchar(15) COLLATE utf8_turkish_ci NOT NULL,
  `metrekare` int(5) NOT NULL,
  `bulundugu_kat` int(5) NOT NULL,
  `kac_yillik` int(4) NOT NULL,
  `koordinat` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `adres` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
  `eklenme_tarihi` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `durum_id` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gayrimenkul_tur_id` (`gayrimenkul_tur_id`),
  KEY `ilan_tur_id` (`ilan_tur_id`),
  KEY `mahalle_id` (`mahalle_id`),
  KEY `emlakci_id` (`emlakci_id`),
  KEY `durum` (`durum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=13 ;

--
-- Tablo döküm verisi `ilanlar`
--

INSERT INTO `ilanlar` (`id`, `emlakci_id`, `gayrimenkul_tur_id`, `ilan_tur_id`, `mahalle_id`, `baslik`, `aciklama`, `fiyat`, `oda_turu`, `metrekare`, `bulundugu_kat`, `kac_yillik`, `koordinat`, `adres`, `eklenme_tarihi`, `durum_id`) VALUES
(5, 1, 2, 2, 1, 'Başakşehirde 10 katlı bina', 'uylim eka ylmuike ylmukiylme kauylimkahgnkğ lmi', 200000000, '5+5', 2343, 23, 423, '', 'Osmangazi mahallesi melikşah setise', '', 3),
(7, 2, 3, 2, 1, 'ulim eklauyie', 'uiyle mkaulimek yluiekayluik eylmkauyilmek yulimke yaulmike ylamukiyel mkauiylmeka uylimeka yuilmke yalumike aylumikeamlkuiyemakyuilmekaulymikeaylumiea', 423423423423, '32+', 32, 23, 42, '', 'uiaui eauieauiea uiae', '', 1),
(8, 1, 2, 1, 1, 'uiamuiylemkayluiea', 'ayluimeylamkuilemka', 3, '23', 3, 3, 3, '', 'uaieauieauieaui', '', 3),
(9, 2, 2, 1, 1, 'yeni ilamım', 'ymaulime kyul im keylumiekyalmuikea', 123123, '23', 22, 34, 14, '', 'auiea iue auieauieau', '', 3),
(10, 2, 1, 1, 1, 'uyi lemakyulimekyal', 'ylm kuyilmek yulmieklyakuyile', 4, '2', 2, 3, 4, '', 'uieaüi eauieaui', '', 3),
(11, 1, 1, 2, 38, 'toplumklu miekly muk', 'mau iykelm kuyilmekayulmiek', 1313, '1+2', 11, 31, 31, '', 'amiueka yluikyelmkuiylmea', '', 1),
(12, 1, 1, 1, 1, 'mkuiyle makuyilemka uylimy', 'u limek uylimkea yulmikea', 223, '23', 23, 211, 32, '', 'auieaui eaui eauiea', '', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ilan_durumlari`
--

CREATE TABLE IF NOT EXISTS `ilan_durumlari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `durum` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=4 ;

--
-- Tablo döküm verisi `ilan_durumlari`
--

INSERT INTO `ilan_durumlari` (`id`, `durum`) VALUES
(1, 'Yayında'),
(2, 'Devredilmiş'),
(3, 'Silinmiş');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ilan_turleri`
--

CREATE TABLE IF NOT EXISTS `ilan_turleri` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=3 ;

--
-- Tablo döküm verisi `ilan_turleri`
--

INSERT INTO `ilan_turleri` (`id`, `tur`) VALUES
(1, 'Satılık'),
(2, 'Kiralık');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iletisim_turleri`
--

CREATE TABLE IF NOT EXISTS `iletisim_turleri` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `guzel_hali` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=6 ;

--
-- Tablo döküm verisi `iletisim_turleri`
--

INSERT INTO `iletisim_turleri` (`id`, `tur`, `guzel_hali`) VALUES
(1, 'email', 'Email'),
(2, 'gsm', 'GSM'),
(3, 'is_telefonu', 'İş Telefonu'),
(4, 'adres', 'Adres'),
(5, 'web_adresi', 'Web Adresi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `istek_durumlari`
--

CREATE TABLE IF NOT EXISTS `istek_durumlari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `durum` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `aciklama` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=7 ;

--
-- Tablo döküm verisi `istek_durumlari`
--

INSERT INTO `istek_durumlari` (`id`, `durum`, `aciklama`) VALUES
(1, 'onaylanmadi', 'Devir isteği onaylanma aşamasında.'),
(2, 'onaylandi', 'Devir isteği onaylandı.'),
(4, 'iptal', 'Devir isteği ilan sahibi tarafından reddedildi.'),
(5, 'iptal', 'İlan silindiğinden iptal edildi.'),
(6, 'iptal', 'İstekte bulunan vazgeçti.');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `katlar`
--

CREATE TABLE IF NOT EXISTS `katlar` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `kat` varchar(10) COLLATE utf8_turkish_ci NOT NULL,
  `aciklama` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=17 ;

--
-- Tablo döküm verisi `katlar`
--

INSERT INTO `katlar` (`id`, `kat`, `aciklama`) VALUES
(1, '0', 'Bodrum Kat'),
(2, '1', '1. Kat'),
(3, '2', '2. Kat'),
(4, '3', '3. Kat'),
(5, '4', '4. Kat'),
(6, '5', '5. Kat'),
(7, '6', '6. Kat'),
(8, '7', '7. Kat'),
(9, '8', '8. Kat'),
(10, '9', '9. Kat'),
(11, '10', '10. Kat'),
(12, '11', '11. Kat'),
(13, '12', '12. Kat'),
(14, '13', '13. Kat'),
(15, '14', '14. Kat'),
(16, '15', '15. Kat');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mesajlar`
--

CREATE TABLE IF NOT EXISTS `mesajlar` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `kimden_id` int(10) NOT NULL,
  `kime_id` int(10) NOT NULL,
  `durum_id` int(3) NOT NULL,
  `konu` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `mesaj` varchar(250) COLLATE utf8_turkish_ci NOT NULL,
  `tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kimden_id` (`kimden_id`,`kime_id`,`durum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=5 ;

--
-- Tablo döküm verisi `mesajlar`
--

INSERT INTO `mesajlar` (`id`, `kimden_id`, `kime_id`, `durum_id`, `konu`, `mesaj`, `tarih`) VALUES
(1, 1, 2, 1, 'uyilmeka', 'ylmka yulimke aluimkeaylmuikyea', '2014-11-03 23:38:59'),
(2, 2, 1, 2, 'uilmekayl umkiea', 'lmkuyilmek yike ayumilka', '2014-11-04 00:05:36'),
(3, 2, 1, 2, 'uilmekayl umkieauaie uie auiea', 'lmkuyilmek yike ayumilkau ieauieauie auie', '2014-11-04 00:05:45'),
(4, 3, 1, 2, 'bilgilendirme', 'hacım telefonun göndersene', '2014-11-06 21:27:47');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mesaj_durumlari`
--

CREATE TABLE IF NOT EXISTS `mesaj_durumlari` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `durum` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `aciklama` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=4 ;

--
-- Tablo döküm verisi `mesaj_durumlari`
--

INSERT INTO `mesaj_durumlari` (`id`, `durum`, `aciklama`) VALUES
(1, 'okunmamis', ''),
(2, 'okunmus', ''),
(3, 'cop_kutusunda', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `oda_turleri`
--

CREATE TABLE IF NOT EXISTS `oda_turleri` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `aciklama` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=5 ;

--
-- Tablo döküm verisi `oda_turleri`
--

INSERT INTO `oda_turleri` (`id`, `tur`, `aciklama`) VALUES
(1, '1+1', '1 Oda 1 Salon'),
(2, '2+1', '2 Oda 1 Salon'),
(3, '3+1', '3 Oda 1 Salon'),
(4, '4+1', '4 Oda 1 Salon');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `_devir_istekleri`
--

CREATE TABLE IF NOT EXISTS `_devir_istekleri` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `istek_yapan_id` int(10) NOT NULL,
  `istek_hedef_id` int(10) NOT NULL,
  `ilan_id` int(10) NOT NULL,
  `ek` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
  `istek_tarihi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `istek_durum_id` int(5) NOT NULL,
  `ziyaret` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `istek_yapan_id` (`istek_yapan_id`,`ilan_id`),
  KEY `istek_durum_id` (`istek_durum_id`),
  KEY `istek_hedef_id` (`istek_hedef_id`),
  KEY `ilan_id` (`ilan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=35 ;

--
-- Tablo döküm verisi `_devir_istekleri`
--

INSERT INTO `_devir_istekleri` (`id`, `istek_yapan_id`, `istek_hedef_id`, `ilan_id`, `ek`, `istek_tarihi`, `istek_durum_id`, `ziyaret`) VALUES
(14, 1, 2, 9, 'ktmktmktm', '2014-10-30 19:43:40', 4, 0),
(15, 1, 2, 10, '112323', '2014-10-30 19:44:04', 4, 0),
(16, 2, 1, 6, 'uieauieauieauieauieaui', '2014-10-30 21:23:29', 4, 0),
(17, 1, 2, 9, 'uia lumike yalmuikeylma kuiyleak', '2014-11-06 00:45:57', 4, 0),
(18, 1, 2, 9, 'uiea uie auiea', '2014-11-06 00:47:32', 4, 0),
(19, 3, 1, 6, 'merhaba gardaş\\n\\nilanan talibim', '2014-11-06 21:24:55', 4, 0),
(20, 3, 1, 6, 'lmkylmkylmk ylm klm kylmky', '2014-11-06 21:27:07', 2, 0),
(21, 3, 1, 11, 'arkadaşım devralma isteği gönderiyorum', '2014-11-07 22:46:26', 4, 0),
(22, 3, 1, 8, 'iieaiea', '2014-11-07 23:55:27', 5, 0),
(23, 3, 1, 5, 'mklmklm lmk lmklmk', '2014-11-08 13:46:19', 5, 1),
(24, 3, 1, 5, 'iaiea', '2014-11-08 21:51:42', 5, 0),
(31, 3, 2, 9, '', '2014-11-08 22:24:58', 5, 1),
(32, 3, 2, 10, '', '2014-11-08 22:27:00', 6, 0),
(33, 3, 2, 10, '', '2014-11-08 22:28:26', 6, 0),
(34, 3, 2, 10, '', '2014-11-08 22:28:29', 5, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `_emlakci_iletisim`
--

CREATE TABLE IF NOT EXISTS `_emlakci_iletisim` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `emlakci_id` int(10) NOT NULL,
  `iletisim_tur_id` int(3) NOT NULL,
  `bilgi` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `emlakci_id` (`emlakci_id`),
  KEY `iletisim_tur_id` (`iletisim_tur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=1 ;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `ilanlar`
--
ALTER TABLE `ilanlar`
  ADD CONSTRAINT `_fk_ilanlar_durumlari` FOREIGN KEY (`durum_id`) REFERENCES `ilan_durumlari` (`id`),
  ADD CONSTRAINT `_fk_ilanlar_emlakcilar` FOREIGN KEY (`emlakci_id`) REFERENCES `emlakcilar` (`id`);

--
-- Tablo kısıtlamaları `_emlakci_iletisim`
--
ALTER TABLE `_emlakci_iletisim`
  ADD CONSTRAINT `_fk_iletisim_emlakcilar` FOREIGN KEY (`emlakci_id`) REFERENCES `emlakcilar` (`id`),
  ADD CONSTRAINT `_fk_iletisim_iletisim_turleri` FOREIGN KEY (`iletisim_tur_id`) REFERENCES `iletisim_turleri` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
