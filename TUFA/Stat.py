import pymysql.cursors
import re
import TeamDict
import sys, getopt
import os
import json

class Player:
    def __init__(self, name, team, kitnum = None, first = True, intime = 1, outtime = 81, onpitch = True):
        self.onpitch = onpitch
        self.name = name
        self.team = team
        self.kitnum = kitnum
        self.intime = [intime]
        self.outtime = [outtime]
        self.goals = 0
        self.owngoal = 0
        self.yc = 0
        self.rc = 0
        self.penalty = 0
        self.penmiss = 0
        self.ontime = outtime - intime
    def getontime(self):
        if self.onpitch:
            self.intime.sort()
            self.outtime.sort()
            ot = 0
            for i in range(len(self.intime)):
                ot += self.outtime[i] - self.intime[i]
        else:
            ot = 0
        self.ontime = ot

class Match:
    def __init__(self, matchid, hometeam, awayteam, stage):
        self.matchid = matchid
        self.hometeam = hometeam
        self.awayteam = awayteam
        self.stage = stage
        self.homegoal = 0
        self.awaygoal = 0
        self.penaltyshootout = False
        self.homepenalty = 0
        self.awaypenalty = 0
        self.result = 3

class Team:
    def __init__(self, name):
        self.name = name
        self.point = 3
        self.goals = 0
        self.owngoal = 0
        self.concede = 0
        self.penalty = 0
        self.penmiss = 0
        self.yc = 0
        self.rc = 0

