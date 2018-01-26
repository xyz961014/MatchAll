import os  
import os.path  
import loadinfo
  
# this folder is custom  
rootdir="男足报名表17-18"  
fname = ''
for parent,dirnames,filenames in os.walk(rootdir):  
    for filename in filenames:    
        fname = os.path.join(parent, filename)
        print('loading:',fname)
        loadinfo.loadplayerinfomanan(fname)
        loadinfo.loadteamandleaderinfomanan(fname)
        print('complete')  
