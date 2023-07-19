v_charge   d            
v_depense              
v_detail_acte  d        
v_detail_facture       
v_detailfacture_patient d
v_montant_total        
v_recette d             
v_saisie_depense       
v_type_charge

CREATE OR REPLACE view v_detail_acte AS
SELECT d.facture_id, a.type_acte, d.montant  FROM detailfactures d
INNER JOIN actes a
ON d.acte_id = a.id;

CREATE OR REPLACE VIEW v_detailfacture_patient AS 
SELECT  p.nom, f.date_facture, d.facture_id, d.montant, a.type_acte FROM patients p
INNER JOIN factures f ON p.id = f.patient_id
INNER JOIN detailfactures d ON f.id = d.facture_id
INNER JOIN actes a ON d.acte_id=a.id;

CREATE OR REPLACE VIEW v_detail_facture AS 
SELECT f.date_facture, d.montant, a.type_acte, a.reference, a.budget, a.annee FROM patients p
INNER JOIN factures f ON p.id = f.patient_id
INNER JOIN detailfactures d ON f.id = d.facture_id
INNER JOIN actes a ON d.acte_id=a.id;

CREATE OR REPLACE VIEW v_recette AS
SELECT
    EXTRACT(MONTH FROM date_facture) AS mois,
    type_acte,
    budget,
    annee,
    round(sum(montant)) AS montant_total,
    round(sum(montant/budget)*100) AS realisation
FROM 
    v_detail_facture
GROUP BY mois, type_acte, budget, annee
ORDER BY mois, type_acte, budget, annee;

CREATE VIEW v_charge AS
SELECT id, montant_depense, depense_id,
    TO_DATE(CONCAT(jour, '-', mois, '-', annee), 'DD-MM-YYY') AS date
FROM charges;

CREATE VIEW charge_depense AS
select c.montant_depense, c.date, d.type_depense, d.reference, d.budget, d.annee
from v_charge c inner join 
depenses d on c.depense_id = d.id; 

CREATE OR REPLACE VIEW v_depense AS
SELECT
    EXTRACT(MONTH FROM date) AS mois,
    type_depense,
    budget,
    annee,
    round(sum(montant_depense)) AS montant_total,
    round(sum(montant_depense/budget)*100) AS realisation
FROM 
    charge_depense
GROUP BY mois, type_depense, budget, annee
ORDER BY mois, type_depense, budget, annee;

CREATE OR REPLACE VIEW v_montant_total AS
SELECT  sum(montant) as montant_total, facture_id FROM detailfactures GROUP BY facture_id;

CREATE OR REPLACE VIEW v_type_charge AS
SELECT c.jour, c.mois, c.annee, c.montant_depense, dep.reference, dep.type_depense FROM charges c
INNER JOIN depenses dep ON c.depense_id = dep.id; 

CREATE OR REPLACE VIEW v_saisie_depense AS
SELECT ch.montant_depense, ch.date, d.type_depense, d.reference FROM 
v_charge ch INNER JOIN depenses d
ON ch.depense_id = d.id;
select*from v_saisie_depense;