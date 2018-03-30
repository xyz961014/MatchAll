import pymysql.cursors
import re
import TeamDict
import os
import sys, getopt
import json
import codecs

class Team:
    def __init__(self, name, groupname, win, draw, lose, point, goals, concede):
        self.name = name
        self.groupname = groupname
        self.win = win
        self.draw = draw
        self.lose = lose
        self.point = point
        self.goals = goals
        self.concede = concede
        self.gd = goals - concede
    def Getgd(self):
        self.gd = self.goals - self.concede


class Group:
    def __init__(self, name, teams = None):
        self.name = name
        self.teams = teams
    def roughsort(self,gd = False):
        if gd:
            self.teams.sort(key = lambda x:(x.point, x.gd), reverse = True)
        else:
            self.teams.sort(key = lambda x:x.point, reverse = True)

class Match:
    def __init__(self, matchid, stage, hometeam, awayteam, time, homegoal, awaygoal, result, homewin = True, valid = False, todecide = False):
        self.matchid = matchid
        self.stage = stage
        self.hometeam = hometeam
        self.awayteam = awayteam
        self.time = time
        self.valid = valid
        self.homegoal = homegoal
        self.awaygoal = awaygoal
        self.result = result
        self.homewin = homewin
        self.todecide = todecide
    def getWL(self,WL = 'W'):
        if WL == 'W':
            if self.homewin:
                return self.hometeam
            else:
                return self.awayteam
        elif WL == 'L':
            if self.homewin:
                return self.awayteam
            else:
                return self.hometeam

