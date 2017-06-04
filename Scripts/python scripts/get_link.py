from bs4 import BeautifulSoup
import requests
import re
# convert zpid and address to link
with open("zpid_address.txt",'r') as fd:
    with open("link.txt",'w') as fd1:
        for line in fd:
            url='http://www.zillow.com/homedetails/'
            newLine=line.split(':')
            zpid=newLine[0]
            address=newLine[1]
            zpid_url=str(zpid)+r'_zpid/'
            address_split=re.split(r'\s*[,\s]\s*',address)
            address_split.remove("")
            if "#" in address_split:
                address_split.remove("#")
            address_url='-'.join(address_split)+'/'
            url+=address_url+zpid_url
            fd1.write(url+'\n')










