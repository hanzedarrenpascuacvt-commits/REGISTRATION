SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS pastries;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS categories;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(255),
  added_by INT,
  last_updated_by INT,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (added_by) REFERENCES users(user_id),
  FOREIGN KEY (last_updated_by) REFERENCES users(user_id)
);

CREATE TABLE menu_items (
  item_id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  details VARCHAR(255),
  added_by INT,
  last_updated_by INT,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
  FOREIGN KEY (added_by) REFERENCES users(user_id),
  FOREIGN KEY (last_updated_by) REFERENCES users(user_id)
);