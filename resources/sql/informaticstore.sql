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
	password		VARCHAR(20),
	PRIMARY KEY(idcustomer)
);

CREATE TABLE product
(
	idproduct		INT AUTO_INCREMENT NOT NULL,
	name			VARCHAR(25),
	description		VARCHAR(500),
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

INSERT INTO product VALUES(NULL, 'Mouse', 'Awesome mouse', 12.5, 14, 'resources/img/product/mouse.jpg');
INSERT INTO product VALUES(NULL, 'Keyboard', 'Awesome keyboard', 17.30, 9, 'resources/img/product/keyboard.jpg');
INSERT INTO product VALUES(NULL, 'Speakers', 'Awesome speakers', 40.00, 6, 'resources/img/product/speakers.jpg');
INSERT INTO product VALUES(NULL, 'Pen drive', 'Awesome pen drive', 26.45, 80, 'resources/img/product/pendrive.jpg');
INSERT INTO product VALUES(NULL, 'CD-Rom', 'Awesome cd-rom', 2.25, 300, 'resources/img/product/cd-rom.jpg');
INSERT INTO product VALUES(NULL, 'Floppy Disk', 'Awesome floppy disk', 1.50, 200, 'resources/img/product/floppydisk.jpg');
INSERT INTO product VALUES(NULL, 'DVD Reader', 'Awesome dvd reader', 24.85, 34, 'resources/img/product/dvdreader.jpg');

INSERT INTO provider VALUES(NULL, 'Colonial Avenue', 'Sound equipment');
INSERT INTO provider VALUES(NULL, 'Industrial area the queens', 'Storage disks');

INSERT INTO order2 VALUES(NULL, 3, NOW(), 4, 175.65);

INSERT INTO contain VALUES(1, 2, 4);
INSERT INTO contain VALUES(1, 4, 1);
INSERT INTO contain VALUES(1, 3, 2);




