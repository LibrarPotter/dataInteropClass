import json
from pprint import pprint

with open('RecordsForDI_3_31_16.json') as data_file:    
    data = json.load(data_file)

#pprint(data) #running this will take a while because of how many rows there are in the file, so be prepared

#I made a test range, but if it looks ready, switch out the for loops.
#for i in range(len(data))
for i in range(5):
    creatorName = data['rows'][i]['creatorName']
    objectType = data['rows'][i]['objectType']
    location = data['rows'][i]['location']
    recordCreationDate = data['rows'][i]['recordCreationDate']
    titleOfObject= data['rows'][i]['titleOfObject']
    description= data['rows'][i]['description']
    #print creatorName
    #print objectType
    #print location
    #print recordCreationDate
    #print titleOfObject
    #print description
    
    sql = "INSERT INTO table_name (objectType, creatorName, location, recordCreationDate, titleOfObject, description) VALUES ("+objectType+","+creatorName+","+location+","+recordCreationDate+","+titleOfObject+","+description+");"
    print sql
    


#when done
data_file.close()