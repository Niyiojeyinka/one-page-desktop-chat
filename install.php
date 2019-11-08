<?php

require 'backend/Database.php';

$db = new Database();
$db->query("CREATE TABLE IF NOT EXISTS users (
        id int(11) NOT NULL AUTO_INCREMENT,
        firstname varchar(128) NOT NULL,
        lastname varchar(128) NOT NULL,
        username varchar(128) NOT NULL,
        password varchar(128) NOT NULL,
        `text` text,
        phone varchar(128),
        email varchar(128) NOT NULL,
        address varchar(128) NOT NULL,
        friends text NOT NULL,
        profile_picture varchar(128),
        lastlog varchar(128) NOT NULL,
        `time` varchar(128) NOT NULL,
        PRIMARY KEY (id)
);");
$db->query("CREATE TABLE IF NOT EXISTS media (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(128) NOT NULL,
        slug varchar(128) NOT NULL,
        type varchar(128) NOT NULL,
        `time` varchar(128) NOT NULL,
        PRIMARY KEY (id)
);");
$db->query(
"CREATE TABLE IF NOT EXISTS conversation (
        id int(11) NOT NULL AUTO_INCREMENT,
        last_updated varchar(128) NOT NULL,
         receiver_id int(10) NOT NULL,
        sender_id int(10) NOT NULL,
        status enum('active','inactive','blocked'),
        blocker_id varchar(128),
        `time` varchar(128) NOT NULL,
        PRIMARY KEY (id)
);");
$db->query(
"CREATE TABLE IF NOT EXISTS messages (
        id int(11) NOT NULL AUTO_INCREMENT,
        `text` text,
        receiver_id int(10) NOT NULL,
        sender_id int(10) NOT NULL,
        conversation_id varchar(128) NOT NULL,
        status enum('sent','seen'),
        type enum('textonly','textobj') NOT NULL,
        `time` varchar(128) NOT NULL,
        PRIMARY KEY (id)
);"
);

//insert testing data

$db->query("INSERT INTO users (firstname, lastname, username,password,email,address,friends,profile_picture,lastlog,`time`,`text` )
 VALUES ('Olaniyi','Ojeyinka','niyi','test','test@test.com','Earth ,Universe','[2,3]','profile1.jpg','".time()."','".time()."','I Create Cool Solutions for the World to use'),
('Olan','yinka','philip','test','test1@test.com','Earth ,Universe','[]','profile2.jpg','".time()."','".time()."','I Create Cool Solutions for the World to use'),
('John','Ojeyinka','niyji','test','test2@test.com','Earth ,Universe','[]','profile3.jpg','".time()."','".time()."','I Create Cool Solutions for the World to use')
;");
$db->close();

echo "All is set Now";