import pymysql.cursors

fp = open('PlayerListmanyu.txt', 'w')
connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db='MANYU_18',
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)

try:
    with connection.cursor() as cursor:
        sql = 'SELECT * FROM Players'
        cursor.execute(sql)
        players = cursor.fetchall()
        for p in players:
            fp.write(p['Team'] + '\t' + p['Name'] + '\t' + p['Class'] + '\n')
            
finally:
    connection.close()
    fp.close()
