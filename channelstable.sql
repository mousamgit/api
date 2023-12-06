create table channels
(
    id                int auto_increment
        primary key,
    name              varchar(255)         not null,
    type              varchar(50)          not null,
    status            tinyint(1) default 0 null,
    last_time_proceed datetime             null
);

create table channel_attributes
(
    id             int auto_increment
        primary key,
    channel_id     int          null,
    attribute_name varchar(255) not null,
    output_label   varchar(255) null,
    formatting     text         null,
    constraint channel_attributes_ibfk_1
        foreign key (channel_id) references channels (id)
);

create index channel_id
    on channel_attributes (channel_id);


set FOREIGN_KEY_CHECKS = 0;

truncate TABLE channel_attributes;
truncate TABLE channels;

set FOREIGN_KEY_CHECKS =1;