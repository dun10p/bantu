
--1: AUDIO_TYPE
--copy data into audio table from sql/audioData.csv
\COPY audio_type FROM sql/audioTypeData.csv WITH DELIMITER ',' CSV HEADER

--2: AUDIO
--copy data into audio from sql/audioData.csv
\COPY audio (audioType, url, description) FROM sql/audioData.csv WITH DELIMITER ',' CSV HEADER

--3: RULE_TYPE
--copy data into rule type from sql/ruletypeData.csv
\COPY rule_type FROM sql/ruletypeData.csv WITH DELIMITER ',' CSV HEADER

--4: RULE_SUB_TYPE 
--copy data into rule_sub_type from sql/rulesubtypeData.csv
\COPY rule_sub_type FROM sql/rulesubtypeData.csv WITH DELIMITER ',' CSV HEADER

--5: INITIAL_SOUND
--copy data into initial_sound table from sql/initialsoundData.csv
\COPY initial_sound FROM sql/initialsoundData.csv WITH DELIMITER ',' CSV HEADER

--6: PART_OF_SPEECH
--copy data into part_of_speech table from sql/partofspeechData.csv
\COPY part_of_speech FROM sql/partofspeechData.csv WITH DELIMITER ',' CSV HEADER

--7: PART_OF_SPEECH
--copy data into allomorphs table from sql/allomorphsData.csv
\COPY allomorphs FROM sql/allomorphsData.csv WITH DELIMITER ',' CSV HEADER

--7: ROOT
--copy data into root table from sql/rootData.csv
\COPY root (root, rootLength, initialSound) FROM sql/rootData.csv WITH DELIMITER ',' CSV HEADER

--8: ROOT_TO_POS
--copy data into root_to_pos table from sql/roottoposData.csv
-- DO WE NEED THIS TABLE SINCE THIS INFORMATION IS IN THE GLOSSARY??????????
--\COPY root_to_pos (rootID, pos, subPos) FROM sql/roottoposData.csv WITH DELIMITER ',' CSV HEADER

--9: RULE
--copy data into rule table from sql/ruleData.csv
\COPY rule (ruleType, subType, rule, description, notation, initialLetter, sigma, sigma1, sigma2, tonal, segmental) FROM sql/ruleData.csv WITH DELIMITER ',' CSV HEADER

--10: GLOSSARY
--copy data into glossary table from sql/glossaryData.csv
\COPY glossary (rootForm, definition, rootID, pos, subPos) FROM sql/glossaryData.csv WITH DELIMITER ',' CSV HEADER

--10: USERS
--copy data into users table from sql/usersData.csv
\COPY users(userID, lastName, firstName) FROM sql/usersData.csv WITH DELIMITER ',' CSV HEADER

--11: USER_INFO
--copu data into users_info table from sql/userinfoData.cav
\COPY user_info (userID, passwordHash) FROM sql/userinfoData.csv WITH DELIMITER ',' CSV HEADER

--12: USER_SALT
--copy data into user_salt table from sql/usersaltData.csv
\COPY user_salt (userID, salt) FROM sql/usersaltData.csv WITH DELIMITER ',' CSV HEADER

--13. GLOSS_NOTES
--copy data into gloss_notes table from sql/gloss_notes.csv
\COPY gloss_notes (glossid, userid, note) FROM sql/glossnotesData.csv WITH DELIMITER ',' CSV HEADER 

--14. RULE_NOTES
--copt date into rule_notes table from sql/rule_note.csv\
\COPY rule_notes (ruleid, userid, note) FROM sql/rulenotesData.csv WITH DELIMITER ',' CSV HEADER


