# -----------------------------------------------------------------------------
#       TABLE : VIK_CREATEUR_EQUIPE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_CREATEUR_EQUIPE
 (
   COM_ID INTEGER(8) NOT NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_EQUIPE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_EQUIPE
 (
   CLU_ID INTEGER(4) NOT NULL  ,
   RAI_ID INTEGER(8) NOT NULL  ,
   COU_ID INTEGER(8) NOT NULL  ,
   EQU_ID INTEGER(8) NOT NULL  ,
   COM_ID_CREATEUR INTEGER(8) NOT NULL  ,
   EQU_NOM CHAR(64) NULL  ,
   EQU_IMAGE CHAR(64) NULL  ,
   EQU_DOSSARD char(32) NULL  ,
   EQU_POINTS INTEGER(5) NULL  ,
   EQU_TEMPS TIME NULL  ,
   EQU_DATE_DEMANDE DATE NULL  ,
   EQU_STATUS CHAR(32) NULL  ,
   EQU_DATE_DECISION DATE NULL  
   , PRIMARY KEY (CLU_ID,RAI_ID,COU_ID,EQU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_EQUIPE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_EQUIPE_VIK_COURSE
     ON VIK_EQUIPE (CLU_ID ASC,RAI_ID ASC,COU_ID ASC);

CREATE  INDEX I_FK_VIK_EQUIPE_VIK_CREATEUR_EQUIPE
     ON VIK_EQUIPE (COM_ID_CREATEUR ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_COURSE_TYPE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COURSE_TYPE
 (
   COU_TYP_ID INTEGER(2) NOT NULL  ,
   COU_TYPE_LIBELLE CHAR(64) NULL  
   , PRIMARY KEY (COU_TYP_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_COUREUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COUREUR
 (
   COM_ID INTEGER(8) NOT NULL  ,
   CAT_AGE_ID INTEGER(2) NOT NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_COUREUR
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_COUREUR_VIK_CATEGORIE_AGE
     ON VIK_COUREUR (CAT_AGE_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_COURSE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COURSE
 (
   CLU_ID INTEGER(4) NOT NULL  ,
   RAI_ID INTEGER(8) NOT NULL  ,
   COU_ID INTEGER(8) NOT NULL  ,
   COU_TYP_ID INTEGER(2) NOT NULL  ,
   COM_ID_ORGANISATEUR_COURSE INTEGER(8) NOT NULL  ,
   COU_NOM CHAR(64) NULL  ,
   COU_DUREE INTEGER(4) NULL  ,
   COU_DATE_DEBUT DATETIME NULL  ,
   COU_DATE_FIN DATETIME NULL  ,
   COU_NB_PARTICIPANT_MAX INTEGER(4) NULL  ,
   COU_NB_PARTICIPANT_MIN INTEGER(4) NULL  ,
   COU_NB_EQUIPE_MIN INTEGER(4) NULL  ,
   COU_NB_EQUIPE_MAX INTEGER(4) NULL  ,
   COU_PRIX_REPAS INTEGER(4) NULL  ,
   COU_NB_MAX_PAR_EQUIPE INTEGER(2) NULL  ,
   COU_DIFFICULTE CHAR(32) NULL  ,
   COU_REDUCTION_LICENCIE INTEGER(2) NULL  ,
   COU_DATE_DEMANDE DATE NULL  ,
   COU_STATUS CHAR(32) NULL  ,
   COU_DATE_DECISION DATE NULL  ,
   COU_PUCE_OBLIGATOIRE BOOL NULL  
   , PRIMARY KEY (CLU_ID,RAI_ID,COU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_COURSE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_COURSE_VIK_COURSE_TYPE
     ON VIK_COURSE (COU_TYP_ID ASC);

CREATE  INDEX I_FK_VIK_COURSE_VIK_RAID
     ON VIK_COURSE (CLU_ID ASC,RAI_ID ASC);

CREATE  INDEX I_FK_VIK_COURSE_VIK_ORGANISATEUR_COURSE
     ON VIK_COURSE (COM_ID_ORGANISATEUR_COURSE ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_RAID
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_RAID
 (
   CLU_ID INTEGER(4) NOT NULL  ,
   RAI_ID INTEGER(8) NOT NULL  ,
   COM_ID_ORGANISATEUR_RAID INTEGER(8) NOT NULL  ,
   RAI_NOM CHAR(64) NULL  ,
   RAI_INSCRIPTION_DATE_DEBUT DATE NULL  ,
   RAI_INSCRIPTION_DATE_FIN DATE NULL  ,
   RAI_DATE_DEBUT DATE NULL  ,
   RAI_DATE_FIN DATE NULL  ,
   RAI_MAIL CHAR(64) NULL  ,
   RAI_TELEPHONE INTEGER(11) NULL  ,
   RAI_LIEU CHAR(128) NULL  ,
   RAI_ILLUSTRATION CHAR(64) NULL  ,
   RAI_SITE_WEB CHAR(100) NULL  ,
   RAI_DATE_DEMANDE DATE NULL  ,
   RAI_STATUS CHAR(32) NULL  ,
   RAI_DATE_DECISION DATE NULL  
   , PRIMARY KEY (CLU_ID,RAI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_RAID
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_RAID_VIK_CLUB
     ON VIK_RAID (CLU_ID ASC);

CREATE  INDEX I_FK_VIK_RAID_VIK_ORGANISATEUR_RAID
     ON VIK_RAID (COM_ID_ORGANISATEUR_RAID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_ORGANISATEUR_RAID
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_ORGANISATEUR_RAID
 (
   COM_ID INTEGER(8) NOT NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_ORGANISATEUR_COURSE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_ORGANISATEUR_COURSE
 (
   COM_ID INTEGER(8) NOT NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_ADMINISTRATEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_ADMINISTRATEUR
 (
   COM_ID INTEGER(8) NOT NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_CLUB
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_CLUB
 (
   CLU_ID INTEGER(4) NOT NULL AUTO_INCREMENT ,
   COM_ID_RESPONSABLE INTEGER(8) NOT NULL  ,
   CLU_NOM CHAR(64) NULL  ,
   CLU_ADRESSE CHAR(64) NULL  ,
   CLU_CODE_POST INTEGER(5) NULL  ,
   CLU_DATE_DEMANDE DATE NULL  ,
   CLU_STATUS CHAR(32) NULL  ,
   CLU_DATE_DECISION DATE NULL ,
   CLU_TELEPHONE CHAR(32) NULL ,  
    PRIMARY KEY (CLU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_CLUB
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_CLUB_VIK_ADHERENT
     ON VIK_CLUB (COM_ID_RESPONSABLE ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_CATEGORIE_AGE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_CATEGORIE_AGE
 (
   CAT_AGE_ID INTEGER(2) NOT NULL  ,
   CAT_AGE_MAX INTEGER(2) NULL  ,
   CAT_AGE_MIN INTEGER(2) NULL  ,
   CAT_AGE_MONTANT INTEGER(8) NULL  
   , PRIMARY KEY (CAT_AGE_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_COMPTE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COMPTE
 (
   COM_ID INTEGER(8) NOT NULL AUTO_INCREMENT ,
   COM_NOM CHAR(32) NULL  ,
   COM_PRENOM CHAR(32) NULL  ,
   COM_PSEUDO CHAR(64) UNIQUE ,
   COM_MDP CHAR(64),
   COM_DATE_NAISSANCE DATE NULL  ,
   COM_ADRESSE CHAR(128) NULL  ,
   COM_TELEPHONE CHAR(32) NULL  ,
   COM_MAIL CHAR(255) NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_ADHERENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_ADHERENT
 (
   COM_ID INTEGER(8) NOT NULL  ,
   ADH_NUM_LICENCIE TEXT NULL  ,
   ADH_NUM_PUCE INTEGER(16) NULL  
   , PRIMARY KEY (COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_CONCERNER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_CONCERNER
 (
   CAT_AGE_ID INTEGER(2) NOT NULL  ,
   CLU_ID INTEGER(4) NOT NULL  ,
   RAI_ID INTEGER(8) NOT NULL  ,
   COU_ID INTEGER(8) NOT NULL  
   , PRIMARY KEY (CAT_AGE_ID,CLU_ID,RAI_ID,COU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_CONCERNER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_CONCERNER_VIK_CATEGORIE_AGE
     ON VIK_CONCERNER (CAT_AGE_ID ASC);

CREATE  INDEX I_FK_VIK_CONCERNER_VIK_COURSE
     ON VIK_CONCERNER (CLU_ID ASC,RAI_ID ASC,COU_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_CONTENIR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_CONTENIR
 (
   CLU_ID INTEGER(4) NOT NULL  ,
   RAI_ID INTEGER(8) NOT NULL  ,
   COU_ID INTEGER(8) NOT NULL  ,
   EQU_ID INTEGER(8) NOT NULL  ,
   COM_ID INTEGER(8) NOT NULL  ,
   COUR_PPS CHAR(32) NULL  ,
   COUREUR_DATE_DEMANDE DATE NULL  ,
   COUREUR_STATUS CHAR(32) NULL  ,
   COUREUR_DATE_DECISION DATE NULL  
   , PRIMARY KEY (CLU_ID,RAI_ID,COU_ID,EQU_ID,COM_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_CONTENIR
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_CONTENIR_VIK_EQUIPE
     ON VIK_CONTENIR (CLU_ID ASC,RAI_ID ASC,COU_ID ASC,EQU_ID ASC);

CREATE  INDEX I_FK_VIK_CONTENIR_VIK_COUREUR
     ON VIK_CONTENIR (COM_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_ADHERER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_ADHERER
 (
   COM_ID INTEGER(8) NOT NULL  ,
   CLU_ID INTEGER(4) NOT NULL  ,
   ADHERER_DATE_DEMANDE DATE NULL  ,
   ADHERER_STATUS CHAR(32) NULL  ,
   ADHERER_DATE_DECISION DATE NULL  
   , PRIMARY KEY (COM_ID,CLU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_ADHERER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_ADHERER_VIK_ADHERENT
     ON VIK_ADHERER (COM_ID ASC);

CREATE  INDEX I_FK_VIK_ADHERER_VIK_CLUB
     ON VIK_ADHERER (CLU_ID ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE VIK_CREATEUR_EQUIPE 
  ADD FOREIGN KEY FK_VIK_CREATEUR_EQUIPE_VIK_COMPTE (COM_ID)
      REFERENCES VIK_COMPTE (COM_ID) ;


ALTER TABLE VIK_EQUIPE 
  ADD FOREIGN KEY FK_VIK_EQUIPE_VIK_COURSE (CLU_ID,RAI_ID,COU_ID)
      REFERENCES VIK_COURSE (CLU_ID,RAI_ID,COU_ID) ;


ALTER TABLE VIK_EQUIPE 
  ADD FOREIGN KEY FK_VIK_EQUIPE_VIK_CREATEUR_EQUIPE (COM_ID_CREATEUR)
      REFERENCES VIK_CREATEUR_EQUIPE (COM_ID) ;


ALTER TABLE VIK_COUREUR 
  ADD FOREIGN KEY FK_VIK_COUREUR_VIK_CATEGORIE_AGE (CAT_AGE_ID)
      REFERENCES VIK_CATEGORIE_AGE (CAT_AGE_ID) ;


ALTER TABLE VIK_COUREUR 
  ADD FOREIGN KEY FK_VIK_COUREUR_VIK_COMPTE (COM_ID)
      REFERENCES VIK_COMPTE (COM_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_COURSE_TYPE (COU_TYP_ID)
      REFERENCES VIK_COURSE_TYPE (COU_TYP_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_RAID (CLU_ID,RAI_ID)
      REFERENCES VIK_RAID (CLU_ID,RAI_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_ORGANISATEUR_COURSE (COM_ID_ORGANISATEUR_COURSE)
      REFERENCES VIK_ORGANISATEUR_COURSE (COM_ID) ;


ALTER TABLE VIK_RAID 
  ADD FOREIGN KEY FK_VIK_RAID_VIK_CLUB (CLU_ID)
      REFERENCES VIK_CLUB (CLU_ID) ;


ALTER TABLE VIK_RAID 
  ADD FOREIGN KEY FK_VIK_RAID_VIK_ORGANISATEUR_RAID (COM_ID_ORGANISATEUR_RAID)
      REFERENCES VIK_ORGANISATEUR_RAID (COM_ID) ;


ALTER TABLE VIK_ORGANISATEUR_RAID 
  ADD FOREIGN KEY FK_VIK_ORGANISATEUR_RAID_VIK_ADHERENT (COM_ID)
      REFERENCES VIK_ADHERENT (COM_ID) ;


ALTER TABLE VIK_ORGANISATEUR_COURSE 
  ADD FOREIGN KEY FK_VIK_ORGANISATEUR_COURSE_VIK_ADHERENT (COM_ID)
      REFERENCES VIK_ADHERENT (COM_ID) ;


ALTER TABLE VIK_ADMINISTRATEUR 
  ADD FOREIGN KEY FK_VIK_ADMINISTRATEUR_VIK_COMPTE (COM_ID)
      REFERENCES VIK_COMPTE (COM_ID) ;


ALTER TABLE VIK_CLUB 
  ADD FOREIGN KEY FK_VIK_CLUB_VIK_ADHERENT (COM_ID_RESPONSABLE)
      REFERENCES VIK_ADHERENT (COM_ID) ;


ALTER TABLE VIK_ADHERENT 
  ADD FOREIGN KEY FK_VIK_ADHERENT_VIK_COMPTE (COM_ID)
      REFERENCES VIK_COMPTE (COM_ID) ;


ALTER TABLE VIK_CONCERNER 
  ADD FOREIGN KEY FK_VIK_CONCERNER_VIK_CATEGORIE_AGE (CAT_AGE_ID)
      REFERENCES VIK_CATEGORIE_AGE (CAT_AGE_ID) ;


ALTER TABLE VIK_CONCERNER 
  ADD FOREIGN KEY FK_VIK_CONCERNER_VIK_COURSE (CLU_ID,RAI_ID,COU_ID)
      REFERENCES VIK_COURSE (CLU_ID,RAI_ID,COU_ID) ;


ALTER TABLE VIK_CONTENIR 
  ADD FOREIGN KEY FK_VIK_CONTENIR_VIK_EQUIPE (CLU_ID,RAI_ID,COU_ID,EQU_ID)
      REFERENCES VIK_EQUIPE (CLU_ID,RAI_ID,COU_ID,EQU_ID) ;


ALTER TABLE VIK_CONTENIR 
  ADD FOREIGN KEY FK_VIK_CONTENIR_VIK_COUREUR (COM_ID)
      REFERENCES VIK_COUREUR (COM_ID) ;


ALTER TABLE VIK_ADHERER 
  ADD FOREIGN KEY FK_VIK_ADHERER_VIK_ADHERENT (COM_ID)
      REFERENCES VIK_ADHERENT (COM_ID) ;


ALTER TABLE VIK_ADHERER 
  ADD FOREIGN KEY FK_VIK_ADHERER_VIK_CLUB (CLU_ID)
      REFERENCES VIK_CLUB (CLU_ID) ;

