import createsheet
import sys
import re

def main(argv):
    matchdb = 'MANAN_1718'
    subtitle = '男子足球执场单'
    if len(argv) > 2:
        matchdb = argv[2]
        if re.match('MANYU',matchdb):
            subtitle = '女子足球执场单'
            fname = createsheet.createsinglesheet(argv[0], argv[1], matchdb=matchdb, subtitle=subtitle)
        elif re.match('MAWU', matchdb):
            subtitle = '五人制足球执场单'
            fname = createsheet.createsinglesheet(argv[0], argv[1], matchdb=matchdb, subtitle=subtitle)
        elif re.match('FRESH', matchdb):
            subtitle = '足球比赛执场单'
            fname = createsheet.create4subsheet(argv[0], argv[1], matchdb=matchdb, subtitle=subtitle)
        elif re.match('MANAN', matchdb):
            fname = createsheet.createmanansheet(argv[0],argv[1],matchdb=matchdb,subtitle=subtitle)
    print('completed')
    print(fname[0])
    print(fname[1])



if __name__ == "__main__":
    main(sys.argv[1:])
