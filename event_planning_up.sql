-- Temporarily disable foreign key checks
SET FOREIGN_KEY_CHECKS=0;

-- Create database 
CREATE DATABASE event_planning;

USE event_planning;

-- Create the 'event' table
CREATE TABLE event (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    event_name VARCHAR(50),
    venue_id INT,
    start_time DATETIME,
    end_time DATETIME,
	seats_left INT,
    CONSTRAINT fk_venue_event FOREIGN KEY (venue_id) REFERENCES venue(venue_id)
);

-- Create the 'venue' table
CREATE TABLE venue (
    venue_id INT PRIMARY KEY AUTO_INCREMENT,
    venue_name VARCHAR(50),
    seat_count INT
);

-- Create the 'group' table
CREATE TABLE group_table (
    group_id INT PRIMARY KEY AUTO_INCREMENT,
	event_id INT,
    group_size INT,
    CONSTRAINT fk_group_event FOREIGN KEY (event_id) REFERENCES event(event_id)
);

-- Create the 'group_member' table
CREATE TABLE group_member (
    group_id INT,
	visit_id INT,
	PRIMARY KEY (group_id, visit_id),
    CONSTRAINT fk_group_member_group FOREIGN KEY (group_id) REFERENCES group_table(group_id),
    CONSTRAINT fk_group_member_visit FOREIGN KEY (visit_id) REFERENCES visit(visit_id)
);

-- Create the 'visit' table
CREATE TABLE visit (
    visit_id INT PRIMARY KEY AUTO_INCREMENT,
    visitor_id INT,
    seat_number INT,
    event_id INT,
    CONSTRAINT fk_visitor_visit FOREIGN KEY (visitor_id) REFERENCES visitor(visitor_id),
    CONSTRAINT fk_event_visit FOREIGN KEY (event_id) REFERENCES event(event_id)
);

-- Create the 'visitor' table
CREATE TABLE visitor (
    visitor_id INT PRIMARY KEY AUTO_INCREMENT,
	visitor_name VARCHAR(50) UNIQUE
);

-- Reenable foreign key checks
SET FOREIGN_KEY_CHECKS=1;
