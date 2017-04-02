<?php
	// Import the connection variables of the database
	// include_once "database.php";

	// const MAIN_PAGE = "index.php";
	// const MAIN_PAGE = "../informaticstore";
	// const MAIN_PAGE = "../";	// Go to the proyect root. Load the index file

	$connection;

	// Cart variables
	$cartProductIDAndAmount = [[]];
	$cartProductsNumber     = 0;
	$cartTotalPrice         = 0;
	$cartProductID          = [];
	$cartProductAmount      = [];
	$cartProductData        = [[]];
	$cartProductName        = [];
	$cartProductPrice       = [];

	// User variables
	$userID       = [];
	$userName     = [];
	$userSurname  = [];
	$userEmail    = [];
	$userAddress  = [];
	$userPhone    = [];
	$userType     = [];
	$userUsername = [];
	$userPassword = [];

	// Product variables
	$productID       = [];
	$productName     = [];
	$productCategory = [];
	$productDescript = [];
	$productPrice    = [];
	$productAmount   = [];
	$productImg      = [];

	// Order variables
	$orderOrderID    = [];
	$orderCustomerID = [];
	$orderDate       = [];
	$orderAmountProd = [];
	$orderPrice      = [];
	$orderProduct    = [[]];
	$orderAmount     = [[]];
	$productName     = [[]];
	$productPrice    = [[]];


