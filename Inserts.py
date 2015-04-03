import datetime

file = open('Setup.sql', 'r')
output = open('CustomScript.sql', 'w') 
for line in file:
        output.write(line)
file.close()

names = ["Raymond Lieu", \
         "Liangrui Lu", \
         "Omar Zioueche", \
         "Ichigo Kurosaki", \
         "Byakuya Kuchiki", \
         "Urahara Kisuke", \
         "Hitsuguya Toshiro", \
         "Renji Abarai", \
         "Aizen Sousuke", \
         "Kenpachi Zaraki", \
         "Naruto Uzumaki", \
         "Sasuke Uchiha", \
         "Itachi Uchiha", \
         "Madara Uchiha", \
         "Kakashi Hatake", \
         "Hinata Hyuga", \
         "Oga Tatsumi", \
         "Aoi Kuneida", \
         "Tatsuya Shiba", \
         "Miyuki Shiba", \
         "Moroha Haimura"]

location = ["Edmonton", "Calgary", "Toronto", "Montreal", "Quebec", "Goryeo", "Gaebong"]

test = ["MRI", "CT", "X-RAY", "BLOOD", "DNA"]

results = [["DEAD", "Doctor messed up"], \
           ["ALIVE", "Cannot believe the doctor actually did it"], \
           ["COMATOSE", "Weird..."], \
           ["PARALYZED", "Darn that Pikachu and its thunder bolt"], \
           ["BURNED", "Darn that Groudon and its fire blast"]]

first_names = []
last_names = []

date = datetime.date.today()

f1 = "RAWTOHEX('Test Thumbnail')"
f2 = "RAWTOHEX('Test Regular Size')"
f3 = "RAWTOHEX('Test Full Size')"

for i in names:
        name = i.split(" ")
        first_names.append(name[0])
        last_names.append(name[len(name)-1])


# persons
for i in range(len(first_names)):
        output.write("Insert into persons values (" + \
                     str(i) + ", " + \
                     "'" + first_names[i] + "'" + ", " + \
                     "'" + last_names[i] + "'" + ", " + \
                     "'" + location[i%len(location)] + "'" + ", " + \
                     "'" + first_names[i] + "@hotmail.com" + "'" + ", " + \
                     "'" + "780" + str(i%9)*3 + str(i%9)*4 + "'" + \
                    ");\n")

# users
output.write("Insert into users values ('admin', 'admin', 'a', 2, " + \
             "'" + date.strftime('%d-%b-%Y') + "'" + \
             ");\n")

for i in range(len(first_names)):
        if (i%3 == 0): 
                output.write("Insert into users values (" + \
                             "'" + "a_" + first_names[i] + last_names[i] + "'" + ", " + \
                             "'" + "pwd" "'" + ", " + \
                             "'" + "a" + "'" + ", " + \
                             str(i) + ", " + \
                             "'" + (date + datetime.timedelta(days=(i+1)*(i+3))).strftime('%d-%b-%Y') + "'" + \
                             ");\n")
        if (i%2 == 0):
                output.write("Insert into users values (" + \
                             "'" + "d_" + first_names[i] + last_names[i] + "'" + ", " + \
                             "'" + "pwd" "'" + ", " + \
                             "'" + "d" + "'" + ", " + \
                             str(i) + ", " + \
                             "'" + (date + datetime.timedelta(days=(i+2)*(i+2))).strftime('%d-%b-%Y') + "'" + \
                             ");\n")
        if (i%5 == 0):
                output.write("Insert into users values (" + \
                             "'" + "r_" + first_names[i] + last_names[i] + "'" + ", " + \
                             "'" + "pwd" "'" + ", " + \
                             "'" + "r" + "'" + ", " + \
                             str(i) + ", " + \
                             "'" + (date + datetime.timedelta(days=(i+1)*(i+5))).strftime('%d-%b-%Y') + "'" + \
                             ");\n")   
        output.write("Insert into users values (" + \
                     "'" + "p_" + first_names[i] + last_names[i] + "'" + ", " + \
                     "'" + "pwd" "'" + ", " + \
                     "'" + "p" + "'" + ", " + \
                     str(i) + ", " + \
                     "'" + (date + datetime.timedelta(days=i)).strftime('%d-%b-%Y') + "'" \
                     ");\n")  
    
# family_doctor
for i in range(len(first_names)):
        for x in [y for y in range(len(first_names)) if y!= i and y%2 == 0]:
                output.write("Insert into family_doctor values (" + \
                             str(x) + ", " + \
                             str(i) + \
                             ");\n")
                   
# radiology_record
count = 0
for i in range(len(first_names)):
        if i != 0:
                for x in [y for y in range(len(first_names)) if y != i and y != 0 and y%2 == 0]:
                        output.write("Insert into radiology_record values (" + \
                                 str(count) + ", " + \
                                 str(i) + ", " + \
                                 str(x) + ", " + \
                                 str(0) + ", " + \
                                 "'" + test[(i*i+x)%len(test)] + "'" + ", " + \
                                 "'" + (date + datetime.timedelta(days=i*(i*x)%300)).strftime('%d-%b-%Y') + "'"  + ", " + \
                                 "'" + (date + datetime.timedelta(days=i*(i*x)+300+15)).strftime('%d-%b-%Y') + "'"  + ", " + \
                                 "'" + results[int((x+i)*0.75)%len(results)][0] + "'" + ", " + \
                                 "'" + results[int((x+i)*0.75)%len(results)][1] + "'" + \
                                 ");\n")
                        count += 1
                
# pacs_images
i_count = 0
r_count = 0
for i in range(len(first_names)):
        if i != 0:
                for x in [y for y in range(len(first_names)) if y != i and y != 0 and y%2 == 0]:
                        for i in range(2): 
                                output.write("Insert into pacs_images values (" + \
                                             str(r_count) + ", " + \
                                             str(i_count) + ", " + \
                                             f1  + ", " + \
                                             f2  + ", " + \
                                             f3  + \
                                             ");\n")
                                i_count += 10
                r_count += 1

output.write("CREATE OR REPLACE VIEW Information_for_data_analysis ( FIRST_NAME, LAST_NAME, TEST_TYPE, TEST_DATE ,IMAGE_ID) AS 
                SELECT p.FIRST_NAME, p.LAST_NAME, r.TEST_TYPE, r.TEST_DATE ,i.IMAGE_ID
					 FROM PERSONS p, RADIOLOGY_RECORD r, PACS_IMAGES i
                WHERE p.PERSON_ID = r.PATIENT_ID AND i.RECORD_ID = r.RECORD_ID;")
output.write("Commit;")
output.close()