--To remember
--WITH rtpID AS (
--	SELECT roottoposid FROM gp.root_to_pos INNER JOIN gp.root USING (root) WHERE root = 'omuundu')
--INSERT INTO gp.glossary (rootToPosID, rootForm, definition, posToSub) VALUES ( rtpID, 'omuundu', 'person', );

INSERT INTO gp.glossary (rootToPosID, rootForm, definition, posToSub) VALUES (SELECT roottoposid FROM gp.root_to_pos INNER JOIN gp.root USING (root) WHERE root = 'omuundu'), 'omuundu', 'person', 5);