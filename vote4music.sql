DROP DATABASE IF EXISTS vote4music;
CREATE DATABASE vote4music DEFAULT CHARACTER SET utf8;
USE vote4music;

# CREATE TABLES
CREATE TABLE user(
user_id         INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
user_name       VARCHAR(75) NOT NULL,
user_email      VARCHAR(75) NOT NULL,
user_pw         CHAR(32) NOT NULL,
user_rights     VARCHAR(15) NOT NULL,
user_song       VARCHAR(120) NOT NULL,
# Foreign key
conn_lobby      INT,
queued_in       INT
);

CREATE TABLE lobby(
lobby_id        INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
lobby_name      VARCHAR(120) NOT NULL,
lobby_song      VARCHAR(120),
lobby_song_time DECIMAL(6,3) DEFAULT 0,
lobby_total_song_time DECIMAL(6,3) DEFAULT 0,
lobby_player    VARCHAR(75)
);

CREATE TABLE queue(
queue_id        INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
queue_max       INT NOT NULL DEFAULT 5,
queue_user_1    VARCHAR(75),
queue_user_2    VARCHAR(75),
queue_user_3    VARCHAR(75),
queue_user_4    VARCHAR(75),
queue_user_5    VARCHAR(75)
);


# ALTER TABLES
ALTER TABLE user ADD FOREIGN KEY (conn_lobby) REFERENCES lobby(lobby_id);
ALTER TABLE user ADD FOREIGN KEY (queued_in) REFERENCES queue(queue_id);

# INSERT DATA
INSERT INTO user(user_name, user_email, user_pw, user_rights) VALUES
('AlenV', 'alen.varazdinac@gmail.com', md5('123'), 'Admin'),
('TestU', 'test.user@gmail.com', md5('123'), 'User');

INSERT INTO lobby(lobby_name) VALUES
('Lobby 1');

INSERT INTO queue(queue_id) VALUES
(1);