// ██████╗  █████╗ ████████╗ █████╗ ██████╗  █████╗ ███████╗███████╗
// ██╔══██╗██╔══██╗╚══██╔══╝██╔══██╗██╔══██╗██╔══██╗██╔════╝██╔════╝
// ██║  ██║███████║   ██║   ███████║██████╔╝███████║███████╗█████╗
// ██║  ██║██╔══██║   ██║   ██╔══██║██╔══██╗██╔══██║╚════██║██╔══╝
// ██████╔╝██║  ██║   ██║   ██║  ██║██████╔╝██║  ██║███████║███████╗
// ╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚═╝  ╚═╝╚═════╝ ╚═╝  ╚═╝╚══════╝╚══════╝

	// function databaseConnection($host     = "mysql.hostinger.es",
	// 							$user     = "u449232361_inf",
	// 							$password = "J9KMLLpWt2JmNmys09",
	// 							$database = "u449232361_store"){

	// function databaseConnection($host     = "localhost",
	// 							$user     = "informatic",
	// 							$password = "store",
	// 							$database = "informaticstore"){

	// Use the connection variables of the database - database.php
	function databaseConnection($host, $user, $password, $database){
		global $connection;

		// Report all errors except 'E_WARNING'
		// Install.php - When database connection is wrong, it showed this error always
		error_reporting(E_ALL ^ E_WARNING);

	    $connection = new mysqli($host, $user, $password, $database);
    	$connection->set_charset("utf8");

	    if ($connection->connect_errno) {
	   		// printf("Connection failed: %s\n", $connection->connect_error);
	        // exit();
	    	showToast("Connection failed with database");
	    	return "false";
	    }else
	    	return "true";
	}

	function insertCustomerTable($connection){
		$insertTable = "
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
		";

	    if ($result = $connection->query($insertTable)) {
            if (!$result)
                echo "Impossible to insert the customer table";
        }else
            echo "Wrong Query";
	}

	function insertProductTable($connection){
		$insertTable = "
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
		";

	    if ($result = $connection->query($insertTable)) {
            if (!$result)
                echo "Impossible to insert the product table";
        }else
            echo "Wrong Query";
	}

	function insertOrderTable($connection){
		// It gives error if the name is ORDER
		$insertTable = "
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
		";

	    if ($result = $connection->query($insertTable)) {
            if (!$result)
                echo "Impossible to insert the order table";
        }else
            echo "Wrong Query";
	}

	function insertCartTable($connection){
		$insertTable = "
			CREATE TABLE shopping_cart
			(
				idcustomer		INT,
				idproduct		INT,
				amount 			INT,
				PRIMARY KEY(idcustomer, idproduct),
				FOREIGN KEY(idcustomer) REFERENCES customer(idcustomer) ON DELETE CASCADE,
				FOREIGN KEY(idproduct) REFERENCES product(idproduct) ON DELETE CASCADE
			);
		";

	    if ($result = $connection->query($insertTable)) {
            if (!$result)
                echo "Impossible to insert the shopping_cart table";
        }else
            echo "Wrong Query";
	}

	function insertContainTable($connection){
		$insertTable = "
			CREATE TABLE contain
			(
				idorder			INT,
				idproduct		INT,
				amount 			INT,
				PRIMARY KEY(idorder, idproduct),
				FOREIGN KEY(idproduct) REFERENCES product(idproduct) ON DELETE CASCADE,
				FOREIGN KEY(idorder) REFERENCES order2(idorder) ON DELETE CASCADE
			);
		";

	    if ($result = $connection->query($insertTable)) {
            if (!$result)
                echo "Impossible to insert the contain table";
        }else
            echo "Wrong Query";
	}

	function insertValuesCustomer($connection){
		$insertTable = [];

		// Encrypted passwords with sha1 algorithm
		// Pass: 1234
		$insertValue[0] = "
			INSERT INTO customer
			VALUES(NULL, 'John', 'Adam', 'john22@gmail.com','C/ La Viera 8','645713299', 'Admin', 'admin', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
		";
		// alvede
		$insertValue[1] = "
			INSERT INTO customer VALUES(NULL, 'Alfonso', 'Velazquez', 'alfonso72@gmail.com','C/ Dardo 14','622478134', 'Admin', 'alfonso72', '45c523be69c10edeef11ee44e4dff82851677ed1');
		";
		// 1234
		$insertValue[2] = "
			INSERT INTO customer VALUES(NULL, 'Alfred', 'Smith', 'alfred@gmail.com','C/ El Corral 2','627395740', 'User', 'user', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
		";
		// 5678
		$insertValue[3] = "
			INSERT INTO customer VALUES(NULL, 'Brenda', 'Cranston', 'brenda22@gmail.com','C/ Laguna 9','649368492', 'User', 'brenda22', '2abd55e001c524cb2cf6300a89ca6366848a77d5');
		";
		// bird
		$insertValue[4] = "
			INSERT INTO customer VALUES(NULL, 'Jeremy', 'Griffin', 'jeremy@gmail.com','C/ Real 22','684926482', 'User', 'jere', 'cd92815bf6273acbaf834b9faed277c722068291');
		";
		// god
		$insertValue[5] = "
			INSERT INTO customer VALUES(NULL, 'Rupert', 'Smith', 'rupert@gmail.com','C/ Villa 4','676543236', 'User', 'rupert14', '21298df8a3277357ee55b01df9530b535cf08ec1');
		";
		// myself
		$insertValue[6] = "
			INSERT INTO customer VALUES(NULL, 'Wilson', 'Morrison', 'wilson@gmail.com','C/ Cantina 11','603825374', 'User', 'wilson41', 'e9ba3d3a5fcb656b3ac7d8b8abca955fe7ff3b88');
		";
		// howii
		$insertValue[7] = "
			INSERT INTO customer VALUES(NULL, 'Howard', 'Albertson', 'howard@gmail.com','C/ Zafra 7','605638611', 'User', 'howii', '72d4475db500cd009b6e5b083226367f41c31831');
		";

		foreach($insertValue as $value){
		    if ($result = $connection->query($value)){
	            if (!$result)
	                echo "Impossible to insert the values of customer table";
	        }else
	            echo "Wrong Query";
        }
	}

	function insertValuesProduct($connection){
		$insertValue = [];

		$insertValue[0] = "
			INSERT INTO product VALUES(NULL, 'Logitech Wireless Mouse M280', 'Mouses', 'Logitech® Wireless Mouse M280 is the perfect combination of functionality, aesthetics and comfort: a unique asymmetrical shape that fits the hand, a wide wheel that improves movement and a smooth rubber surface with a nice texture especially to the touch. Logitech´s advanced optical tracking delivers precise, consistent tracking. Thanks to the design to save energy, the mouse batteries can be up to 18 months, and the mouse automatically goes into sleep when not in use. In addition, the wireless wireless receiver virtually disappears into the computer´s USB port and provides a practical and reliable wireless connection.\n\nCharacteristics:\n\n-  Comfortable shape for the right hand\n- Battery life up to 18 months\n- Logitech® Enhanced Advanced Optical Sensor', 12.5, 14, 'resources/img/product/mouse1.jpg');
		";
		$insertValue[1] = "
			INSERT INTO product VALUES(NULL, 'NGS Popcorn USB Keyboard', 'Keyboards', 'Practical keyboard with USB connection. Equipped with 20 multimedia keys, Internet and other functions of daily use, they facilitate and accelerate the access to the different applications of the computer and its habitual functions.\n\nCharacteristics:\n\n- Device Interface: USB\n- Recommended Use: Workshop\n- Keyboard language: English\n- Connectivity technology: Wired\n- Use with: Universal\n- Keyboard Format: Standard\n- Keyboard Style: Right\n- Shortcut keys: Yes\n- Windows keys: Yes\n- Rest Dolls: No\n- Keypad: Yes', 17.30, 9, 'resources/img/product/keyboard1.jpg');
		";
		$insertValue[2] = "
			INSERT INTO product VALUES(NULL, 'Logitech Speaker System Z906 500W 5.1 THX Digital', 'Speakers', 'Experience the home cinema experience with the Logitech Z906, a powerful and clear 5.1 sound system that moves your sofa to a movie theater every time you turn it on.\n\nWith its 6 connections, you can connect it to everything: TV, Console, PC, MP4 player ...\n\nPowerful and crisp 3D stereo sound with a 500 watt THX-certified speaker. The sufbwoofer has a bass port. Movies and games reach another level with a few sustained by 165 watts of power. Enjoy the Z906 comfortably from the sofa and set them up one by one with your remote and console.\n\nInstall them and make each night a premiere. Let the walls tremble with this sound system!\n\nCharacteristics:\n\n- 500 watts (RMS) of power\n- Digital Decoding\n- Easy-to-read control console\n- Subwoofer with bass port and side output\n- Surround sound with 3D stereo\n- THX® Certification\n- Analog and digital inputs\n- Wireless remote control\n- Satellite speakers with wall mounting capability', 79.95, 0, 'resources/img/product/speakers1.jpg');
		";
		$insertValue[3] = "
			INSERT INTO product VALUES(NULL, 'SanDisk Cruzer Blade 32GB USB', 'USB Flash Drives', 'Carry your favorite files with you on the ultra-compact and portable Cruzer® BladeTM USB flash drive. It is the most convenient and quick way to save and transfer your digital files from computer to computer, or take them with you when you move. Simply copy your pictures, songs and other files you want into the stylish Cruzer Blade USB flash drive to start sharing them with your friends and family.\n\nThis USB flash drive also allows you to prevent unauthorized access to your files with built-in SanDisk® SecureAccessTM software and enjoy the additional protection of an online backup (up to 2 GB) offered by the backup partner in SanDisk line. † Trust SanDisk® to protect your important files.\n\nCharacteristics:\n\n- An elegant and ultra-compact portable unit\n- Share photos, videos, songs and other files between computers with ease\n- Protect your private files with built-in SanDisk® SecureAccess ™ software\n- Store more files thanks to its 32GB capacity\n- Backed by a five-year limited warranty\n- SanDisk® SecureAccess ™ software compatible with Windows XP®, Windows Vista®, Windows 7®, and Mac OS X v10.5', 26.45, 80, 'resources/img/product/pendrive1.jpg');
		";
		$insertValue[4] = "
			INSERT INTO product VALUES(NULL, 'SilverStone ST70F-ESG Power Supply 700W', 'Power Supplies', 'SilverStone ST70F-ESG power supply. SilverStone Essential Gold are 80 PLUS Gold certified FAs within the affordable Strider Essential series. They retain their practical and reliable fixed wire arrangement of other Strider Essential FAs and adhere to the same outstanding features of all Strider FAs without reducing quality or reliability. The 80 PLUS Gold certification ensures that Strider Essential Gold FAs produce 40% less waste heat wasted by energy conversion compared to basic 80 PLUS certified FAs, resulting in lower electrical consumption and cooling. In addition, features of the higher-level Strider series, such as voltage regulation ± 3%, single-rail design with high amperage + 12V and support for multiple graphics cards are preserved to provide the best quality solutions at any price.\n\nCharacteristics:\n\n- High efficiency with 80 PLUS Gold certification\n- Continuous output power with operating temperature of 40ºC\n- First class single rail with + 12V\n- Strict voltage regulation of ± 3%\n- Circuitry with multiple protection\n- Accepts 8-pin and 6-pin PCI-E connectors\n- 120mm HYB Fan\n- Active PFC', 117.85, 300, 'resources/img/product/power_supply1.jpg');
		";
		$insertValue[5] = "
			INSERT INTO product VALUES(NULL, 'HDMI wire 1.4 male / male 15m', 'Wires', 'HDMI wire 3D of 15m ideal for all types of devices that use the HDMI interface.\n\nCharacteristics:\n\n- Connector 1: HDMI type A, male\n- Connector 2: HDMI type A, male\n- Standard HDMI: High speed HDMI with Ethernet\n- Cable type: CAT 2\n- UL Certification: UL20276\n- Standard HDTV: 1080\n- HDTV Resolution max .: 1920 x 1200 Pixel, 60Hz\n- Molded\n- Protection: triple protection\n- Material of thread: CU\n- AWG: 28\n- Connector: gold plated\n- Color of the cable: black\n- Connector Color: Black\n- Length: 15 Mts', 1.50, 200, 'resources/img/product/wire1.jpg');
		";
		$insertValue[6] = "
			INSERT INTO product VALUES(NULL, 'LG DVD Recorder. Internal Slim 9.5mm SATA', 'Accessories', 'Introducing LG´s GUD0N.AUAA10B, an internal DVD Slim recorder with playback disc and M-Disc support.\n\nThe player of your dreams, with an image of great realism and able to reproduce practically any type of disc. You will enjoy the most of your movies and music.\n\nCharacteristics:\n\n- DivX Ultra certification for enhanced DivX video playback\n- 12-bit / 108 Mhz video processing for a crisp, natural image\n- USB media connection for multimedia playback from USB flash drives\n- ProReader Drive for smooth playback with almost any disc\n- Screen adjustment for optimal viewing at all times\n- Intelligent Image Control allows you to customize settings', 24.85, 34, 'resources/img/product/accessory1.jpg');
		";
		$insertValue[7] = "
			INSERT INTO product VALUES(NULL, 'Creative Inspire T3300 2.1', 'Speakers', 'Desktop speaker system of high-performance 2.1 with impressive volume and bass.\n\nInspire is the most sonic model yet, but with innovations delivering excellent performance, the Creative Inspire T3300 delivers stunning bass and full spectrum audio that is simply the best of its kind. For starters, the speaker system includes the largest subwoofer with bass port and lower output of its class with bass level adjustment. In addition, we have improved the audio spectrum of the satellites by incorporating Creative DSE (Dual Slot Enclosure), a design feature that makes it possible to implement a conventional port tube in an airtight enclosure. When used with Creative IFP (Image Focusing Plate), a function that improves the direction of the sound and the organization of the listening areas, it is achieved that the music has more volume, is better oriented and has more severe means, maintaining the same Time tonal precision.\n\nCharacteristics:\n\n- Creative DSE (Dual Slot Enclosure) incorporates a conventional port tube and fully operative in an airtight enclosure. It improves notably the distribution of the frequency in the whole system, providing a reproduction with more volume and more serious means.\n- The enhanced IFP (Image Focusing Plate) feature includes a wider part that surrounds the satellites to improve the direction and organization of the sound, resulting in a wider optimum acoustic level while maintaining pitch precision.\n- The subwoofer with bass port and lower bass output of its class with bass level adjustment.\n- Remote control with integrated cable with on / off button and volume control.', 39.95, 16, 'resources/img/product/speakers2.jpg');
		";
		$insertValue[8] = "
			INSERT INTO product VALUES(NULL, 'Kingston DataTravel R3.0 G2 64GB USB 3.0', 'USB Flash Drives', 'Kingston´s DataTraveler® R3.0 G2 USB Flash Drive features high-speed USB 3.0 technology with speeds of 120 MB/s in read and 45 MB/s in write (16 GB drives have speeds of 120 MB / s In read and 25 MB / s in writing), which saves time when transferring files. It is 8 times faster than a standard 2.0 drive and saves about 20 minutes when transferring a full HD 1080p video.\n\nIts stunning speed relieves bottlenecks by storing or viewing HD videos, music libraries, digital graphics, presentations, or other large files. The DTR30G2 has a sturdy rubber-coated casing with a waterproof and shock-resistant certificate that allows it to withstand shock and impact when moving from one place to another. The DTR30G2 drives are compatible with USB 2.0 technology and are backed by a five-year warranty, free technical support and legendary Kingston reliability.\n\nCharacteristics:\n\n- USB 3.0 High Speed Performance\n- 8 times faster than a USB 2.0 drive\n- Durable, state-of-the-art unit with reduced form factor for easy transport\n- Waterproof and impact resistant', 31, 67, 'resources/img/product/pendrive2.jpg');
		";
		$insertValue[9] = "
			INSERT INTO product VALUES(NULL, 'Nox Krom Khalon Keyboard Games RGB', 'Keyboards', 'Krom introduces a new keyboard, Khalon. It is a membrane keyboard with high performance and multiple customization options, which will allow you to adjust the keyboard options to various games with great versatility.\n\nCharacteristics:\n\n- Ergonomics and comfort\n- Maximum customization\n- Multiple choices', 28, 4, 'resources/img/product/keyboard2.jpg');
		";
		$insertValue[10] = "
			INSERT INTO product VALUES(NULL, 'Cable USB 2.0 a Mini USB 3m M/M', 'Wires', 'USB 2.0 cable of 3 meters that at one end has a connection USB 2.0 male type A and at the other end a connection Mini USB male type B\n\n- Made with double shielded cable\n- 4 Conductors (UL2725, 2 x AWG 28, 2 x AWG 26)\n- Injected plastic enclosures\n- Connector 1: USB A (6-pin) Male\n- Connector 2: Mini USB B (5-pin) Male\n- Compatible USB Vers. 1.1 and 2.0\n- Compatible with PSP (PlayStation Portable)\n- Length 3 meters', 1.50, 200, 'resources/img/product/wire2.jpg');
		";
		$insertValue[11] = "
			INSERT INTO product VALUES(NULL, 'Trust MaxTrack Compact Bluetooth Mouse 1600DPI', 'Mouses', 'We present the Maxtrack Bluethooth Compact from Trust, a compact wireless optical mouse with Bluetooth technology.\n\nCharacteristics:\n\n- No USB receiver required\n- Rubber inlay design for firmer grip\n- Speed select button (800-1200-1600 dpi)\n- 10 m wireless coverage\n- Also compatible with Android tablets', 24.95, 111, 'resources/img/product/mouse2.jpg');
		";
		$insertValue[12] = "
			INSERT INTO product VALUES(NULL, 'USB 2.0 Wire AM/AH 1.8m Male/Female Extension Wire', 'Wires', '- Made with double shielded cable\n- 4 conductors (UL2725, 2 x AWG24, 2 x AWG28)\n- Injection molded plastic enclosures\n- Connector 1: USB A (6-pin) Male\n- Connector 2: USB A (6 Pin) Female\n- Compatible USB Version 1.1\n- USB Certificate Version 2.0\n\nNote: Color may be black or white depending on availability.', 1.75, 26, 'resources/img/product/wire3.jpg');
		";
		$insertValue[13] = "
			INSERT INTO product VALUES(NULL, 'Xbox One Power Supply', 'Power Supplies', 'Replacement power adapter for Xbox One. Whether to replace the battered adapter or to have a spare one and never stop enjoying your new console. The power adapter has an indicator light to show the power status of the console, and its automatic voltage feature allows the adapter to be used around the world.This is the best option to replace your faulty adapter. Replacing it will take a few seconds and still enjoy your console.\n\nSpecifications:\n\n- Input: AC 100-240V. 2A. 47-63Hz\n- Output: DC 135W. 12V. 10,83A. 5Vsb. 1A\n\nNote: Plug with connection for Spain', 17.95, 300, 'resources/img/product/power_supply2.jpg');
		";
		$insertValue[14] = "
			INSERT INTO product VALUES(NULL, 'The G-Lab Microphone Hi-Fi Gaming', 'Accessories', 'Introduce yourself the G-Lab Hi-fi microphone.\n\nCharacteristics:\n\n- Great for use when playing with speakers and need to talk to other players\n- Perfect sound thanks to the USB digital connection\n- A directional sensor that cancels the surrounding noise\n- An illuminated base that can be turned on / off\n- Flexible structure\n- A useful mute button\n- Plug & Play, compatible', 25, 21, 'resources/img/product/accessory2.jpg');
		";
		$insertValue[15] = "
			INSERT INTO product VALUES(NULL, 'G.Skill MicroSDXC 64GB Class 10 + SD Adapter', 'Accessories', 'Introduce yourself the G-Lab Hi-fi microphone.\n\nCharacteristics:\n\n- Great for use when playing with speakers and need to talk to other players\n- Perfect sound thanks to the USB digital connection\n- A directional sensor that cancels the surrounding noise\n- An illuminated base that can be turned on / off\n- Flexible structure\n- A useful mute button\n- Plug & Play, compatible', 28, 60, 'resources/img/product/accessory3.jpg');
		";
		$insertValue[16] = "
			INSERT INTO product VALUES(NULL, 'NGS Clipper Red USB Keyboard', 'Keyboards', 'This keyboard has a glossy black finish giving it elegance and personality. It also incorporates a USB connection with Plug & Play function so the connection to any equipment will be made immediately. The pulsation of the 104 membrane keys is very smooth and the noise they emit when being pulsed is practically imperceptible. Finally, it incorporates a classic design that is resistant to spills, which offers a longer operation, approximately 10 million pulsations.\n\nNote: All our products are distributed by official Spanish channel, so all keyboards include Ñ and layout of Spanish keys, and may differ from the distribution of keys of the images shown, unless clearly stated otherwise.', 14.95, 38, 'resources/img/product/keyboard3.jpg');
		";
		$insertValue[17] = "
			INSERT INTO product VALUES(NULL, 'NGS Ice 2400DPI Gray Mouse', 'Mouses', 'It is the first mouse in the world with effects of light with the colors of the rainbow and integral finish in silver color. It includes a system of emission of neon light that makes the emitted light has a harmonious beauty thanks to our Lighting-S conductor. It is bathed with new paint technology called FILM-Silverized, using a silver finish that resembles a piece made in said metal, but at the same time semitransparent. It will light up like an art deco jewel on your desk.\n\nInput device:\n\n- Device Interface: USB\n- Button Type: Pressed buttons\n- Type of displacement: Wheel\n- Movement Resolution: 2400 DPI\n- Number of scroll wheels: 1\n- Customizable resolution movement: Y\n- Number of resolution modes: 3\n\nDesign:\n\n- Form Factor: Ambidextrous\n- Color of product: Gray\n- Illumination: Yes\n- Surface Coloration: Monotonous', 19.95, 42, 'resources/img/product/mouse3.jpg');
		";
		$insertValue[18] = "
			INSERT INTO product VALUES(NULL, 'Trust Vigor 2.1 Subwoofer Speaker Set', 'Speakers', 'Introducing the Vigor 2.1 Subwoofer Speaker Set from Trust, a powerful subwoofer 2.1 speaker set to listen to audio from PCs, tablets, phones or other audio devices.\n\nCharacteristics:\n\n- RMS 50 W, maximum power 100 W\n- Rugged design, subwoofer and wood satellites\n- High quality sound with low penetrants\n- Comfortable wired control for easy volume control with headphones and line-in jacks\n- Volume control and subwoofer bass sounds\n- For use with PCs, tablets, TV, DVD, CD, MP3 or other audio devices', 69.95, 10, 'resources/img/product/speakers3.jpg');
		";
		$insertValue[19] = "
			INSERT INTO product VALUES(NULL, 'Corsair Voyager GS 512GB USB 3.0', 'USB Flash Drives', 'Introducing Corsair´s Voyager GS 512GB USB 3.0 flash drive, a high-performance, high-performance USB 3.0 flash drive in an elegant, rugged case. It takes full advantage of the high-speed USB 3.0 interface and offers full USB 2.0 compatibility for older systems. And the zinc alloy body with aluminum accents make it a USB drive from which you´ll want to brag.\n\nCharacteristics:\n\n- Optimized for performance\n-  USB 3.0 speed and compatibility with USB 2.0\n- The top-level performance is combined with a top-level design', 219, 12, 'resources/img/product/pendrive3.jpg');
		";

		foreach($insertValue as $value){
		    if ($result = $connection->query($value)){
	            if (!$result)
	                echo "Impossible to insert the values of product table";
	        }else
	            echo "Wrong Query";
        }
	}


// ██╗   ██╗███████╗███████╗██████╗
// ██║   ██║██╔════╝██╔════╝██╔══██╗
// ██║   ██║███████╗█████╗  ██████╔╝
// ██║   ██║╚════██║██╔══╝  ██╔══██╗
// ╚██████╔╝███████║███████╗██║  ██║
//  ╚═════╝ ╚══════╝╚══════╝╚═╝  ╚═╝

	function getUserData($connection, $userID){
		$userData = [];
		$getCustomer = "SELECT *
	                    FROM customer
	                    WHERE idcustomer = $userID;
	                   ";

	    if ($result = $connection->query($getCustomer)) {
	        if ($result->num_rows > 0){
	            $customer = $result->fetch_object();
				$userData['id']       = $customer->idcustomer;
				$userData['name']     = $customer->name;
				$userData['surname']  = $customer->surname;
				$userData['email']    = $customer->email;
				$userData['address']  = $customer->address;
				$userData['phone']    = $customer->phone;
				$userData['type']     = $customer->type;
				$userData['username'] = $customer->username;
				$userData['pass']     = $customer->password;

	        	return $userData;
	        }else
	            echo "Impossible to get the user data <br><br>";
	    }else
	        echo "Wrong Query";
	}

	function getAllUser($connection){
		$userData = [[]];
		$i = 0;
		$getCustomer = "SELECT * FROM customer;";

        if ($result = $connection->query($getCustomer)){
            if ($result->num_rows > 0){
            	while($customer = $result->fetch_object()){
					$userData['id'][$i]       = $customer->idcustomer;
					$userData['name'][$i]     = $customer->name;
					$userData['surname'][$i]  = $customer->surname;
					$userData['email'][$i]    = $customer->email;
					$userData['address'][$i]  = $customer->address;
					$userData['phone'][$i]    = $customer->phone;
					$userData['type'][$i]     = $customer->type;
					$userData['username'][$i] = $customer->username;
					$userData['password'][$i] = $customer->password;
					$i++;
            	}
            	return $userData;
            }else
                echo "Impossible to get all the users";
        }else
            echo "Wrong Query";
	}

	function refreshUsers($connection){
		global $userID;
		global $userName;
		global $userSurname;
		global $userEmail;
		global $userAddress;
		global $userPhone;
		global $userType;
		global $userUsername;
		global $userPassword;

		$usersData = getAllUser($connection);
		$userID       = $usersData['id'];
		$userName     = $usersData['name'];
		$userSurname  = $usersData['surname'];
		$userEmail    = $usersData['email'];
		$userAddress  = $usersData['address'];
		$userPhone    = $usersData['phone'];
		$userType     = $usersData['type'];
		$userUsername = $usersData['username'];
		$userPassword = $usersData['password'];
	}

	function deleteUser($connection, $userID){
        $deleteUser = "DELETE FROM customer WHERE idcustomer = $userID;";

	    if ($result = $connection->query($deleteUser)) {
            if (!$result)
                echo "Impossible to delete the user";
        }else
            echo "Wrong Query";
	}

	function insertUser($connection, $userData){
		$id      = $userData['id'];
		$name    = $userData['name'];
		$surname = $userData['surname'];
		$email   = $userData['email'];
		$address = $userData['address'];
		$phone   = $userData['phone'];
		$type    = $userData['type'];
		$user    = $userData['user'];
		// If password is encoded with sha1 algorithm
		$pass = (strlen($userData['pass']) >= 40) ? $userData['pass'] : sha1($userData['pass']);

        $insertUser = "INSERT INTO customer
                       VALUES($id, '$name', '$surname', '$email', '$address', '$phone', '$type', '$user', '$pass');
                      ";

	    if ($result = $connection->query($insertUser)) {
            if (!$result)
                echo "Impossible to insert the user";
        }else
            echo "Wrong Query";
	}


// ██████╗ ██████╗  ██████╗ ██████╗ ██╗   ██╗ ██████╗████████╗
// ██╔══██╗██╔══██╗██╔═══██╗██╔══██╗██║   ██║██╔════╝╚══██╔══╝
// ██████╔╝██████╔╝██║   ██║██║  ██║██║   ██║██║        ██║
// ██╔═══╝ ██╔══██╗██║   ██║██║  ██║██║   ██║██║        ██║
// ██║     ██║  ██║╚██████╔╝██████╔╝╚██████╔╝╚██████╗   ██║
// ╚═╝     ╚═╝  ╚═╝ ╚═════╝ ╚═════╝  ╚═════╝  ╚═════╝   ╚═╝

	function getProductData($connection, $prodID){
	    $productData = [];
	    $getproduct = "SELECT *
	                   FROM product
	                   WHERE idproduct = $prodID;
	                  ";

	    if ($result = $connection->query($getproduct)) {
	        if ($result->num_rows > 0){
	        	$product = $result->fetch_object();

				$productData['id']          = $product->idproduct;
				$productData['name']        = $product->name;
				$productData['category']    = $product->category;
				$productData['description'] = $product->description;
				$productData['price']       = $product->price;
				$productData['amount']      = $product->amount;
				$productData['urlImage']    = $product->urlimage;

				return $productData;
	        }else
	            echo "Impossible to get the product data";
	    }else
	        echo "Wrong Query";
    }

	function getProductDataArray($connection, $prodID){
		$productData = [[[]]];

		for($i=0;$i<count($prodID);$i++){				// Orders
			for($j=0;$j<count($prodID[$i]);$j++){		// Values
		        $getProducts = "SELECT *
		                        FROM product
		                        WHERE idproduct = ".$prodID[$i][$j].";
		                       ";

		        if ($result = $connection->query($getProducts)) {
		            if ($result->num_rows > 0){
		            	$product = $result->fetch_object();

						$productData['name'][$i][$j]        = $product->name;
						$productData['category'][$i][$j]    = $product->category;
						$productData['description'][$i][$j] = $product->description;
						$productData['price'][$i][$j]       = $product->price;
						$productData['amount'][$i][$j]      = $product->amount;
						$productData['urlImage'][$i][$j]    = $product->urlimage;
		            }else
		                echo "Impossible to get the products";
		        }else
		            echo "Wrong Query";
		    }
		}
	    return $productData;
	}

    function getAllProduct($connection){
		$productData = [[]];
		$i = 0;
		$getProducts = "SELECT * FROM product;";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
					$productData['id'][$i]          = $product->idproduct;
					$productData['name'][$i]        = $product->name;
					$productData['category'][$i]    = $product->category;
					$productData['description'][$i] = $product->description;
					$productData['price'][$i]       = $product->price;
					$productData['amount'][$i]      = $product->amount;
					$productData['urlImage'][$i]    = $product->urlimage;
					$i++;
            	}
            }else
                echo "Impossible to get the products";
        }else
            echo "Wrong Query";

        return $productData;
	}

	function refreshProducts($connection){
		global $productID;
		global $productName;
		global $productCategory;
		global $productDescript;
		global $productPrice;
		global $productAmount;
		global $productImg;

		$productsData = getAllProduct($connection);
		// != 1  -> The array isn't empty. '1' because array is 2 dimensions
		$productID       = (count($productsData) != 1) ? $productsData['id'] : [];
		$productName     = (count($productsData) != 1) ? $productsData['name'] : [];
		$productCategory = (count($productsData) != 1) ? $productsData['category'] : [];
		$productDescript = (count($productsData) != 1) ? $productsData['description'] : [];
		$productPrice    = (count($productsData) != 1) ? $productsData['price'] : [];
		$productAmount   = (count($productsData) != 1) ? $productsData['amount'] : [];
		$productImg      = (count($productsData) != 1) ? $productsData['urlImage'] : [];
	}

	function getProductCategory($connection, $categ){
		$productData = [[]];
		$i = 0;
		$getProducts = "SELECT * FROM product WHERE category = '$categ';";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
					$productData['id'][$i]       = $product->idproduct;
					$productData['name'][$i]     = $product->name;
					$productData['price'][$i]    = $product->price;
					$productData['urlImage'][$i] = $product->urlimage;
					$i++;
            	}
            }else
                echo "Impossible to get the products by category";
        }else
            echo "Wrong Query";
        return $productData;
	}

	function getAllProductCategory($connection){
		$productCategory = [];
        $getProducts = "SELECT DISTINCT category FROM product ORDER BY category ASC;";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object())
            		array_push($productCategory, $product->category);
            	return $productCategory;
            }else
                echo "Impossible to get the products category";
        }else
            echo "Wrong Query";
	}

	function deleteProduct($connection, $productID){
        $deleteProduct = "DELETE FROM product WHERE idproduct = $productID;";

	    if ($result = $connection->query($deleteProduct)) {
            if (!$result)
                echo "Impossible to delete the product";
        }else
            echo "Wrong Query";
	}

	function insertProduct($connection, $productData){
		$id       = $productData['id'];
		$name     = $productData['name'];
		$category = $productData['category'];
		$descript = $productData['description'];
		$price    = $productData['price'];
		$amount   = $productData['amount'];
		$urlImage = $productData['urlImage'];

        $insertUser = "INSERT INTO product
                       VALUES($id, '$name', '$category', '$descript', '$price', '$amount', '$urlImage');
                      ";

	    if ($result = $connection->query($insertUser)) {
            if (!$result)
                echo "Impossible to insert the product";
        }else
            echo "Wrong Query";
	}



