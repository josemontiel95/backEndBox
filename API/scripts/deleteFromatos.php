SELECT * FROM footerEnsayo WHERE id_footerEnsayo IN (26,28,27,34);

SELECT * FROM footerEnsayo WHERE id_footerEnsayo IN (26,28,27,34);

SELECT id_ensayoViga, registrosCampo_id FROM ensayoViga  WHERE footerEnsayo_id IN (26,28,27,34);


DELETE FROM ensayoViga WHERE footerEnsayo_id= 26;
DELETE FROM registrosCampo WHERE formatoCampo_id= 1151;
DELETE FROM footerEnsayo WHERE id_footerEnsayo= 26;
Delete FROM formatoCampo WHERE id_formatoCampo= 1151;



DELETE FROM ensayoViga WHERE footerEnsayo_id= 27;
DELETE FROM registrosCampo WHERE formatoCampo_id= 1130;
DELETE FROM footerEnsayo WHERE id_footerEnsayo= 27;
Delete FROM formatoCampo WHERE id_formatoCampo= 1130;


DELETE FROM ensayoViga WHERE footerEnsayo_id= 34;
DELETE FROM registrosCampo WHERE formatoCampo_id= 1118;
DELETE FROM footerEnsayo WHERE id_footerEnsayo= 34;
Delete FROM formatoCampo WHERE id_formatoCampo= 1118;
