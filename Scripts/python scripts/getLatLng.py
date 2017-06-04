import requests
import xml.etree.ElementTree as ET
tree=ET.parse('offender.xml')
root=tree.getroot()
google_url='https://maps.googleapis.com/maps/api/geocode/xml?address=target_address,+Saint+Louis,+MO&key=AIzaSyBxR6metB2tyKKvR2apuDq6tc9KAuC9H1U'
for table in root.findall('table'):
    for column in table.findall('column'):
        if column.attrib['name']=='address':
            address='+'.join(column.text.split(' '))
            #trim address
            if '#+' in address:
                address=address.replace('#+','')
            url=google_url.replace('target_address',address)
            request=requests.get(url)
            request_str=request.text
            request_root=ET.fromstring(request_str)
            for element in request_root.findall('result'):
                for geometry in element.findall('geometry'):
                    location=geometry.find('location')
                    lat=location.find('lat').text
                    lng=location.find('lng').text
        if column.attrib['name']=='lat':
            column.text=lat
        if column.attrib['name']=='lng':
            column.text=lng
tree.write('offender.xml')

















