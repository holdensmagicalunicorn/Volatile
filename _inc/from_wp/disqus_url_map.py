#! /usr/bin/python -B
# -*- coding: utf-8 -*-

import sys
from xml.dom import minidom

def main():
    import re
    regex = 'http:\/\/.*\/(.*)\/.*\/'
    data = []
    xmldoc = minidom.parse(sys.argv[1])
    threadlist = xmldoc.getElementsByTagName('thread')

    for thread in threadlist:
        link = thread.getElementsByTagName('link')
        try:
            before = link[0].firstChild.data
            matchObj = re.search(regex, before)
            #if matchObj:
            #    print "search --> matchObj.group() : ", matchObj.group(1)
            after = before.replace('/'+matchObj.group(1)+'/', '/')
            #print before
            #print after
            sys.exit(1)
            data.append( (before, after) )
        except:
            for node in link:
                before = node.firstChild.data
                matchObj = re.search(regex, before)
                try:
                    after = before.replace('/'+matchObj.group(1)+'/', '/')
                except:
                    continue
                data.append( (before, after) )


    import csv
    spamWriter = csv.writer(open('eggs.csv', 'wb'), delimiter=',', quotechar='|', quoting=csv.QUOTE_MINIMAL)
    for line in data:
        spamWriter.writerow(line)

if __name__ == "__main__":
    main()
