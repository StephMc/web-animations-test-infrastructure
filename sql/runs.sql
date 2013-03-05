DROP TABLE IF EXISTS runs;
CREATE TABLE runs (
  id  int unsigned NOT NULL auto_increment,
  runTime  timestamp NOT NULL,
  commitSHA  varchar(255) NOT NULL,
  commitMessage varchar(500) NOT NULL,
  testsPassed varchar(255) NOT NULL,

  PRIMARY KEY (id)
);