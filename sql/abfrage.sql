SELECT * FROM teams WHERE NAME LIKE '%SC 1912 Berger Preuß%';

/* Se caso o Auto_Increment bugar */
/*ALTER TABLE teams AUTO_INCREMENT = valor desejado;*/

ALTER TABLE spiele
	ADD FOREIGN KEY(TEAM_HEIM_ID) REFERENCES teams(ID);
	
ALTER TABLE spiele
	ADD FOREIGN KEY(TEAM_GAST_ID) REFERENCES teams(ID);