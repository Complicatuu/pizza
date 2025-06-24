<?php
class Produs {
    private $con;
    private $table = "produse";

    public function __construct($db) {
        $this->con = $db;
    }

    public function adaugaProdus($nume, $descriere, $pret, $imagine = null) {
        try {
            $stmt = $this->con->prepare("INSERT INTO {$this->table} (nume, descriere, pret, imagine)
                                         VALUES (:nume, :descriere, :pret, :imagine)");

            $stmt->bindParam(':nume', $nume);
            $stmt->bindParam(':descriere', $descriere);
            $stmt->bindParam(':pret', $pret);
            $stmt->bindParam(':imagine', $imagine);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Eroare la adăugare produs: " . $e->getMessage();
            return false;
        }
    }

    public function editeazaProdus($id, $nume, $descriere, $pret, $imagine = null) {
    try {
        if ($imagine) {
            $query = "UPDATE {$this->table} SET nume = :nume, descriere = :descriere, pret = :pret, imagine = :imagine WHERE id = :id";
        } else {
            $query = "UPDATE {$this->table} SET nume = :nume, descriere = :descriere, pret = :pret WHERE id = :id";
        }

        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':nume', $nume);
        $stmt->bindParam(':descriere', $descriere);
        $stmt->bindParam(':pret', $pret);
        $stmt->bindParam(':id', $id);
        if ($imagine) {
            $stmt->bindParam(':imagine', $imagine);
        }

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Eroare la editare: " . $e->getMessage();
        return false;
    }
}
}
