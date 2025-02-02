DELIMITER //
CREATE PROCEDURE setup_root_user()
BEGIN
  IF @@hostname LIKE '%dev%' THEN
    CREATE USER 'root'@'%' IDENTIFIED BY 'root_password';
    GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
END IF;
END //
DELIMITER ;
CALL setup_root_user();
DROP PROCEDURE IF EXISTS setup_root_user;
