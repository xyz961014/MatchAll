#coding=utf-8
import xlrd
import pymysql.cursors
import re
import datetime

class CMatch:
    def __init__(self,level=None,stage=None,group=None,roundnum=None,time=None,field=None,hometeam=None,awayteam=None):
        self.level = level
        self.stage = stage
        self.group = group
        self.round = roundnum
        self.time = time
        self.field = field
        self.hometeam = hometeam
        self.awayteam = awayteam



def createschedule(filename, sheetnum,level=None,group=True):
    if not re.search('\.xlsx?$',filename):
        return
    data = xlrd.open_workbook(filename)
    table = data.sheet_by_index(sheetnum)
    nrows = table.nrows
    matches = []
    for i in range(1,nrows):
        matchinfo = table.row_values(i)
        matches.append(matchinfo)
    for ind,item in enumerate(matches):
        for rind,ritem in enumerate(item):
            if ritem == '':
                matches[ind][rind] = matches[ind-1][rind]
        matches[ind].append(level)
        matches[ind].append(group)
    return matches

def inputschedule(matches,matchname):
    infos = []
    for m in matches:
        info = CMatch()
        info.level = m[-2]
        if type(m[4]) != float:
            m[-1] = False
        if m[-1] and re.search('[a-zA-Z]',m[3]):
            info.stage = 'Group'
            info.group = m[3]
            info.round = m[4]
            hour = int(m[5] * 24)
            minute = round((m[5]*24 - hour) * 60)
            if minute == 60:
                minute = 0
                hour = hour + 1
            time = datetime.datetime(1899,12,30,hour=hour,minute=minute)
            delta = datetime.timedelta(days = m[1])
            time = time + delta
            timestr = time.strftime('%Y-%m-%d %H:%M:%S')
            info.time = timestr
            info.hometeam = m[6]
            info.awayteam = m[7]
            info.field = m[8]
        else:
            info.stage = m[4]
            hour = int(m[5] * 24)
            minute = round((m[5]*24 - hour) * 60)
            if minute == 60:
                minute = 0
                hour = hour + 1
            time = datetime.datetime(1899,12,30,hour=hour,minute=minute)
            delta = datetime.timedelta(days = m[1])
            time = time + delta
            timestr = time.strftime('%Y-%m-%d %H:%M:%S')
            info.time = timestr
            info.hometeam = m[6]
            info.awayteam = m[7]
            info.field = m[8]
        infos.append(info)
        print(info.hometeam, info.awayteam, info.time)
    connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db=matchname,
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)
    try:
        for info in infos:
            #print(info.level, info.stage, info.group,info.round, info.time,info.field, info.hometeam,info.awayteam)
            with connection.cursor() as cursor:
                sql = "INSERT INTO `Matches` (`Level`, `Stage`, `GroupName`, `Round`, `MatchTime`, `MatchField`, `HomeTeam`, `AwayTeam`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"
                cursor.execute(sql,(info.level, info.stage, info.group, info.round, info.time, info.field, info.hometeam, info.awayteam))
        connection.commit()
    finally:
        connection.close()


#mA = createschedule('Schedule.xlsx',1,level = '甲级')
#mB = createschedule('Schedule.xlsx',2,level = '乙级')
#m = mA
#for i in mB:
#    m.append(i)
#m.sort(key = lambda x:x[1] + x[5])
#inputschedule(m)
#mT = createschedule('nanqischedule.xlsx',0)
#mT.sort(key = lambda x:x[1] + x[5])
#inputschedule(mT,'NANQI_18')
