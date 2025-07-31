SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS 
    Payment, Billing, AdditionalCharges, Orders, QRCodes, CheckInCheckOut, 
    Booking, Discount, Chat, Feedback, Menu, Amenities, Cottages, Rooms, 
    UserSessionLog, Guest, Staff, User;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE User (
    userID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('Active', 'Deactivated') NOT NULL
);

CREATE TABLE Staff (
    staffID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL, 
    gender VARCHAR(255) NOT NULL,
    mobilenum INT(11) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    avatar MEDIUMBLOB NOT NULL,
    userID INT(20) NOT NULL,
    CONSTRAINT fk_userID_Staff FOREIGN KEY (userID) REFERENCES User(userID)
);

CREATE TABLE Guest (
    guestID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    mobilenum INT(11) NOT NULL,
    email VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    role VARCHAR(255) NOT NULL,
    avatar MEDIUMBLOB NOT NULL,
    userID INT(20) NOT NULL,
    CONSTRAINT fk_userID_Guest FOREIGN KEY (userID) REFERENCES User(userID)
);

CREATE TABLE UserSessionLog (
    sessionID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    useragent VARCHAR(255) NOT NULL,
    loginstatus VARCHAR(255) NOT NULL,
    sessioncreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sessionexpired DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    userID INT(20) NOT NULL,
    CONSTRAINT fk_userID_Session FOREIGN KEY (userID) REFERENCES User(userID)
);

CREATE TABLE Rooms (
    roomID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    roomnum INT(20) UNIQUE NOT NULL,
    roomtype VARCHAR(255) NOT NULL,
    roomcapacity INT(20) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image MEDIUMBLOB NOT NULL,
    status ENUM('Available', 'Unavailable', 'Booked', 'Maintenance') NOT NULL
);

CREATE TABLE Amenities (
    amenityID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    amenityname VARCHAR(255) NOT NULL,
    adultprice DECIMAL(10,2) NOT NULL,
    childprice DECIMAL(10,2) NOT NULL,
    image MEDIUMBLOB NOT NULL,
    status ENUM('Available', 'Unavailable', 'Maintenance') NOT NULL
);

CREATE TABLE Cottages (
    cottageID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    cottagename VARCHAR(255) NOT NULL,
    capacity INT(20) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('Available', 'Unavailable', 'Booked', 'Maintenance') NOT NULL,
    amenityID INT(20) NOT NULL,
    CONSTRAINT fk_amenityID_Cottage FOREIGN KEY (amenityID) REFERENCES Amenities(amenityID)
);

CREATE TABLE Menu (
    menuID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    menuname VARCHAR(255) NOT NULL,
    itemtype VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image MEDIUMBLOB NOT NULL,
    status ENUM('Available', 'Unavailable') NOT NULL
);

CREATE TABLE Feedback (
    feedbackID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    message TEXT NOT NULL,
    date DATE DEFAULT(CURRENT_DATE()) NOT NULL,
    rating INT(5) NOT NULL,
    status ENUM('Read', 'Unread'),
    guestID INT(20) NOT NULL,
    CONSTRAINT fk_guestID_Feedback FOREIGN KEY (guestID) REFERENCES Guest(guestID)
);

CREATE TABLE Chat (
    chatID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    chat TEXT NOT NULL,
    datesent DATE DEFAULT(CURRENT_DATE()) NOT NULL,
    reply TEXT NOT NULL,
    datereplied DATE DEFAULT(CURRENT_DATE()) NOT NULL, 
    status ENUM('Read', 'Unread') NOT NULL,
    guestID INT(20) NOT NULL,
    staffID INT(20) NOT NULL,
    CONSTRAINT fk_guestID_Chat FOREIGN KEY (guestID) REFERENCES Guest(guestID),
    CONSTRAINT fk_staffID_Chat FOREIGN KEY (staffID) REFERENCES Staff(staffID)
);

CREATE TABLE Discount (
    discountID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Available', 'Unavailable')
);

