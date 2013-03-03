#! bash
#echo starting to run tests
touch imhere.txt
xvfb-run -a google-chrome http://localhost/web-animations-test-framework/tests/testRunner.html &
# How do I kill this after the tests are run....
#echo tests finished
