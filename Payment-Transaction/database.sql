CREATE TABLE accounts(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    balance INT
);

INSERT INTO accounts VALUES (NULL,'User',5000),(NULL,'Merchant',2000);
