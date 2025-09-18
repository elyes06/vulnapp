-- schema_sqlite.sql
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL, -- mot de passe en clair
  email TEXT
);

DROP TABLE IF EXISTS vulnerability_tables;
CREATE TABLE vulnerability_tables (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  owner_id INTEGER NOT NULL,
  name TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS vulnerabilities;
CREATE TABLE vulnerabilities (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  table_id INTEGER NOT NULL,
  title TEXT,
  description TEXT,
  cvss REAL,
  status TEXT
);

INSERT INTO users (username, password, email) VALUES
('alice','password','alice@example.com'),
('bob','password','bob@example.com');

INSERT INTO vulnerability_tables (owner_id, name) VALUES
(1,'Alice - Prod'), (2,'Bob - Tests');

INSERT INTO vulnerabilities (table_id, title, description, cvss, status) VALUES
(1,'SQL Injection','Appel SQL sans préparation',9.0,'open'),
(1,'XSS stored','Description contenant payload xss basique',6.1,'open'),
(2,'LFI','upload path included in page',7.2,'open');
