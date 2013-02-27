DROP TABLE IF EXISTS runs;
CREATE TABLE runs (
  id  int unsigned NOT NULL auto_increment,
  runTime  date NOT NULL,
  commitSHA  varchar(255) NOT NULL,

  PRIMARY KEY (id)
);