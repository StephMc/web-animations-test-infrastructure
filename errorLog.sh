#!/bin/bash

date >> errorLog.txt
chmod 777 errorLog.txt
echo $1 >> errorLog.txt
