#!/bin/bash

while :
do 
ls_date=`date +%Y-%m-%d_%H:%M:%S`
mysqldump -u root -p961014 -A > /var/www/TUFA/sqlbackup/sqldata_$ls_date.sql
echo "backup at $ls_date"
sleep 12h
done
