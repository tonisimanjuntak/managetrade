-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2022 at 02:32 PM
-- Server version: 10.3.35-MariaDB-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serd5225_managetrade`
--
CREATE DATABASE IF NOT EXISTS `serd5225_managetrade` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `serd5225_managetrade`;

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `create_idbalance`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idbalance` (`_tgl` DATE) RETURNS CHAR(7) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(3);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(3);
	DECLARE jumlah_digit INT DEFAULT 3;
	DECLARE cTgl CHAR(4);
	
	SET cTgl = DATE_FORMAT(_tgl, '%y%m') ;
	
	SELECT MAX(RIGHT(RTRIM(idbalance),jumlah_digit)) FROM balancetrading  
		WHERE LEFT(idbalance,4) = CONCAT(cTgl) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cTgl, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idbroker`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idbroker` (`var_namabroker` VARCHAR(100)) RETURNS CHAR(5) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(2);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(2);
	DECLARE jumlah_digit INT DEFAULT 2;
	DECLARE cUnix CHAR(3);
	
	SET cUnix = create_unix_name(var_namabroker, 3);
	
	SELECT MAX(RIGHT(RTRIM(idbroker),jumlah_digit)) FROM broker  
		WHERE LEFT(idbroker,3) = CONCAT(cUnix) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cUnix, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idcurrency`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idcurrency` (`var_singkatan` VARCHAR(15)) RETURNS CHAR(3) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(2);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(2);
	DECLARE jumlah_digit INT DEFAULT 2;
	declare cUnix char(1);
	
	SET cUnix = create_unix_name(var_singkatan, 1);
	
	SELECT MAX(RIGHT(RTRIM(idcurrency),jumlah_digit)) FROM currency  
		WHERE LEFT(idcurrency,1) = CONCAT(cUnix) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cUnix, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idjenisasset`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idjenisasset` () RETURNS CHAR(2) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(2);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(2);
	DECLARE jumlah_digit INT DEFAULT 2;
	
	
	SELECT MAX(RIGHT(RTRIM(idjenisasset),jumlah_digit)) FROM jenisasset INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idjenisstrategy`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idjenisstrategy` (`var_namajenisstrategy` VARCHAR(50)) RETURNS CHAR(5) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(2);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(2);
	DECLARE jumlah_digit INT DEFAULT 2;
	declare cUnix char(3);
	
	SET cUnix = create_unix_name(var_namajenisstrategy, 3);
	
	SELECT MAX(RIGHT(RTRIM(idjenisstrategy),jumlah_digit)) FROM jenisstrategy  
		WHERE LEFT(idjenisstrategy,3) = CONCAT(cUnix) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cUnix, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idkonversi`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idkonversi` () RETURNS CHAR(2) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(2);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(2);
	DECLARE jumlah_digit INT DEFAULT 2;
	
	
	SELECT MAX(RIGHT(RTRIM(idkonversi),jumlah_digit)) FROM konversi INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idpair`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idpair` (`var_namapair` VARCHAR(15)) RETURNS CHAR(5) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(2);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(2);
	DECLARE jumlah_digit INT DEFAULT 2;
	DECLARE cUnix CHAR(3);
	
	SET cUnix = create_unix_name(var_namapair, 3);
	
	SELECT MAX(RIGHT(RTRIM(idpair),jumlah_digit)) FROM pair  
		WHERE LEFT(idpair,3) = CONCAT(cUnix) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cUnix, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idpengguna`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idpengguna` (`var_namapengguna` VARCHAR(100), `_tgl` DATE) RETURNS CHAR(10) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(3);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(3);
	DECLARE jumlah_digit INT DEFAULT 3;
	declare cUnix char(3);
	DECLARE cTgl CHAR(7);
	
	SET cUnix = create_unix_name(var_namapengguna, 3);
	SET cTgl = concat(cUnix, DATE_FORMAT(_tgl, '%y%m')) ;
	
	SELECT MAX(RIGHT(RTRIM(idpengguna),jumlah_digit)) FROM pengguna  
		WHERE LEFT(idpengguna,7) = CONCAT(cTgl) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cTgl, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idtargetbalance`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idtargetbalance` (`var_tgl` DATE) RETURNS CHAR(10) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(4);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(4);
	DECLARE jumlah_digit INT DEFAULT 4;
	DECLARE cTgl CHAR(6);
	
	SET cTgl = DATE_FORMAT(var_tgl, '%y%m%d');
	
	SELECT MAX(RIGHT(RTRIM(idtargetbalance),jumlah_digit)) FROM targetbalance  
		WHERE LEFT(idtargetbalance,6) = CONCAT(cTgl) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cTgl, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idtopup`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idtopup` (`var_tgl` DATE, `var_idbalance` CHAR(7)) RETURNS CHAR(17) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(4);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(4);
	DECLARE jumlah_digit INT DEFAULT 4;
	DECLARE cTgl CHAR(13);
	
	SET cTgl = concat(var_idbalance, DATE_FORMAT(var_tgl, '%y%m'), 'TU') ;
	
	SELECT MAX(RIGHT(RTRIM(idtopup),jumlah_digit)) FROM topup  
		WHERE LEFT(idtopup,13) = CONCAT(cTgl) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cTgl, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idtrade`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idtrade` (`var_tgl` DATE, `var_idbalance` CHAR(7)) RETURNS CHAR(17) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(4);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(4);
	DECLARE jumlah_digit INT DEFAULT 4;
	DECLARE cTgl CHAR(13);
	
	SET cTgl = CONCAT(var_idbalance, DATE_FORMAT(var_tgl, '%y%m'), 'TR') ;
	
	SELECT MAX(RIGHT(RTRIM(idtrade),jumlah_digit)) FROM trade  
		WHERE LEFT(idtrade,13) = CONCAT(cTgl) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cTgl, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_idwithdraw`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_idwithdraw` (`var_tgl` DATE, `var_idbalance` CHAR(7)) RETURNS CHAR(17) CHARSET utf8mb4 BEGIN	
	DECLARE cNosekarang CHAR(4);
	DECLARE nlen INT;
	DECLARE nNoselanjutnya INT;
	DECLARE cNoselanjutnya CHAR(4);
	DECLARE jumlah_digit INT DEFAULT 4;
	DECLARE cTgl CHAR(13);
	
	SET cTgl = CONCAT(var_idbalance, DATE_FORMAT(var_tgl, '%y%m'), 'WD') ;
	
	SELECT MAX(RIGHT(RTRIM(idwithdraw),jumlah_digit)) FROM withdraw  
		WHERE LEFT(idwithdraw,13) = CONCAT(cTgl) INTO cNosekarang;	
	SET cNosekarang = IF(ISNULL(cNosekarang),0,cNosekarang);
	
	SET nNoselanjutnya = CONVERT(cNosekarang,INT)+1;
	SET cNoselanjutnya = RTRIM(CONVERT(nNoselanjutnya,CHAR));
	SET nlen = LENGTH(cNoselanjutnya);
	
	WHILE nlen+1 <= jumlah_digit DO		
		SET cNoselanjutnya= CONCAT('0',cNoselanjutnya);
		SET nlen=nlen+1;
	END WHILE;	
	
	RETURN CONCAT(cTgl, cNoselanjutnya);
    END$$

