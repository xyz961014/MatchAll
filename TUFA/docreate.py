import createsheet
import sys

def main(argv):
    matchdb = 'MANAN_1718'
    if len(argv) > 2:
        matchdb = argv[2]
    fname = createsheet.createmanansheet(argv[0],argv[1],matchdb=matchdb)
    print('completed')
    print(fname[0])
    print(fname[1])



if __name__ == "__main__":
    main(sys.argv[1:])
