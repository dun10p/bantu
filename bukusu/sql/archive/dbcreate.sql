/*CS 3380: Group Project
    Group 15
    Created by: Jeffrey Friel, Lizzy White
*/

DROP SCHEMA IF EXISTS gp CASCADE;

CREATE SCHEMA gp;
SET SEARCH_PATH = gp;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    userID varchar(30),
    lastName varchar(30) NOT NULL,
    firstName varchar(30) NOT NULL,
    PRIMARY KEY (userID)
);

DROP TABLE IF EXISTS user_info;
CREATE TABLE user_info (
    userID varchar(30) REFERENCES users on DELETE CASCADE,
    passwordHash varchar(40) NOT NULL,
    PRIMARY KEY (userID)
);

DROP TABLE IF EXISTS user_salt;
CREATE TABLE user_salt (
    userID varchar(30) REFERENCES users on DELETE CASCADE,
    salt varchar(40) NOT NULL,
    PRIMARY KEY (userID)
);

DROP TABLE IF EXISTS rule_type;
CREATE TABLE rule_type (
    ruleType varchar(40),
    PRIMARY KEY (ruleType)
);

DROP TABLE IF EXISTS rule_sub_type;
CREATE TABLE rule_sub_type (
    ruleType varchar(40) REFERENCES rule_type on DELETE CASCADE,
    ruleSubType varchar(40),
    description varchar(250) NOT NULL,
    PRIMARY KEY (ruleType, ruleSubType)
);

DROP TABLE IF EXISTS audio_type;
CREATE TABLE audio_type (
    audioType varchar(20) PRIMARY KEY
);

DROP TABLE IF EXISTS audio;
CREATE TABLE audio (
    audioID serial PRIMARY KEY,
    audioType varchar(20) REFERENCES audio_type,
    url varchar(250) NOT NULL,
    description varchar(250) NOT NULL
);


DROP TABLE IF EXISTS initial_sound;
CREATE TABLE initial_sound (
    initialSound varchar(10),
    initialSoundDescr varchar(250),
    PRIMARY KEY (initialSound)
);

DROP TABLE IF EXISTS root;
CREATE TABLE root (
    rootID serial,
    root varchar(40) NOT NULL,
    rootLength smallint NOT NULL,
    initialSound varchar(10) REFERENCES initial_sound on DELETE CASCADE,
    PRIMARY KEY (rootID)
);

DROP TABLE IF EXISTS part_of_speech;
CREATE TABLE part_of_speech(
    partOfSpeech varchar(24),
    PRIMARY KEY (partOfSpeech)
);

DROP TABLE IF EXISTS sub_part_of_speech;
CREATE TABLE sub_part_of_speech (
    subPartOfSpeech varchar(24),
    PRIMARY KEY (subPartOfSpeech)
);

DROP TABLE IF EXISTS pos_to_subpos;
CREATE TABLE pos_to_subpos (
    posToSubID serial,
    pos varchar(24),
    subPos varchar(24),
    PRIMARY KEY (posToSubID)
);

DROP TABLE IF EXISTS rule;
CREATE TABLE rule (
    ruleID serial,
    ruleType varchar(40),
    subType varchar(40),
    rule varchar(40) NOT NULL,
    description TEXT NOT NULL,
    notation varchar(10),
    initialLetter varchar(2),
    sigma smallint,
    sigma1 smallint,
    sigma2 smallint,
    tonal boolean,
    segmental boolean,
    FOREIGN KEY (ruleType, subType) REFERENCES rule_sub_type(ruleType, ruleSubType),
    PRIMARY KEY (ruleID)
);

DROP TABLE IF EXISTS rule_notes;
CREATE TABLE rule_notes (
    dtTimeStamp TIMESTAMP,
    ruleID integer REFERENCES rule(ruleID),
    userID varchar(30) REFERENCES users(userID),
    note TEXT NOT NULL,
    PRIMARY KEY (dtTimeStamp, ruleID, userID)
);

DROP TABLE IF EXISTS root_to_pos;
CREATE TABLE root_to_pos (
    rootToPosID serial,
    rootID integer REFERENCES root(rootID),
    pos varchar(24) REFERENCES part_of_speech(partOfSpeech),
    PRIMARY KEY (rootToPosID)
);

DROP TABLE IF EXISTS glossary;
CREATE TABLE glossary (
    glossID serial,
    rootToPosID integer REFERENCES root_to_pos,
    rootForm varchar(40) NOT NULL,
    definition text NOT NULL,
    posToSub integer REFERENCES pos_to_subpos(posToSubID),
    PRIMARY KEY (glossID)
);

DROP TABLE IF EXISTS gloss_to_rule;
CREATE TABLE gloss_to_rule (
    glossToRuleID serial,
    ruleID integer REFERENCES rule(ruleID),
    glossID integer REFERENCES glossary(glossID),
    PRIMARY KEY (glossToRuleID)
);





