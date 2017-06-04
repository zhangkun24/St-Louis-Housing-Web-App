import fileinput

data={}

with open('zpid_address.txt','r') as fd:
    index=0
    for line in fd:
        line=line.splitlines()

        trim_line_list=line[0].split(',')
        if len(trim_line_list)!=5:
            continue
        else:
            zillow_id=trim_line_list[0]
            address=trim_line_list[1]
            city=trim_line_list[2]
            state=trim_line_list[3]
            zipcode=trim_line_list[4]
            data[index]=[]
            data[index].append(zillow_id)
            data[index].append(address)
            data[index].append(city)
            data[index].append(state)
            data[index].append(zipcode)
            index+=1
with open('detailed_data.txt','r') as fd1:
    index=0
    for line in fd1:
        line=line.splitlines()
        trim_line_list=line[0].split(',')
        if len(trim_line_list)!=5:
            continue
        else:
            bed_room=trim_line_list[0]
            bath_room=trim_line_list[1]
            size=trim_line_list[2]
            zest_value=trim_line_list[3]
            real_value=trim_line_list[4]
            data[index].append(bed_room)
            data[index].append(bath_room)
            data[index].append(size)
            data[index].append(zest_value)
            data[index].append(real_value)
            index+=1
with open('link.txt','r') as fd2:
    index=0
    for line in fd2:
        line=line.splitlines()
        if len(line)==0:
            continue
        else:
            data[index].append(line[0])
            index+=1
with open('final_data.txt','w') as fd3:
    index=0
    for key,value in data.items():
        s=','.join(value)+'\n'
        fd3.write(s)









