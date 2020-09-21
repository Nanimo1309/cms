CREATE TABLE IF NOT EXISTS file
(
	id INT AUTO_INCREMENT,
	url varchar(255) NOT NULL,
	data MEDIUMBLOB NOT NULL,
	name VARCHAR(255) NOT NULL,
	extension varchar(10) NOT NULL,
	mime varchar(50) NOT NULL,
	size INT NOT NULL,
	hash char(64) NOT NULL,
	lastModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(id),
	UNIQUE KEY(url)
) engine = MyISAM;
