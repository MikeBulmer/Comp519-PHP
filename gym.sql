-- COMP 519 assignment 3
-- Author Michael Bulmer
-- Submission 15/12/2017


USE m7mb;

CREATE TABLE class (
  name     VARCHAR(25) PRIMARY KEY,
  capacity INT
);

CREATE TABLE session (
  sessionid INT AUTO_INCREMENT PRIMARY KEY,
  name      VARCHAR(25) REFERENCES class (name),
  session   VARCHAR(25)
);


CREATE TABLE booking (
  sessionid    INT REFERENCES session (sessionid),
  name         VARCHAR(50),
  phone_number VARCHAR(25),
  PRIMARY KEY (sessionid, name, phone_number)
);

INSERT INTO class VALUES ("Boot Camp", 2), ("Boxercise", 4), ("Pilates", 3), ("Yoga", 2), ("Zumba", 2);

INSERT INTO session (name, session) VALUES
  ("Boot Camp", "Monday, 9.00"), ("Boot Camp", "Tuesday, 9.00"), ("Boot Camp", "Wednesday, 9.00"),
  ("Boxercise", "Thursday, 10.00"), ("Boxercise", "Friday, 10.00"),
  ("Pilates", "Monday, 11.00"), ("Pilates", "Wednesday, 11.00"), ("Pilates", "Friday, 11.00"),
  ("Yoga", "Tuesday, 13.00"), ("Yoga", "Wednesday, 13.00"), ("Zumba", "Friday, 14.00");

CREATE VIEW sessioncount AS
  SELECT
    count(*) AS sessioncount,
    c.capacity,
    s.sessionid,
    s.session
  FROM booking AS b, class AS c, session AS s
  WHERE b.sessionid = s.sessionid AND s.name = c.name
  GROUP BY s.sessionid;

CREATE VIEW available AS
  SELECT
    s.sessionid,
    c.capacity,
    c.capacity - IFNULL(sc.sessioncount, 0) AS remain,
    c.name,
    s.session
  FROM class AS c, session AS s LEFT JOIN sessioncount AS sc ON sc.sessionid = s.sessionid
  WHERE s.name = c.name;
