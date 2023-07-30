<?php

// Database Wrapper
// bisa digunakan untuk tabel manapun
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    private $dbh;
    private $stmt;

    public function __construct()
    {
        // data source name 
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        // option biasa digunakan ketika kita ingin optimasi ke database kita
        $option = [
            // parameter dari konfigurasi databasenya,
            // untuk membuat koneksi database terjaga terus
            PDO::ATTR_PERSISTENT => true,
            // mode error-nya tampilkan exception
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        // block try and catch
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // query-nya generic, bisa dipakai untuk apapun (crud) agar fleksibel
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    // binding data,
    // didalam query terdapat awarenya, atau
    // kalau insert, insert into Values nya apa
    // kalau update, update set datanya apa
    // istilahnya itu Parameter

    // kenapa gak langsung di masukkan kedalam query?
    // melakukan ini agar terhindar dari sql injection
    // query di eksekusi setelah stringnya dibersihkan terlebih dahulu

    // type = null, supaya yang menentukan itu sistem
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        // bindValue,
        // misalkan,
        // 'WHERE id = 1'
        // 1 akan dicek, integer
        // kasih option PDO::PARAM_INT
        // 1 tersebut kita bind
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        $this->stmt->execute();
    }

    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
}
