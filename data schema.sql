CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  address VARCHAR(255),
  age INT,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pastries (
  pastry_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  added_by INT,
  last_updated_by INT,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (added_by) REFERENCES users(user_id),
  FOREIGN KEY (last_updated_by) REFERENCES users(user_id)
);

CREATE TABLE ingredients (
  ingredient_id INT AUTO_INCREMENT PRIMARY KEY,
  pastry_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  quantity VARCHAR(50),
  added_by INT,
  last_updated_by INT,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pastry_id) REFERENCES pastries(pastry_id) ON DELETE CASCADE,
  FOREIGN KEY (added_by) REFERENCES users(user_id),
  FOREIGN KEY (last_updated_by) REFERENCES users(user_id)
);