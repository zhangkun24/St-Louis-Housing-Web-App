'''
python script to catch up the address and zpid of house on zillow
'''
import requests
import re
#zipcode lists
zipcode=[63147, 63120, 63115, 63112, 63113, 63107, 63108, 63106, 63110, 63103, 63101, 63102, 63104, 63139, 63118, 63109, 63116, 63111]
#regular expression for zpid
rezpid=re.compile(r'id="zpid_(\d*)"')
#regular expression for address
readdress=re.compile(r'data-address="(.*?)"')
#open a fd to write html encode str
fd=open('zpid_address.txt','w')
url="http://www.zillow.com/homes/zipcode_rb/"
for zipc in zipcode:
    #trim the request url
    requestUrl=url.replace('zipcode',str(zipc))
    #make a requst
    request=requests.get(requestUrl)
    #match the substrings and store them in a list
    str_content=request.text
    zpid_list=rezpid.findall(str_content)
    zpid_list_length=len(zpid_list)
    address_list=readdress.findall(str_content)
    address_list_length=len(address_list)
    #handle the special case
    if(2*zpid_list_length==address_list_length):
        for zpid,address in zip(zpid_list,address_list[::2]):
            fd.write(zpid+':'+address+'\n')
    elif(zpid_list_length==address_list_length):
        for zpid,address in zip(zpid_list,address_list):
            fd.write(zpid+':'+address+'\n')
    else:
        print("error url is {} and the zpid/address={}".format(requestUrl,zpid_list_length/address_list_length))
fd.close()




