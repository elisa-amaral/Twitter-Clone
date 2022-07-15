create database twitter_clone collate utf8_unicode_ci;

use twitter_clone;

create table users(
    id int not null primary key AUTO_INCREMENT,
    full_name varchar(100) not null,
    email varchar(150) not null,
    password varchar(32) not null,
    profile_picture varchar(100) not null,
    header_image varchar(100) not null 
);

create table tweets(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    user_id int not null,
    tweet varchar(140) not null,
    datetime_published datetime default current_timestamp 
);

create table follows(
    id int not null primary key auto_increment, 
    follower_used_id int not null, 
    followed_user_id int not null
);