def Stat(matchname, matchid = None, add = True):
    connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db=matchname,
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)
    try:
        outtime = 81
        if re.match('MANYU',matchname):
            outtime = 61
        EliMatches = dict()
        if os.path.exists('../html/' + matchname + '.json'):
            with open('../html/' + matchname + '.json') as ef:
                deli = json.loads(ef.readline())[1]
            for eid,eit in deli.items():
                EliMatches[int(eid)] = Match(matchid = eit['matchid'], stage = eit['stage'], hometeam = eit['hometeam'], awayteam = eit['awayteam'])
        with connection.cursor() as cursor:
            sql = 'SELECT MatchID,Hometeam,Awayteam,Stage,Valid FROM Matches'
            if matchid != None:
                sql = sql + " WHERE MatchID = %s"
                cursor.execute(sql,matchid)
            else:
                cursor.execute(sql)
            matches = cursor.fetchall() #info of all matches
            Vmatch = []
            for match in matches:
                if match['Valid'] == 1:
                    #all valid matches
                    if not match['Stage'] == 'Group':
                        match['Hometeam'] = EliMatches[match['MatchID']].hometeam
                        match['Awayteam'] = EliMatches[match['MatchID']].awayteam

                    if re.match('^MA.+',matchname):
                        match['Hometeam'] = TeamDict.getfull(match['Hometeam'])
                        match['Awayteam'] = TeamDict.getfull(match['Awayteam'])
                    Vmatch.append(Match(match['MatchID'],match['Hometeam'],match['Awayteam'],match['Stage']));
            #print(Vmatch)
            for m in Vmatch:
                hometeam=Team(m.hometeam)
                awayteam=Team(m.awayteam)
                sql = 'SELECT * FROM Match' + str(m.matchid) + ' ORDER BY EventTime,StoppageTime,EventType'
                cursor.execute(sql)
                groupbool = False
                if m.stage == 'Group':
                    groupbool = True
                Infos = cursor.fetchall() #all events
                if re.match("MANAN", matchname) and not groupbool:
                    ohg = 0
                    oag = 0
                    for info in Infos:
                        if info['EventTime'] < 81:
                            if info['EventType'] == '进球' or info['EventType'] == '点球':
                                if info['Team'] == m.hometeam:
                                    ohg += 1
                                elif info['Team'] == m.awayteam:
                                    oag += 1
                            if info['EventType'] == '乌龙球':
                                if info['Team'] == m.hometeam:
                                    oag += 1
                                elif info['Team'] == m.awayteam:
                                    ohg += 1
                    if oag == ohg:
                        outtime = 111
                players = dict()
                Habandon = False
                Aabandon = False
                for info in Infos:
                    if info['EventType'] == '弃赛':
                        if info['Team'] == m.hometeam:
                            Habandon = True
                        else:
                            Aabandon = True
                    elif not str(info['KitNumber']) + info['Name'] in players:
                        if info['EventType'] == '首发':
                            players[str(info['KitNumber']) + info['Name']] = Player(name = info['Name'], team = info['Team'], kitnum = info['KitNumber'], outtime = outtime)
                        elif info['EventType'] == '换上':
                            players[str(info['KitNumber']) + info['Name']] = Player(name = info['Name'], team = info['Team'], kitnum = info['KitNumber'], first = False, intime = info['EventTime'], outtime = outtime)
                        elif info['EventType'] == '黄牌':
                            players[str(info['KitNumber']) + info['Name']] = Player(name = info['Name'], team = info['Team'], kitnum = info['KitNumber'], first = False, onpitch = False, outtime = outtime)
                            players[str(info['KitNumber']) + info['Name']].intime.remove(1)
                            players[str(info['KitNumber']) + info['Name']].yc += 1
                            if info['Team'] == m.hometeam:
                                hometeam.yc += 1
                            else:
                                awayteam.yc += 1
                        elif info['EventType'] == '红牌':
                            players[str(info['KitNumber']) + info['Name']] = Player(name = info['Name'], team = info['Team'], kitnum = info['KitNumber'], first = False, onpitch = False, outtime = outtime)
                            players[str(info['KitNumber']) + info['Name']].intime.remove(1)
                            players[str(info['KitNumber']) + info['Name']].rc += 1
                            if info['Team'] == m.hometeam:
                                hometeam.rc += 1
                            else:
                                awayteam.rc += 1
                    else:
                        if info['EventType'] == '进球':
                            players[str(info['KitNumber']) + info['Name']].goals += 1
                            if info['Team'] == m.hometeam:
                                hometeam.goals += 1
                                awayteam.concede += 1
                            else:
                                hometeam.concede += 1
                                awayteam.goals += 1
                        elif info['EventType'] == '换上':
                            players[str(info['KitNumber']) + info['Name']].intime.append(info['EventTime'])
                            players[str(info['KitNumber']) + info['Name']].onpitch = True
                        elif info['EventType'] == '换下':
                            players[str(info['KitNumber']) + info['Name']].outtime.append(info['EventTime'])
                        elif info['EventType'] == '黄牌':
                            players[str(info['KitNumber']) + info['Name']].yc += 1
                            if players[str(info['KitNumber']) + info['Name']].yc == 2:
                                players[str(info['KitNumber']) + info['Name']].outtime.append(info['EventTime'])
                            if info['Team'] == m.hometeam:
                                hometeam.yc += 1
                            else:
                                awayteam.yc += 1
                        elif info['EventType'] == '红牌':
                            players[str(info['KitNumber']) + info['Name']].rc += 1
                            players[str(info['KitNumber']) + info['Name']].outtime.append(info['EventTime'])
                            if info['Team'] == m.hometeam:
                                hometeam.rc += 1
                            else:
                                awayteam.rc += 1
                        elif info['EventType'] == '点球':
                            players[str(info['KitNumber']) + info['Name']].penalty += 1
                            players[str(info['KitNumber']) + info['Name']].goals += 1
                            if info['Team'] == m.hometeam:
                                hometeam.goals += 1
                                hometeam.penalty += 1
                                awayteam.concede += 1
                            else:
                                hometeam.concede += 1
                                awayteam.goals += 1
                                awayteam.penalty += 1
                        elif info['EventType'] == '乌龙球':
                            players[str(info['KitNumber']) + info['Name']].owngoal += 1
                            if info['Team'] == m.hometeam:
                                awayteam.goals += 1
                                hometeam.concede += 1
                                hometeam.owngoal += 1
                            else:
                                awayteam.concede += 1
                                hometeam.goals += 1
                                awayteam.owngoal += 1
                        elif info['EventType'] == '点球罚失':
                            players[str(info['KitNumber']) + info['Name']].penmiss += 1
                            if info['Team'] == m.hometeam:
                                hometeam.penmiss += 1
                            else:
                                awayteam.penmiss += 1
                        elif info['EventType'] == '点球决胜罚进':
                            if info['Team'] == m.hometeam:
                                m.homepenalty += 1
                            else:
                                m.awaypenalty += 1
                if Habandon and Aabandon:
                    hometeam.point = 0
                    awayteam.point = 0
                    hometeam.concede = 3
                    awayteam.concede = 3
                elif Habandon and not Aabandon:
                    hometeam.point = 0
                    awayteam.point = 3
                    hometeam.concede = 3
                    awayteam.goals = 3
                elif not Habandon and Aabandon:
                    hometeam.point = 3
                    awayteam.point = 0
                    hometeam.goals = 3
                    awayteam.concede = 3
                elif hometeam.goals > awayteam.goals:
                    hometeam.point = 3
                    awayteam.point = 0
                elif hometeam.goals < awayteam.goals:
                    hometeam.point = 0
                    awayteam.point = 3
                else:
                    hometeam.point = 1
                    awayteam.point = 1
                for p in players.items():
                    p[1].getontime()
                    print(p[1].team, p[1].name,'A:',p[1].onpitch,'G:',p[1].goals,'Y:',p[1].yc,'R:',p[1].rc,'P:',p[1].penalty,'PM:',p[1].penmiss,'OG:',p[1].owngoal,'MIN:',p[1].ontime)
                print(hometeam.name,'G:',hometeam.goals,'C:',hometeam.concede,'P:',hometeam.penalty,'PM:',hometeam.penmiss,'Y:',hometeam.yc,'R:',hometeam.rc,'PT:',hometeam.point)
                print(awayteam.name,'G:',awayteam.goals,'C:',awayteam.concede,'P:',awayteam.penalty,'PM:',awayteam.penmiss,'Y:',awayteam.yc,'R:',awayteam.rc,'PT:',awayteam.point)

                #update the match
                if add:
                    m.homegoal = hometeam.goals
                    m.awaygoal = awayteam.goals
                    m.result = hometeam.point
                    if Habandon and Aabandon:
                        m.result = -1
                    if m.stage != 'Group' and m.homegoal == m.awaygoal:
                        m.penaltyshootout = True
                        sql = 'UPDATE Matches SET HomeGoal = %s, AwayGoal = %s, PenaltyShootOut = %s, HomePenalty = %s, AwayPenalty = %s, Result = %s WHERE MatchID = %s'
                        cursor.execute(sql,(m.homegoal, m.awaygoal, m.penaltyshootout, m.homepenalty, m.awaypenalty, m.result, m.matchid))
                    else:
                        sql = 'UPDATE Matches SET HomeGoal = %s, AwayGoal = %s, Result = %s WHERE MatchID = %s'
                        cursor.execute(sql,(m.homegoal, m.awaygoal, m.result, m.matchid))
                else:
                    sql = 'UPDATE Matches SET HomeGoal = %s, AwayGoal = %s, PenaltyShootOut = %s, HomePenalty = %s, AwayPenalty = %s, Result = %s WHERE MatchID = %s'
                    cursor.execute(sql,(m.homegoal, m.awaygoal, m.penaltyshootout, m.homepenalty, m.awaypenalty, m.result, m.matchid))


                #update teams
                sql = 'SELECT * FROM Teams WHERE TeamName = %s'
                cursor.execute(sql,hometeam.name)
                ht = cursor.fetchall()[0]
                sql = 'SELECT * FROM Teams WHERE TeamName = %s'
                cursor.execute(sql,awayteam.name)
                at = cursor.fetchall()[0]
                print(ht,at)
                if add:
                    if hometeam.point == 3:
                        ht['Win'] += 1
                    elif hometeam.point == 1:
                        ht['Draw'] += 1
                    elif hometeam.point == 0:
                        ht['Lose'] += 1
                    if awayteam.point == 3:
                        at['Win'] += 1
                    elif awayteam.point == 1:
                        at['Draw'] += 1
                    elif awayteam.point == 0:
                        at['Lose'] += 1
                    if groupbool:
                        ht['Point'] += hometeam.point
                        at['Point'] += awayteam.point
                    ht['Goal'] += hometeam.goals
                    at['Goal'] += awayteam.goals
                    ht['Concede'] += hometeam.concede
                    at['Concede'] += awayteam.concede
                    ht['Penalty'] += hometeam.penalty
                    at['Penalty'] += awayteam.penalty
                    ht['Penaltymiss'] += hometeam.penmiss
                    at['Penaltymiss'] += awayteam.penmiss
                    ht['OwnGoal'] += hometeam.owngoal
                    at['OwnGoal'] += awayteam.owngoal
                    ht['YellowCard'] += hometeam.yc
                    at['YellowCard'] += awayteam.yc
                    ht['RedCard'] += hometeam.rc
                    at['RedCard'] += awayteam.rc
                else:
                    if hometeam.point == 3:
                        ht['Win'] -= 1
                    elif hometeam.point == 1:
                        ht['Draw'] -= 1
                    elif hometeam.point == 0:
                        ht['Lose'] -= 1
                    if awayteam.point == 3:
                        at['Win'] -= 1
                    elif awayteam.point == 1:
                        at['Draw'] -= 1
                    elif awayteam.point == 0:
                        at['Lose'] -= 1
                    if groupbool:
                        ht['Point'] -= hometeam.point
                        at['Point'] -= awayteam.point
                    ht['Goal'] -= hometeam.goals
                    at['Goal'] -= awayteam.goals
                    ht['Concede'] -= hometeam.concede
                    at['Concede'] -= awayteam.concede
                    ht['Penalty'] -= hometeam.penalty
                    at['Penalty'] -= awayteam.penalty
                    ht['Penaltymiss'] -= hometeam.penmiss
                    at['Penaltymiss'] -= awayteam.penmiss
                    ht['OwnGoal'] -= hometeam.owngoal
                    at['OwnGoal'] -= awayteam.owngoal
                    ht['YellowCard'] -= hometeam.yc
                    at['YellowCard'] -= awayteam.yc
                    ht['RedCard'] -= hometeam.rc
                    at['RedCard'] -= awayteam.rc
                print(ht,at)
                sql = 'UPDATE Teams SET Win = %s, Draw = %s, Lose = %s, Goal = %s, Concede = %s, Point = %s, Penalty = %s, YellowCard = %s, RedCard = %s, Penaltymiss = %s, OwnGoal = %s WHERE TeamName = %s'
                cursor.execute(sql,(ht['Win'], ht['Draw'], ht['Lose'], ht['Goal'], ht['Concede'], ht['Point'], ht['Penalty'], ht['YellowCard'], ht['RedCard'], ht['Penaltymiss'], ht['OwnGoal'], ht['TeamName']))
                cursor.execute(sql,(at['Win'], at['Draw'], at['Lose'], at['Goal'], at['Concede'], at['Point'], at['Penalty'], at['YellowCard'], at['RedCard'], at['Penaltymiss'], at['OwnGoal'], at['TeamName']))
                
                #update players
                for p in players.items(): 
                    p[1].getontime()
                    sql = 'SELECT * FROM Players WHERE Team = %s AND Name = %s AND KitNumber = %s'
                    cursor.execute(sql,(p[1].team, p[1].name, p[1].kitnum))
                    #print(p[1].name)
                    playerinfo = cursor.fetchall()
                    playerinfo = playerinfo[0]
                    #print(playerinfo)
                    if add:
                        if p[1].onpitch:
                            playerinfo['Appearances'] += 1
                        playerinfo['Minutes'] += p[1].ontime
                        playerinfo['Goals'] += p[1].goals
                        playerinfo['YellowCards'] += p[1].yc
                        playerinfo['RedCards'] += p[1].rc
                        playerinfo['Penalties'] += p[1].penalty
                        playerinfo['Penaltymiss'] += p[1].penmiss
                        playerinfo['OwnGoals'] += p[1].owngoal

                    else:
                        if p[1].onpitch:
                            playerinfo['Appearances'] -= 1
                        playerinfo['Minutes'] -= p[1].ontime
                        playerinfo['Goals'] -= p[1].goals
                        playerinfo['YellowCards'] -= p[1].yc
                        playerinfo['RedCards'] -= p[1].rc
                        playerinfo['Penalties'] -= p[1].penalty
                        playerinfo['Penaltymiss'] -= p[1].penmiss
                        playerinfo['OwnGoals'] -= p[1].owngoal
                    #print(playerinfo)
                    sql = 'UPDATE Players SET Appearances = %s, Minutes = %s, Goals = %s, YellowCards = %s, RedCards = %s, Penalties = %s, Penaltymiss = %s, OwnGoals = %s WHERE Team = %s AND Name = %s AND KitNumber = %s'
                    cursor.execute(sql,(playerinfo['Appearances'], playerinfo['Minutes'], playerinfo['Goals'], playerinfo['YellowCards'], playerinfo['RedCards'], playerinfo['Penalties'], playerinfo['Penaltymiss'], playerinfo['OwnGoals'], playerinfo['Team'], playerinfo['Name'], playerinfo['KitNumber']))

            connection.commit()
    finally:
        connection.close()

