DROP DATABASE IF EXISTS `informaticstore`;
CREATE DATABASE IF NOT EXISTS `informaticstore`;
USE `informaticstore`;

   set storage_engine = InnoDB;
-- set storage_engine = MyISAM;
-- set storage_engine = Falcon;
-- set storage_engine = PBXT;
-- set storage_engine = Maria;

CREATE USER IF NOT EXISTS 'informatic'@'localhost' IDENTIFIED BY 'store';
GRANT ALL PRIVILEGES ON informaticstore.* TO 'informatic'@'localhost';
FLUSH PRIVILEGES;


-- Tables's creation
CREATE TABLE customer
(
	idcustomer		INT AUTO_INCREMENT NOT NULL,
	name			VARCHAR(25),
	surname			VARCHAR(50),
	email			VARCHAR(45),
	address			VARCHAR(100),
	phone			VARCHAR(16),
	type			VARCHAR(75),
	username		VARCHAR(40),
	password		VARCHAR(50),
	PRIMARY KEY(idcustomer)
);

CREATE TABLE product
(
	idproduct		INT AUTO_INCREMENT NOT NULL,
	name			VARCHAR(80),
	category		VARCHAR(50),
	description		VARCHAR(2000),
	price			DECIMAL(7,2),
	amount			INT,
	urlimage		VARCHAR(250),
	PRIMARY KEY(idproduct)
);

-- It gives error if the name is ORDER
CREATE TABLE order2
(
    idorder         INT AUTO_INCREMENT NOT NULL,
    idcustomer      INT,
    dateorder       DATE,
    amountproducts  INT,
    totalprice      DECIMAL(7,2),
    PRIMARY KEY(idorder, idcustomer),
    FOREIGN KEY(idcustomer) REFERENCES customer(idcustomer) ON DELETE CASCADE
);

CREATE TABLE provider
(
	idprovider		INT AUTO_INCREMENT NOT NULL,
	location		VARCHAR(100),
	material		VARCHAR(50),
	PRIMARY KEY(idprovider)
);

CREATE TABLE shopping_cart
(
	idcustomer		INT,
	idproduct		INT,
	PRIMARY KEY(idcustomer, idproduct),
	FOREIGN KEY(idcustomer) REFERENCES customer(idcustomer) ON DELETE CASCADE,
	FOREIGN KEY(idproduct) REFERENCES product(idproduct) ON DELETE CASCADE
);

CREATE TABLE contain
(
	idorder			INT,
	idproduct		INT,
	amount 			INT,
	PRIMARY KEY(idorder, idproduct),
	FOREIGN KEY(idproduct) REFERENCES product(idproduct) ON DELETE CASCADE,
	FOREIGN KEY(idorder) REFERENCES order2(idorder) ON DELETE CASCADE
);


-- Insertion within of the tables
INSERT INTO customer VALUES(NULL, 'John', 'Adam', 'john22@gmail.com','Street','6765432', 'Admin', 'admin', '1234');
INSERT INTO customer VALUES(NULL, 'Alfred', 'Smith', 'alfred@gmail.com','Street','6765432', 'User', 'user', '1234');
INSERT INTO customer VALUES(NULL, 'Brenda', 'Cranston', 'brenda22@gmail.com','Street','6765432', 'User', 'brenda22', '5678');
INSERT INTO customer VALUES(NULL, 'Jeremy', 'Griffin', 'jeremy@gmail.com','Street','6765432', 'User', 'jere', 'bird');
INSERT INTO customer VALUES(NULL, 'Rupert', 'Smith', 'rupert@gmail.com','Street','6765432', 'User', 'rupert14', 'god');
INSERT INTO customer VALUES(NULL, 'Wilson', 'Morrison', 'wilson@gmail.com','Street','6765432', 'User', 'wilson41', 'myself');
INSERT INTO customer VALUES(NULL, 'Howard', 'Albertson', 'howard@gmail.com','Street','6765432', 'User', 'howii', 'howii');

