
INSERT INTO VIK_COMPTE (COM_ID, COM_NOM, COM_PRENOM, COM_PSEUDO, COM_MDP, COM_DATE_NAISSANCE, COM_ADRESSE, COM_TELEPHONE, COM_MAIL) VALUES 
(1, 'Sorel', 'Milo', 'pepin2pomme', 'milo', '1995-05-12', 'Caen', '0601010101', 'sorel@gmail.com'),
(2, 'Golliot', 'Camille', 'Badghoul', 'camille', '1980-08-20', 'Falaise', '0602020202', 'golliot@gmail.com'),
(3, 'Kahlouche', 'Mohamed', 'Ulysse', 'mohamed', '2005-02-15', 'Bayeux', '0603030303', 'kahlouche@gmail.com'),
(4, 'Montals', 'Lylian', 'Starkio', 'lylian', '1990-01-01', 'Server', '0000000000', 'montals@gmail.com'),
(5, 'Jort', 'Fabienne', 'Faby', 'fabienne', '1975-03-10', 'Rouen', '0605050505', 'jort@gmail.com'),
(6, 'Margerie', 'Zoe', 'RockLover', 'zoe', '1998-07-22', 'Lisieux', '0606060606', 'margerie@gmail.com'),
(7, 'Heyberger', 'Nathanael', 'darknatha008', 'nathanael', '2009-01-30', 'Caen', '0607070707', 'heyberger@gmail.com'),
(8, 'Affholder', 'Quentin', 'AccousticBaobab', 'quentin', '1982-11-11', 'Vire', '0608080808', 'affholder@gmail.com');

INSERT INTO VIK_ADMINISTRATEUR (COM_ID) VALUES (1),(2),(3);

INSERT INTO VIK_COURSE_TYPE (COU_TYP_ID, COU_TYPE_LIBELLE) VALUES (1, 'Compétitif'), (2, 'Rando / Loisirs');
INSERT INTO VIK_CATEGORIE_AGE (CAT_AGE_ID, CAT_AGE_MAX, CAT_AGE_MIN) VALUES (1, 12, 0), (2, 14, 13), (3, 16, 15), (4, 18, 17), (5, 20, 19), (6, 39, 21), (7, 99, 40);