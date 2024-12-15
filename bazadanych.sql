# Utworzenie bazy danych #
CREATE DATABASE test;

# Tabela subscribers #
CREATE TABLE test.subscribers (
  id INT NOT NULL AUTO_INCREMENT,
  fname VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

# Tabela audit_subscribers #
CREATE TABLE test.audit_subscribers (
  id INT NOT NULL AUTO_INCREMENT,
  subscriber_name VARCHAR(50) NOT NULL,
  action_performed VARCHAR(50) NOT NULL,
  date_added TIMESTAMP,
  PRIMARY KEY (id)
);

# Wyzwalacz before_subscriber_insert: przed poleceniem wpisania uzytkownika do tabeli subscribers, uruchamia sie wyzwalacz wstawiajacy informacje do tabeli audit_subscribers. Pseudorekord NEW zawiera wstawiane wartosci. #
CREATE TRIGGER test.before_subscriber_insert
BEFORE INSERT
ON test.subscribers
FOR EACH ROW
INSERT INTO test.audit_subscribers (subscriber_name, action_performed, date_added)
VALUES (NEW.fname, "Insert a new subscriber", NOW());

# Wyzwalacz after_subscriber_delete: po poleceniu usuniecia uzytkownika z tabeli subscribers, uruchamia sie wyzwalacz wstawiajacy informacje do tabeli audit_subscribers. Pseudorekord OLD zawiera wartosci usuniete. #
CREATE TRIGGER test.after_subscriber_delete
AFTER DELETE
ON test.subscribers
FOR EACH ROW
INSERT INTO test.audit_subscribers (subscriber_name, action_performed, date_added)
VALUES (OLD.fname, "Deleted a subscriber", NOW());

# Wyzwalacz after_subscriber_edit: po polecenieu edytowania uzytkownika w tabeli subscribers, uruchamia sie wyzwalacz wstawiajacy informacje do tabeli audit_subscribers. Pseudorekord OLD zawiera stare wartosci sprzed edycji. #
CREATE TRIGGER test.after_subscriber_edit
AFTER UPDATE
ON test.subscribers
FOR EACH ROW
INSERT INTO test.audit_subscribers (subscriber_name, action_performed, date_added)
VALUES (OLD.fname, "Updated a subscriber", NOW());

# Widoki do zadania_3
CREATE VIEW users_added AS
SELECT subscriber_name, date_added
FROM audit_subscribers
WHERE action_performed = 'Insert a new subscriber';

CREATE VIEW users_deleted AS
SELECT subscriber_name, date_added AS date_deleted
FROM audit_subscribers
WHERE action_performed = 'Deleted a subscriber';

CREATE VIEW users_edited AS
SELECT subscriber_name, date_added AS date_edited
FROM audit_subscribers
WHERE action_performed = 'Updated a subscriber';

CREATE VIEW users_added_deleted AS
SELECT ud.subscriber_name, ua.date_added, ud.date_deleted
FROM users_deleted ud
JOIN users_added ua
ON ud.subscriber_name = ua.subscriber_name;

CREATE VIEW users_exist AS
SELECT ua.subscriber_name
FROM users_added ua
LEFT JOIN users_deleted ud
ON ua.subscriber_name = ud.subscriber_name
WHERE ud.subscriber_name IS NULL;

# Widok z ktorego korzysta plik viewsubsribers.php do wyswietlania danych
CREATE VIEW all_users AS
SELECT ua.subscriber_name, ua.date_added, ue.date_edited, ud.date_deleted
FROM users_added ua
LEFT JOIN users_edited ue
ON ua.subscriber_name = ue.subscriber_name
LEFT JOIN users_deleted ud
ON ua.subscriber_name = ud.subscriber_name;

