
--Create users table
create table users
(
id int  not null auto_increment primary key,
username varchar(50) not null unique key,
password varchar(255),
email varchar(100),
activ_status tinyint(1) default 0,
activ_key varchar(1000)
);


--Create user social table

create table users_social
(
id int  not null auto_increment primary key,
username varchar(100),
email varchar(100),
source varchar(100)
);
