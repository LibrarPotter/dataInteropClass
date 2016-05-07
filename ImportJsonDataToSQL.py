#To make this work you must the following from this stackoverflow post: http://stackoverflow.com/questions/372885/how-do-i-connect-to-a-mysql-database-in-python
#You must install a MySQL driver before doing anything. Unlike PHP, only the SQLite driver is installed by default with Python. The most used package to do so is MySQLdb but it's hard to install it using easy_install.
#For Windows user, you can get an exe of MySQLdb.
#For Linux, this is a casual package (python-mysqldb). (You can use sudo apt-get install python-mysqldb (for debian based distros), yum install mysql-python (for rpm-based), or dnf install python-mysql (for modern fedora distro) in command line to download.)
#For Mac, you can install MySQLdb using Macport.



import json
import MySQLdb

with open('RecordsForDI_3_31_16.json') as data_file:    
    data = json.load(data_file)

#pprint(data) #running this will take a while because of how many rows there are in the file, so be prepared
db = MySQLdb.connect(host="localhost",    # your host, usually localhost
                     user="john",         # your username
                     passwd="megajonhy",  # your password
                     db="jonhydb")        # name of the data base
cur = db.cursor()


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
    
    cur.execute(sql)
    
    


#when done
db.close()
data_file.close()