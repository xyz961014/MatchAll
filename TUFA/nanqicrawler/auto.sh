#!/bin/bash
cd /var/www/TUFA/nanqicrawler
while :
do
scrapy crawl nanqi
sleep 20
done
