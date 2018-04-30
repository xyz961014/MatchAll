#coding=utf-8
import scrapy
import re
import os
import urllib
import pymysql
import sys
import random
sys.path.append("/var/www/TUFA")
from scrapy.selector import Selector
from scrapy.http import HtmlResponse,Request

from nanqi.items import PlayerItem #导入item对应的类，crawlPictures是项目名，items是items.py文件，import的是items.py中的class，也可以import *

import TeamDict

class Spdier_nanqi(scrapy.spiders.Spider):
    name = "nanqi"    #定义爬虫名，要和settings中的BOT_NAME属性对应的值一致
    allowed_domains = ['www.tafa.org.cn']
    start_urls = ['http://www.tafa.org.cn/member/login.php']
    login_url = 'http://www.tafa.org.cn/member/login.php'

    def start_requests(self):
        yield scrapy.Request(self.login_url,callback=self.login)

    def login(self, response):
        formdata = {
                'username': 'xyz961014@126.com',
                'password': 'p=19961014'
                }
        yield scrapy.FormRequest.from_response(response, formdata=formdata, callback=self.parse_login)

    def parse_login(self, response):
        teams = [3, 7, 13, 14, 116, 117, 127, 128, 140]
        urls = [
                'http://www.tafa.org.cn/member/fan_other.php?tid=%s' % i for i in teams
        ]
        for url in urls:
            yield scrapy.Request(url=url, callback=self.parse)

    def parse(self, response):
        tbl = response.css("#tbl_gameslist.ewTable.ewTableSeparate")[1]
        for line in tbl.css("tbody tr"):
            teamid = response.url.split("=")[-1]
            info = line.css("td::text").extract()
            item = PlayerItem()
            item["name"] = info[0]
            item["team"] = TeamDict.getnanqifull(int(teamid))
            if len(info) > 1:
                item["school"] = TeamDict.getfull(info[1])
            else:
                item["school"] = None
            item["num"] = random.randint(0,99)
            yield item
    
