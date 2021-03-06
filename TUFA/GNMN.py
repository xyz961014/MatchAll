import pymysql.cursors
import TeamDict

connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db='MANAN_1819',
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)

try:
    with connection.cursor() as cursor:
        sql = 'SELECT * FROM Matches'
        cursor.execute(sql)
        matches = cursor.fetchall()
        for m in matches:
            if m['Stage'] == 'Group':
                sql = 'UPDATE Teams SET GroupName = %s, Level = %s WHERE TeamName = %s'
                cursor.execute(sql,(m['GroupName'], m['Level'], TeamDict.getfull(m['HomeTeam'])))
                cursor.execute(sql,(m['GroupName'], m['Level'], TeamDict.getfull(m['AwayTeam'])))
        connection.commit()
finally:
    connection.close()