INSERT INTO product VALUES(NULL, 'Logitech Wireless Mouse M280', 'Mouses', 'Logitech® Wireless Mouse M280 is the perfect combination of functionality, aesthetics and comfort: a unique asymmetrical shape that fits the hand, a wide wheel that improves movement and a smooth rubber surface with a nice texture especially to the touch. Logitech´s advanced optical tracking delivers precise, consistent tracking. Thanks to the design to save energy, the mouse batteries can be up to 18 months, and the mouse automatically goes into sleep when not in use. In addition, the wireless wireless receiver virtually disappears into the computer´s USB port and provides a practical and reliable wireless connection.\n\nCharacteristics:\n\n-  Comfortable shape for the right hand\n- Battery life up to 18 months\n- Logitech® Enhanced Advanced Optical Sensor\n- Nano "connect and forget" type receiver', 12.5, 14, 'resources/img/product/mouse.jpg');
INSERT INTO product VALUES(NULL, 'NGS Popcorn USB Keyboard', 'Keyboards', 'Practical keyboard with USB connection. Equipped with 20 multimedia keys, Internet and other functions of daily use, they facilitate and accelerate the access to the different applications of the computer and its habitual functions.\n\nCharacteristics:\n\n- Device Interface: USB\n- Recommended Use: Workshop\n- Keyboard language: English\n- Connectivity technology: Wired\n- Use with: Universal\n- Keyboard Format: Standard\n- Keyboard Style: Right\n- Shortcut keys: Yes\n- Windows keys: Yes\n- Rest Dolls: No\n- Keypad: Yes', 17.30, 9, 'resources/img/product/keyboard.jpg');
INSERT INTO product VALUES(NULL, 'Logitech Speaker System Z906 500W 5.1 THX Digital', 'Speakers', 'Experience the home cinema experience with the Logitech Z906, a powerful and clear 5.1 sound system that moves your sofa to a movie theater every time you turn it on.\n\nWith its 6 connections, you can connect it to everything: TV, Console, PC, MP4 player ...\n\nPowerful and crisp 3D stereo sound with a 500 watt THX-certified speaker. The sufbwoofer has a bass port. Movies and games reach another level with a few sustained by 165 watts of power. Enjoy the Z906 comfortably from the sofa and set them up one by one with your remote and console.\n\nInstall them and make each night a premiere. Let the walls tremble with this sound system!\n\nCharacteristics:\n\n- 500 watts (RMS) of power\n- Digital Decoding\n- Easy-to-read control console\n- Subwoofer with bass port and side output\n- Surround sound with 3D stereo\n- THX® Certification\n- Analog and digital inputs\n- Wireless remote control\n- Satellite speakers with wall mounting capability', 79.95, 0, 'resources/img/product/speakers.jpg');
INSERT INTO product VALUES(NULL, 'SanDisk Cruzer Blade 32GB USB', 'USB Flash Drives', 'Carry your favorite files with you on the ultra-compact and portable Cruzer® BladeTM USB flash drive. It is the most convenient and quick way to save and transfer your digital files from computer to computer, or take them with you when you move. Simply copy your pictures, songs and other files you want into the stylish Cruzer Blade USB flash drive to start sharing them with your friends and family.\n\nThis USB flash drive also allows you to prevent unauthorized access to your files with built-in SanDisk® SecureAccessTM software and enjoy the additional protection of an online backup (up to 2 GB) offered by the backup partner in SanDisk line. † Trust SanDisk® to protect your important files.\n\nCharacteristics:\n\n- An elegant and ultra-compact portable unit\n- Share photos, videos, songs and other files between computers with ease\n- Protect your private files with built-in SanDisk® SecureAccess ™ software\n- Store more files thanks to its 32GB capacity\n- Backed by a five-year limited warranty\n- SanDisk® SecureAccess ™ software compatible with Windows XP®, Windows Vista®, Windows 7®, and Mac OS X v10.5', 26.45, 80, 'resources/img/product/pendrive.jpg');
INSERT INTO product VALUES(NULL, 'SilverStone ST70F-ESG Power Supply 700W', 'Power Supplies', 'SilverStone ST70F-ESG power supply. SilverStone Essential Gold are 80 PLUS Gold certified FAs within the affordable Strider Essential series. They retain their practical and reliable fixed wire arrangement of other Strider Essential FAs and adhere to the same outstanding features of all Strider FAs without reducing quality or reliability. The 80 PLUS Gold certification ensures that Strider Essential Gold FAs produce 40% less waste heat wasted by energy conversion compared to basic 80 PLUS certified FAs, resulting in lower electrical consumption and cooling. In addition, features of the higher-level Strider series, such as voltage regulation ± 3%, single-rail design with high amperage + 12V and support for multiple graphics cards are preserved to provide the best quality solutions at any price.\n\nCharacteristics:\n\n- High efficiency with 80 PLUS Gold certification\n- Continuous output power with operating temperature of 40ºC\n- First class single rail with + 12V\n- Strict voltage regulation of ± 3%\n- Circuitry with multiple protection\n- Accepts 8-pin and 6-pin PCI-E connectors\n- 120mm HYB Fan\n- Active PFC', 117.85, 300, 'resources/img/product/power_supply.jpg');
INSERT INTO product VALUES(NULL, 'HDMI wire 1.4 male / male 15m', 'Wires', 'HDMI wire 3D of 15m ideal for all types of devices that use the HDMI interface.\n\nCharacteristics:\n\n- Connector 1: HDMI type A, male\n- Connector 2: HDMI type A, male\n- Standard HDMI: High speed HDMI with Ethernet\n- Cable type: CAT 2\n- UL Certification: UL20276\n- Standard HDTV: 1080\n- HDTV Resolution max .: 1920 x 1200 Pixel, 60Hz\n- Molded\n- Protection: triple protection\n- Material of thread: CU\n- AWG: 28\n- Connector: gold plated\n- Color of the cable: black\n- Connector Color: Black\n- Length: 15 Mts', 1.50, 200, 'resources/img/product/hdmi_wire.jpg');
INSERT INTO product VALUES(NULL, 'LG DVD Recorder. Internal Slim 9.5mm SATA', 'Accessories', 'Introducing LG´s GUD0N.AUAA10B, an internal DVD Slim recorder with playback disc and M-Disc support.\n\nThe player of your dreams, with an image of great realism and able to reproduce practically any type of disc. You will enjoy the most of your movies and music.\n\nCharacteristics:\n\n- DivX Ultra certification for enhanced DivX video playback\n- 12-bit / 108 Mhz video processing for a crisp, natural image\n- USB media connection for multimedia playback from USB flash drives\n- ProReader Drive for smooth playback with almost any disc\n- Screen adjustment for optimal viewing at all times\n- Intelligent Image Control allows you to customize settings', 24.85, 34, 'resources/img/product/dvdreader.jpg');

INSERT INTO provider VALUES(NULL, 'Colonial Avenue', 'Sound equipment');
INSERT INTO provider VALUES(NULL, 'Industrial area the queens', 'Storage disks');

INSERT INTO order2 VALUES(NULL, 3, NOW(), 4, 175.65);

INSERT INTO contain VALUES(1, 2, 4);
INSERT INTO contain VALUES(1, 4, 1);
INSERT INTO contain VALUES(1, 3, 2);




