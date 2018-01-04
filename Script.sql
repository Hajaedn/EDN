CREATE
	TABLE
		users(
			usr_id INT AUTO_INCREMENT PRIMARY KEY,
			usr_login VARCHAR(30),
			usr_pwd VARCHAR(20),
			usr_name VARCHAR(50),
			usr_right VARCHAR(20),
			usr_create DATE,
			usr_enable INT(1)
		);


Select * from users;