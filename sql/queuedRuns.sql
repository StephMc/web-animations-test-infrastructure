DROP TABLE IF EXISTS queuedRuns;
CREATE TABLE queuedRuns (
  id  int unsigned NOT NULL auto_increment,
  sha1 varchar(255) NOT NULL,
  commitMessage varchar(255) NOT NULL,
  commitTime timestamp NOT NULL,

  PRIMARY KEY (id)
);