CREATE TABLE Booking (
    bookingID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    guestamount INT(20) NOT NULL,
    totalprice DECIMAL(10,2) NOT NULL,
    status ENUM('Booked', 'Pending', 'Cancelled'),
    guestID INT(20) NOT NULL,
    roomID INT(20),
    amenityID INT(20),
    cottageID INT(20),
    CONSTRAINT fk_guestID_Book FOREIGN KEY (guestID) REFERENCES Guest(guestID),
    CONSTRAINT fk_roomID_Book FOREIGN KEY (roomID) REFERENCES Rooms(roomID),
    CONSTRAINT fk_amenityID_Book FOREIGN KEY (amenityID) REFERENCES Amenities(amenityID),
    CONSTRAINT fk_cottageID_Book FOREIGN KEY (cottageID) REFERENCES Cottages(cottageID)
);

CREATE TABLE CheckInCheckOut (
    checkID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(255) NOT NULL,
    guestID INT(20) NOT NULL,
    bookingID INT(20) NOT NULL
);

CREATE TABLE QRCodes (
    qrID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    accessdate DATETIME DEFAULT CURRENT_TIMESTAMP,
    amenityID INT(20) NOT NULL,
    guestID INT(20) NOT NULL,
    CONSTRAINT fk_amenityID_QRCode FOREIGN KEY (amenityID) REFERENCES Amenities(amenityID),
    CONSTRAINT fk_guestID_QRCode FOREIGN KEY (guestID) REFERENCES Guest(guestID)
);

CREATE TABLE Orders (
    orderID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    orderticket INT(20) NOT NULL,
    orderquantity INT(20) NOT NULL,
    orderdate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('Delivered', 'Preparing', 'Pending'),
    guestID INT(20) NOT NULL,
    menuID INT(20) NOT NULL,
    CONSTRAINT fk_guestID_Order FOREIGN KEY (guestID) REFERENCES Guest(guestID),
    CONSTRAINT fk_menuID_Order FOREIGN KEY (menuID) REFERENCES Menu(menuID)
);

CREATE TABLE AdditionalCharges (
    chargeID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    chargedescription VARCHAR(255) NOT NULL
);

CREATE TABLE Billing (
    billingID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    totalamount DECIMAL(10,2) NOT NULL,
    datebilled DATE DEFAULT(CURRENT_DATE()) NOT NULL,
    status ENUM('Unpaid', 'Paid') NOT NULL,
    bookingID INT(20),
    orderID INT(20),
    amenityID INT(20),
    chargeID INT(20),
    discountID INT(20),
    guestID INT(20) NOT NULL,
    CONSTRAINT fk_bookingID_Bill FOREIGN KEY (bookingID) REFERENCES Booking(bookingID),
    CONSTRAINT fk_orderID_Bill FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    CONSTRAINT fk_amenityID_Bill FOREIGN KEY (amenityID) REFERENCES Amenities(amenityID),
    CONSTRAINT fk_chargeID_Bill FOREIGN KEY (chargeID) REFERENCES AdditionalCharges(chargeID),
    CONSTRAINT fk_discountID_Bill FOREIGN KEY (discountID) REFERENCES Discount(discountID),
    CONSTRAINT fk_guestID_Bill FOREIGN KEY (guestID) REFERENCES Guest(guestID)
);

CREATE TABLE Payment (
    paymentID INT(20) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    totatltender DECIMAL(10,2) NOT NULL,
    totalchange DECIMAL(10,2) NOT NULL,
    datepayment DATE DEFAULT(CURRENT_DATE()) NOT NULL,
    guestID INT(20) NOT NULL,
    billingID INT(20) NOT NULL,
    CONSTRAINT fk_guestID_Pay FOREIGN KEY (guestID) REFERENCES Guest(guestID),
    CONSTRAINT fk_billingID_Pay FOREIGN KEY (billingID) REFERENCES Billing(billingID)
);