def Clear(dbname):
    connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db=dbname,
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)
    try:
        with connection.cursor() as cursor:
            sql = 'UPDATE Players SET Appearances = 0, Minutes = 0, Goals = 0, YellowCards = 0, RedCards = 0, Penalties = 0, Penaltymiss = 0, OwnGoals = 0'
            cursor.execute(sql)
            sql = 'UPDATE Teams SET Win = 0, Draw = 0, Lose = 0,Goal = 0, Concede = 0, Point = 0,Penalty = 0, YellowCard = 0, RedCard = 0, Penaltymiss = 0, OwnGoal = 0'
            cursor.execute(sql)
            sql = 'UPDATE Matches SET HomeGoal = 0, AwayGoal = 0,PenaltyShootOut = NULL, HomePenalty = NULL, AwayPenalty = NULL, Result = NULL'
            cursor.execute(sql)
            connection.commit()
            print('successfully cleared')
    finally:
        connection.close()



def main(argv):
    try:
        opts, args = getopt.getopt(argv, "hca:d:n:",["help", "add=", "delete=", "clear", "allvalid", "matchname"])
    except getopt.GetoptError:
        print('Syntax Error. Please use -h or --help to see the usage.')
        sys.exit(2)
    mode = ""
    mid = ""
    add = True
    clear = False
    for opt, arg in opts:
        if opt in ("-h", "--help"):
            print('Usage:\n-n or --matchname= \t input matchname\n-a or --add= \t add a match into the Stats.Argument:MatchID\n-d or --delete= \t delete a match from the Stats.Argument:MatchID\n-c or --clear \t clear the Stats.Argument:None\n--allvalid \t stat all valid matches\n-h or --help \t see the help words')
            sys.exit()
        elif opt in ("-a", "--add"):
            add = True
            mid = arg
        elif opt in ("-n","--matchname"):
            matchname = arg
        elif opt in ("-d", "--delete"):
            add = False
            mid = arg
        elif opt in ("-c", "--clear"):
            clear = True
        elif opt == "--allvalid":
            mid = None
        if clear:
            Clear(dbname=matchname)
        else:
            Stat(matchname = matchname, matchid = mid, add = add)



if __name__ == "__main__":
    main(sys.argv[1:])
