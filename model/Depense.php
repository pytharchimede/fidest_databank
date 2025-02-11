<?php
class Depense
{
    private $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTotalDepensesTousChantiers()
    {
        // Requête pour récupérer la somme des montants des décaissements pour tous les chantiers
        $sql = "SELECT SUM(d.montant) AS total_decaissé
            FROM decaissement d
            JOIN fiche f ON f.num_fiche = d.num_fiche_decaissement
            WHERE d.montant > 0"; // On ne prend que les montants > 0

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Retourner le total des dépenses ou 0 si aucune dépense
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_decaissé'] : 0;
    }


    // Calculer le total des dépenses par chantier
    public function getTotalDepensesParChantier($chantierId)
    {
        // Requête pour récupérer les montants des décaissements liés aux fiches d'un chantier
        $sql = "SELECT SUM(d.montant) AS total_decaissé
             FROM decaissement d
             JOIN fiche f ON f.num_fiche = d.num_fiche_decaissement
             WHERE f.chantier_id = :chantierId AND d.montant > 0"; // On ne prend que les montants > 0

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':chantierId' => $chantierId]);

        // Retourner le total des dépenses ou 0 si aucune dépense
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_decaissé'] : 0;
    }

    // Méthode pour obtenir la liste des fiches associées à un chantier
    public function getFichesParChantier($chantier_id)
    {
        // Récupérer toutes les fiches liées à un chantier
        $query = "SELECT f.id, f.beneficiaire_fiche, f.montant_fiche, f.date_creat_fiche, f.num_fiche, 
                          f.designation_fiche, f.num_piece, f.precision_fiche
                  FROM fiche f
                  WHERE f.chantier_id = :chantier_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['chantier_id' => $chantier_id]);

        // Retourner les fiches sous forme de tableau
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour vérifier si la designation_fiche dans fiche correspond à lib_designation dans designation
    public function verifierDesignationFiche($chantier_id)
    {
        $query = "SELECT f.designation_fiche, d.lib_designation 
                  FROM fiche f
                  JOIN designation d ON f.designation_fiche = d.lib_designation
                  WHERE f.chantier_id = :chantier_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['chantier_id' => $chantier_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
