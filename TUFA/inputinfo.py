import pymysql.cursors

connection = pymysql.connect(host='localhost',
                             user='root',
                             password='961014',
                             db='MATCHES',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)
try:
    with connection.cursor() as cursor:
        sql = "SELECT * FROM matches"
        cursor.execute(sql)
        matches = cursor.fetchall()

finally:
    connection.close()    

for m in matches:
    connection = pymysql.connect(host='localhost',
                                 user='root',
                                 password='961014',
                                 db=m['dbname'],
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)
    try:
        with connection.cursor() as cursor:
            sql = "CREATE TABLE Info (name varchar(255), subname varchar(255), maxonfield int, minonfield int, enablekitnum boolean, class varchar(255), penalty varchar(255), ordinarytime int, extratime int, penaltyround int, year varchar(255))"
            cursor.execute(sql)
            sql = "INSERT INTO Info (name, subname, maxonfield, minonfield, enablekitnum, class, penalty, ordinarytime, extratime, penaltyround, year) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
            cursor.execute(sql, (m['name'], m['subname'], m['maxonfield'], m['minonfield'], m['enablekitnum'], m['class'], m['penalty'], m['ordinarytime'], m['extratime'], m['penaltyround'], m['year']))
            connection.commit()
    finally:
        connection.close()

                          
                          
                          
                          
                          
                          