DROP FUNCTION IF EXISTS `create_unix_name`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `create_unix_name` (`var_string` VARCHAR(255), `var_length` INT(11)) RETURNS CHAR(10) CHARSET latin1 BEGIN
	DECLARE var_return CHAR(10);
	DECLARE var_char CHAR(1);
	DECLARE posisi_space INT(11);	
	DECLARE posisi_space_temp INT(11);	
	DECLARE i INT(11);
	
	SET posisi_space = 0;
	SET posisi_space_temp = 0;
	SET i = 0;
	IF LEFT(var_string,2) IN ('PT', 'CV', 'UD') THEN
		SET var_return = '';
	ELSE
		SET var_return = LEFT(var_string,1);
		SET var_length = var_length-1;
	END IF;
	
	LoopChar: WHILE i < var_length DO
			
			SET posisi_space_temp = LOCATE(' ' ,var_string, posisi_space+1);
			IF posisi_space_temp = 0 THEN 
				-- leave LoopChar;
				SET var_char = SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ', FLOOR(RAND()*(25-1)+1), 1);
				SET var_return = CONCAT(var_return, var_char);
			ELSE
				SET posisi_space = posisi_space_temp;
				SET var_char = SUBSTRING(var_string, posisi_space+1, 1);
				IF TRIM(var_char) <> '' THEN
					SET var_return = CONCAT(var_return, var_char);
				ELSE
					SET var_char = SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ', FLOOR(RAND()*(25-1)+1), 1);
					SET var_return = CONCAT(var_return, var_char);
				END IF;
			END IF;
			SET i = i +1;
		END WHILE LoopChar;	
		
		
	RETURN UPPER(var_return);
    END$$

DROP FUNCTION IF EXISTS `get_lasttrade_date`$$
CREATE DEFINER=`serd5225`@`localhost` FUNCTION `get_lasttrade_date` (`var_idbalance` CHAR(7)) RETURNS DATE BEGIN
	declare tglTrade date;
	declare jlhrow int(11);
		
	select count(*) into jlhrow FROM trade WHERE idbalance=var_idbalance;
	if jlhrow>0 then
		select tglentrytrade into tglTrade from trade where idbalance=var_idbalance order by tglentrytrade limit 1;
	else
		set tglTrade = null;
	end if;
	
	return tglTrade;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `balancetrading`
--

