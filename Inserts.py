first_names = ["Raymond", "Ichigo", "Naruto", "Omar", "Liangrui", "Byakuya"]
last_names = ["Lieu", "Kurosaki", "Uzumaki", "Zioueche", "Lu", "Kuchiki"]

# persons
for i in range(len(first_names)):
    print("Insert into persons values (" + \
          str(i) + ", " + \
          "'" + first_names[i] + "'" + ", " + \
          "'" + last_names[i] + "'" + ", " + \
          "'" + str(i) + "Edmonton" + "'" + ", " + \
          "'" + first_names[i] + "@hotmail.com" + "'" + ", " + \
          "'" + "780" + str(i)*3 + str(i)*4 + "'" + \
         ");")

# users
for i in range(len(first_names)):
    if (i%3 == 0): 
        print("Insert into users values (" + \
              "'" + "a_" + first_names[i] + last_names[i] + "'" + ", " + \
              "'" + "pwd" "'" + ", " + \
              "'" + "a" + "'" + ", " + \
              str(i) + ", " + \
              "null" + \
              ");")
    if (i%2 == 0):
        print("Insert into users values (" + \
              "'" + "d_" + first_names[i] + last_names[i] + "'" + ", " + \
              "'" + "pwd" "'" + ", " + \
              "'" + "d" + "'" + ", " + \
              str(i) + ", " + \
              "null" + \
              ");")
    if (i%5 == 0):
        print("Insert into users values (" + \
              "'" + "r_" + first_names[i] + last_names[i] + "'" + ", " + \
              "'" + "pwd" "'" + ", " + \
              "'" + "r" + "'" + ", " + \
              str(i) + ", " + \
              "null" + \
              ");")   
    print("Insert into users values (" + \
          "'" + "p_" + first_names[i] + last_names[i] + "'" + ", " + \
          "'" + "pwd" "'" + ", " + \
          "'" + "p" + "'" + ", " + \
          str(i) + ", " + \
          "null" + \
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
                  "null"  + ", " + \
                  "null"  + ", " + \
                  "'" + "DEAD" + "'" + ", " + \
                  "'" + "Doctor messed up" + "'" + \
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
                      "null"  + ", " + \
                      "null"  + ", " + \
                      "null"  + \
                      ");")
                i_count += 10
            r_count += 1