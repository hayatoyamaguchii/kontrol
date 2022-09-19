USE yamaguchihayato_kontrol;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS pre_user;
DROP TABLE IF EXISTS trainings;
DROP TABLE IF EXISTS meal;
DROP TABLE IF EXISTS foodlist;
DROP TABLE IF EXISTS mealwithfood;
DROP TABLE IF EXISTS bodycom;
DROP TABLE IF EXISTS target;

CREATE TABLE user (
  id INT NOT NULL AUTO_INCREMENT,
  mail VARCHAR(50),
  password VARCHAR(100),
  created DATETIME,
  updated DATETIME,
  PRIMARY KEY (id)
);

INSERT INTO user (mail, password, created, updated) VALUES ('test@test.test', '$2y$10$vqtt8gMKbvaybpzq1QtdQeNeVcCt0a/YeTI74/NoUUS1ZppoTwena', now(), now());

CREATE TABLE pre_user (
  id INT NOT NULL AUTO_INCREMENT,
  urltoken VARCHAR(64),
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

CREATE TABLE mealwithfood
AS
SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food;

CREATE TABLE target (
  id INT NOT NULL AUTO_INCREMENT,
  user INT,
  date DATETIME,
  targetpro DECIMAL(7, 4) UNSIGNED,
  targetfat DECIMAL(7, 4) UNSIGNED,
  targetcar DECIMAL(7, 4) UNSIGNED,
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