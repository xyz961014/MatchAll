import createsheet
import sys

def main(argv):
  fname = createsheet.createmanansheet(argv[0],argv[1])
  print('completed')
  print(fname[0])
  print(fname[1])



if __name__ == "__main__":
  main(sys.argv[1:])
