create table Tasks
(
    task_name varchar(255) not null,
    identifier int auto_increment
        primary key,
    task_description varchar(255) not null,
    due_date datetime not null,
    username varchar(30) not null,
    is_done tinyint(1) not null,
    web_id int default 0 not null,
    constraint Tasks_ibfk_1
        foreign key (username) references Users (username)
);
