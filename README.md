web-animations-test-infrastructure
==================================

Aim:
To allow the web-animation tests to be autoamatically trigged by github
postrecieve hooks and the results + results history to be displayed on a
web page.

Overview:
1. Github's post recieve hook sends a JSON POST request to queueRuns.php.
2. In queueRuns.php aach commit in the post request is put into the
   queuedRuns tables in the database.
3. It then uses curl to cause triggerTests.php to run.
4. TriggerTests.php takes the most recent commit from queuedRuns table,
   creates a new Run for it then passes the Run's id and the commits sha1
   to triggerTests.sh
5. TriggerTests.sh moves the local repo to the desired commit using
   updateRepos.sh, cleans the chrome cache and then uses xvfb-run to
   start testRunner.html (in test framework repo) running though the tests.
6. TestRunner.html sends XHRs to collectResults.php. The results are put
   into the database. It sends a XHR to say when it is finished. This
   causes collectResults.php to remove the latest QueuedRun from the
   database and call resetAndTrigger.sh.
7. ResetAndTrigger.sh kills the Xvfb and chrome and then uses curl to
   call triggerTests.php. Then repeats from 4 if more commits to process
   otherwise it will stop there.

Notes:
- Results of all the tests are displayed using index.php.
- All the tables for the database are created using the .sql files in the
  sql directory.

Todos:
- Remove specific QueuedRun as soon as the Run for it is created
- Fix how times for commits are added to database
- Visually display the results in index.php
- Get git to update to the right commit


