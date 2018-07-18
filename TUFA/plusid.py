import pymysql.cursors
import re
def addid(dbname):
    connection = pymysql.connect(host='localhost',
                                 user='root',
                                 password='961014',
                                 db=dbname,
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)
    try:
        with connection.cursor() as cursor:
            sql = "show tables"
            cursor.execute(sql)
            tables = cursor.fetchall()
    
        for table in tables:
            tablename = table['Tables_in_' + dbname]
            with connection.cursor() as cursor:
                if re.match(r'Match[\d]+', tablename):
                    sql = 'ALTER TABLE ' + tablename + ' ADD EventID INT NOT NULL AUTO_INCREMENT PRIMARY KEY'
                    print(sql)
                    cursor.execute(sql)
                if tablename == 'Players':
                    sql = 'ALTER TABLE Players ADD PlayerID INT NOT NULL AUTO_INCREMENT PRIMARY KEY'
                    print(sql)
                    cursor.execute(sql)
                if tablename == 'Teams':
                    sql = 'ALTER TABLE Teams ADD TeamID INT NOT NULL AUTO_INCREMENT PRIMARY KEY'
                    print(sql)
                    cursor.execute(sql)
                if tablename == 'Leaders':
                    sql = 'ALTER TABLE Leaders ADD LeaderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY'
                    print(sql)
                    cursor.execute(sql)
        connection.commit()
    finally:
        connection.close()

addid("FRESHMANCUP_18")
addid("MANAN_1718")
addid("MANYU_18")
addid("MAWU_18")
addid("NANQI_18")
