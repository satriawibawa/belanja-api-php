<?php
    class Barang{

        // Connection
        private $conn;

        // Table
        private $db_table = "tb_barang";

        // Columns
        public $id;
        public $nama;
        public $jenis;
        public $harga;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        public function getBarang(){
            $sqlQuery = "SELECT id_barang, nama_barang, jenis_barang, harga_barang FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        public function createBarang(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        nama_barang = :nama_barang, 
                        jenis_barang = :jenis_barang, 
                        harga_barang = :harga_barang";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->nama = htmlspecialchars(strip_tags($this->nama));
            $this->jenis = htmlspecialchars(strip_tags($this->jenis));
            $this->harga = htmlspecialchars(strip_tags($this->harga));
        
            $stmt->bindParam(":nama_barang", $this->nama);
            $stmt->bindParam(":jenis_barang", $this->jenis);
            $stmt->bindParam(":harga_barang", $this->harga);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        public function getSingleBarang(){
            $sqlQuery = "SELECT
                        id_barang, 
                        nama_barang, 
                        jenis_barang, 
                        harga_barang
                      FROM
                        ". $this->db_table ."
                    WHERE 
                        id_barang = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();
                $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
                try {
                $this->nama = $dataRow['nama_barang']  ?? '';
                $this->jenis = $dataRow['jenis_barang']  ?? '';
                $this->harga = $dataRow['harga_barang']  ?? '';
            }catch (Exception $e) {
            }
        }        

        public function updateBarang(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        nama_barang = :nama_barang, 
                        jenis_barang = :jenis_barang, 
                        harga_barang = :harga_barang
                    WHERE 
                        id_barang = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->nama = htmlspecialchars(strip_tags($this->nama));
            $this->jenis = htmlspecialchars(strip_tags($this->jenis));
            $this->harga = htmlspecialchars(strip_tags($this->harga));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(":nama_barang", $this->nama);
            $stmt->bindParam(":jenis_barang", $this->jenis);
            $stmt->bindParam(":harga_barang", $this->harga);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        function deleteBarang(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_barang = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id = htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }

    }
?>