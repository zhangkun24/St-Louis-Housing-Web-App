'''
spider to catch the data
'''
import requests
from bs4 import BeautifulSoup
import re
'''
request=requests.get("http://www.zillow.com/homedetails/816-McLaran-Ave-Saint-Louis-MO-63147/2975825_zpid/")
str_content=request.text
soup=BeautifulSoup(str_content,"lxml")
#house value

print(re.split(r'[$,]',value))
#datailed data

    for child in tag.children:
        print(child.string)
'''
#regular expression
re_price=re.compile(r'\$(\d*,*\d*,*\d*)')
#regular expression
re_num=re.compile(r'(\d)')
#open the link file
with open("link.txt",'r') as fd:
    with open('detailed_data.txt','w') as fd1:
        for line in fd:
            #trim the line to the correct link
            line=line.splitlines()
            request=requests.get(line[0])
            str_content=request.text
            soup=BeautifulSoup(str_content,'lxml')
            #tag list (3 elements :1.bedrooms 2.bathrooms 3.size)  it contains special base of which length !=3
            addr_bbs_list=soup.select('.addr_bbs')
            addr_bbs_list_length=len(addr_bbs_list)
            #handle the special case
            if(addr_bbs_list_length!=3):
                bed_room,bath_room,size='error','error','error'
            else:
                #bed room
                bed_room=addr_bbs_list[0].string
                #bath_room
                bath_room=addr_bbs_list[1].string
                #size
                size=addr_bbs_list[2].string
                if ',' in size:
                    size=size.replace(',','')
                size=size.split(' ')[0]
                #studio
                if(bed_room=='Studio'):
                    bed_room=1
                    bath_room=1
                else:
                    bed_num=re_num.match(bed_room)
                    bath_num=re_num.match(bath_room)
                    if bed_num is None:
                        bed_room='null'
                    else:
                        bed_room=bed_num.group(1)
                    if bath_num is None:
                       bath_room='null'
                    else:
                        bath_room=bath_num.group(1)
            #fd1.write(bed_room+','+bath_room+','+size+'\n')
            #find zestmate  value
            zest_value_content=soup.select('.zest-value')[0].string
            zest_value='null'
            if '$' in zest_value_content:
                zest_value_match=re_price.findall(zest_value_content)
                zest_value=zest_value_match[0]
                if ',' in zest_value:
                    zest_value=zest_value.replace(',','')
            #find value
            value=soup.find(class_='main-row home-summary-row')
            real_value='null'
            if value is not None:
                for string in value.strings:
                    if '$' in string:
                        real_value=string
                        break
            #real_value
            if '$'  in real_value:
                real_value_match=re_price.findall(real_value)
                real_value=real_value_match[0]
                print(real_value)
                if ',' in real_value:
                    real_value=real_value.replace(',','')
            else:
                real_value='null'
            fd1.write(str(bed_room)+':'+str(bath_room)+':'+str(size)+':'+str(zest_value)+':'+str(real_value)+'\n')























