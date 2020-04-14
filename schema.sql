CREATE DATABASE IF NOT EXISTS readme
DEFAULT CHARACTER SET UTF8MB4
DEFAULT COLLATE utf8mb4_general_ci;
USE readme;

CREATE TABLE IF NOT EXISTS users (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(32) NOT NULL UNIQUE,
dt_add DATETIME,
email VARCHAR(128) NOT NULL UNIQUE,
password VARCHAR(64),
avatar VARCHAR(128)
);

CREATE TABLE IF NOT EXISTS content_types  (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
type_name VARCHAR(128),
type_class VARCHAR(128)
);

CREATE TABLE IF NOT EXISTS posts (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
author_id INT UNSIGNED,
post_type INT UNSIGNED,
dt_add DATETIME,
heading VARCHAR(128),
content TEXT,
quote_author TEXT,
img_url VARCHAR(128),
youtube_url VARCHAR(128),
url VARCHAR(128),
view_count INT,
FOREIGN KEY (author_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE,
FOREIGN KEY (post_type) REFERENCES content_types(id)
ON UPDATE CASCADE
ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED,
post_id INT UNSIGNED,
dt_add DATETIME,
content TEXT,
FOREIGN KEY (user_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE,
FOREIGN KEY (post_id) REFERENCES posts(id)
ON UPDATE CASCADE
ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS likes (
user_id INT UNSIGNED,
post_id INT UNSIGNED,
FOREIGN KEY (user_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE,
FOREIGN KEY (post_id) REFERENCES posts(id)
ON UPDATE CASCADE
ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS subscribe (
follower_id INT UNSIGNED,
author_id INT UNSIGNED,
FOREIGN KEY (follower_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE,
FOREIGN KEY (author_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
dt_add DATETIME,
content TEXT,
sender_id INT UNSIGNED,
receiver_id INT UNSIGNED,
FOREIGN KEY (sender_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE,
FOREIGN KEY (receiver_id) REFERENCES users(id)
ON UPDATE CASCADE
ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS hashtags (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
tag_name VARCHAR(128)
);

CREATE TABLE IF NOT EXISTS post_tags (
post_id INT UNSIGNED,
hashtag_id INT UNSIGNED,
FOREIGN KEY (post_id) REFERENCES posts(id)
ON UPDATE CASCADE
ON DELETE CASCADE,
FOREIGN KEY (hashtag_id) REFERENCES hashtags(id)
ON UPDATE CASCADE
ON DELETE CASCADE
);