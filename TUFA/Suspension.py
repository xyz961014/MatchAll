import pymysql.cursors
import TeamDict
import sys,getopt

class Player:
    def __init__(self, name, team, kitnum = None, suspension = 0, accuyc = 0):
        self.name = name
        self.team = team
        self.kitnum = kitnum
        self.suspension = suspension #停赛场数
        self.accuyc = accuyc #累计黄牌（红牌记作多张累计）

class Match:
    def __init__(self, matchid, hometeam, awayteam, stage, matchtime):
        self.matchid = matchid
        self.hometeam = hometeam
        self.awayteam = awayteam
        self.stage = stage
        self.matchtime = matchtime

def Suspension():
    connection = pymysql.connect(host='localhost',
                                   user='root',
                                   password='961014',
                                   db='MANAN_1718',
                                   charset='utf8mb4',
                                   cursorclass=pymysql.cursors.DictCursor)
    try:
        with connection.cursor() as cursor:
            #初始化
            sql = 'UPDATE Players SET Suspension = 0'
            cursor.execute(sql)
            #额外的附加停赛
            #...
            sql = 'SELECT MatchID, HomeTeam, AwayTeam, Stage, MatchTime FROM Matches WHERE Valid = 1 ORDER BY MatchTime'
            cursor.execute(sql) #取出所有生效比赛
            matches = cursor.fetchall()
            Vmatch = []
            VPlayer = dict()
            errstr = ''
            for match in matches:
                Vmatch.append(Match(match['MatchID'], match['HomeTeam'], match['AwayTeam'], match['Stage'], match['MatchTime']))
            
            for i, m in enumerate(Vmatch):
                if m.stage != 'Group' and Vmatch[i-1].stage == 'Group': #小组赛清牌
                    for p in VPlayer.items():
                        if p[1].accuyc != 0:
                            p[1].accuyc = 0
                sql = 'SELECT * FROM Match' + str(m.matchid) + " ORDER BY EventTime,StoppageTime"
                cursor.execute(sql)
                Infos = cursor.fetchall()
                for info in Infos:
                    key = info['Team'] + str(info['KitNumber']) + info['Name']
                    if not key in VPlayer:
                        VPlayer[key] = Player(name = info['Name'], team = info['Team'], kitnum = info['KitNumber'])
                    if VPlayer[key].suspension > 0:
                        #停赛上场警告
                        es = 'Match' + str(m.matchid) +'\t' +m.hometeam +'\t' +m.awayteam + '\t' + str(VPlayer[key].kitnum) + '-' + VPlayer[key].name + '\t' + info['EventType'] + '\n'
                        errstr += es
                        print('ERROR! ',es)
                    else:
                        if info['EventType'] == '黄牌':
                            VPlayer[key].accuyc += 1
                        if info['EventType'] == '红牌':
                            VPlayer[key].accuyc += 2
                for p in VPlayer.items():
                    if p[1].team in (TeamDict.getfull(m.hometeam), TeamDict.getfull(m.awayteam)): #处理停赛的实施
                        if p[1].suspension > 0:
                            p[1].suspension -= 1
                    if p[1].accuyc > 1: #处理停赛的产生
                        sus = p[1].accuyc // 2
                        p[1].accuyc = 0
                        p[1].suspension = sus
            for p in VPlayer.items():
                if p[1].suspension != 0:
                    print('TEAM:', p[1].team, 'NAME:', p[1].name, 'SUS:', p[1].suspension)
                    sql = 'UPDATE Players SET Suspension = %s WHERE Team = %s AND KitNumber = %s AND Name = %s'
                    cursor.execute(sql,(p[1].suspension, p[1].team, p[1].kitnum, p[1].name))
            connection.commit()

            with open('errsuspension.txt', 'w') as errfile:
                errfile.write(errstr)

    finally:
        connection.close()

Suspension()
