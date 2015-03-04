import datetime

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

results = [["DEAD", "Doctor messed up"], \
           ["ALIVE", "Cannot believe the doctor actually did it"], \
           ["COMATOSE", "Weird..."]]

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
    print("Insert into persons values (" + \
          str(i) + ", " + \
          "'" + first_names[i] + "'" + ", " + \
          "'" + last_names[i] + "'" + ", " + \
          "'" + str(i) + "Edmonton" + "'" + ", " + \
          "'" + first_names[i] + "@hotmail.com" + "'" + ", " + \
          "'" + "780" + str(i%9)*3 + str(i%9)*4 + "'" + \
         ");")

# users
for i in range(len(first_names)):
    if (i%3 == 0): 
        print("Insert into users values (" + \
              "'" + "a_" + first_names[i] + last_names[i] + "'" + ", " + \
              "'" + "pwd" "'" + ", " + \
              "'" + "a" + "'" + ", " + \
              str(i) + ", " + \
              "'" + (date + datetime.timedelta(days=i)).strftime('%d-%b-%Y') + "'" + \
              ");")
    if (i%2 == 0):
        print("Insert into users values (" + \
              "'" + "d_" + first_names[i] + last_names[i] + "'" + ", " + \
              "'" + "pwd" "'" + ", " + \
              "'" + "d" + "'" + ", " + \
              str(i) + ", " + \
              "'" + (date + datetime.timedelta(days=i)).strftime('%d-%b-%Y') + "'" + \
              ");")
    if (i%5 == 0):
        print("Insert into users values (" + \
              "'" + "r_" + first_names[i] + last_names[i] + "'" + ", " + \
              "'" + "pwd" "'" + ", " + \
              "'" + "r" + "'" + ", " + \
              str(i) + ", " + \
              "'" + (date + datetime.timedelta(days=i)).strftime('%d-%b-%Y') + "'" + \
              ");")   
    print("Insert into users values (" + \
          "'" + "p_" + first_names[i] + last_names[i] + "'" + ", " + \
          "'" + "pwd" "'" + ", " + \
          "'" + "p" + "'" + ", " + \
          str(i) + ", " + \
          "'" + (date + datetime.timedelta(days=i)).strftime('%d-%b-%Y') + "'" \
          ");")  
    
# family_doctor
for i in range(len(first_names)):
    for x in [y for y in range(len(first_names)) if y!= i and y%2 == 0]:
        print("Insert into family_doctor values (" + \
              str(x) + ", " + \
              str(i) + \
              ");")
        
# radiology_record
count = 0
for i in range(len(first_names)):
    if i != 0:
        for x in [y for y in range(len(first_names)) if y != i and y != 0 and y%2 == 0]:
            print("Insert into radiology_record values (" + \
                  str(count) + ", " + \
                  str(i) + ", " + \
                  str(x) + ", " + \
                  str(0) + ", " + \
                  "'" + "MRI" + "'" + ", " + \
                  "'" + (date + datetime.timedelta(days=i*i%300+25)).strftime('%d-%b-%Y') + "'"  + ", " + \
                  "'" + (date + datetime.timedelta(days=i*i+300+40)).strftime('%d-%b-%Y') + "'"  + ", " + \
                  "'" + results[int((x+i)*0.75)%len(results)][0] + "'" + ", " + \
                  "'" + results[int((x+i)*0.75)%len(results)][1] + "'" + \
                  ");")
            count += 1
            
# pacs_images
i_count = 0
r_count = 0
for i in range(len(first_names)):
    if i != 0:
        for x in [y for y in range(len(first_names)) if y != i and y != 0 and y%2 == 0]:
            for i in range(2): 
                print("Insert into pacs_images values (" + \
                      str(r_count) + ", " + \
                      str(i_count) + ", " + \
                      f1  + ", " + \
                      f2  + ", " + \
                      f3  + \
                      ");")
                i_count += 10
        r_count += 1
