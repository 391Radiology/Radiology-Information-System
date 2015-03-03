/*
 *  File name:  setup.sql
 *  Function:   to create the initial database schema for the CMPUT 391 project,
 *              Winter Term, 2015
 *  Author:     Prof. Li-Yan Yuan
 */
DROP TABLE family_doctor;
DROP TABLE pacs_images;
DROP TABLE radiology_record;
DROP TABLE users;
DROP TABLE persons;

/*
 *  To store the personal information
 */
CREATE TABLE persons (
   person_id int,
   first_name varchar(24),
   last_name  varchar(24),
   address    varchar(128),
   email      varchar(128),
   phone      char(10),
   PRIMARY KEY(person_id),
   UNIQUE (email)
);

/*
 *  To store the log-in information
 *  Note that a person may have been assigned different user_name(s), depending
 *  on his/her role in the log-in  
 */
CREATE TABLE users (
   user_name varchar(24),
   password  varchar(24),
   class     char(1),
   person_id int,
   date_registered date,
   CHECK (class in ('a','p','d','r')),
   PRIMARY KEY(user_name),
   FOREIGN KEY (person_id) REFERENCES persons
);

/*
 *  to indicate who is whose family doctor.
 */
CREATE TABLE family_doctor (
   doctor_id    int,
   patient_id   int,
   FOREIGN KEY(doctor_id) REFERENCES persons,
   FOREIGN KEY(patient_id) REFERENCES persons,
   PRIMARY KEY(doctor_id,patient_id)
);

/*
 *  to store the radiology records
 */
CREATE TABLE radiology_record (
   record_id   int,
   patient_id  int,
   doctor_id   int,
   radiologist_id int,
   test_type   varchar(24),
   prescribing_date date,
   test_date    date,
   diagnosis    varchar(128),
   description   varchar(1024),
   PRIMARY KEY(record_id),
   FOREIGN KEY(patient_id) REFERENCES persons,
   FOREIGN KEY(doctor_id) REFERENCES  persons,
   FOREIGN KEY(radiologist_id) REFERENCES  persons
);

/*
 *  to store the pacs images
 */
CREATE TABLE pacs_images (
   record_id   int,
   image_id    int,
   thumbnail   blob,
   regular_size blob,
   full_size    blob,
   PRIMARY KEY(record_id,image_id),
   FOREIGN KEY(record_id) REFERENCES radiology_record
);

GRANT ALL ON family_doctor TO zioueche;
GRANT ALL ON pacs_images TO zioueche;
GRANT ALL ON radiology_record TO zioueche;
GRANT ALL ON users TO zioueche;
GRANT ALL ON persons TO zioueche;

GRANT ALL ON family_doctor TO liangrui;
GRANT ALL ON pacs_images TO liangrui;
GRANT ALL ON radiology_record TO liangrui;
GRANT ALL ON users TO liangrui;
GRANT ALL ON persons TO liangrui;

Insert into persons values (0, 'Raymond', 'Lieu', '0Edmonton', 'Raymond@hotmail.com', '7800000000');
Insert into persons values (1, 'Ichigo', 'Kurosaki', '1Edmonton', 'Ichigo@hotmail.com', '7801111111');
Insert into persons values (2, 'Naruto', 'Uzumaki', '2Edmonton', 'Naruto@hotmail.com', '7802222222');
Insert into persons values (3, 'Omar', 'Zioueche', '3Edmonton', 'Omar@hotmail.com', '7803333333');
Insert into persons values (4, 'Liangrui', 'Lu', '4Edmonton', 'Liangrui@hotmail.com', '7804444444');
Insert into persons values (5, 'Byakuya', 'Kuchiki', '5Edmonton', 'Byakuya@hotmail.com', '7805555555');
Insert into users values ('a_RaymondLieu', 'pwd', 'a', 0, null);
Insert into users values ('d_RaymondLieu', 'pwd', 'd', 0, null);
Insert into users values ('r_RaymondLieu', 'pwd', 'r', 0, null);
Insert into users values ('p_RaymondLieu', 'pwd', 'p', 0, null);
Insert into users values ('p_IchigoKurosaki', 'pwd', 'p', 1, null);
Insert into users values ('d_NarutoUzumaki', 'pwd', 'd', 2, null);
Insert into users values ('p_NarutoUzumaki', 'pwd', 'p', 2, null);
Insert into users values ('a_OmarZioueche', 'pwd', 'a', 3, null);
Insert into users values ('p_OmarZioueche', 'pwd', 'p', 3, null);
Insert into users values ('d_LiangruiLu', 'pwd', 'd', 4, null);
Insert into users values ('p_LiangruiLu', 'pwd', 'p', 4, null);
Insert into users values ('r_ByakuyaKuchiki', 'pwd', 'r', 5, null);
Insert into users values ('p_ByakuyaKuchiki', 'pwd', 'p', 5, null);
Insert into family_doctor values (2, 0);
Insert into family_doctor values (4, 0);
Insert into family_doctor values (0, 1);
Insert into family_doctor values (2, 1);
Insert into family_doctor values (4, 1);
Insert into family_doctor values (0, 2);
Insert into family_doctor values (4, 2);
Insert into family_doctor values (0, 3);
Insert into family_doctor values (2, 3);
Insert into family_doctor values (4, 3);
Insert into family_doctor values (0, 4);
Insert into family_doctor values (2, 4);
Insert into family_doctor values (0, 5);
Insert into family_doctor values (2, 5);
Insert into family_doctor values (4, 5);
Insert into radiology_record values (0, 1, 2, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (1, 1, 4, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (2, 2, 4, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (3, 3, 2, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (4, 3, 4, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (5, 4, 2, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (6, 5, 2, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into radiology_record values (7, 5, 4, 0, 'MRI', null, null, 'DEAD', 'Doctor messed up');
Insert into pacs_images values (0, 0, null, null, null);
Insert into pacs_images values (0, 10, null, null, null);
Insert into pacs_images values (1, 20, null, null, null);
Insert into pacs_images values (1, 30, null, null, null);
Insert into pacs_images values (2, 40, null, null, null);
Insert into pacs_images values (2, 50, null, null, null);
Insert into pacs_images values (3, 60, null, null, null);
Insert into pacs_images values (3, 70, null, null, null);
Insert into pacs_images values (4, 80, null, null, null);
Insert into pacs_images values (4, 90, null, null, null);
Insert into pacs_images values (5, 100, null, null, null);
Insert into pacs_images values (5, 110, null, null, null);
Insert into pacs_images values (6, 120, null, null, null);
Insert into pacs_images values (6, 130, null, null, null);
Insert into pacs_images values (7, 140, null, null, null);
Insert into pacs_images values (7, 150, null, null, null);
