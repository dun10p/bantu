/*CS 3380: Group Project
    Group 15
    Created by: Jeffrey Friel, Lizzy White, Kenneth Steimel
*/

DROP SCHEMA IF EXISTS gp CASCADE;

CREATE SCHEMA gp;
SET SEARCH_PATH = gp;
--Login related information:
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
-- 		ruleType	- e.g. glide formation, tone insertion, tone anticipation, nasal deletion, nasal place assimilation, voicing assimilation etc.
DROP TABLE IF EXISTS rule_type;
CREATE TABLE rule_type (
    ruleType varchar(40),
	description varchar(100),
	notation varchar(250),
	segmental boolean,
	tonal boolean,
    PRIMARY KEY (ruleType)
);
--The general idea behind why we're keeping track of rule information: we want to be able to query for particular rules (e.g. pull up all examples of High Tone Insertion)
--We would also like to be able to find the root of a noun or verb based upon the allomorphic rules we have.
-- Table: gp.rule_sub_type
-- Columns:
--		ruleType	-  references the rule_type above
--		ruleSubType - This table is used if a rule may have multiple ways it is applied
--                              to a word.  For example if the rule is glide formation: we have two sub-types of glide formation attested. One in which i => j and one in which u => w.
--								rules are nearly always formulated for an entire class of sounds (e.g. all high vowels become glides before vowels)
--								these classes of sounds would be the rule type, the specific sound changes would be the rule sub types.
--     description - a brief prose description of the phenomenon
--     notation - a formal representation of the rule
--	   segmental - if true this indicates that the rule is segmental
--     tonal - if true the rule is tonal

DROP TABLE IF EXISTS rule_sub_type;
CREATE TABLE rule_sub_type (
    ruleType varchar(40),
    ruleSubType varchar(250),
    FOREIGN KEY (ruleType) REFERENCES rule_type(ruleType) on DELETE CASCADE,
    PRIMARY KEY (ruleType, ruleSubType)
);
--I don't think that audio_type will actually be something meaningful to keep track of. -Ken
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
--		initialSound	- Only options currently of interest are nasal, vowel and voiceless fricative (think [s])
--		initialSoundDescr	- 
DROP TABLE IF EXISTS initial_sound;
CREATE TABLE initial_sound (
    initialSound varchar(10),
    initialSoundDescr varchar(150),
    PRIMARY KEY (initialSound)
);

-- Table: gp.root
-- Columns:
--		rootId		- a unique ID for the root entry, set by a sequence
--		root		- the root of the word
--		rootLength	- integer to hold the number of syllables in the root
--		initialSound - what is the initial sound (possible options outlined above)
DROP TABLE IF EXISTS root;
CREATE TABLE root (
    rootID SERIAL,
    root varchar(40) NOT NULL,
    rootLength smallint NOT NULL,
	initialSound varchar(10) REFERENCES initial_sound on DELETE CASCADE,
	sigma1 smallint NOT NULL,
	sigma2 smallint NOT NULL,
    PRIMARY KEY (rootID)
);

-- Table: gp.part_of_speech
-- Columns:
--		partOfSpeech 	-- lexical category that the word belongs to
DROP TABLE IF EXISTS part_of_speech;
CREATE TABLE part_of_speech(
    partOfSpeech varchar(24),
    subPartOfSpeech varchar(100),
    PRIMARY KEY (partOfSpeech, subPartOfSpeech)
);

