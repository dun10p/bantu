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
--           

DROP TABLE IF EXISTS rule_sub_type;
CREATE TABLE rule_sub_type (
    ruleType varchar(40) REFERENCES rule_type on DELETE CASCADE,
    ruleSubType varchar(40),
    PRIMARY KEY (ruleType, ruleSubType)
);
--I don't think that audio_type will actually be something meaningful to keep track of. -Ken
-- Table: gp.audio_type
-- Columns:
--		audioType 	- whether the audio depicts a word or a phrase
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
--		initialSoundDescr	- description of the initial sound
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
--		rootLength	- integer to hold the number of characters in the root
--		initialSound - what is the initial sound (possible options outlined above)
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
--While it is potentially not necessary right now to utilize a many to many relationship (e.g. cross reference table) for this type of relationship,
--I think this offers a healthy amount of flexibility. For example, currently we don't have possessor infinitive verbs, like "the fun of running".
--It's not impossible that such data could surface and currently possessor would be associated with the noun pos and infinitive would be associated with the verb pos.

DROP TABLE IF EXISTS pos_to_subpos;
CREATE TABLE pos_to_subpos (
    pos varchar(24) REFERENCES part_of_speech(partOfSpeech) NOT NULL,
    subPos varchar(24) REFERENCES sub_part_of_speech(subPartOfSpeech) NOT NULL,
    PRIMARY KEY (pos, subPos)
);

-- Table: gp.rule
-- Columns:
--		ruleID 		- unique ID of the rule of the word
--		ruleType 	- which lexical rule is applied 
--		subType		- a lexical rule can be used in different ways
-- 		rule		- the rule associated with the word (e.g. glide formation, muessen's rule, voiceless fricative deletion)
--		description	- a short description of the particulars of this rule in Bukusu (e.g. plateau of short low syllable betwixt two H's)
--		notation	- some sort of theoretical notation for the rule (e.g. using phonetic feature theory notation or simple transformation rules like e=>o/_u#)
--		InitialLetter - initial letter (for allomorphic rules)
--		sigma		- number of syllables in the allomorph
--		sigma1		- is the first syllable of the allomorph long or short? long =2 short =1
--		sigma2		- is the second syllable of the allomorph long or short? long=2 short =1
--		tonal		- boolean value simply stating whether the rule is tonal
--		segmental	- boolean value stating whether the rule is segmental (possible these last two fields could be consolidated to one field since a rule cannot be both tonal and segmental)
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
DROP TABLE IF EXISTS glossary;
CREATE TABLE glossary (
    glossID SERIAL,
    rootToPosID integer REFERENCES root_to_pos,
    rootForm varchar(40) NOT NULL,
    definition text NOT NULL,
    pos varchar(24),
	subPos varchar(24),
    FOREIGN KEY (pos, subPos) REFERENCES pos_to_subpos(pos, subPos),
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
    PRIMARY KEY (dtTimeStamp, glossID, userID)
);