// ███████╗██╗  ██╗ ██████╗ ██████╗ ██████╗ ██╗███╗   ██╗ ██████╗      ██████╗ █████╗ ██████╗ ████████╗
// ██╔════╝██║  ██║██╔═══██╗██╔══██╗██╔══██╗██║████╗  ██║██╔════╝     ██╔════╝██╔══██╗██╔══██╗╚══██╔══╝
// ███████╗███████║██║   ██║██████╔╝██████╔╝██║██╔██╗ ██║██║  ███╗    ██║     ███████║██████╔╝   ██║
// ╚════██║██╔══██║██║   ██║██╔═══╝ ██╔═══╝ ██║██║╚██╗██║██║   ██║    ██║     ██╔══██║██╔══██╗   ██║
// ███████║██║  ██║╚██████╔╝██║     ██║     ██║██║ ╚████║╚██████╔╝    ╚██████╗██║  ██║██║  ██║   ██║
// ╚══════╝╚═╝  ╚═╝ ╚═════╝ ╚═╝     ╚═╝     ╚═╝╚═╝  ╚═══╝ ╚═════╝      ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝

    function deleteProductFromCart($connection, $id){
        $getProducts = "DELETE FROM shopping_cart WHERE idproduct = $id;";

        if ($result = $connection->query($getProducts)) {
            if (!$result)
                echo "Impossible to delete the product";
        }else
            echo "Wrong Query";
    }

    function clearCart($connection){
        $getProducts = "DELETE FROM shopping_cart;";

        if ($result = $connection->query($getProducts)) {
            if (!$result)
                echo "Impossible to clear the cart";
        }else
            echo "Wrong Query";
    }

	function getCartProductAndAmount($connection, $userID){
        $cartProductAndAmount = [[]];
        $i = 0;
        $getCart = "SELECT *
                    FROM shopping_cart
                    WHERE idcustomer = $userID;
                   ";

        if ($result = $connection->query($getCart)) {
            if ($result->num_rows > 0){
                while($product = $result->fetch_object()){
					$cartProductAndAmount['idProduct'][$i] = $product->idproduct;
					$cartProductAndAmount['amount'][$i]    = $product->amount;
					$i++;
				}
            }else{
                // echo "The shopping cart is empty";
            }
        }else
            echo "Wrong Query";
		return $cartProductAndAmount;
    }

    function getProductIDFromContain($connection, $userID){
        $cartProductID = [];	// Asociative array
        $getusername = "SELECT *
                        FROM contain
                        WHERE idcustomer = $userID;
                       ";

        if ($result = $connection->query($getusername)) {
            if ($result->num_rows > 0){
                while($product = $result->fetch_object())
					$cartProductID[$product->idproduct] = $product->amount;
            }else{
                // echo "The shopping cart is empty";
            }
        }else
            echo "Wrong Query";
		return $cartProductID;
    }

	function getProductDataFromCart($connection, $prodID){
		$cartProductData = [[]];

		for($i=0;$i<count($prodID);$i++){
	        $getProducts = "SELECT *
	                        FROM product
	                        WHERE idproduct = ".$prodID[$i].";
	                       ";

	        if ($result = $connection->query($getProducts)) {
	            if ($result->num_rows > 0){
	            	$product = $result->fetch_object();
					$cartProductData['name'][$i]  = $product->name;
					$cartProductData['price'][$i] = $product->price;
	            }else
	                echo "Impossible to get the products";
	        }else
	            echo "Wrong Query";
		}
	    return $cartProductData;
	}

    function checkProductIsInserted($connection, $prodID){
	    $productInserted = false;

        $getproduct = "SELECT idproduct
        			   FROM shopping_cart
        			   WHERE idproduct = $prodID;
                      ";

        if ($result = $connection->query($getproduct)){
			$productInserted = ($result->num_rows > 0) ? true : false;
        	return $productInserted;
        }else
            echo "Wrong Query";
    }

    function addToCart($connection, $userID, $prodID, $prodAmount){
	    $getproduct = "INSERT INTO shopping_cart
        			   VALUES($userID, $prodID, $prodAmount);
                      ";

        if ($result = $connection->query($getproduct)){
            if (!$result)
                echo "Impossible insert the product within shopping cart";
        }else
            echo "Wrong Query";
    }

	function getAmountProductOfCart($connection, $prodID){
        $getproduct = "SELECT amount
        			   FROM shopping_cart
        			   WHERE idproduct = $prodID;
                      ";

        if ($result = $connection->query($getproduct)){
            if ($result->num_rows > 0)
            	return $result->fetch_object()->amount;
        }else
            echo "Wrong Query";
	}

    function riseAmountProductOfCart($connection, $currentAmount, $newAmount, $userID, $prodID){
        $quantity = $currentAmount;
        $quantity += $newAmount;

        $getproduct = "UPDATE shopping_cart
        			   SET amount = $quantity
                       WHERE idproduct = $prodID;
                      ";

        if ($result = $connection->query($getproduct)){
            if (!$result)
                echo "Impossible update the new quantity of a product";
        }else
            echo "Wrong Query";
    }

	function refreshCart($connection){
		global $cartProductIDAndAmount;
		global $cartProductsNumber;
		global $cartTotalPrice;
		global $cartProductID;
		global $cartProductAmount;
		global $cartProductData;
		global $cartProductName;
		global $cartProductPrice;

        // Get client's products from shopping cart
		$cartProductIDAndAmount = getCartProductAndAmount($connection, $_SESSION['userID']);
		$cartProductID     = (count($cartProductIDAndAmount) != 1) ? $cartProductIDAndAmount['idProduct']: [];
		$cartProductAmount = (count($cartProductIDAndAmount) != 1) ? $cartProductIDAndAmount['amount'] : [];

		$cartProductData  = getProductDataFromCart($connection, $cartProductID);
		$cartProductName  = (count($cartProductData) != 1) ? $cartProductData['name'] : [];
		$cartProductPrice = (count($cartProductData) != 1) ? $cartProductData['price'] : [];

		$cartProductsNumber = count($cartProductID);
		$cartTotalPrice     = 0;

		// Calculates the purchase price
		for($i=0;$i<$cartProductsNumber;$i++)
			$cartTotalPrice += $cartProductPrice[$i] * $cartProductAmount[$i];
	}


