/*CS 3380: Group Project
    Group 15
    Created by: Jeffrey Friel, Lizzy White
*/

DROP SCHEMA IF EXISTS gp CASCADE;

CREATE SCHEMA gp;
SET SEARCH_PATH = gp;

-- Table: gp.users
-- Columns:
--		userId		- unique login ID for each user
--		lastName	- the last name of the user
--		firstName 	- the first name of the user
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    userID varchar(30),
    lastName varchar(30) NOT NULL,
    firstName varchar(30) NOT NULL,
    PRIMARY KEY (userID)
);

-- Table: gp.user_info
-- Columns:
--		userId		- unique login ID for the user
--		passwordHash - the hash of the user's password plus the salt
DROP TABLE IF EXISTS user_info;
CREATE TABLE user_info (
    userID varchar(30) REFERENCES users on DELETE CASCADE,
    passwordHash varchar(40) NOT NULL,
    PRIMARY KEY (userID)
);

-- Table: gp.user_salt
-- Columns:
--		userId 		- unique user ID that the salt corresponds to
--		salt 		- salt to use for security of password storage
DROP TABLE IF EXISTS user_salt;
CREATE TABLE user_salt (
    userID varchar(30) REFERENCES users on DELETE CASCADE,
    salt varchar(40) NOT NULL,
    PRIMARY KEY (userID)
);

-- Table: gp.rule_type
-- Columns:
-- 		ruleType	- 
DROP TABLE IF EXISTS rule_type;
CREATE TABLE rule_type (
    ruleType varchar(40),
    PRIMARY KEY (ruleType)
);

-- Table: gp.rule_sub_type
-- Columns:
--		ruleType	-
--		ruleSubType - This table is used if a rule may have multiple ways it is applied
--                              to a word.  Ken had an example of this when we were talking a 
--                              couple of weeks ago
--		description - description of the rule
DROP TABLE IF EXISTS rule_sub_type;
CREATE TABLE rule_sub_type (
    ruleType varchar(40) REFERENCES rule_type on DELETE CASCADE,
    ruleSubType varchar(40),
    description varchar(250) NOT NULL,
    PRIMARY KEY (ruleType, ruleSubType)
);

-- Table: gp.audio_type
-- Columns:
--		audioType 	- 
DROP TABLE IF EXISTS audio_type;
CREATE TABLE audio_type (
    audioType varchar(20) PRIMARY KEY
);

-- Table: gp.audio
-- Columns:
--		audioID		- indexed value to hold a unique idea for each audio file
--		audioType	- is this a single word or phrase
--		url			- link to the audio file
--		description	- description of the contents of the audio file
DROP TABLE IF EXISTS audio;
CREATE TABLE audio (
    audioID serial PRIMARY KEY,
    audioType varchar(20) REFERENCES audio_type,
    url varchar(250) NOT NULL,
    description varchar(250) NOT NULL
);

-- Table: gp.initial_sound
-- Columns:
--		initialSound	- 
--		initialSoundDescr	- 
DROP TABLE IF EXISTS initial_sound;
CREATE TABLE initial_sound (
    initialSound varchar(10),
    initialSoundDescr varchar(250),
    PRIMARY KEY (initialSound)
);

-- Table: gp.root
-- Columns:
--		rootId		- a unique ID for the root entry, set by a sequence
--		root		- the root of the word
--		rootLength	- integer to hold the number of characters in the root
--		initialSound - 
DROP TABLE IF EXISTS root;
CREATE TABLE root (
    rootID SERIAL,
    root varchar(40) NOT NULL,
    rootLength smallint NOT NULL,
    initialSound varchar(10) REFERENCES initial_sound on DELETE CASCADE,
    PRIMARY KEY (rootID)
);

-- Table: gp.part_of_speech
-- Columns:
--		partOfSpeech 	-- lexical category that the word belongs to
DROP TABLE IF EXISTS part_of_speech;
CREATE TABLE part_of_speech(
    partOfSpeech varchar(24),
    PRIMARY KEY (partOfSpeech)
);