def Evolve(matchname):

    connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db=matchname,
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)
    try:
        with connection.cursor() as cursor:
            Groups = dict()
            EliMatches = dict()
            if os.path.exists('../html/' + matchname + '.json'):
                #Get info from file
                with codecs.open('../html/' + matchname + '.json','r','utf-8') as rankfile:
                    line = rankfile.readline()
                    [dgroups, deli] = json.loads(line)
                #print(deli)
                for gid,git in dgroups.items():
                    lt = git['teams']
                    T = []
                    for t in lt:
                        T.append(Team(name = t['name'], groupname = t['groupname'], win = t['win'], draw = t['draw'], lose = t['lose'], point = t['point'], goals = t['goals'],  concede = t['concede']))
                    #print(git['name'], T)
                    Groups[gid] = Group(name = git['name'],teams = T)
                for eid,eit in deli.items():
                    if not eid == 'end':
                        EliMatches[int(eid)] = Match(matchid = eit['matchid'], stage = eit['stage'], hometeam = eit['hometeam'], awayteam = eit['awayteam'], time = eit['time'], homegoal = eit['homegoal'], awaygoal = eit['awaygoal'], result = eit['result'], homewin = eit['homewin'], valid = eit['valid'], todecide = eit['todecide'])
                #update data
                sql = 'SELECT * FROM Teams'
                cursor.execute(sql)
                teams = cursor.fetchall()
                for t in teams:
                    for i,g in Groups.items():
                        for Ti,Te in enumerate(g.teams):
                            if Te.name == t['TeamName']:
                                Groups[i].teams[Ti].win = t['Win']
                                Groups[i].teams[Ti].draw = t['Draw']
                                Groups[i].teams[Ti].lose = t['Lose']
                                Groups[i].teams[Ti].point = t['Point']
                                Groups[i].teams[Ti].goals = t['Goal']
                                Groups[i].teams[Ti].concede = t['Concede']
                                Groups[i].teams[Ti].Getgd()
                                break
                sql = "SELECT * FROM Matches ORDER BY MatchTime"
                cursor.execute(sql)
                matches = cursor.fetchall()
                for m in matches:
                    if not m['Stage'] == 'Group':
                        resultstr = str(m['HomeGoal']) + ':' + str(m['AwayGoal'])
                        if m['PenaltyShootOut']:
                            resultstr += '(' + str(int(m['HomeGoal']) + int(m['HomePenalty'])) + ':' +str(int(m['AwayGoal']) + int(m['AwayPenalty'])) + ')'
                        for Mi,Ma in EliMatches.items():
                            if m['MatchID'] == Ma.matchid:
                                EliMatches[Mi].result = resultstr
                                if m['Result'] == '3': #HOMEWIN
                                    EliMatches[Mi].homewin = True
                                elif m['Result'] == '0': #AWAYWIN
                                    EliMatches[Mi].homewin = False
                                elif m['Result'] == '1': #PENALTY
                                    if int(m['HomePenalty']) > int(m['AwayPenalty']):
                                        #HOMWPENALTYWIN
                                        EliMatches[Mi].homewin = True
                                    else:
                                        #AWAYPENALTYWIN
                                        EliMatches[Mi].homewin = False            
                                EliMatches[Mi].valid = m['Valid']
                                EliMatches[Mi].homegoal = m['HomeGoal']
                                EliMatches[Mi].awaygoal = m['AwayGoal']
                                if not Ma.todecide:
                                    EliMatches[Mi].hometeam = m['HomeTeam']
                                    EliMatches[Mi].awayteam = m['AwayTeam']
                for i,g in Groups.items():
                    g.roughsort()
                    

            else:
                sql = 'SELECT * FROM Teams'
                cursor.execute(sql)
                teams = cursor.fetchall()
                for t in teams:
                    T = Team(name = t['TeamName'], groupname = t['GroupName'], win = t['Win'], draw = t['Draw'], lose = t['Lose'], point = t['Point'], goals = t['Goal'], concede = t['Concede'])
                    for i,g in Groups.items():
                        if T.groupname == g.name:
                            Groups[i].teams.append(T)
                            break
                    else:
                        Groups[T.groupname] = Group(name = T.groupname, teams = [T])
                for i,g in Groups.items():
                    g.roughsort(gd = True)
                sql = "SELECT * FROM Matches ORDER BY MatchTime"
                cursor.execute(sql)
                matches = cursor.fetchall()
                for m in matches:
                    resultstr = str(m['HomeGoal']) + ':' + str(m['AwayGoal'])
                    if m['PenaltyShootOut']:
                        resultstr += '(' + str(int(m['HomeGoal']) + int(m['HomePenalty'])) + ':' +str(int(m['AwayGoal']) + int(m['AwayPenalty'])) + ')'
                    M = Match(matchid = m['MatchID'], stage = m['Stage'], hometeam = m['HomeTeam'],awayteam = m['AwayTeam'], time = m['MatchTime'].strftime('%Y-%m-%d %H:%M'), homegoal = m['HomeGoal'], awaygoal = m['AwayGoal'], result = resultstr, valid = m['Valid'])
                    if not m['Stage'] == 'Group':
                        if m['Result'] == '3': #HOMEWIN
                            M.homewin = True
                        elif m['Result'] == '0': #AWAYWIN
                            M.homewin = False
                        elif m['Result'] == '1': #PENALTY
                            if int(m['HomePenalty']) > int(m['AwayPenalty']):
                                #HOMWPENALTYWIN
                                M.homewin = True
                            else:
                                #AWAYPENALTYWIN
                                M.homewin = False
                        EliMatches[M.matchid] = M
            for emid,em in EliMatches.items():
                ht = em.hometeam
                at = em.awayteam
                hg,hr,hmid,hw = Tracestr(ht)
                ag,ar,amid,aw = Tracestr(at)
                if hg:
                    for m in matches:
                        if m['GroupName'] == hg:
                            if m['Valid'] == 0:
                                break
                    else:
                        EliMatches[emid].hometeam = Groups[hg].teams[hr].name
                elif hmid:
                    if EliMatches[hmid].valid:
                        if hw:
                            EliMatches[emid].hometeam = EliMatches[hmid].getWL()
                        else:
                            EliMatches[emid].hometeam = EliMatches[hmid].getWL(WL = 'L')
                else:
                    EliMatches[emid].todecide = True
                if ag:
                    for m in matches:
                        if m['GroupName'] == ag:
                            if m['Valid'] == 0:
                                break
                    else:
                        EliMatches[emid].awayteam = Groups[ag].teams[ar].name
                elif amid:
                    if EliMatches[amid].valid:
                        if aw:
                            EliMatches[emid].awayteam = EliMatches[amid].getWL()
                        else:
                            EliMatches[emid].awayteam = EliMatches[amid].getWL(WL = 'L')
                else:
                    EliMatches[emid].todecide = True
                if em.valid:
                    for i,g in Groups.items():
                        for ti,te in enumerate(g.teams):
                            if em.hometeam == TeamDict.getfull(te.name):
                                Groups[i].teams[ti].goals -= em.homegoal
                                Groups[i].teams[ti].concede -= em.awaygoal
                                if em.homegoal > em.awaygoal:
                                    Groups[i].teams[ti].win -= 1
                                elif em.homegoal < em.awaygoal:
                                    Groups[i].teams[ti].lose -= 1
                                elif em.homegoal == em.awaygoal:
                                    Groups[i].teams[ti].draw -= 1
                            if em.awayteam == TeamDict.getfull(te.name):
                                Groups[i].teams[ti].goals -= em.awaygoal
                                Groups[i].teams[ti].concede -= em.homegoal
                                if em.homegoal > em.awaygoal:
                                    Groups[i].teams[ti].lose -= 1
                                elif em.homegoal < em.awaygoal:
                                    Groups[i].teams[ti].win -= 1
                                elif em.homegoal == em.awaygoal:
                                    Groups[i].teams[ti].draw -= 1
            jsonstr = json.dumps([Groups, EliMatches], default = lambda g:g.__dict__, sort_keys = True)
            #print(groupstr,elistr)
            with open('../html/' + matchname + '.json', 'w') as rankfile:
                rankfile.write(jsonstr)
            for i,g in Groups.items():
                print(g.name)
                for t in g.teams:
                    print(t.name, t.point)
            for emid,em in EliMatches.items():
                print(emid,em.time,em.hometeam,em.awayteam)

                    
    finally:
        connection.close()

def Tracestr(t):
    '''
    Not permit Group WLR to exist, for WLR are used for Win and Lose and RunnerUp.
    '''
    group = None
    rank = None
    matchid = None
    win = True
    if re.match(r'(?=^W[^WLR])(?=^W[A-Z]).',t):
        t = t[1:] + '1'
    elif re.match(r'(?=^R[^WLR])(?=^R[A-Z]).',t):
        t = t[1:] + '2'
    if re.match(r'(?=^[^WLR])(?=^[A-Z]).[0-9]',t):
        group = t[:1]
        rank = int(t[1:]) - 1
        return group, rank, matchid, win
    if re.match(r'^[WL]',t):
        matchid = int(t[1:])
        if re.match(r'^L',t):
            win = False
        return group, rank, matchid, win
    return group,rank,matchid,win
    
def main(argv):
    Evolve(argv[0])

if __name__ == "__main__":
    main(sys.argv[1:])