//  ██████╗ ██████╗ ██████╗ ███████╗██████╗
// ██╔═══██╗██╔══██╗██╔══██╗██╔════╝██╔══██╗
// ██║   ██║██████╔╝██║  ██║█████╗  ██████╔╝
// ██║   ██║██╔══██╗██║  ██║██╔══╝  ██╔══██╗
// ╚██████╔╝██║  ██║██████╔╝███████╗██║  ██║
//  ╚═════╝ ╚═╝  ╚═╝╚═════╝ ╚══════╝╚═╝  ╚═╝


    function getOrderData($connection, $orderID){
	    $orderData = [];
	    $getOrder = "SELECT *
	                 FROM order2
	                 WHERE idorder = $orderID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	$order = $result->fetch_object();
				$orderData['orderID']        = $order->idorder;
				$orderData['customerID']     = $order->idcustomer;
				$orderData['date']           = $order->dateorder;
				$orderData['amountProducts'] = $order->amountproducts;
				$orderData['price']          = $order->totalprice;
				return $orderData;
	        }else{
	            // echo "Impossible to get the order data";
	        }
	    }else
	        echo "Wrong Query";
    }

	function getOrderDataOfAClient($connection, $userID){
	    $orderData = [[]];
		$i = 0;
	    $getOrder = "SELECT *
	                 FROM order2
	                 WHERE idcustomer = $userID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	while($order = $result->fetch_object()){
					$orderData['orderID'][$i]    = $order->idorder;
					$orderData['customerID'][$i] = $order->idcustomer;
					$orderData['date'][$i]       = $order->dateorder;
					$orderData['amount'][$i]     = $order->amountproducts;
					$orderData['price'][$i]      = $order->totalprice;
					$i++;
				}
				return $orderData;
	        }else{
	            // echo "Impossible to get the order data";
	        }
	    }else
	        echo "Wrong Query";
    }

    function getAllOrders($connection){
		$orderData = [[]];
		$i = 0;
		$getOrders = "SELECT * FROM order2;";

        if ($result = $connection->query($getOrders)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
					$orderData['orderID'][$i]    = $product->idorder;
					$orderData['customerID'][$i] = $product->idcustomer;
					$orderData['date'][$i]       = $product->dateorder;
					$orderData['amount'][$i]     = $product->amountproducts;
					$orderData['price'][$i]      = $product->totalprice;
					$i++;
            	}
            }else{
                // echo "Impossible to get the orders";
            }
        }else
            echo "Wrong Query";

        return $orderData;
	}

	function getOrderProductAndAmountFromArray($connection, $orderID){
	    $orderProductAndAmount = [[[]]];	// idProduct - order - value

	    for($i=0;$i<count($orderID);$i++){	// Orders (i) - $orderID is a array
	 	    $j = 0;							// Values (j)
		    $getOrder = "SELECT *
		                 FROM contain
		                 WHERE idorder = ".$orderID[$i].";
		                ";

		    if ($result = $connection->query($getOrder)) {
		        if ($result->num_rows > 0){
		        	while($order = $result->fetch_object()){	// A order have a few rows in the database
						$orderProductAndAmount['idProduct'][$i][$j] = $order->idproduct;
						$orderProductAndAmount['amount'][$i][$j]    = $order->amount;
						$j++;
					}
		        }else
		            echo "Impossible to get the relationship between product and its quantity.";
		    }else
		        echo "Wrong Query";
		}
		return $orderProductAndAmount;
	}

	function getOrderProductAndAmount($connection, $orderID){
	    $orderProductAndAmount = [[]];	// idProduct - value
 	    $i = 0;
	    $getOrder = "SELECT *
	                 FROM contain
	                 WHERE idorder = $orderID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	while($order = $result->fetch_object()){
					$orderProductAndAmount['idProduct'][$i] = $order->idproduct;
					$orderProductAndAmount['amount'][$i]    = $order->amount;
					$i++;
				}
	        }else
	            echo "Impossible to get the relationship between product and its quantity.";
	    }else
	        echo "Wrong Query";

		return $orderProductAndAmount;
	}

	function refreshOrders($connection){
		global $orderOrderID;
		global $orderCustomerID;
		global $orderDate;
		global $orderAmount;
		global $orderPrice;

		$ordersData = getAllOrders($connection);

		// != 1  -> The array isn't empty. '1' because array is 2 dimensions
		$orderOrderID    = (count($ordersData) != 1) ? $ordersData['orderID'] : [];
		$orderCustomerID = (count($ordersData) != 1) ? $ordersData['customerID'] : [];
		$orderDate       = (count($ordersData) != 1) ? $ordersData['date'] : [];
		$orderAmount     = (count($ordersData) != 1) ? $ordersData['amount'] : [];
		$orderPrice      = (count($ordersData) != 1) ? $ordersData['price'] : [];
	}

	function refreshOrdersOfAClient($connection, $userID){
		global $orderOrderID;
		global $orderCustomerID;
		global $orderDate;
		global $orderAmountProd;
		global $orderPrice;
		global $orderProduct;
		global $orderAmount;
		global $productName;
		global $productPrice;

		$ordersData = getOrderDataOfAClient($connection, $userID);								// Return 2 dimensions
		$orderOrderID    = $ordersData['orderID'];
		$orderCustomerID = $ordersData['customerID'];
		$orderDate       = $ordersData['date'];
		$orderAmountProd = $ordersData['amount'];
		$orderPrice      = $ordersData['price'];

		// != 1  -> The array isn't empty. '1' because array is 3 dimensions
		$orderProductAndAmount = getOrderProductAndAmountFromArray($connection, $orderOrderID);	// Return 3 dimensions
		$orderProduct = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['idProduct'] : [];
		$orderAmount  = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['amount'] : [];

		$productData = getProductDataArray($connection, $orderProduct);							// Return 3 dimensions
		$productName  = (count($productData) != 1) ? $productData['name'] : [];
		$productPrice = (count($productData) != 1) ? $productData['price'] : [];

		/*
		$productName[0][0]				// Order 0 - Prod 1
		$productName[1][0]				// Order 1 - Prod 1
		$productName[1][1]				// Order 1 - Prod 2
		$productName[1][2]				// Order 1 - Prod 3

		echo count($productName);		// 2 Orders

		echo count($productName[0]);	// Order 1 - 1 Prod
		echo count($productName[1]);	// Order 2 - 3 Prod
		*/
	}

	function deleteOrder($connection, $orderID){
        $deleteOrder = "DELETE FROM order2 WHERE idorder = $orderID;";

	    if ($result = $connection->query($deleteOrder)) {
            if (!$result)
                echo "Impossible to delete the order";
        }else
            echo "Wrong Query";
	}

	function insertOrder($connection, $orderData){
		$orderID        = $orderData['orderID'];
		$customerID     = $orderData['customerID'];
		$date           = (strlen($orderData['date']) == 0) ? date('Y-m-d') : $orderData['date'];
		$amountProducts = $orderData['amountProducts'];
		$price          = $orderData['price'];
		$lastID			= 0;

        $insertOrder = "INSERT INTO order2
                        VALUES($orderID, $customerID, '$date', $amountProducts, $price);
                       ";

	    if ($result = $connection->query($insertOrder)) {

            if ($result)
            	$lastID = $connection->insert_id;
            else
                echo "Impossible insert within of the order.";

        }else
            echo "Wrong Query";

        return $lastID;
	}

	function insertContain($connection, $idorder, $productID, $productAmount){
        for($i=0;$i<count($productID);$i++){
	    	$setPurchase = "INSERT INTO contain
	        			    VALUES($idorder, $productID[$i], $productAmount[$i]);
	                       ";

	        if ($result = $connection->query($setPurchase)){
	            if (!$result)
	                echo "Impossible insert within of the contain table.";
	        }else
	            echo "Wrong Query";
	    }
	}



