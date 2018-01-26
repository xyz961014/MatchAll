# coding=utf-8
PYTHONIOENCODING="UTF-8"
import pymysql.cursors
import sys
print(sys.stdout.encoding)

print(('化学').encode('utf-8'))
hometeam = '物理系'
#print(hometeam)
connection = pymysql.connect(host='localhost',
                               user='apache',
                               password='961014',
                               db='MANAN_1718',
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)

try:
    with connection.cursor() as cursor:
      sql = "SELECT * FROM Players where Team = %s"
      cursor.execute(sql,hometeam)
      homeplayers = cursor.fetchall()
      for player in homeplayers:
        print(player['Name'].encode('utf-8'))

finally:
    connection.close()