-- Table: gp.sub_part_of_speech
-- Columns:
-- 		sub_part_of_speech 	- sub type of lexical category 
--                                      - ex: imperative, possesive, singular,
--                                      - plural, future, present, past, etc.
DROP TABLE IF EXISTS sub_part_of_speech;
CREATE TABLE sub_part_of_speech (
    subPartOfSpeech varchar(24),
    PRIMARY KEY (subPartOfSpeech)
);

-- Table: gp.pos_to_subpos
-- Columns:
--		pos				- part of speech of the word
--		subPos			- sub part of speech of the word
DROP TABLE IF EXISTS pos_to_subpos;
CREATE TABLE pos_to_subpos (
    pos varchar(24) REFERENCES part_of_speech(partOfSpeech) NOT NULL,
    subPos varchar(24) REFERENCES sub_part_of_speech(sub_part_of_speech) NOT NULL,
    PRIMARY KEY (pos, subPos)
);

-- Table: gp.rule
-- Columns:
--		ruleID 		- unique ID of the rule of the word
--		ruleType 	- which lexical rule is applied 
--		subType		- a lexical rule can be used in different ways
-- 		rule		- the rule associated with the word
--		description	- 
--		notation	- 
--		initialLetter - 
--		sigma		- 
--		sigma1		- 
--		sigma2		- 
--		tonal		- 
--		segmental	- 
DROP TABLE IF EXISTS rule;
CREATE TABLE rule (
    ruleID SERIAL,
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

-- Table: gp.rule_notes
-- Columns:
--		dtTimeStamp 	- the date and time of the entry of the rule note, automatically set when inserted
-- 		ruleID			- unique ID of the rule of a word
--		userID			- userID of the user who created the note
--		note			- note regarding the rule of the word
DROP TABLE IF EXISTS rule_notes;
CREATE TABLE rule_notes (
    dtTimeStamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ruleID integer REFERENCES rule(ruleID),
    userID varchar(30) REFERENCES users(userID),
    note TEXT NOT NULL,
    PRIMARY KEY (dtTimeStamp, ruleID, userID)
);

-- Table: gp.root_to_pos
-- Columns:
--		rootToPosID		- unique ID linking the root of the word to the part of speech
-- 		rootID 			- ID of the root word
--		pos				- part of speech of the word
DROP TABLE IF EXISTS root_to_pos;
CREATE TABLE root_to_pos (
    rootToPosID SERIAL,
    rootID integer REFERENCES root(rootID),
    pos varchar(24) REFERENCES part_of_speech(partOfSpeech),
    PRIMARY KEY (rootToPosID)
);

-- Table: gp.glossary
-- Columns:
--		glossID		- unique ID for the glossary definition of the word
--		rootToPosID	- ID to reference the part of speech of the root of the word
--		rootForm 	- 
-- 		definition	- definition of the word
-- 		posToSub	- linking the subpart of speech to the part of speech of the word
DROP TABLE IF EXISTS glossary;
CREATE TABLE glossary (
    glossID SERIAL,
    rootToPosID integer REFERENCES root_to_pos,
    rootForm varchar(40) NOT NULL,
    definition text NOT NULL,
    posToSub integer REFERENCES pos_to_subpos(posToSubID),
    PRIMARY KEY (glossID)
);

-- Table: gp.gloss_to_rule
-- Columns:
--		glossToRuleID	- unique ID to link the glossary definition of the word to the rule of the word
--		ruleID			- rule ID of the word
--		glossID			- glossary ID of the word
DROP TABLE IF EXISTS gloss_to_rule;
CREATE TABLE gloss_to_rule (
    glossToRuleID SERIAL,
    ruleID integer REFERENCES rule(ruleID),
    glossID integer REFERENCES glossary(glossID),
    PRIMARY KEY (glossToRuleID)
);

-- Table: gp.gloss_notes
-- Columns:
--		dtTimeStamp 	- the date and time of the entry of the glossary note, automatically set when inserted
-- 		glossID			- unique ID of the glossary of a word
--		userID			- userID of the user who created the note
--		note			- note regarding the rule of the word
DROP TABLE IF EXISTS gloss_notes;
CREATE TABLE gloss_notes (
    dtTimeStamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    glossID integer REFERENCES glossary(glossID),
    userID varchar(30) REFERENCES users(userID),
    note TEXT NOT NULL,
    PRIMARY KEY (dtTimeStamp, ruleID, userID)
);



