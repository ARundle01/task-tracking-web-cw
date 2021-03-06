create table Users
(
    username varchar(30) not null,
    password varchar(255) not null,
    salt varchar(255) not null,
    id int not null,
    constraint username
        unique (username)
);

alter table Users
    add primary key (username);
