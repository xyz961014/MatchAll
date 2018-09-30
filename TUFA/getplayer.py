import argparse
import pymysql.cursors
import urllib.request
import json
import re


def parse_args(arg = None):
    parser = argparse.ArgumentParser()
    parser.add_argument("--name", "-n", help="team name")
    parser.add_argument("--teamidweb", "-i", help="web team id")
    parser.add_argument("--database", "-d", help="team database")
    parser.add_argument("--sex", "-s", help="team database")
    return parser.parse_args(arg)

def main(args):
    webteamid = args.teamidweb
    db = args.database
    teamname = args.name
    sex = args.sex
    url = "https://www.tafa.org.cn/member/helper_read.php"
    headers = {
        'User-Agent': 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
    }
    values = {
        'username': "xyz961014@126.com",
        'password': "p=19961014",
        'function': "team",
        "content": webteamid
    }
    data = urllib.parse.urlencode(values).encode('utf-8')
    request = urllib.request.Request(url, data, headers)
    html = urllib.request.urlopen(request).read().decode('utf-8')
    players = json.loads(html)
    connection = pymysql.connect(host='localhost',
                                 user='root',
                                 password='961014',
                                 db=db,
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)
    try:
        for player in players:
            with connection.cursor() as cursor:
                sql = "SELECT Class FROM Players"
                cursor.execute(sql)
                ids = cursor.fetchall()
                ids = [d['Class'] for d in ids]
            print(player)
            remark = ""
            if re.match(r"2018", player['enter']) and player['act'] == "本科":
                remark = "大一"
            doneyear = player['done'].split('-')[0]
            if not doneyear == "0000":
                if int(doneyear) <= 2018:
                    remark = "校友"
            if player['idnum'] == "" or player["idnum"] == '8':
                idnum = None
            else:
                idnum = player["idnum"]
            if player['num'] is None:
                kitnum = 0
            else:
                kitnum = player['num']
            with connection.cursor() as cursor:
                if player['sex'] == sex and int(player["level"]) > 0 and int(player["level"]) < 3:
                    if player['id'] in ids:
                        sql = "UPDATE Players SET Team = %s, Name = %s, IDNumber = %s, PhoneNumber = %s, KitNumber = %s, ExtraInfo = %s WHERE Class = %s" 
                        cursor.execute(sql, (teamname, player['name'], idnum, player['mobile'], kitnum, remark, player['id']))
                    else:
                        sql = "INSERT INTO Players (Team, Name, Class, IDNumber, PhoneNumber, KitNumber, ExtraInfo) VALUES (%s, %s, %s, %s, %s, %s, %s)"
                        cursor.execute(sql, (teamname, player['name'], player['id'], idnum, player['mobile'], kitnum, remark))
        connection.commit()

    finally:
        connection.close()

if __name__ == "__main__":
    main(parse_args())

