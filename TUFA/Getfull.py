#coding:utf-8
import sys
import urllib
import TeamDict


def main(argv):
    if len(argv) == 1:
        argv.append(False)
    ans = TeamDict.getfull(argv[0], argv[1])
    print(ans)

if __name__ == '__main__':
    if len(sys.argv) > 1:
        s = sys.argv[1]
    main(sys.argv[1:])
