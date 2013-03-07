#! bash
echo "Yay! Tests are done." >> logfile.txt
chmod 777 logfile.txt
pkill Xvfb >> logfile.txt
pkill chrome >> logfile.txt
curl http://14.200.8.150/web-animations-test-infrastructure/triggerTests.php
