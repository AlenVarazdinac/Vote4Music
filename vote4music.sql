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
# Foreign keys
conn_lobby      INT
);

CREATE TABLE lobby(
lobby_id        INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
lobby_name      VARCHAR(120) NOT NULL,
lobby_song      VARCHAR(120),
lobby_song_time DECIMAL(6,3),
# Foreign keys
lobby_player    VARCHAR(75)
);

CREATE TABLE user_lobby(
user            INT NOT NULL,
lobby           INT NOT NULL,
primary key     (user, lobby)
);

# ALTER TABLES
ALTER TABLE user_lobby ADD FOREIGN KEY (user) REFERENCES user(user_id);
ALTER TABLE user_lobby ADD FOREIGN KEY (lobby) REFERENCES lobby(lobby_id);

# INSERT DATA
INSERT INTO user(user_name, user_email, user_pw, user_rights) VALUES
('AlenV', 'alen.varazdinac@gmail.com', md5('123'), 'Admin'),
('TestU', 'test.user@gmail.com', md5('123'), 'User');

INSERT INTO lobby(lobby_name) VALUES
('Lobby 1');