-- Table: gp.allomorphs
-- Columns:
--		morphemeID 	- unique ID of the morpheme 
--		subType		- a lexical rule can be used in different ways
-- 		rule		- the rule associated with the word (e.g. glide formation, muessen's rule, voiceless fricative deletion)
--		description	- a short description of the particulars of this rule in Bukusu (e.g. plateau of short low syllable betwixt two H's)
--		notation	- some sort of theoretical notation for the rule (e.g. using phonetic feature theory notation or simple transformation rules like e=>o/_u#)
--		InitialSound - initial sound of allomorph
--		sigma		- number of syllables in the allomorph
--		sigma1		- is the first syllable of the allomorph long or short? long =2 short =1
--		sigma2		- is the second syllable of the allomorph long or short? long=2 short =1
--		tonal		- boolean value simply stating whether the morpheme is tonal (e.g. if its represented by a tone with no segmental component)
--		segmental	- boolean value stating whether the morpheme is segmental (possible these last two fields could be consolidated to one field since a rule cannot be both tonal and segmental)
DROP TABLE IF EXISTS allomorphs;
CREATE TABLE allomorphs (
    morphemeID SERIAL,
    form varchar(10) NOT NULL,
	rule varchar(40) REFERENCES rule_type(ruleType),
    description TEXT NOT NULL,
    initialSound	varchar(2) REFERENCES initial_sound,
	followingSound varchar (2) REFERENCES initial_sound,
    sigma smallint,
    sigma1 smallint,
    sigma2 smallint,
    tonal boolean,
    segmental boolean,
    PRIMARY KEY (morphemeID)
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
    rule varchar(40) REFERENCES rule_type(ruleType),
    userID varchar(30) REFERENCES users(userID),
    note TEXT NOT NULL,
    PRIMARY KEY (dtTimeStamp, rule, userID)
);

-- Table: gp.root_to_pos
-- Columns:
--		rootToPosID		- unique ID linking the root of the word to the part of speech
-- 		rootID 			- ID of the root word
--		pos				- part of speech of the word
DROP TABLE IF EXISTS root_to_pos;
/*CREATE TABLE root_to_pos (
    rootID integer REFERENCES root(rootID),
    pos varchar(24),
    subPos varchar(100),
    FOREIGN KEY (pos, subPos) REFERENCES part_of_speech(partOfSpeech, subPartOfSpeech),
    PRIMARY KEY (rootID, pos, subPos)
);*/

-- Table: gp.words
-- NOTE: before we had thought that glossary would simply contain all isolated words and phrasal data. The NP and N excel spreadsheets I have created could output csvs.
--one would contain the nouns in isolation and one would contain the phrasal data. They would then be linked by their noun id field (see noun phrase data spreadsheet).
--We could then use a join to create something like the glossary table. I thought this decomposition would allow us to easily determine nouns which we only have phrasal data for, 
-- nouns which have no phrasal data and the lexial properties of the word in the phrasal data (e.g. we know that person has certain lexical properties. 
--each phrasal example should have lexical properties that mirror the lexical properties of the head word (e.g. nouns in noun phrases and verbs in verb phrases (or sentences))
--(This would allow us to query for phrasal examples involving words of a certain type (e.g. all demonstrative + Noun phrases which involve 3 syllable nouns etc.). 
--Rather than rewriting each lexical property of the head noun for every phrasal example we should be able to quickly get the lexical properties of head nouns in noun phrases from the lexical properties of the noun in isolation.

-- Columns:
--		glossID		- unique ID for the glossary definition of the word
--		rootToPosID	- ID to reference the part of speech of the root of the word
--		rootForm 	- 
-- 		definition	- definition of the word
-- 		posToSub	- linking the subpart of speech to the part of speech of the word
DROP TABLE IF EXISTS words;
CREATE TABLE words (
	wordID SERIAL PRIMARY KEY,
	wordForm varchar(20),
	morpheme1 integer REFERENCES allomorphs(morphemeID),
	morpheme2 integer REFERENCES allomorphs(morphemeID),
	rootID integer REFERENCES root(rootID),
	pos varchar(24),
	subPos varchar(100),
	FOREIGN KEY (pos, subPos) REFERENCES part_of_speech(partOfSpeech, subPartOfSpeech),
	numOfSyllables integer,
	sigma1 integer,
	sigma2 integer,
	initialSound varchar(2) REFERENCES initial_sound(initialSound),
	gloss varchar(555550) --definitions of single words only
);
DROP TABLE IF EXISTS modifiers;
CREATE TABLE modifiers(
	modifier varchar(20) PRIMARY KEY

);
DROP TABLE IF EXISTS phrasal_data;
CREATE TABLE phrasal_data (
    phraseID SERIAL,
    phraseForm varchar(100) NOT NULL,
    definition text NOT NULL,
    noun1 integer REFERENCES words(wordID), --used to link phrase to a noun IF null indicates no overt noun present
	noun2 integer REFERENCES words(wordID), --used to link phrase to a noun
	noun3 integer REFERENCES words(wordID), --used to link phrase to noun
	verb1 integer REFERENCES words(wordID), --if NULL indicates this is a noun phrase
	verb2 integer REFERENCES words(wordID),
	verb3 integer REFERENCES words(wordID),
   	numModifiers integer,
	modifier1 varchar(20) REFERENCES modifiers,
	modifier2 varchar(20) REFERENCES modifiers,
--    FOREIGN KEY (rootID, pos, subPos) REFERENCES root_to_pos(rootID, pos, subPos),
    PRIMARY KEY (phraseID)
);


-- Table: gp.gloss_to_rule
-- Columns:
--		glossToRuleID	- unique ID to link the glossary definition of the word to the rule of the word
--		ruleID			- rule ID of the word
--		glossID			- glossary ID of the word
DROP TABLE IF EXISTS gloss_to_rule;
CREATE TABLE gloss_to_rule (
    glossToRuleID SERIAL,
    rule varchar(40) REFERENCES rule_type(ruleType),
    glossID integer REFERENCES phrasal_data(phraseID),
    PRIMARY KEY (glossToRuleID)
);

-- Table: gp.phrasal_notes
-- Columns:
--		dtTimeStamp 	- the date and time of the entry of the glossary note, automatically set when inserted
-- 		glossID			- unique ID of the glossary of a phrase
--		userID			- userID of the user who created the note
--		note			- note regarding the rule of the phrase
DROP TABLE IF EXISTS gloss_notes;
DROP TABLE IF EXISTS phrasal_notes;
CREATE TABLE phrasal_notes (
    dtTimeStamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    glossID integer REFERENCES phrasal_data(phraseID),
    userID varchar(30) REFERENCES users(userID),
    note TEXT NOT NULL,
    PRIMARY KEY (dtTimeStamp, glossID, userID)
);
/*
--Table: gp.user_salt
--Columns:
--		userID			- the username of the user creating the salt value
--		salt			- the salt value used to hash the user's password
DROP TABLE IF EXISTS user_salt CASCADE;
CREATE TABLE user_salt (
	userID varchar(30) REFERENCES users(userID),
	salt varchar(40) NOT NULL,
	PRIMARY KEY (userID)
);

--Table: gp.user_info
--Columns:
--		userID			- the username of the user entering their password
-- 		passwordHash	- the complete hash of the user's password
DROP TABLE IF EXISTS user_info CASCADE;
CREATE TABLE user_info(
	userID varchar(30) REFERENCES users(userID),
	passwordHash varchar(40) NOT NULL,
	PRIMARY KEY (userID)
);
--Table: gp.users
--Columns:
--		userID			- the username of the user who can update, insert, and delete rows from the database
--		lastName		- the last name of the user
--		firstName		- the first name of the user
DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
	userID varchar(30),
	lastName varchar(30) NOT NULL,
	firstName varchar(30) NOT NULL,
	PRIMARY KEY (userID)
);
*/


