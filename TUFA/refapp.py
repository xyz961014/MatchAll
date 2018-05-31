import os
import json
import codecs

if os.path.exists('/home/xyz/Desktop/ref.json'):
    with codecs.open('/home/xyz/Desktop/ref.json', 'r', 'utf-8') as f:
        line = f.readline()
        ref = json.loads(line)
print(ref)
