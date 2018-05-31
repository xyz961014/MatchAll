# -*- coding: utf-8 -*-
import scrapy
import re
import xlrd 
import xlsxwriter
import json

class CMatch:
    def __init__(self, info, assrefs, avairefs):
        self.info = info
        self.assrefs = assrefs
        self.avairefs = avairefs
def dict2list(dic:dict):
    keys = dic.keys()
    vals = dic.values()
    lst = [(key, val) for key, val in zip(keys, vals)]
    return lst


class RefSpider(scrapy.Spider):
    name = "ref"    #定义爬虫名，要和settings中的BOT_NAME属性对应的值一致
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
        urls = ['http://www.tafa.org.cn/member/ref_app.php']
        for url in urls:
            yield scrapy.Request(url=url, callback=self.parse)


    def parse(self, response):
        tbl = dict()
        tbl["matches"] = []
        tbl["refs"] = {}
        content = response.css("#ewContentColumn table")[1]
        matchrefs = content.css(".reftd")
        matchinfo = content.css(".gametd")
        assignrefs = []
        availablerefs = []
        for ind, match in enumerate(matchrefs):
            arefs = match.css("tr").extract()
            texts = match.css("::text").extract()
            assignref = [[], [], [], []]
            for aref in arefs:
                if re.search("img", aref):
                    if re.search(r"_ref1|_ref2", aref):
                        assignref[0].append(re.sub(r"[^\u4e00-\u9fa5]", "", aref))
                    elif re.search(r"ref3|ref4", aref):
                        assignref[1].append(re.sub(r"[^\u4e00-\u9fa5]", "", aref))
                    elif re.search(r"ref5|ref6|ref7|ref8", aref):
                        assignref[2].append(re.sub(r"[^\u4e00-\u9fa5]", "", aref))
                elif re.search("bbbbbb", aref):
                    assignref[3].append(re.sub(r"[^\u4e00-\u9fa5]", "", aref))
            assignrefs.append(assignref)
            availableref = []
            for text in texts:
                if not re.match(r"\s", text):
                    if not re.match(r"[选派|修改|发布|已发布|√|?]", text):
                        availableref.append(text)
            availablerefs.append(availableref)
        for ind, match in enumerate(matchinfo):
            matches = match.css("::text").extract()
            for i, text in enumerate(matches):
                matches[i] = re.sub(r"\s", "", text)
            newmatch = CMatch(matches, assignrefs[ind], availablerefs[ind])
            tbl["matches"].append(newmatch)
        for m in tbl["matches"]:
            for p in m.avairefs:
                if not p in tbl["refs"].keys():
                    tbl["refs"][str(p)] = []
        for i, m in enumerate(tbl["matches"]):
            for ref, lst in tbl["refs"].items():
                if ref in m.avairefs:
                    print(m.assrefs)
                    if ref in m.assrefs[0]:
                        tbl["refs"][ref].append(2)
                    elif ref in m.assrefs[1]:
                        tbl["refs"][ref].append(3)
                    elif ref in m.assrefs[2]:
                        tbl["refs"][ref].append(4)
                    elif ref in m.assrefs[3]:
                        tbl["refs"][ref].append(0)
                    else:
                        tbl["refs"][ref].append(1)
                else:
                    tbl["refs"][ref].append(0)
        with open('/home/xyz/Desktop/ref.json', 'w') as f:
            f.write(json.dumps(tbl, default = lambda g:g.__dict__, sort_keys = True))
        workbook = xlsxwriter.Workbook('/home/xyz/Desktop/ref.xlsx')
        worksheet = workbook.add_worksheet()
        available = workbook.add_format()
        available.set_bg_color("yellow")
        R = workbook.add_format()
        R.set_bg_color("red")
        AR = workbook.add_format()
        AR.set_bg_color("orange")
        FO = workbook.add_format()
        FO.set_bg_color("blue")
        row = 1
        col = 1
        refstable = sorted(dict2list(tbl["refs"]), key = lambda x:sum([1 if i != 0 else 0 for i in x[1]]), reverse = False)
        for p in refstable:
            worksheet.write(row, 0, p[0])
            i = 0
            print(p)
            for i, a in enumerate(p[1]):
                if a == 1:
                    worksheet.write(row, 1 + i, "", available)
                elif a == 2:
                    worksheet.write(row, 1 + i, "", R)
                elif a == 3:
                    worksheet.write(row, 1 + i, "", AR)
                elif a == 4:
                    worksheet.write(row, 1 + i, "", FO)
            row += 1
        for m in tbl["matches"]:
            worksheet.write(0, col, " ".join(m.info))
            col += 1
        workbook.close()
