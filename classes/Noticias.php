<?php
class Noticias{
    private $conn;
    private $table_name = "noticias";   
    
    public function __construct($db){
        $this -> conn = $db;
    }   
        public function registrar($titulo, $noticia, $data, $autor, $imagem){
            $query = "INSERT INTO " . $this->table_name . " (titulo, noticia, data, autor, imagem) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$titulo, $noticia, $data, $autor, $imagem]);
            return $stmt;
        }

        public function criar($titulo, $noticia, $data, $autor, $imagem){
            return $this->registrar($titulo, $noticia, $data, $autor, $imagem);
        }
        
        public function ler(){
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function lerPorId($id){
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public function deletar($id){
            $query = "DELETE FROM " . $this->table_name. " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt;
        }

}
?>