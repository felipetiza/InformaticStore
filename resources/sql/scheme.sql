DROP DATABASE IF EXISTS `informaticstore`;
CREATE DATABASE IF NOT EXISTS `informaticstore`;
USE `informaticstore`;

-- Tables's erasing
DROP TABLE IF EXISTS `CUSTOMER`;
DROP TABLE IF EXISTS `PRODUCT`;
DROP TABLE IF EXISTS `ORDER2`;			-- It gives error if the name is ORDER
DROP TABLE IF EXISTS `PROVIDER`;
DROP TABLE IF EXISTS `SHOPPINGCART`;
DROP TABLE IF EXISTS `CONTAIN`;

   set storage_engine = InnoDB;
-- set storage_engine = MyISAM;
-- set storage_engine = Falcon;
-- set storage_engine = PBXT;
-- set storage_engine = Maria;


-- Tables's creation
CREATE TABLE CUSTOMER
(
	IDCUSTOMER		INT AUTO_INCREMENT NOT NULL,
	NAME			VARCHAR(25),
	SURNAME			VARCHAR(50),
	EMAIL			VARCHAR(45),
	ADDRESS			VARCHAR(100),
	PHONE			VARCHAR(16),
	TYPE			VARCHAR(75),
	USERNAME		VARCHAR(40),
	PASSWORD		VARCHAR(20),
	PRIMARY KEY(IDCUSTOMER)
);

CREATE TABLE PRODUCT
(
	IDPRODUCT		INT AUTO_INCREMENT NOT NULL,
	NAME			VARCHAR(25),
	DESCRIPTION		VARCHAR(500),
	PRICE			DECIMAL(7,2),
	AMOUNT			INT,
	URLIMAGE		VARCHAR(250),
	PRIMARY KEY(IDPRODUCT)
);

CREATE TABLE ORDER2
(
    IDORDER         INT AUTO_INCREMENT NOT NULL,
    IDCUSTOMER      INT,
    DATEORDER       DATE,
    AMOUNTPRODUCTS  INT,
    TOTALPRICE      DECIMAL(7,2),
    PRIMARY KEY(IDORDER, IDCUSTOMER),
    FOREIGN KEY(IDCUSTOMER) REFERENCES CUSTOMER(IDCUSTOMER) ON DELETE CASCADE
);

CREATE TABLE PROVIDER
(
	IDPROVIDER		INT AUTO_INCREMENT NOT NULL,
	LOCATION		VARCHAR(100),
	MATERIAL		VARCHAR(50),
	PRIMARY KEY(IDPROVIDER)
);

CREATE TABLE SHOPPINGCART
(
	IDCUSTOMER		INT,
	IDPRODUCT		INT,
	PRIMARY KEY(IDCUSTOMER, IDPRODUCT),
	FOREIGN KEY(IDCUSTOMER) REFERENCES CUSTOMER(IDCUSTOMER) ON DELETE CASCADE,
	FOREIGN KEY(IDPRODUCT) REFERENCES PRODUCT(IDPRODUCT) ON DELETE CASCADE
);

CREATE TABLE CONTAIN
(
	IDORDER			INT,
	IDPRODUCT		INT,
	AMOUNT 			INT,
	PRIMARY KEY(IDORDER, IDPRODUCT),
	FOREIGN KEY(IDPRODUCT) REFERENCES PRODUCT(IDPRODUCT) ON DELETE CASCADE,
	FOREIGN KEY(IDORDER) REFERENCES ORDER2(IDORDER) ON DELETE CASCADE
);


-- Insertion within of the tables
INSERT INTO CUSTOMER VALUES(NULL, 'John', 'Adam', 'john22@gmail.com','Calle','6765432', 'Admin', 'admin', '1234');
INSERT INTO CUSTOMER VALUES(NULL, 'Alfred', 'Smith', 'alfred@gmail.com','Calle','6765432', 'User', 'user', '1234');
INSERT INTO CUSTOMER VALUES(NULL, 'Brenda', 'Cranston', 'brenda22@gmail.com','Calle','6765432', 'User', 'brenda22', '5678');
INSERT INTO CUSTOMER VALUES(NULL, 'Jeremy', 'Griffin', 'jeremy@gmail.com','Calle','6765432', 'User', 'jere', 'bird');
INSERT INTO CUSTOMER VALUES(NULL, 'Rupert', 'Smith', 'rupert@gmail.com','Calle','6765432', 'User', 'rupert14', 'god');
INSERT INTO CUSTOMER VALUES(NULL, 'Wilson', 'Morrison', 'wilson@gmail.com','Calle','6765432', 'User', 'wilson41', 'myself');
INSERT INTO CUSTOMER VALUES(NULL, 'Howard', 'Albertson', 'howard@gmail.com','Calle','6765432', 'User', 'howii', 'howii');

INSERT INTO PRODUCT VALUES(NULL, 'Mouse', 'Awesome mouse', 12.5, 14, 'http://image.com');
INSERT INTO PRODUCT VALUES(NULL, 'Keyboard', 'Awesome keyboard', 17.30, 9, 'http://image.com');
INSERT INTO PRODUCT VALUES(NULL, 'Speakers', 'Awesome speakers', 40.00, 6, 'http://image.com');
INSERT INTO PRODUCT VALUES(NULL, 'Pen drive', 'Awesome pen drive', 26.45, 80, 'http://image.com');
INSERT INTO PRODUCT VALUES(NULL, 'CD-Rom', 'Awesome cd-rom', 2.25, 300, 'http://image.com');
INSERT INTO PRODUCT VALUES(NULL, 'Floppy Disk', 'Awesome floppy disk', 1.50, 200, 'http://image.com');
INSERT INTO PRODUCT VALUES(NULL, 'DVD Reader', 'Awesome dvd reader', 24.85, 34, 'http://image.com');

INSERT INTO PROVIDER VALUES(NULL, 'Colonial Avenue', 'Sound equipment');
INSERT INTO PROVIDER VALUES(NULL, 'Industrial area the queens', 'Storage disks');

INSERT INTO ORDER2 VALUES(NULL, 3, NOW(), 4, 175.65);

INSERT INTO CONTAIN VALUES(1, 2, 4);
INSERT INTO CONTAIN VALUES(1, 4, 1);
INSERT INTO CONTAIN VALUES(1, 3, 2);




