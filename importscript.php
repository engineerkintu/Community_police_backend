LOAD DATA LOCAL INFILE  
'sp5.csv'
INTO TABLE specialist  
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
(id,reg_no, surname,other_name,sex,telephone,email,employer,postal_address,location,first_qualification,profession,registration_date,additional_qualification,speciality,sub_speciality);

LOAD DATA LOCAL INFILE  
'drug_registry.csv'
INTO TABLE drug  
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
(manufacturer , drug_name,reg_no,generic_name,strength,dosage_form,pack_size,licence_holder,country_of_manufacture,ltr);