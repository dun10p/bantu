--File to load data into the database in the necessary order
--Created by: Jeffrey Friel

--1: AUDIO
--copy data into audio from sql/audioData.csv
\COPY audio (audioType, url, description) FROM sql/audioData.csv WITH DELIMITER ',' CSV HEADER

--2: RULE_TYPE
--copy data into rule type from sql/ruletypeData.csv
\COPY rule_type FROM sql/ruletypeData.csv WITH DELIMITER ',' CSV HEADER

--3: RULE_SUB_TYPE 
--copy data into rule_sub_type from sql/rulesubtypeData.csv
\COPY rule_sub_type FROM sql/rulesubtypeData.csv WITH DELIMITER ',' CSV HEADER

--4: INITIAL_SOUND
--copy data into initial_sound table from sql/initialsoundData.csv
\COPY initial_sound FROM sql/initialsoundData.csv WITH DELIMITER ',' CSV HEADER

--5: PART_OF_SPEECH
--copy data into allomorphs table from sql/allomorphsData.csv
\COPY allomorphs (form, rule, description, initialSound, followingSound, sigma, sigma1, sigma2, tonal, segmental) FROM sql/allomorphsData.csv WITH DELIMITER ',' CSV HEADER

--6: ROOT
--copy data into root table from sql/rootData.csv
\COPY root (root, rootLength, initialSound, sigma1, sigma2) FROM sql/rootData.csv WITH DELIMITER ',' CSV HEADER

--7: WORDS
--copy data into words from sql/wordsData.csv
\COPY words (wordForm, gloss, morpheme1, morpheme2, rootID, pos, subPos, numOfSyllables, sigma1, sigma2, initialSound, tonalPattern) FROM sql/wordsData.csv WITH DELIMITER ',' CSV HEADER

--8: MODIFIERS
--copy data into modifiers from sql/modifiers.csv
\COPY modifiers (modifier) FROM sql/modifiers.csv WITH DELIMITER ',' CSV HEADER

--9: PHRASAL_DATA
--copy data into phrasal_data from sql/phrasalData.csv
\COPY phrasal_data(phraseForm, definition, noun1, noun2, noun3, verb1, verb2, verb3, numModifiers, modifier1, modifier2) FROM sql/phrasalData.csv WITH DELIMITER ',' CSV HEADER

--10: USERS
--copy data into users table from sql/usersData.csv
--\COPY users(userID, lastName, firstName) FROM sql/usersData.csv WITH DELIMITER ',' CSV HEADER

--11: USER_INFO
--copu data into users_info table from sql/userinfoData.cav
--\COPY user_info (userID, passwordHash, salt) FROM sql/userinfoData.csv WITH DELIMITER ',' CSV HEADER

--12: USER_SALT
--copy data into user_salt table from sql/usersaltData.csv
--\COPY user_salt (userID, salt) FROM sql/usersaltData.csv WITH DELIMITER ',' CSV HEADER




