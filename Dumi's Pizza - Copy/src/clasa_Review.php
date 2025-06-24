<?php
class Review {
    private $con;
    private $table = "reviewuri";

    public function __construct($db) {
        $this->con = $db;
    }

    // Adaugă un review nou
    public function adaugaReview($username, $mesaj, $rating, $imagine = null) {
        try {
            $stmt = $this->con->prepare("INSERT INTO {$this->table} (username, mesaj, rating, imagine)
                                         VALUES (:username, :mesaj, :rating, :imagine)");

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':mesaj', $mesaj);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':imagine', $imagine);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Eroare la adăugare review: " . $e->getMessage();
            return false;
        }
    }

    // Editează un review existent
    public function editeazaReview($id, $username, $mesaj, $rating, $imagine = null) {
        try {
            // Doar autorul poate edita reviewul
            if ($imagine) {
                $sql = "UPDATE {$this->table} SET mesaj = :mesaj, rating = :rating, imagine = :imagine
                        WHERE id = :id AND username = :username";
            } else {
                $sql = "UPDATE {$this->table} SET mesaj = :mesaj, rating = :rating
                        WHERE id = :id AND username = :username";
            }

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':mesaj', $mesaj);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);

            if ($imagine) {
                $stmt->bindParam(':imagine', $imagine);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Eroare la editare review: " . $e->getMessage();
            return false;
        }
    }

    // Opțional: returnează un review după ID
    public function getReview($id) {
        $stmt = $this->con->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
