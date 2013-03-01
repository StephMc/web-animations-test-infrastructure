DROP TABLE IF EXISTS results;
CREATE TABLE results (
  id  int unsigned NOT NULL auto_increment,
  testRunID int unsigned NOT NULL,
  testName varchar(255) NOT NULL,
  assertsPassed varchar(255) NOT NULL,

  PRIMARY KEY (id),
  FOREIGN KEY (testRunID) REFERENCES runs (id)
);