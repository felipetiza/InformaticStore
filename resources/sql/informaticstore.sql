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

INSERT INTO product VALUES(NULL, 'Logitech Wireless Mouse M280', 'Mouses', 'Logitech® Wireless Mouse M280 is the perfect combination of functionality, aesthetics and comfort: a unique asymmetrical shape that fits the hand, a wide wheel that improves movement and a smooth rubber surface with a nice texture especially to the touch. Logitech´s advanced optical tracking delivers precise, consistent tracking. Thanks to the design to save energy, the mouse batteries can be up to 18 months, and the mouse automatically goes into sleep when not in use. In addition, the wireless wireless receiver virtually disappears into the computer´s USB port and provides a practical and reliable wireless connection.\n\nCharacteristics:\n\n-  Comfortable shape for the right hand\n- Battery life up to 18 months\n- Logitech® Enhanced Advanced Optical Sensor\n- Nano "connect and forget" type receiver', 12.5, 14, 'resources/img/product/mouse1.jpg');
INSERT INTO product VALUES(NULL, 'NGS Popcorn USB Keyboard', 'Keyboards', 'Practical keyboard with USB connection. Equipped with 20 multimedia keys, Internet and other functions of daily use, they facilitate and accelerate the access to the different applications of the computer and its habitual functions.\n\nCharacteristics:\n\n- Device Interface: USB\n- Recommended Use: Workshop\n- Keyboard language: English\n- Connectivity technology: Wired\n- Use with: Universal\n- Keyboard Format: Standard\n- Keyboard Style: Right\n- Shortcut keys: Yes\n- Windows keys: Yes\n- Rest Dolls: No\n- Keypad: Yes', 17.30, 9, 'resources/img/product/keyboard1.jpg');
INSERT INTO product VALUES(NULL, 'Logitech Speaker System Z906 500W 5.1 THX Digital', 'Speakers', 'Experience the home cinema experience with the Logitech Z906, a powerful and clear 5.1 sound system that moves your sofa to a movie theater every time you turn it on.\n\nWith its 6 connections, you can connect it to everything: TV, Console, PC, MP4 player ...\n\nPowerful and crisp 3D stereo sound with a 500 watt THX-certified speaker. The sufbwoofer has a bass port. Movies and games reach another level with a few sustained by 165 watts of power. Enjoy the Z906 comfortably from the sofa and set them up one by one with your remote and console.\n\nInstall them and make each night a premiere. Let the walls tremble with this sound system!\n\nCharacteristics:\n\n- 500 watts (RMS) of power\n- Digital Decoding\n- Easy-to-read control console\n- Subwoofer with bass port and side output\n- Surround sound with 3D stereo\n- THX® Certification\n- Analog and digital inputs\n- Wireless remote control\n- Satellite speakers with wall mounting capability', 79.95, 0, 'resources/img/product/speakers1.jpg');
INSERT INTO product VALUES(NULL, 'SanDisk Cruzer Blade 32GB USB', 'USB Flash Drives', 'Carry your favorite files with you on the ultra-compact and portable Cruzer® BladeTM USB flash drive. It is the most convenient and quick way to save and transfer your digital files from computer to computer, or take them with you when you move. Simply copy your pictures, songs and other files you want into the stylish Cruzer Blade USB flash drive to start sharing them with your friends and family.\n\nThis USB flash drive also allows you to prevent unauthorized access to your files with built-in SanDisk® SecureAccessTM software and enjoy the additional protection of an online backup (up to 2 GB) offered by the backup partner in SanDisk line. † Trust SanDisk® to protect your important files.\n\nCharacteristics:\n\n- An elegant and ultra-compact portable unit\n- Share photos, videos, songs and other files between computers with ease\n- Protect your private files with built-in SanDisk® SecureAccess ™ software\n- Store more files thanks to its 32GB capacity\n- Backed by a five-year limited warranty\n- SanDisk® SecureAccess ™ software compatible with Windows XP®, Windows Vista®, Windows 7®, and Mac OS X v10.5', 26.45, 80, 'resources/img/product/pendrive1.jpg');
INSERT INTO product VALUES(NULL, 'SilverStone ST70F-ESG Power Supply 700W', 'Power Supplies', 'SilverStone ST70F-ESG power supply. SilverStone Essential Gold are 80 PLUS Gold certified FAs within the affordable Strider Essential series. They retain their practical and reliable fixed wire arrangement of other Strider Essential FAs and adhere to the same outstanding features of all Strider FAs without reducing quality or reliability. The 80 PLUS Gold certification ensures that Strider Essential Gold FAs produce 40% less waste heat wasted by energy conversion compared to basic 80 PLUS certified FAs, resulting in lower electrical consumption and cooling. In addition, features of the higher-level Strider series, such as voltage regulation ± 3%, single-rail design with high amperage + 12V and support for multiple graphics cards are preserved to provide the best quality solutions at any price.\n\nCharacteristics:\n\n- High efficiency with 80 PLUS Gold certification\n- Continuous output power with operating temperature of 40ºC\n- First class single rail with + 12V\n- Strict voltage regulation of ± 3%\n- Circuitry with multiple protection\n- Accepts 8-pin and 6-pin PCI-E connectors\n- 120mm HYB Fan\n- Active PFC', 117.85, 300, 'resources/img/product/power_supply1.jpg');
INSERT INTO product VALUES(NULL, 'HDMI wire 1.4 male / male 15m', 'Wires', 'HDMI wire 3D of 15m ideal for all types of devices that use the HDMI interface.\n\nCharacteristics:\n\n- Connector 1: HDMI type A, male\n- Connector 2: HDMI type A, male\n- Standard HDMI: High speed HDMI with Ethernet\n- Cable type: CAT 2\n- UL Certification: UL20276\n- Standard HDTV: 1080\n- HDTV Resolution max .: 1920 x 1200 Pixel, 60Hz\n- Molded\n- Protection: triple protection\n- Material of thread: CU\n- AWG: 28\n- Connector: gold plated\n- Color of the cable: black\n- Connector Color: Black\n- Length: 15 Mts', 1.50, 200, 'resources/img/product/wire1.jpg');
INSERT INTO product VALUES(NULL, 'LG DVD Recorder. Internal Slim 9.5mm SATA', 'Accessories', 'Introducing LG´s GUD0N.AUAA10B, an internal DVD Slim recorder with playback disc and M-Disc support.\n\nThe player of your dreams, with an image of great realism and able to reproduce practically any type of disc. You will enjoy the most of your movies and music.\n\nCharacteristics:\n\n- DivX Ultra certification for enhanced DivX video playback\n- 12-bit / 108 Mhz video processing for a crisp, natural image\n- USB media connection for multimedia playback from USB flash drives\n- ProReader Drive for smooth playback with almost any disc\n- Screen adjustment for optimal viewing at all times\n- Intelligent Image Control allows you to customize settings', 24.85, 34, 'resources/img/product/accessory1.jpg');
INSERT INTO product VALUES(NULL, 'Creative Inspire T3300 2.1', 'Speakers', 'Desktop speaker system of high-performance 2.1 with impressive volume and bass.\n\nInspire is the most sonic model yet, but with innovations delivering excellent performance, the Creative Inspire T3300 delivers stunning bass and full spectrum audio that is simply the best of its kind. For starters, the speaker system includes the largest subwoofer with bass port and lower output of its class with bass level adjustment. In addition, we have improved the audio spectrum of the satellites by incorporating Creative DSE (Dual Slot Enclosure), a design feature that makes it possible to implement a conventional port tube in an airtight enclosure. When used with Creative IFP (Image Focusing Plate), a function that improves the direction of the sound and the organization of the listening areas, it is achieved that the music has more volume, is better oriented and has more severe means, maintaining the same Time tonal precision.\n\nCharacteristics:\n\n- Creative DSE (Dual Slot Enclosure) incorporates a conventional port tube and fully operative in an airtight enclosure. It improves notably the distribution of the frequency in the whole system, providing a reproduction with more volume and more serious means.\n- The enhanced IFP (Image Focusing Plate) feature includes a wider part that surrounds the satellites to improve the direction and organization of the sound, resulting in a wider optimum acoustic level while maintaining pitch precision.\n- The subwoofer with bass port and lower bass output of its class with bass level adjustment.\n- Remote control with integrated cable with on / off button and volume control.', 39.95, 16, 'resources/img/product/speakers2.jpg');
INSERT INTO product VALUES(NULL, 'Kingston DataTravel R3.0 G2 64GB USB 3.0', 'USB Flash Drives', 'Kingston´s DataTraveler® R3.0 G2 USB Flash Drive features high-speed USB 3.0 technology with speeds of 120 MB/s in read and 45 MB/s in write (16 GB drives have speeds of 120 MB / s In read and 25 MB / s in writing), which saves time when transferring files. It is 8 times faster than a standard 2.0 drive and saves about 20 minutes when transferring a full HD 1080p video.\n\nIts stunning speed relieves bottlenecks by storing or viewing HD videos, music libraries, digital graphics, presentations, or other large files. The DTR30G2 has a sturdy rubber-coated casing with a waterproof and shock-resistant certificate that allows it to withstand shock and impact when moving from one place to another.
The DTR30G2 drives are compatible with USB 2.0 technology and are backed by a five-year warranty, free technical support and legendary Kingston reliability.\n\nCharacteristics:\n\n- USB 3.0 High Speed Performance\n- 8 times faster than a USB 2.0 drive\n- Durable, state-of-the-art unit with reduced form factor for easy transport\n- Waterproof and impact resistant', 31, 67, 'resources/img/product/pendrive2.jpg');
INSERT INTO product VALUES(NULL, 'Nox Krom Khalon Keyboard Games RGB', 'Keyboards', 'Krom introduces a new keyboard, Khalon. It is a membrane keyboard with high performance and multiple customization options, which will allow you to adjust the keyboard options to various games with great versatility.\n\nCharacteristics:\n\n- Ergonomics and comfort\n- Maximum customization\n- Multiple choices', 28, 4, 'resources/img/product/keyboard2.jpg');
INSERT INTO product VALUES(NULL, 'Cable USB 2.0 a Mini USB 3m M/M', 'Wires', 'USB 2.0 cable of 3 meters that at one end has a connection USB 2.0 male type A and at the other end a connection Mini USB male type B\n\n- Made with double shielded cable\n- 4 Conductors (UL2725, 2 x AWG 28, 2 x AWG 26)\n- Injected plastic enclosures\n- Connector 1: USB A (6-pin) Male\n- Connector 2: Mini USB B (5-pin) Male\n- Compatible USB Vers. 1.1 and 2.0\n- Compatible with PSP (PlayStation Portable)\n- Length 3 meters', 1.50, 200, 'resources/img/product/wire2.jpg');
INSERT INTO product VALUES(NULL, 'Trust MaxTrack Compact Bluetooth Mouse 1600DPI', 'Mouses', 'We present the Maxtrack Bluethooth Compact from Trust, a compact wireless optical mouse with Bluetooth technology.\n\nCharacteristics:\n\n- No USB receiver required\n- Rubber inlay design for firmer grip\n- Speed select button (800-1200-1600 dpi)\n- 10 m wireless coverage\n- Also compatible with Android tablets', 24.95, 111, 'resources/img/product/mouse2.jpg');
INSERT INTO product VALUES(NULL, 'USB 2.0 Wire AM/AH 1.8m Male/Female Extension Wire', 'Wires', '- Made with double shielded cable\n- 4 conductors (UL2725, 2 x AWG24, 2 x AWG28)\n- Injection molded plastic enclosures\n- Connector 1: USB A (6-pin) Male\n- Connector 2: USB A (6 Pin) Female\n- Compatible USB Version 1.1\n- USB Certificate Version 2.0\n\nNote: Color may be black or white depending on availability.', 1.75, 26, 'resources/img/product/wire3.jpg');
INSERT INTO product VALUES(NULL, 'Xbox One Power Supply', 'Power Supplies', 'Replacement power adapter for Xbox One. Whether to replace the battered adapter or to have a spare one and never stop enjoying your new console. The power adapter has an indicator light to show the power status of the console, and its automatic voltage feature allows the adapter to be used around the world.This is the best option to replace your faulty adapter. Replacing it will take a few seconds and still enjoy your console.\n\nSpecifications:\n\n- Input: AC 100-240V. 2A. 47-63Hz\n- Output: DC 135W. 12V. 10,83A. 5Vsb. 1A\n\nNote: Plug with connection for Spain', 17.95, 300, 'resources/img/product/power_supply2.jpg');
INSERT INTO product VALUES(NULL, 'The G-Lab Microphone Hi-Fi Gaming', 'Accessories', 'Introduce yourself the G-Lab Hi-fi microphone.\n\nCharacteristics:\n\n- Great for use when playing with speakers and need to talk to other players\n- Perfect sound thanks to the USB digital connection\n- A directional sensor that cancels the surrounding noise\n- An illuminated base that can be turned on / off\n- Flexible structure\n- A useful mute button\n- Plug & Play, compatible', 25, 21, 'resources/img/product/accessory2.jpg');
INSERT INTO product VALUES(NULL, 'G.Skill MicroSDXC 64GB Class 10 + SD Adapter', 'Accessories', 'Introduce yourself the G-Lab Hi-fi microphone.\n\nCharacteristics:\n\n- Great for use when playing with speakers and need to talk to other players\n- Perfect sound thanks to the USB digital connection\n- A directional sensor that cancels the surrounding noise\n- An illuminated base that can be turned on / off\n- Flexible structure\n- A useful mute button\n- Plug & Play, compatible', 28, 60, 'resources/img/product/accessory3.jpg');
INSERT INTO product VALUES(NULL, 'NGS Clipper Red USB Keyboard', 'Keyboards', 'This keyboard has a glossy black finish giving it elegance and personality. It also incorporates a USB connection with Plug & Play function so the connection to any equipment will be made immediately. The pulsation of the 104 membrane keys is very smooth and the noise they emit when being pulsed is practically imperceptible. Finally, it incorporates a classic design that is resistant to spills, which offers a longer operation, approximately 10 million pulsations.\n\nNote: All our products are distributed by official Spanish channel, so all keyboards include Ñ and layout of Spanish keys, and may differ from the distribution of keys of the images shown, unless clearly stated otherwise.', 14.95, 38, 'resources/img/product/keyboard3.jpg');

INSERT INTO product VALUES(NULL, 'NGS Ice 2400DPI Gray Mouse', 'Mouses', 'It is the first mouse in the world with effects of light with the colors of the rainbow and integral finish in silver color. It includes a system of emission of neon light that makes the emitted light has a harmonious beauty thanks to our "Lighting-S conductor". It is bathed with new paint technology called "FILM-Silverized", using a silver finish that resembles a piece made in said metal, but at the same time semitransparent. It will light up like an "art deco" jewel on your desk.\n\nInput device:\n\n- Device Interface: USB\n- Button Type: Pressed buttons\n- Type of displacement: Wheel\n- Movement Resolution: 2400 DPI\n- Number of scroll wheels: 1\n- Customizable resolution movement: Y\n- Number of resolution modes: 3\n\nDesign:\n\n- Form Factor: Ambidextrous\n- Color of product: Gray\n- Illumination: Yes\n- Surface Coloration: Monotonous', 19.95, 42, 'resources/img/product/mouse3.jpg');
INSERT INTO product VALUES(NULL, 'Trust Vigor 2.1 Subwoofer Speaker Set', 'Speakers', 'Introducing the Vigor 2.1 Subwoofer Speaker Set from Trust, a powerful subwoofer 2.1 speaker set to listen to audio from PCs, tablets, phones or other audio devices.\n\nCharacteristics:\n\n- RMS 50 W, maximum power 100 W\n- Rugged design, subwoofer and wood satellites\n- High quality sound with low penetrants\n- Comfortable wired control for easy volume control with headphones and line-in jacks\n- Volume control and subwoofer bass sounds\n- For use with PCs, tablets, TV, DVD, CD, MP3 or other audio devices', 69.95, 10, 'resources/img/product/speakers3.jpg');
INSERT INTO product VALUES(NULL, 'Corsair Voyager GS 512GB USB 3.0', 'USB Flash Drives', 'Introducing Corsair´s Voyager GS 512GB USB 3.0 flash drive, a high-performance, high-performance USB 3.0 flash drive in an elegant, rugged case. It takes full advantage of the high-speed USB 3.0 interface and offers full USB 2.0 compatibility for older systems. And the zinc alloy body with aluminum accents make it a USB drive from which you´ll want to brag.\n\nCharacteristics:\n\n- Optimized for performance\n-  USB 3.0 speed and compatibility with USB 2.0\n- The top-level performance is combined with a top-level design', 219, 12, 'resources/img/product/pendrive3.jpg');

INSERT INTO provider VALUES(NULL, 'Colonial Avenue', 'Sound equipment');
INSERT INTO provider VALUES(NULL, 'Industrial area the queens', 'Storage disks');

INSERT INTO order2 VALUES(NULL, 3, NOW(), 4, 175.65);

INSERT INTO contain VALUES(1, 2, 4);
INSERT INTO contain VALUES(1, 4, 1);
INSERT INTO contain VALUES(1, 3, 2);



















