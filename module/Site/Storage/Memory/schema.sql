
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
