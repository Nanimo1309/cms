CREATE TABLE IF NOT EXISTS account
(
	id INT NOT NULL AUTO_INCREMENT,
	login VARCHAR(255) NOT NULL,
	password CHAR(64) NOT NULL,
	email VARCHAR(255) NOT NULL,
	name VARCHAR(50) DEFAULT "-",
	rights TINYINT UNSIGNED NOT NULL,
	idKey CHAR(96) DEFAULT NULL,
	lastActive TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	registration TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	
	PRIMARY KEY(id),
	UNIQUE KEY(login),
	UNIQUE KEY(email),
	UNIQUE KEY(idKey)
) engine = InnoDB;
