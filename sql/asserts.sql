DROP TABLE IF EXISTS asserts;
CREATE TABLE asserts (
  id  int unsigned NOT NULL auto_increment,
  resultID  int unsigned NOT NULL,
  message  text NOT NULL,

  PRIMARY KEY (id),
  FOREIGN KEY (resultID) REFERENCES results (id)
);
