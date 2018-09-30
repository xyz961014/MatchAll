import os  
import os.path  
import loadinfo
import argparse  
# this folder is custom  
#rootdir="男足报名表17-18"  
#fname = ''
#for parent,dirnames,filenames in os.walk(rootdir):  
#    for filename in filenames:    
#        fname = os.path.join(parent, filename)
#        print('loading:',fname)
#        loadinfo.loadplayerinfomanan(fname)
#        loadinfo.loadteamandleaderinfomanan(fname)
#        print('complete')  
#rootdir="新生杯队伍报名表"  
#fname = ''
#for parent,dirnames,filenames in os.walk(rootdir):  
#    for filename in filenames:    
#        fname = os.path.join(parent, filename)
#        print('loading:',fname)
#        loadinfo.loadinfofreshman(fname)
#        print('complete')  

#rootdir="女足报名18"  
#fname = ''
#for parent,dirnames,filenames in os.walk(rootdir):  
#    for filename in filenames:    
#        fname = os.path.join(parent, filename)
#        print('loading:',fname)
#        loadinfo.loadinfomanyu(fname)
#        print('complete') 

#rootdir="男足补报名18"  
#fname = ''
#for parent,dirnames,filenames in os.walk(rootdir):  
#    for filename in filenames:    
#        fname = os.path.join(parent, filename)
#        print('loading:',fname)
#        loadinfo.loadinfobubaoming(fname)
#        print('complete') 

def parseargs(arg=None):
    parser = argparse.ArgumentParser()
    parser.add_argument("--rootdir", '-r', help="root direction of tables")
    return parser.parse_args(arg)

def main(args):
    rootdir = args.rootdir
    fname = ''
    for parent,dirnames,filenames in os.walk(rootdir):  
        for filename in filenames:    
            fname = os.path.join(parent, filename)
            print('loading:',fname)
            loadinfo.loadteamandleaderinfomanan(fname)
            loadinfo.loadplayerinfomanan(fname)
            print('complete') 

if __name__ == "__main__":
    args = parseargs()
    main(args)

