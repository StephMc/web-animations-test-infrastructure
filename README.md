web-animations-test-infrastructure
==================================

<h3>Aim: </h3>
To allow the web-animation tests to be autoamatically trigged by github
postrecieve hooks and the results + results history to be displayed on a
web page.

<h3>Overview: </h3>
1. Github's post recieve hook sends a JSON POST request to queueRuns.php. <br>
2. In queueRuns.php aach commit in the post request is put into the
   queuedRuns tables in the database. <br>
3. It then uses curl to cause triggerTests.php to run. <br>
4. TriggerTests.php takes the most recent commit from queuedRuns table,
   creates a new Run for it (and removes it from queueRuns table) then
   passes the Run's id and the commits sha1 to triggerTests.sh <br>
5. TriggerTests.sh moves the local repo to the desired commit using
   updateRepos.sh, cleans the chrome cache and then uses xvfb-run to
   start testRunner.html (in test framework repo) running though the tests.<br>
6. TestRunner.html sends XHRs to collectResults.php. The results are put
   into the database. It sends a XHR to say when it is finished. This
   causes collectResults.php to call resetAndTrigger.sh. <br>
7. ResetAndTrigger.sh kills the Xvfb and chrome and then uses curl to
   call triggerTests.php. Then repeats from 4 if more commits to process
   otherwise it will stop there. <br>

<h3>How to set up</h3>
1. Install Apache2, MySQL and PHP. PhpMyAdmin is also handy to visually see & manage the databases.
2. Clone web-animations-test-infrastructure into /var/www
3. Create a database called results and add the tables in the .sql files in directory sql
   in the order queuedRuns, runs, results, asserts.
4. Go to the github repository that you want to test with each push request. On the top
   menu bar click "settings", on the side bar click "Service Hooks", then select WebHook URLs
   under the available service hooks. Paste "http://YOUR_IP_ADDESS/web-animations-test-infrastructure/queueRuns.php"
   into the url box and click update settings.
5. Click "Test Hook" on the same page then go to "http://YOUR_IP_ADDESS/web-animations-test-infrastructure" and 
   if its all working you should see some results in grey boxes.   

<h3>Notes:</h3>
- There a few hard coded links & ip addresses in some of the files that will need to be changed for it to work.
- Apache2 has to own the git repo that it is reading the tests & web-animations-js from otherwise it wont have the
  permissions to update it.
- Results of all the tests are displayed using index.php.
- All the tables for the database are created using the .sql files in the
  sql directory.
- Everything in the classes directory controlls how to access a database
  table of the same name.

<h3>Terms:</h3>
- Run: Collection of all the individual test file results for a certain commit.
- Result: Collection of all the indivdual asserts results inside a test file.
- Assert: Result of a single check inside a test file.
- QueuedRun: A run that is scheduled to be processed.