DROP TABLE IF EXISTS `balancetrading`;
CREATE TABLE IF NOT EXISTS `balancetrading` (
  `idbalance` char(7) NOT NULL,
  `namabalance` varchar(50) DEFAULT NULL,
  `idbroker` char(5) DEFAULT NULL,
  `idpengguna` char(10) DEFAULT NULL,
  `idjenisasset` char(2) DEFAULT NULL,
  `jenisbalance` enum('Trading','Investasi') DEFAULT NULL,
  `aturantrading` text DEFAULT NULL,
  `idcurrency` char(3) DEFAULT NULL,
  `topup` decimal(18,2) DEFAULT NULL,
  `withdraw` decimal(18,2) DEFAULT NULL,
  `lostprofit` decimal(18,2) DEFAULT NULL,
  `maxlose` decimal(5,2) DEFAULT NULL COMMENT 'maksimal kekalahan dalam satu trading (persen)',
  `maxprofit` decimal(5,2) DEFAULT NULL COMMENT 'maksimal kemenangan dalam satu trading (persen)',
  `jumlahbalance` decimal(18,2) DEFAULT NULL,
  `tglbukaakun` date DEFAULT NULL,
  `tgltutupakun` date DEFAULT NULL,
  `statusaktif` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  `tglupdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idbalance`),
  KEY `idpengguna` (`idpengguna`),
  KEY `idcurrency` (`idcurrency`),
  KEY `idjenisasset` (`idjenisasset`),
  KEY `idbroker` (`idbroker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balancetrading`
--

INSERT INTO `balancetrading` (`idbalance`, `namabalance`, `idbroker`, `idpengguna`, `idjenisasset`, `jenisbalance`, `aturantrading`, `idcurrency`, `topup`, `withdraw`, `lostprofit`, `maxlose`, `maxprofit`, `jumlahbalance`, `tglbukaakun`, `tgltutupakun`, `statusaktif`, `tglinsert`, `tglupdate`) VALUES
('2207001', 'HotForex Account', 'HCV01', '2MK0000001', '03', 'Trading', '-', 'U01', '514.28', '100.00', '0.00', '10.00', '0.00', '539.53', '2022-07-05', NULL, 'Aktif', '2022-07-05 00:05:34', '2022-07-07 10:37:07'),
('2207002', 'Binance', 'BRS01', '2MK0000001', '02', 'Investasi', '-', 'U01', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2022-07-05', NULL, 'Aktif', '2022-07-05 00:06:15', '2022-07-06 19:05:18'),
('2207003', 'Ajaib Saham', 'AKS01', '2MK0000001', '01', 'Investasi', '-', 'C02', '0.00', '0.00', '0.00', '0.00', '0.00', '1739000.00', '2022-07-05', NULL, 'Aktif', '2022-07-05 00:07:18', '2022-07-07 10:37:21');

-- --------------------------------------------------------

--
-- Table structure for table `broker`
--

DROP TABLE IF EXISTS `broker`;
CREATE TABLE IF NOT EXISTS `broker` (
  `idbroker` char(5) NOT NULL,
  `namabroker` varchar(100) DEFAULT NULL,
  `logobroker` varchar(255) DEFAULT NULL,
  `statusaktif` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  PRIMARY KEY (`idbroker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `broker`
--

INSERT INTO `broker` (`idbroker`, `namabroker`, `logobroker`, `statusaktif`) VALUES
('AKS01', 'Ajaib', '025788100_1639671178-Ajaib.jpg', 'Aktif'),
('BRS01', 'Binance', 'png-transparent-binance-macos-bigsur-icon-thumbnail.png', 'Aktif'),
('HCV01', 'HotForex', 'hf.png', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `idcurrency` char(3) NOT NULL,
  `singkatan` varchar(15) DEFAULT NULL,
  `namacurrency` varchar(25) DEFAULT NULL,
  `statusaktif` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  PRIMARY KEY (`idcurrency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`idcurrency`, `singkatan`, `namacurrency`, `statusaktif`) VALUES
('C02', 'Rp', 'Rupiah', 'Aktif'),
('U01', '$', 'US Dollar', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `jenisasset`
--

DROP TABLE IF EXISTS `jenisasset`;
CREATE TABLE IF NOT EXISTS `jenisasset` (
  `idjenisasset` char(2) NOT NULL,
  `namajenisasset` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`idjenisasset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenisasset`
--

INSERT INTO `jenisasset` (`idjenisasset`, `namajenisasset`) VALUES
('01', 'Saham & Reksadana'),
('02', 'Cryptocurrency'),
('03', 'Forex');

-- --------------------------------------------------------

--
-- Table structure for table `jenisstrategy`
--

DROP TABLE IF EXISTS `jenisstrategy`;
CREATE TABLE IF NOT EXISTS `jenisstrategy` (
  `idjenisstrategy` char(5) NOT NULL,
  `namajenisstrategy` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `statusaktif` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  PRIMARY KEY (`idjenisstrategy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenisstrategy`
--

INSERT INTO `jenisstrategy` (`idjenisstrategy`, `namajenisstrategy`, `deskripsi`, `statusaktif`) VALUES
('BRL01', 'Bearish Rectangle', 'Continious Pattern', 'Aktif'),
('DBJ01', 'Double Bottom', 'Reversal Strategy', 'Aktif'),
('DTI01', 'Double Top', 'Reversal Strategy', 'Aktif'),
('HAS01', 'Head And Shoulders', 'Reversal strategy', 'Aktif'),
('ICP01', 'Investasi', 'Ini tidak memiliki strategi, hanya untuk investasi saja', 'Aktif'),
('RWM01', 'Rising Wedge', 'Countiniuos Pattern', 'Aktif'),
('SOO01', 'Scalping', 'Scalping Yang Result nya Tidak sampai lebih dari 1 jam', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `konversi`
--

DROP TABLE IF EXISTS `konversi`;
CREATE TABLE IF NOT EXISTS `konversi` (
  `idkonversi` char(2) NOT NULL,
  `currencyutama` char(3) DEFAULT NULL,
  `currencypasangan` char(3) DEFAULT NULL,
  `jumlahkonversi` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`idkonversi`),
  KEY `currencyutama` (`currencyutama`),
  KEY `currencypasangan` (`currencypasangan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `konversi`
--

INSERT INTO `konversi` (`idkonversi`, `currencyutama`, `currencypasangan`, `jumlahkonversi`) VALUES
('01', 'U01', 'C02', '14900.00');

-- --------------------------------------------------------

--
-- Table structure for table `pair`
--

DROP TABLE IF EXISTS `pair`;
CREATE TABLE IF NOT EXISTS `pair` (
  `idpair` char(5) NOT NULL,
  `namapair` varchar(15) DEFAULT NULL,
  `idjenisasset` char(2) DEFAULT NULL,
  `statusaktif` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  `pricenow` decimal(10,6) NOT NULL DEFAULT 0.000000,
  `tglupdate` datetime DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  PRIMARY KEY (`idpair`),
  KEY `idjenisasset` (`idjenisasset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pair`
--

INSERT INTO `pair` (`idpair`, `namapair`, `idjenisasset`, `statusaktif`, `pricenow`, `tglupdate`, `tglinsert`) VALUES
('AKW01', 'AUDUSD', '03', 'Aktif', '0.000000', '2022-07-25 23:26:44', '2022-07-25 23:26:44'),
('BDP01', 'BBCA', '01', 'Aktif', '7800.000000', '2022-07-06 14:34:17', '2022-07-06 14:34:17'),
('BKC01', 'BBRI', '01', 'Aktif', '4080.000000', '2022-07-06 14:37:50', '2022-07-06 14:37:50'),
('CQA01', 'CHFJPY', '03', 'Aktif', '0.000000', '2022-07-06 22:59:36', '2022-07-06 22:59:36'),
('EKU01', 'EURUSD', '03', 'Aktif', '0.000000', '2022-07-06 13:45:55', '2022-07-06 13:45:55'),
('ELE01', 'EURJPY', '03', 'Aktif', '0.000000', '2022-07-06 13:45:52', '2022-07-06 13:45:52'),
('EUC01', 'EURAUD', '03', 'Aktif', '0.000000', '2022-07-23 15:13:47', '2022-07-23 15:13:47'),
('EXR01', 'EURGBP', '03', 'Aktif', '0.000000', '2022-07-13 14:02:29', '2022-07-13 14:02:29'),
('GKO01', 'GBPUSD', '03', 'Aktif', '0.000000', '2022-07-23 16:30:55', '2022-07-23 16:30:55'),
('GLB01', 'GOTO', '01', 'Aktif', '324.000000', '2022-07-06 15:30:01', '2022-07-06 15:30:01'),
('GSP01', 'GBPJPY', '03', 'Aktif', '0.000000', '2022-07-08 23:25:07', '2022-07-08 23:25:07'),
('GTJ01', 'GBPCAD', '03', 'Aktif', '0.000000', '2022-07-06 16:12:33', '2022-07-06 16:12:33'),
('UAE01', 'USDJPY', '03', 'Aktif', '0.000000', '2022-07-23 15:33:32', '2022-07-23 15:33:32'),
('UAQ01', 'UNVR', '01', 'Aktif', '4780.000000', '2022-07-06 15:34:20', '2022-07-06 15:34:20'),
('UFS01', 'USDCAD', '03', 'Aktif', '0.000000', '2022-07-07 20:58:47', '2022-07-07 20:58:47'),
('UOM01', 'USDCHF', '03', 'Aktif', '0.000000', '2022-07-23 15:26:11', '2022-07-23 15:26:11'),
('XVO01', 'XAUUSD', '03', 'Aktif', '0.000000', '2022-07-08 18:23:26', '2022-07-08 18:23:26');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE IF NOT EXISTS `pengguna` (
  `idpengguna` char(10) NOT NULL,
  `namapengguna` varchar(50) DEFAULT NULL,
  `jk` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `nohp` char(16) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `statusaktif` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  `akseslevel` enum('Admin','Trader') DEFAULT NULL,
  PRIMARY KEY (`idpengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`idpengguna`, `namapengguna`, `jk`, `nohp`, `email`, `username`, `password`, `tglinsert`, `lastlogin`, `foto`, `statusaktif`, `akseslevel`) VALUES
('2MK0000001', 'Toni Simanjuntak', 'Laki-laki', '08120000', 'toni@gmail.com', 'toni', 'aefe34008e63f1eb205dc4c4b8322253', '2022-07-23 20:28:05', NULL, 'photo-1633332755192-727a05c4013d.jpg', 'Aktif', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `targetbalance`
--

DROP TABLE IF EXISTS `targetbalance`;
CREATE TABLE IF NOT EXISTS `targetbalance` (
  `idtargetbalance` char(10) NOT NULL,
  `tglmulai` date DEFAULT NULL,
  `tglselesai` date DEFAULT NULL,
  `idbalance` char(7) DEFAULT NULL,
  `startingbalance` decimal(18,2) DEFAULT NULL,
  `endingbalance` decimal(18,2) DEFAULT NULL,
  `idpengguna` char(10) DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  `tglupdate` datetime DEFAULT NULL,
  `statustarget` enum('Masih Berjalan','Selesai') DEFAULT NULL,
  `targettrading` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`idtargetbalance`),
  KEY `idbalance` (`idbalance`),
  KEY `idpengguna` (`idpengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `targetbalance`
--

INSERT INTO `targetbalance` (`idtargetbalance`, `tglmulai`, `tglselesai`, `idbalance`, `startingbalance`, `endingbalance`, `idpengguna`, `tglinsert`, `tglupdate`, `statustarget`, `targettrading`) VALUES
('2207250001', '2022-07-25', NULL, '2207001', '610.63', '0.00', '2MK0000001', '2022-07-25 01:27:01', '2022-07-26 00:28:40', 'Masih Berjalan', '730.00');

-- --------------------------------------------------------

--
-- Table structure for table `topup`
--

DROP TABLE IF EXISTS `topup`;
CREATE TABLE IF NOT EXISTS `topup` (
  `idtopup` char(17) NOT NULL,
  `tgltopup` datetime DEFAULT NULL,
  `idbalance` char(7) DEFAULT NULL,
  `jumlahtopup` decimal(18,2) DEFAULT NULL,
  `idpengguna` char(10) DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  `tglupdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idtopup`),
  KEY `idpengguna` (`idpengguna`),
  KEY `idbalance` (`idbalance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topup`
--

INSERT INTO `topup` (`idtopup`, `tgltopup`, `idbalance`, `jumlahtopup`, `idpengguna`, `tglinsert`, `tglupdate`) VALUES
('22070012207TU0001', '2022-07-05 00:00:00', '2207001', '500.00', '2MK0000001', '2022-07-05 00:08:20', '2022-07-05 00:08:20'),
('22070012207TU0002', '2022-07-21 00:00:00', '2207001', '6.00', '2MK0000001', '2022-07-23 16:41:29', '2022-07-23 16:41:29'),
('22070012207TU0003', '2022-07-26 00:00:00', '2207001', '8.28', '2MK0000001', '2022-07-26 12:38:44', '2022-07-26 12:38:44');

--
-- Triggers `topup`
--
DROP TRIGGER IF EXISTS `topup_delete`;
DELIMITER $$
CREATE TRIGGER `topup_delete` BEFORE DELETE ON `topup` FOR EACH ROW BEGIN
	UPDATE balancetrading SET balancetrading.`topup`= balancetrading.`topup` - old.jumlahtopup,
		balancetrading.`jumlahbalance` = balancetrading.`jumlahbalance` - old.jumlahtopup
		WHERE idbalance=old.idbalance; 
    END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `topup_insert`;
DELIMITER $$
CREATE TRIGGER `topup_insert` AFTER INSERT ON `topup` FOR EACH ROW BEGIN
	update balancetrading set balancetrading.`topup`= balancetrading.`topup` + new.jumlahtopup,
		balancetrading.`jumlahbalance` = balancetrading.`jumlahbalance` + new.jumlahtopup
		where idbalance=new.idbalance;
	
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `trade`
--

DROP TABLE IF EXISTS `trade`;
CREATE TABLE IF NOT EXISTS `trade` (
  `idtrade` char(17) NOT NULL,
  `tglentrytrade` datetime DEFAULT NULL,
  `tglexittrade` datetime DEFAULT NULL,
  `idbalance` char(7) DEFAULT NULL,
  `idpair` char(5) DEFAULT NULL,
  `qty` decimal(18,2) DEFAULT NULL,
  `entryprice` decimal(10,6) DEFAULT NULL,
  `exitprice` decimal(10,6) DEFAULT NULL,
  `lostprofit` decimal(18,2) DEFAULT NULL,
  `idpengguna` char(10) DEFAULT NULL,
  `istradeclose` enum('Ya','Tidak') DEFAULT NULL,
  `idjenisstrategy` char(5) DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  `tglupdate` datetime DEFAULT NULL,
  `fotoentry` varchar(255) DEFAULT NULL,
  `fotoexit` varchar(255) DEFAULT NULL,
  `hasiltrade` enum('Profit','Lost') DEFAULT NULL,
  PRIMARY KEY (`idtrade`),
  KEY `idpengguna` (`idpengguna`),
  KEY `idpair` (`idpair`),
  KEY `idbalance` (`idbalance`),
  KEY `idjenisstrategy` (`idjenisstrategy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trade`
--

INSERT INTO `trade` (`idtrade`, `tglentrytrade`, `tglexittrade`, `idbalance`, `idpair`, `qty`, `entryprice`, `exitprice`, `lostprofit`, `idpengguna`, `istradeclose`, `idjenisstrategy`, `tglinsert`, `tglupdate`, `fotoentry`, `fotoexit`, `hasiltrade`) VALUES
('22070012207TR0002', '2022-07-06 22:59:42', '2022-07-13 19:46:19', '2207001', 'CQA01', '0.01', '139.629000', '140.552000', '-16.26', '2MK0000001', 'Ya', 'HAS01', '2022-07-06 23:00:35', '2022-07-13 19:48:39', 'Capture.JPG', 'Capture6.PNG', 'Lost'),
('22070012207TR0004', '2022-07-07 20:58:53', '2022-07-08 15:46:10', '2207001', 'UFS01', '0.02', '0.298240', '1.303080', '-7.89', '2MK0000001', 'Ya', 'DTI01', '2022-07-07 21:00:17', '2022-07-08 22:46:31', 'Capture2.JPG', 'Capture.JPG', 'Lost'),
('22070012207TR0006', '2022-07-08 18:24:05', '2022-07-08 19:18:42', '2207001', 'XVO01', '0.01', '1739.333333', '1744.280000', '-4.95', '2MK0000001', 'Ya', 'RWM01', '2022-07-08 18:24:51', '2022-07-08 19:19:19', 'Capture2.PNG', 'Capture4.PNG', 'Lost'),
('22070012207TR0007', '2022-07-08 23:25:11', '2022-07-11 04:52:26', '2207001', 'GSP01', '0.01', '163.694000', '164.162000', '-3.42', '2MK0000001', 'Ya', 'DTI01', '2022-07-08 23:26:26', '2022-07-11 10:53:55', 'Capture4.JPG', 'Capture5.PNG', 'Lost'),
('22070012207TR0008', '2022-07-08 19:16:37', '2022-07-11 22:19:11', '2207001', 'EKU01', '0.02', '1.007280', '1.004880', '-4.80', '2MK0000001', 'Ya', 'DBJ01', '2022-07-12 21:19:08', '2022-07-12 21:20:01', 'Capture5.JPG', 'Capture1.JPG', 'Lost'),
('22070012207TR0009', '2022-07-12 21:20:01', '2022-07-13 19:49:51', '2207001', 'ELE01', '0.01', '137.286000', '137.873000', '4.28', '2MK0000001', 'Ya', 'DBJ01', '2022-07-12 21:27:22', '2022-07-13 19:51:06', 'Capture6.JPG', 'Capture7.PNG', 'Profit'),
('22070012207TR0010', '2022-07-13 14:02:35', '2022-07-13 19:53:37', '2207001', 'EXR01', '0.02', '0.841940', '0.845040', '-8.00', '2MK0000001', 'Ya', 'BRL01', '2022-07-13 14:04:21', '2022-07-13 19:54:46', 'Capture3.PNG', 'Capture8.PNG', 'Lost'),
('22070012207TR0011', '2022-07-14 15:14:45', '2022-07-14 15:15:11', '2207001', 'EUC01', '0.05', '0.000000', '0.000000', '-5.15', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:15:09', '2022-07-23 15:15:34', '', '', 'Lost'),
('22070012207TR0012', '2022-07-15 15:15:34', '2022-07-15 15:16:20', '2207001', 'XVO01', '0.11', '0.000000', '0.000000', '20.23', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:16:14', '2022-07-23 15:17:05', '', '', 'Profit'),
('22070012207TR0013', '2022-07-15 15:17:05', '2022-07-15 15:18:21', '2207001', 'ELE01', '0.39', '0.000000', '0.000000', '19.21', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:18:18', '2022-07-23 15:20:40', '', '', 'Profit'),
('22070012207TR0014', '2022-07-17 15:21:23', '2022-07-17 15:22:04', '2207001', 'XVO01', '0.04', '0.000000', '0.000000', '15.50', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:21:56', '2022-07-23 15:22:43', '', '', 'Profit'),
('22070012207TR0015', '2022-07-18 15:22:43', '2022-07-18 15:23:52', '2207001', 'ELE01', '0.10', '0.000000', '0.000000', '13.79', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:23:32', '2022-07-23 15:25:00', '', '', 'Profit'),
('22070012207TR0016', '2022-07-18 15:26:15', '2022-07-18 15:27:47', '2207001', 'UOM01', '0.22', '0.000000', '0.000000', '-22.46', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:26:45', '2022-07-23 15:28:02', '', '', 'Lost'),
('22070012207TR0017', '2022-07-18 15:28:02', '2022-07-18 15:28:51', '2207001', 'XVO01', '0.06', '0.000000', '0.000000', '-17.49', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:28:49', '2022-07-23 15:29:43', '', '', 'Lost'),
('22070012207TR0018', '2022-07-19 15:31:42', '2022-07-19 15:32:47', '2207001', 'XVO01', '0.02', '0.000000', '0.000000', '-1.02', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:32:45', '2022-07-23 15:32:57', '', '', 'Lost'),
('22070012207TR0019', '2022-07-19 15:33:38', '2022-07-19 15:34:09', '2207001', 'UAE01', '0.16', '0.000000', '0.000000', '4.56', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:34:07', '2022-07-23 15:35:38', '', '', 'Profit'),
('22070012207TR0020', '2022-07-19 15:35:38', '2022-07-23 15:50:02', '2207001', 'ELE01', '0.05', '0.000000', '0.000000', '-4.82', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:36:23', '2022-07-23 15:50:16', '', '', 'Lost'),
('22070012207TR0021', '2022-07-19 15:36:47', '2022-07-23 15:52:44', '2207001', 'EKU01', '0.13', '0.000000', '0.000000', '-17.56', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:37:29', '2022-07-23 15:52:54', '', '', 'Lost'),
('22070012207TR0022', '2022-07-20 15:52:54', '2022-07-20 15:53:51', '2207001', 'UOM01', '1.00', '0.000000', '0.000000', '1.55', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:53:47', '2022-07-23 15:54:02', '', '', 'Profit'),
('22070012207TR0023', '2022-07-20 15:54:02', '2022-07-20 15:54:35', '2207001', 'EXR01', '0.05', '0.000000', '0.000000', '5.70', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:54:26', '2022-07-23 15:54:53', '', '', 'Profit'),
('22070012207TR0024', '2022-07-20 15:55:31', '2022-07-20 15:57:52', '2207001', 'XVO01', '0.37', '0.000000', '0.000000', '19.35', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 15:56:35', '2022-07-23 15:58:10', '', '', 'Profit'),
('22070012207TR0025', '2022-07-20 16:09:34', '2022-07-20 16:14:20', '2207001', 'EKU01', '0.31', '0.000000', '0.000000', '4.02', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:10:38', '2022-07-23 16:15:58', '', '', 'Profit'),
('22070012207TR0026', '2022-07-20 16:19:00', '2022-07-20 20:33:26', '2207001', 'XVO01', '0.50', '0.000000', '0.000000', '10.83', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:19:15', '2022-07-23 20:33:35', '', '', 'Profit'),
('22070012207TR0027', '2022-07-20 16:22:43', '2022-07-20 16:24:17', '2207001', 'UFS01', '0.23', '0.000000', '0.000000', '-10.74', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:23:21', '2022-07-23 16:24:29', '', '', 'Lost'),
('22070012207TR0028', '2022-07-21 16:24:29', '2022-07-21 20:35:03', '2207001', 'ELE01', '0.57', '0.000000', '0.000000', '16.80', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:26:14', '2022-07-23 20:35:10', '', '', 'Profit'),
('22070012207TR0029', '2022-07-21 16:30:59', '2022-07-21 16:32:34', '2207001', 'GKO01', '0.14', '0.000000', '0.000000', '-5.36', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:31:25', '2022-07-23 16:32:46', '', '', 'Lost'),
('22070012207TR0030', '2022-07-21 16:32:46', '2022-07-21 16:35:18', '2207001', 'XVO01', '0.45', '0.000000', '0.000000', '98.92', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:33:40', '2022-07-23 16:35:36', '', '', 'Profit'),
('22070012207TR0031', '2022-07-22 16:36:17', '2022-07-22 16:37:48', '2207001', 'ELE01', '0.34', '0.000000', '0.000000', '99.81', '2MK0000001', 'Ya', 'SOO01', '2022-07-23 16:37:10', '2022-07-23 16:37:59', '', '', 'Profit'),
('22070012207TR0032', '2022-07-25 15:23:27', '2022-07-25 16:26:01', '2207001', 'XVO01', '0.08', '0.000000', '0.000000', '-20.06', '2MK0000001', 'Ya', 'SOO01', '2022-07-25 23:24:56', '2022-07-25 23:26:16', '', '', 'Lost'),
('22070012207TR0033', '2022-07-25 15:26:50', '2022-07-25 15:27:57', '2207001', 'AKW01', '1.14', '0.000000', '0.000000', '-160.32', '2MK0000001', 'Ya', 'SOO01', '2022-07-25 23:27:54', '2022-07-25 23:30:50', '', '', 'Lost'),
('22070012207TR0034', '2022-07-25 16:31:03', '2022-07-25 16:32:11', '2207001', 'ELE01', '0.90', '0.000000', '0.000000', '-71.87', '2MK0000001', 'Ya', 'SOO01', '2022-07-25 23:32:08', '2022-07-25 23:34:14', '', '', 'Lost'),
('22070012207TR0035', '2022-07-25 16:35:08', '2022-07-25 16:36:17', '2207001', 'EKU01', '0.68', '0.000000', '0.000000', '112.41', '2MK0000001', 'Ya', 'SOO01', '2022-07-25 23:36:15', '2022-07-25 23:39:02', '', '', 'Profit'),
('22070012207TR0036', '2022-07-25 16:39:02', '2022-07-25 16:39:52', '2207001', 'ELE01', '0.29', '0.000000', '0.000000', '53.70', '2MK0000001', 'Ya', 'SOO01', '2022-07-25 23:39:50', '2022-07-25 23:41:13', '', '', 'Profit'),
('22070012207TR0037', '2022-07-26 12:37:02', '2022-07-26 12:37:35', '2207001', 'XVO01', '0.07', '0.000000', '0.000000', '6.76', '2MK0000001', 'Ya', 'SOO01', '2022-07-26 12:37:33', '2022-07-26 12:37:54', '', '', 'Profit'),
('22070032207TR0001', '2022-04-11 14:36:35', NULL, '2207003', 'BKC01', '100.00', '4600.000000', '0.000000', '0.00', '2MK0000001', 'Tidak', 'ICP01', '2022-07-06 14:36:55', '2022-07-06 14:36:55', '', NULL, NULL),
('22070032207TR0002', '2022-04-27 14:36:55', NULL, '2207003', 'BKC01', '100.00', '4870.000000', '0.000000', '0.00', '2MK0000001', 'Tidak', 'ICP01', '2022-07-06 14:37:27', '2022-07-06 14:37:27', '', NULL, NULL),
('22070032207TR0003', '2022-07-06 15:33:28', NULL, '2207003', 'GLB01', '1000.00', '306.000000', '0.000000', '0.00', '2MK0000001', 'Tidak', 'ICP01', '2022-07-06 15:33:41', '2022-07-06 15:33:41', '', NULL, NULL),
('22070032207TR0004', '2022-07-06 15:34:59', NULL, '2207003', 'UAQ01', '100.00', '4860.000000', '0.000000', '0.00', '2MK0000001', 'Tidak', 'ICP01', '2022-07-06 15:35:27', '2022-07-06 15:35:27', '', NULL, NULL);

--
-- Triggers `trade`
--
DROP TRIGGER IF EXISTS `trade_delete`;
DELIMITER $$
CREATE TRIGGER `trade_delete` BEFORE DELETE ON `trade` FOR EACH ROW BEGIN
	DECLARE var_idjenisasset CHAR(2);
	SELECT idjenisasset INTO var_idjenisasset FROM balancetrading WHERE idbalance=old.idbalance;
	
	IF old.lostprofit <> 0 AND var_idjenisasset='03' THEN
		UPDATE balancetrading SET jumlahbalance = jumlahbalance - old.lostprofit
			WHERE balancetrading.idbalance = old.idbalance;
	END IF;
	
	IF var_idjenisasset<>'03' THEN
		IF old.istradeclose='Tidak' THEN
			UPDATE balancetrading SET jumlahbalance = jumlahbalance - (old.qty*old.entryprice)
				WHERE balancetrading.idbalance = old.idbalance;
		END IF;
	END IF;
	
    END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trade_insert`;
DELIMITER $$
CREATE TRIGGER `trade_insert` AFTER INSERT ON `trade` FOR EACH ROW BEGIN
	DECLARE var_idjenisasset CHAR(2);
	SELECT idjenisasset INTO var_idjenisasset FROM balancetrading WHERE idbalance=new.idbalance;
	
	IF var_idjenisasset<>'03' THEN
		UPDATE balancetrading SET jumlahbalance = jumlahbalance + (new.qty*new.entryprice)
			WHERE balancetrading.idbalance = new.idbalance;
	END IF;
	
    END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trade_update`;
DELIMITER $$
CREATE TRIGGER `trade_update` AFTER UPDATE ON `trade` FOR EACH ROW BEGIN
	DECLARE var_idjenisasset CHAR(2);
	
	SELECT idjenisasset INTO var_idjenisasset
			FROM balancetrading WHERE idbalance=new.idbalance;
	
	IF var_idjenisasset='03' THEN
		UPDATE balancetrading SET jumlahbalance = jumlahbalance - old.lostprofit + new.lostprofit
				WHERE balancetrading.idbalance = old.idbalance;
	END IF;
	
	IF var_idjenisasset<>'03' THEN
		IF old.istradeclose='Tidak' THEN
			UPDATE balancetrading SET jumlahbalance = jumlahbalance - (old.qty*old.entryprice)
				WHERE balancetrading.idbalance = old.idbalance;
		END IF;
	END IF;
	
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_balancetrading`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_balancetrading`;
CREATE TABLE IF NOT EXISTS `v_balancetrading` (
`idbalance` char(7)
,`namabalance` varchar(50)
,`idbroker` char(5)
,`idpengguna` char(10)
,`idjenisasset` char(2)
,`jenisbalance` enum('Trading','Investasi')
,`aturantrading` text
,`idcurrency` char(3)
,`topup` decimal(18,2)
,`withdraw` decimal(18,2)
,`lostprofit` decimal(18,2)
,`maxlose` decimal(5,2)
,`maxprofit` decimal(5,2)
,`jumlahbalance` decimal(18,2)
,`tglbukaakun` date
,`tgltutupakun` date
,`statusaktif` enum('Aktif','Tidak Aktif')
,`namabroker` varchar(100)
,`logobroker` varchar(255)
,`namacurrency` varchar(25)
,`singkatan` varchar(15)
,`namajenisasset` varchar(25)
,`namapengguna` varchar(50)
,`tgltradeterakhir` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_konversi`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_konversi`;
CREATE TABLE IF NOT EXISTS `v_konversi` (
`idkonversi` char(2)
,`currencyutama` char(3)
,`singkatancurrencyutama` varchar(15)
,`namacurrencyutama` varchar(25)
,`currencypasangan` char(3)
,`singkatancurrencypasangan` varchar(15)
,`namacurrencypasangan` varchar(25)
,`jumlahkonversi` decimal(18,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_pair`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_pair`;
CREATE TABLE IF NOT EXISTS `v_pair` (
`idpair` char(5)
,`namapair` varchar(15)
,`idjenisasset` char(2)
,`namajenisasset` varchar(25)
,`statusaktif` enum('Aktif','Tidak Aktif')
,`pricenow` decimal(10,6)
,`tglinsert` datetime
,`tglupdate` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_targetbalance`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_targetbalance`;
CREATE TABLE IF NOT EXISTS `v_targetbalance` (
`idtargetbalance` char(10)
,`tglmulai` date
,`tglselesai` date
,`idbalance` char(7)
,`startingbalance` decimal(18,2)
,`endingbalance` decimal(18,2)
,`idpengguna` char(10)
,`tglinsert` datetime
,`tglupdate` datetime
,`statustarget` enum('Masih Berjalan','Selesai')
,`targettrading` decimal(18,2)
,`namabalance` varchar(50)
,`idbroker` char(5)
,`idjenisasset` char(2)
,`jenisbalance` enum('Trading','Investasi')
,`namabroker` varchar(100)
,`logobroker` varchar(255)
,`namacurrency` varchar(25)
,`namajenisasset` varchar(25)
,`singkatan` varchar(15)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_topup`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_topup`;
CREATE TABLE IF NOT EXISTS `v_topup` (
`idtopup` char(17)
,`tgltopup` datetime
,`idbalance` char(7)
,`jumlahtopup` decimal(18,2)
,`idpengguna` char(10)
,`tglinsert` datetime
,`tglupdate` datetime
,`namabalance` varchar(50)
,`idbroker` char(5)
,`namabroker` varchar(100)
,`logobroker` varchar(255)
,`idcurrency` char(3)
,`singkatan` varchar(15)
,`namacurrency` varchar(25)
,`namapengguna` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_trade`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_trade`;
CREATE TABLE IF NOT EXISTS `v_trade` (
`idtrade` char(17)
,`tglentrytrade` datetime
,`tglexittrade` datetime
,`idbalance` char(7)
,`idpair` char(5)
,`qty` decimal(18,2)
,`entryprice` decimal(10,6)
,`exitprice` decimal(10,6)
,`lostprofit` decimal(18,2)
,`idpengguna` char(10)
,`istradeclose` enum('Ya','Tidak')
,`idjenisstrategy` char(5)
,`tglinsert` datetime
,`tglupdate` datetime
,`fotoentry` varchar(255)
,`fotoexit` varchar(255)
,`hasiltrade` enum('Profit','Lost')
,`namabalance` varchar(50)
,`idbroker` char(5)
,`namabroker` varchar(100)
,`logobroker` varchar(255)
,`namapair` varchar(15)
,`idjenisasset` char(2)
,`pricenow` decimal(10,6)
,`namapengguna` varchar(50)
,`namajenisstrategy` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_withdraw`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_withdraw`;
CREATE TABLE IF NOT EXISTS `v_withdraw` (
`idwithdraw` char(17)
,`tglwithdraw` datetime
,`idbalance` char(7)
,`jumlahwithdraw` decimal(18,2)
,`idpengguna` char(10)
,`tglinsert` datetime
,`tglupdate` datetime
,`namapengguna` varchar(50)
,`namabalance` varchar(50)
,`idbroker` char(5)
,`namabroker` varchar(100)
,`logobroker` varchar(255)
,`idcurrency` char(3)
,`singkatan` varchar(15)
,`namacurrency` varchar(25)
);

-- --------------------------------------------------------

--
-- Table structure for table `withdraw`
--

DROP TABLE IF EXISTS `withdraw`;
CREATE TABLE IF NOT EXISTS `withdraw` (
  `idwithdraw` char(17) NOT NULL,
  `tglwithdraw` datetime DEFAULT NULL,
  `idbalance` char(7) DEFAULT NULL,
  `jumlahwithdraw` decimal(18,2) DEFAULT NULL,
  `idpengguna` char(10) DEFAULT NULL,
  `tglinsert` datetime DEFAULT NULL,
  `tglupdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idwithdraw`),
  KEY `idpengguna` (`idpengguna`),
  KEY `idbalance` (`idbalance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `withdraw`
--

INSERT INTO `withdraw` (`idwithdraw`, `tglwithdraw`, `idbalance`, `jumlahwithdraw`, `idpengguna`, `tglinsert`, `tglupdate`) VALUES
('22070012207WD0001', '2022-07-21 00:00:00', '2207001', '100.00', '2MK0000001', '2022-07-23 16:42:24', '2022-07-23 16:42:24');

--
-- Triggers `withdraw`
--
DROP TRIGGER IF EXISTS `withdraw_delete`;
DELIMITER $$
CREATE TRIGGER `withdraw_delete` BEFORE DELETE ON `withdraw` FOR EACH ROW BEGIN
	UPDATE balancetrading SET balancetrading.`withdraw`= balancetrading.`withdraw` - old.jumlahwithdraw,
		balancetrading.`jumlahbalance` = balancetrading.`jumlahbalance` + old.jumlahwithdraw
		WHERE idbalance=old.idbalance; 
    END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `withdraw_insert`;
DELIMITER $$
CREATE TRIGGER `withdraw_insert` AFTER INSERT ON `withdraw` FOR EACH ROW BEGIN
	UPDATE balancetrading SET balancetrading.`withdraw`= balancetrading.`withdraw` + new.jumlahwithdraw,
		balancetrading.`jumlahbalance` = balancetrading.`jumlahbalance` - new.jumlahwithdraw
		WHERE idbalance=new.idbalance; 
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `v_balancetrading`
--
DROP TABLE IF EXISTS `v_balancetrading`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_balancetrading`  AS SELECT `balancetrading`.`idbalance` AS `idbalance`, `balancetrading`.`namabalance` AS `namabalance`, `balancetrading`.`idbroker` AS `idbroker`, `balancetrading`.`idpengguna` AS `idpengguna`, `balancetrading`.`idjenisasset` AS `idjenisasset`, `balancetrading`.`jenisbalance` AS `jenisbalance`, `balancetrading`.`aturantrading` AS `aturantrading`, `balancetrading`.`idcurrency` AS `idcurrency`, `balancetrading`.`topup` AS `topup`, `balancetrading`.`withdraw` AS `withdraw`, `balancetrading`.`lostprofit` AS `lostprofit`, `balancetrading`.`maxlose` AS `maxlose`, `balancetrading`.`maxprofit` AS `maxprofit`, `balancetrading`.`jumlahbalance` AS `jumlahbalance`, `balancetrading`.`tglbukaakun` AS `tglbukaakun`, `balancetrading`.`tgltutupakun` AS `tgltutupakun`, `balancetrading`.`statusaktif` AS `statusaktif`, `broker`.`namabroker` AS `namabroker`, `broker`.`logobroker` AS `logobroker`, `currency`.`namacurrency` AS `namacurrency`, `currency`.`singkatan` AS `singkatan`, `jenisasset`.`namajenisasset` AS `namajenisasset`, `pengguna`.`namapengguna` AS `namapengguna`, `get_lasttrade_date`(`balancetrading`.`idbalance`) AS `tgltradeterakhir` FROM ((((`balancetrading` join `broker` on(`balancetrading`.`idbroker` = `broker`.`idbroker`)) join `jenisasset` on(`balancetrading`.`idjenisasset` = `jenisasset`.`idjenisasset`)) join `currency` on(`balancetrading`.`idcurrency` = `currency`.`idcurrency`)) join `pengguna` on(`balancetrading`.`idpengguna` = `pengguna`.`idpengguna`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_konversi`
--
DROP TABLE IF EXISTS `v_konversi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_konversi`  AS SELECT `konversi`.`idkonversi` AS `idkonversi`, `konversi`.`currencyutama` AS `currencyutama`, `currency`.`singkatan` AS `singkatancurrencyutama`, `currency`.`namacurrency` AS `namacurrencyutama`, `konversi`.`currencypasangan` AS `currencypasangan`, `currency_1`.`singkatan` AS `singkatancurrencypasangan`, `currency_1`.`namacurrency` AS `namacurrencypasangan`, `konversi`.`jumlahkonversi` AS `jumlahkonversi` FROM ((`konversi` join `currency` on(`konversi`.`currencyutama` = `currency`.`idcurrency`)) join `currency` `currency_1` on(`konversi`.`currencypasangan` = `currency_1`.`idcurrency`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_pair`
--
DROP TABLE IF EXISTS `v_pair`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_pair`  AS SELECT `pair`.`idpair` AS `idpair`, `pair`.`namapair` AS `namapair`, `pair`.`idjenisasset` AS `idjenisasset`, `jenisasset`.`namajenisasset` AS `namajenisasset`, `pair`.`statusaktif` AS `statusaktif`, `pair`.`pricenow` AS `pricenow`, `pair`.`tglinsert` AS `tglinsert`, `pair`.`tglupdate` AS `tglupdate` FROM (`pair` join `jenisasset` on(`pair`.`idjenisasset` = `jenisasset`.`idjenisasset`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_targetbalance`
--
DROP TABLE IF EXISTS `v_targetbalance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_targetbalance`  AS SELECT `targetbalance`.`idtargetbalance` AS `idtargetbalance`, `targetbalance`.`tglmulai` AS `tglmulai`, `targetbalance`.`tglselesai` AS `tglselesai`, `targetbalance`.`idbalance` AS `idbalance`, `targetbalance`.`startingbalance` AS `startingbalance`, `targetbalance`.`endingbalance` AS `endingbalance`, `targetbalance`.`idpengguna` AS `idpengguna`, `targetbalance`.`tglinsert` AS `tglinsert`, `targetbalance`.`tglupdate` AS `tglupdate`, `targetbalance`.`statustarget` AS `statustarget`, `targetbalance`.`targettrading` AS `targettrading`, `v_balancetrading`.`namabalance` AS `namabalance`, `v_balancetrading`.`idbroker` AS `idbroker`, `v_balancetrading`.`idjenisasset` AS `idjenisasset`, `v_balancetrading`.`jenisbalance` AS `jenisbalance`, `v_balancetrading`.`namabroker` AS `namabroker`, `v_balancetrading`.`logobroker` AS `logobroker`, `v_balancetrading`.`namacurrency` AS `namacurrency`, `v_balancetrading`.`namajenisasset` AS `namajenisasset`, `v_balancetrading`.`singkatan` AS `singkatan` FROM (`targetbalance` join `v_balancetrading` on(`targetbalance`.`idbalance` = `v_balancetrading`.`idbalance`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_topup`
--
DROP TABLE IF EXISTS `v_topup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_topup`  AS SELECT `topup`.`idtopup` AS `idtopup`, `topup`.`tgltopup` AS `tgltopup`, `topup`.`idbalance` AS `idbalance`, `topup`.`jumlahtopup` AS `jumlahtopup`, `topup`.`idpengguna` AS `idpengguna`, `topup`.`tglinsert` AS `tglinsert`, `topup`.`tglupdate` AS `tglupdate`, `balancetrading`.`namabalance` AS `namabalance`, `balancetrading`.`idbroker` AS `idbroker`, `broker`.`namabroker` AS `namabroker`, `broker`.`logobroker` AS `logobroker`, `currency`.`idcurrency` AS `idcurrency`, `currency`.`singkatan` AS `singkatan`, `currency`.`namacurrency` AS `namacurrency`, `pengguna`.`namapengguna` AS `namapengguna` FROM ((((`topup` join `balancetrading` on(`topup`.`idbalance` = `balancetrading`.`idbalance`)) join `pengguna` on(`topup`.`idpengguna` = `pengguna`.`idpengguna`)) join `broker` on(`balancetrading`.`idbroker` = `broker`.`idbroker`)) join `currency` on(`balancetrading`.`idcurrency` = `currency`.`idcurrency`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_trade`
--
DROP TABLE IF EXISTS `v_trade`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_trade`  AS SELECT `trade`.`idtrade` AS `idtrade`, `trade`.`tglentrytrade` AS `tglentrytrade`, `trade`.`tglexittrade` AS `tglexittrade`, `trade`.`idbalance` AS `idbalance`, `trade`.`idpair` AS `idpair`, `trade`.`qty` AS `qty`, `trade`.`entryprice` AS `entryprice`, `trade`.`exitprice` AS `exitprice`, `trade`.`lostprofit` AS `lostprofit`, `trade`.`idpengguna` AS `idpengguna`, `trade`.`istradeclose` AS `istradeclose`, `trade`.`idjenisstrategy` AS `idjenisstrategy`, `trade`.`tglinsert` AS `tglinsert`, `trade`.`tglupdate` AS `tglupdate`, `trade`.`fotoentry` AS `fotoentry`, `trade`.`fotoexit` AS `fotoexit`, `trade`.`hasiltrade` AS `hasiltrade`, `balancetrading`.`namabalance` AS `namabalance`, `balancetrading`.`idbroker` AS `idbroker`, `broker`.`namabroker` AS `namabroker`, `broker`.`logobroker` AS `logobroker`, `pair`.`namapair` AS `namapair`, `pair`.`idjenisasset` AS `idjenisasset`, CASE WHEN `pair`.`pricenow` <> 0 THEN `pair`.`pricenow` ELSE `trade`.`entryprice` END AS `pricenow`, `pengguna`.`namapengguna` AS `namapengguna`, `jenisstrategy`.`namajenisstrategy` AS `namajenisstrategy` FROM (((((`trade` join `balancetrading` on(`trade`.`idbalance` = `balancetrading`.`idbalance`)) join `pair` on(`trade`.`idpair` = `pair`.`idpair`)) join `jenisstrategy` on(`trade`.`idjenisstrategy` = `jenisstrategy`.`idjenisstrategy`)) join `pengguna` on(`trade`.`idpengguna` = `pengguna`.`idpengguna`)) join `broker` on(`balancetrading`.`idbroker` = `broker`.`idbroker`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_withdraw`
--
DROP TABLE IF EXISTS `v_withdraw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`serd5225`@`localhost` SQL SECURITY DEFINER VIEW `v_withdraw`  AS SELECT `withdraw`.`idwithdraw` AS `idwithdraw`, `withdraw`.`tglwithdraw` AS `tglwithdraw`, `withdraw`.`idbalance` AS `idbalance`, `withdraw`.`jumlahwithdraw` AS `jumlahwithdraw`, `withdraw`.`idpengguna` AS `idpengguna`, `withdraw`.`tglinsert` AS `tglinsert`, `withdraw`.`tglupdate` AS `tglupdate`, `pengguna`.`namapengguna` AS `namapengguna`, `balancetrading`.`namabalance` AS `namabalance`, `balancetrading`.`idbroker` AS `idbroker`, `broker`.`namabroker` AS `namabroker`, `broker`.`logobroker` AS `logobroker`, `balancetrading`.`idcurrency` AS `idcurrency`, `currency`.`singkatan` AS `singkatan`, `currency`.`namacurrency` AS `namacurrency` FROM ((((`withdraw` join `balancetrading` on(`withdraw`.`idbalance` = `balancetrading`.`idbalance`)) join `pengguna` on(`withdraw`.`idpengguna` = `pengguna`.`idpengguna`)) join `broker` on(`balancetrading`.`idbroker` = `broker`.`idbroker`)) join `currency` on(`balancetrading`.`idcurrency` = `currency`.`idcurrency`)) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balancetrading`
--
ALTER TABLE `balancetrading`
  ADD CONSTRAINT `balancetrading_ibfk_1` FOREIGN KEY (`idpengguna`) REFERENCES `pengguna` (`idpengguna`),
  ADD CONSTRAINT `balancetrading_ibfk_2` FOREIGN KEY (`idcurrency`) REFERENCES `currency` (`idcurrency`),
  ADD CONSTRAINT `balancetrading_ibfk_4` FOREIGN KEY (`idjenisasset`) REFERENCES `jenisasset` (`idjenisasset`),
  ADD CONSTRAINT `balancetrading_ibfk_5` FOREIGN KEY (`idbroker`) REFERENCES `broker` (`idbroker`);

--
-- Constraints for table `konversi`
--
ALTER TABLE `konversi`
  ADD CONSTRAINT `konversi_ibfk_1` FOREIGN KEY (`currencyutama`) REFERENCES `currency` (`idcurrency`),
  ADD CONSTRAINT `konversi_ibfk_2` FOREIGN KEY (`currencypasangan`) REFERENCES `currency` (`idcurrency`);

--
-- Constraints for table `pair`
--
ALTER TABLE `pair`
  ADD CONSTRAINT `pair_ibfk_1` FOREIGN KEY (`idjenisasset`) REFERENCES `jenisasset` (`idjenisasset`);

--
-- Constraints for table `targetbalance`
--
ALTER TABLE `targetbalance`
  ADD CONSTRAINT `targetbalance_ibfk_1` FOREIGN KEY (`idbalance`) REFERENCES `balancetrading` (`idbalance`),
  ADD CONSTRAINT `targetbalance_ibfk_2` FOREIGN KEY (`idpengguna`) REFERENCES `pengguna` (`idpengguna`);

--
-- Constraints for table `topup`
--
ALTER TABLE `topup`
  ADD CONSTRAINT `topup_ibfk_2` FOREIGN KEY (`idpengguna`) REFERENCES `pengguna` (`idpengguna`),
  ADD CONSTRAINT `topup_ibfk_3` FOREIGN KEY (`idbalance`) REFERENCES `balancetrading` (`idbalance`);

--
-- Constraints for table `trade`
--
ALTER TABLE `trade`
  ADD CONSTRAINT `trade_ibfk_2` FOREIGN KEY (`idpengguna`) REFERENCES `pengguna` (`idpengguna`),
  ADD CONSTRAINT `trade_ibfk_3` FOREIGN KEY (`idpair`) REFERENCES `pair` (`idpair`),
  ADD CONSTRAINT `trade_ibfk_4` FOREIGN KEY (`idbalance`) REFERENCES `balancetrading` (`idbalance`),
  ADD CONSTRAINT `trade_ibfk_5` FOREIGN KEY (`idjenisstrategy`) REFERENCES `jenisstrategy` (`idjenisstrategy`);

--
-- Constraints for table `withdraw`
--
ALTER TABLE `withdraw`
  ADD CONSTRAINT `withdraw_ibfk_2` FOREIGN KEY (`idpengguna`) REFERENCES `pengguna` (`idpengguna`),
  ADD CONSTRAINT `withdraw_ibfk_3` FOREIGN KEY (`idbalance`) REFERENCES `balancetrading` (`idbalance`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
