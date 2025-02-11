<?php
// Inclure le modèle Chantier (ajustez le chemin si nécessaire)
require_once 'model/Database.php';
require_once 'model/Chantier.php';
require_once 'model/Helper.php';
require_once 'model/Depense.php';




$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

// Créer une instance de la classe Chantier en passant la connexion PDO
$chantierObj = new Chantier($pdo);

// Obtenir le total des montants facturés
$totalMontantDevis = $chantierObj->getTotalMontantDevis();


$depenseObj = new Depense($pdo);

// Total des dépenses pour tous les chantiers
$totalDepenses = $depenseObj->getTotalDepensesTousChantiers();


// Calculer le taux de rentabilité
$tauxRentabilite = Helper::calculerTauxRentabilite($totalDepenses, $totalMontantDevis);


// Récupérer tous les chantiers
$chantiers = $chantierObj->getAllChantiersDesc();