// ██████╗ ██╗   ██╗███╗   ██╗         ██╗███████╗
// ██╔══██╗██║   ██║████╗  ██║         ██║██╔════╝
// ██████╔╝██║   ██║██╔██╗ ██║         ██║███████╗
// ██╔══██╗██║   ██║██║╚██╗██║    ██   ██║╚════██║
// ██║  ██║╚██████╔╝██║ ╚████║    ╚█████╔╝███████║
// ╚═╝  ╚═╝ ╚═════╝ ╚═╝  ╚═══╝     ╚════╝ ╚══════╝


	// Check if user is logged and access only allowed pages
	function checkAccesOption($pageBelongs){
		if(isset($_SESSION["userID"])){
            if($_SESSION["userType"] != $pageBelongs){
            	session_destroy();
				header('Location: index.php');
            }
		}else
			header('Location: index.php');
	}

	// Return you to the first page
	function checkAccesOptionOnLogin(){
		if(isset($_SESSION["userID"])){
            if($_SESSION["userType"] == "Admin")
                header('Location: admin.php');
            else if($_SESSION["userType"] == "User")
                header('Location: menu.php');
		}
	}

	function loadModalWindow($modalWindow, $buttonClose){
		echo "<script>
				window.onload = function(){
					loadModalWindow('$modalWindow', '$buttonClose');
			 	};
			  </script>
			 ";
	}

	function showToast($message){
		echo "<div id='toast'>$message</div>";
        echo "<script>loadToast();</script>";
	}

	function toggleDesignCart(){
		global $cartProductsNumber;

		if($cartProductsNumber == 0){
			echo "<script>";
			echo "document.addEventListener('load', function(){";
			echo "document.getElementById('inner').style.display = 'none';";
			echo "document.getElementById('cartEmpty').style.display = 'flex';";
			echo "}, true);";
			echo "</script>";
		}else{
			echo "<script>";
			echo "document.addEventListener('load', function(){";
			echo "document.getElementById('inner').style.display = 'inline';";
			echo "document.getElementById('cartEmpty').style.display = 'none';";
			echo "}, true);";
			echo "</script>";
		}
	}

	function shortenStrings($string, $maxNumberCharacter){
		if(strlen($string) >= $maxNumberCharacter)
			echo substr($string, 0, $maxNumberCharacter)."...";
		else
			echo $string;
	}

	// function uploadImg(){
	// 	$valid       = true;
	// 	$tmp_file    = $_FILES['addImg']['tmp_name'];
	// 	$target_dir  = "resources/img/product/";
	// 	$target_file = strtolower($target_dir . basename($_FILES['addImg']['name']));

 //        if (file_exists($target_file)) {
 //            echo "Sorry, file already exists.";
 //            $valid = false;
 //        }

 //        if($_FILES['addImg']['size'] > (2048000)) {
 //            $valid = false;
	//         echo 'Oops!  Your file\'s size is to large.';
 //        }

 //        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
 //        if ($file_extension != "jpg" &&
 //            $file_extension != "jpeg" &&
 //            $file_extension != "png" &&
 //            $file_extension != "gif") {
 //            $valid = false;
 //            echo "Only JPG, JPEG, PNG & GIF files are allowed";
 //        }
	// }
?>
