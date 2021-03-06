
/*
  Default tables for users
*/

CREATE TABLE `users` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `password` varchar(255) NOT NULL COMMENT 'Password hash',
    `role` varchar(10) NOT NULL COMMENT 'Role constant',
    `email` varchar(255) NOT NULL COMMENT 'User email',
    `name` varchar(255) NOT NULL COMMENT 'User name',
    `birthday` DATE DEFAULT NULL COMMENT 'Date of birth',
    `about` TEXT NOT NULL COMMENT 'Basic information',
    `gender` TINYINT NOT NULL COMMENT 'Gender constant',
    `avatar` varchar(255) NOT NULL COMMENT 'Path to avatar',
    `since` DATETIME NOT NULL COMMENT 'Registration date and time',
    `token` varchar(255) NOT NULL COMMENT 'User unique token',
    `activated` BOOLEAN NOT NULL COMMENT 'Whether this profile is activated'
);

CREATE TABLE users_recovery (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `token` varchar(255) NOT NULL COMMENT 'Unique recovery token',
    `datetime` DATETIME NOT NULL COMMENT 'Date and time of making request',

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);