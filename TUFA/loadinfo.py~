#coding=utf-8
from docx import Document
import pymysql.cursors
import re

def loadteamandleaderinfomanan(filename):
  if not re.search('\.docx$',filename):
    return
  document = Document(filename)
  ps = document.paragraphs
  teaminfo = ps[2].text.split()
  print(teaminfo)
  tnObj = re.match(r'院系：\S',teaminfo[0])
  col = 0
  if tnObj:
    teamname = teaminfo[0][3:]
    col += 1
  else:
    teamname = teaminfo[1]
    col += 2
  colObj = re.match(r'比赛服颜色（上衣/短裤）：\S',teaminfo[col])
  if colObj:
    teamcolor = teaminfo[col][13:]
  else:
    teamcolor = teaminfo[col+1]
  print(teamname,teamcolor)
  tb = document.tables
  connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db='MANAN_1718',
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)

  try:
    if len(tb) > 1:
      for p in tb[1].columns:
        if p.cells[0].text != '姓名':
          name = p.cells[0].text
          job = p.cells[1].text
          phonenumber = p.cells[2].text
          email = p.cells[3].text
          with connection.cursor() as cursor:
          # Create a new record
            sql = "INSERT INTO `Leaders` (`Team`, `Name`,`Job`,`PhoneNumber`,`Email`) VALUES (%s, %s, %s, %s, %s)"
            cursor.execute(sql, (teamname, name, job, phonenumber, email))
    with connection.cursor() as cursor:
      sql = "INSERT INTO `Teams` (`TeamName`, `KitColor`) VALUES (%s, %s)"
      cursor.execute(sql,(teamname,teamcolor))

    # connection is not autocommit by default. So you must commit to save
    # your changes.
      connection.commit()

  finally:
    connection.close()


def loadplayerinfomanan(filename):
  if not re.search('\.docx$',filename):
    return
  document = Document(filename)
  ps = document.paragraphs
  print(len(ps))
  teaminfo = ps[2].text.split()
  print(teaminfo)
  tnObj = re.match(r'院系：\S',teaminfo[0])
  col = 0
  if tnObj:
    teamname = teaminfo[0][3:]
    col += 1
  else:
    teamname = teaminfo[1]
    col += 2
  colObj = re.match(r'比赛服颜色（上衣/短裤）：\S',teaminfo[col])
  if colObj:
    teamcolor = teaminfo[col][13:]
  else:
    teamcolor = teaminfo[col+1]
  print(teamname,teamcolor)
  tb = document.tables
  connection = pymysql.connect(host='localhost',
                               user='root',
                               password='961014',
                               db='MANAN_1718',
                               charset='utf8mb4',
                               cursorclass=pymysql.cursors.DictCursor)

  try:
    for p in tb[0].rows[1:]:
      name = p.cells[1].text
      class1 = p.cells[2].text
      idnumber = p.cells[3].text
      phonenumber = p.cells[4].text
      kitnumber = p.cells[5].text
      extrainfo = p.cells[6].text
      if name != '':
        kitnumber = int(kitnumber)
        if re.match('\s+',idnumber) or idnumber == '无' or idnumber == '':
          idnumber = None
        with connection.cursor() as cursor:
          # Create a new record
          sql = "INSERT INTO `Players` (`Team`, `Name`,`Class`,`IDNumber`,`PhoneNumber`,`KitNumber`,`ExtraInfo`) VALUES (%s, %s, %s, %s, %s, %s, %s)"
          cursor.execute(sql, (teamname, name, class1, idnumber, phonenumber, kitnumber, extrainfo))

    # connection is not autocommit by default. So you must commit to save
    # your changes.
      connection.commit()

  finally:
    connection.close()
  
