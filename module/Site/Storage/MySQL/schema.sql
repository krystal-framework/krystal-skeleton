
/*
  Default tables for users
*/

CREATE TABLE `users` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `login` varchar(255) NOT NULL COMMENT 'User login',
    `password` varchar(255) NOT NULL COMMENT 'Password hash',
    `role` varchar(10) NOT NULL COMMENT 'Role constant',
    `email` varchar(255) NOT NULL COMMENT 'User email',
    `name` varchar(255) NOT NULL COMMENT 'User name'
);

CREATE TABLE users_recovery (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `token` varchar(255) NOT NULL COMMENT 'Unique recovery token',
    `datetime` DATETIME NOT NULL COMMENT 'Date and time of making request',

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);