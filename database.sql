create database pokemondatabase
    default character set utf8
    collate utf8_unicode_ci;

use pokemondatabase;

create table pokemon (
  id bigint(20) not null auto_increment primary key,
  name varchar(100) not null unique,
  type varchar(100) not null,
  weight decimal(9,2) not null,
  height decimal(9,2) not null,
  lvl int not null
) engine=innodb default charset=utf8 collate=utf8_unicode_ci;

create user pokemon_user@localhost
    identified by 'pokemon_user';

grant all
    on pokemondatabase.*
    to pokemon_user@localhost;

flush privileges;
