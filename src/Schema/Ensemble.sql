-- DCL
 SET foreign_key_checks = 0;

-- Customers table
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(120) NOT NULL,
  `EmailAddress` varchar(120) NOT NULL,
  `Telephone` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Items table
DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(120) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Orders table
DROP TABLE IF EXISTS `customerorder`;
CREATE TABLE `customerorder` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderCreation` datetime NOT NULL,
  `CustomerID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `customer_fk` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Order Items table
DROP TABLE IF EXISTS `orderitem`;
CREATE TABLE `orderitem` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `OrderID` (`OrderID`),
  KEY `ItemID` (`ItemID`),
  CONSTRAINT `customerorder_fk` FOREIGN KEY (`OrderID`) REFERENCES `customerorder` (`Id`),
  CONSTRAINT `item_fk` FOREIGN KEY (`ItemID`) REFERENCES `item` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET foreign_key_checks = 1;

-- DML
-- Inserts
INSERT INTO customer (`Name`, `EmailAddress`, `Telephone`) VALUES ('Tamara', 'tamara.matamoros@gmail.com', '0414123123');
INSERT INTO customer (`Name`, `EmailAddress`, `Telephone`) VALUES ('Reynaldo', 'reyre8@gmail.com', '0414567567');
INSERT INTO item (`Name`) VALUES ('Hat'), ('Gloves'), ('Pants');
INSERT INTO `customerorder` (`OrderCreation`, `CustomerID`) VALUES (NOW(), 1);
INSERT INTO orderitem (`OrderID`, `ItemID`, `Quantity`) VALUES (1, 1, 3), (1, 2, 7), (1, 3, 1);

INSERT INTO `customerorder` (`OrderCreation`, `CustomerID`) VALUES (NOW(), 2);
INSERT INTO orderitem (`OrderID`, `ItemID`, `Quantity`) VALUES (2, 1, 1), (2, 2, 1), (2, 3, 2);

INSERT INTO `customerorder` (`OrderCreation`, `CustomerID`) VALUES (NOW(), 1);
INSERT INTO orderitem (`OrderID`, `ItemID`, `Quantity`) VALUES (3, 1, 2), (3, 2, 3), (3, 3, 6);


