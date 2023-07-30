<?php
class Mahasiswa_model
{
    // nama table spesifik untuk model ini menggunakan table apa
    private $table = 'mahasiswa';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }


    public function getAllMahasiswa()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getMahasiswaById($id)
    {
        // id=:id,
        // untuk menyimpan data yang akan kita binding
        // jadi untuk id tidak langsung id=$id, untuk menghindari sql injection
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id=:id");

        // kita bind
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}
