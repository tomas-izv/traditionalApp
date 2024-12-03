create database productdatabase
    default character set utf8
    collate utf8_unicode_ci;

use productdatabase;

create table product (
  id bigint(20) not null auto_increment primary key,
  name varchar(100) not null unique,
  price decimal(9,2) not null
) engine=innodb default charset=utf8 collate=utf8_unicode_ci;

create user productuser@localhost
    identified by 'productpassword';

grant all
    on productdatabase.*
    to productuser@localhost;

flush privileges;