USE yamaguchihayato_kontrol;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS pre_user;
DROP TABLE IF EXISTS trainings;
DROP TABLE IF EXISTS meal;
DROP TABLE IF EXISTS foodlist;
DROP TABLE IF EXISTS mealwithfood;
DROP TABLE IF EXISTS body;
DROP TABLE IF EXISTS bodycom;

CREATE TABLE user (
  id INT NOT NULL AUTO_INCREMENT,
  mail VARCHAR(50),
  password VARCHAR(100),
  name VARCHAR(20),
  status BOOLEAN,
  created DATETIME,
  updated DATETIME,
  PRIMARY KEY (id)
);

CREATE TABLE pre_user (
  id INT NOT NULL AUTO_INCREMENT,
  token VARCHAR(64),
  mail VARCHAR(50),
  created DATETIME,
  status BOOLEAN,
  PRIMARY KEY (id)
);

CREATE TABLE trainings (
  id INT NOT NULL AUTO_INCREMENT,
  user INT,
  date DATETIME,
  part VARCHAR(8),
  type VARCHAR(50),
  sets INT UNSIGNED,
  weight DECIMAL(5, 2) UNSIGNED,
  reps INT UNSIGNED,
  PRIMARY KEY (id)
);

CREATE TABLE meal (
  id INT NOT NULL AUTO_INCREMENT,
  user INT,
  date DATETIME,
  food VARCHAR(50),
  weight DECIMAL(5) UNSIGNED,
  PRIMARY KEY (id)
);

CREATE TABLE foodlist (
  id INT NOT NULL AUTO_INCREMENT,
  user INT,
  hidden BOOLEAN NOT NULL DEFAULT 0,
  genre VARCHAR(50),
  food VARCHAR(50),
  cal DECIMAL(7, 4) UNSIGNED,
  pro DECIMAL(7, 4) UNSIGNED,
  fat DECIMAL(7, 4) UNSIGNED,
  car DECIMAL(7, 4) UNSIGNED,
  PRIMARY KEY (id)
);

INSERT INTO foodlist (hidden,genre, food, cal, pro, fat, car) VALUES 
  (1, "主食", "白米", 1.68, 0.025, 0.003, 0.371),
  (1, "主菜", "鶏もも", 2, 0.162, 0.14, 0),
  (1, "主菜", "牛もも", 1.4, 0.225, 0.046, 0.005),
  (1, "主菜", "鶏胸皮なし", 1.08, 0.223, 0.015, 0),
  (1, "主菜", "全卵(1個)", 91, 7.4, 6.2, 0.2),
  (1, "おやつ", "バナナ(1本)", 77, 0.1, 0.02, 2.02),
  (1, "飲み物", "牛乳", 0.67, 0.033, 0.038, 0.048);

CREATE TABLE mealwithfood
AS
SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food;

INSERT INTO meal (date, food, weight) VALUES
  (20220721, "バナナ(1本)", 1),
  (20220721, "牛乳", 150);

CREATE TABLE body (
  id INT NOT NULL AUTO_INCREMENT,
  user INT,
  date DATETIME,
  sex ENUM('male', 'female'),
  dob DATETIME,
  height DECIMAL(5, 2) UNSIGNED,
  weight DECIMAL(5, 2) UNSIGNED,
  bodyfat DECIMAL(5, 2) UNSIGNED,
  acticity TINYINT UNSIGNED,
  targetweight DECIMAL(5, 2) UNSIGNED,
  PRIMARY KEY (id)
);

CREATE TABLE bodycom (
  id INT NOT NULL AUTO_INCREMENT,
  user INT,
  date DATETIME,
  weight DECIMAL(5, 2) UNSIGNED,
  bodyfat DECIMAL(5, 2) UNSIGNED,
  PRIMARY KEY (id)
);

SELECT * FROM trainings;
SELECT * FROM meal;
SELECT * FROM foodlist;
SELECT * FROM meal INNER JOIN foodlist ON meal.food = foodlist.food;
SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food;
SELECT * FROM mealwithfood;
SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('2022-07-21', '%Y-%m-%d');
SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food ORDER BY meal.date DESC LIMIT 5;
SELECT * FROM user WHERE mail = 'yamaguchihayatoo@gmail.com';