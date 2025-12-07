SELECT * FROM teams WHERE NAME LIKE '%Teamname%';

/* Falls Auto_Increment bugt */
/*ALTER TABLE teams AUTO_INCREMENT = gewünschter Wert;*/

ALTER TABLE spiele
	ADD FOREIGN KEY(TEAM_HEIM_ID) REFERENCES teams(ID);
	
ALTER TABLE spiele
	ADD FOREIGN KEY(TEAM_GAST_ID) REFERENCES teams(ID);