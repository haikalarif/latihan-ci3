<?php
defined('BASEPATH') or exit('No direct script access allowed');

class siswa_model extends CI_Model
{
    /**
     * Get All Data Siswa
     */
    public function get_all()
    {
        $this->db->select("*");
        $this->db->from("tbl_siswa");
        $this->db->order_by("id", "ASC");
        return $this->db->get();
    }

    /**
     * Simpan Data Siswa
     */
    public function simpan_siswa($data)
    {
        $simpan = $this->db->insert("tbl_siswa", $data);

        if ($simpan) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Detail Data Siswa
     */
    public function detail_siswa($id_siswa)
    {
        $this->db->select("*");
        $this->db->from("tbl_siswa");
        $this->db->where("id", $id_siswa);
        return $this->db->get();
    }

    /**
     * Update Data Siswa
     */
    public function update_siswa($data, $id)
    {
        $update = $this->db->update("tbl_siswa", $data, $id);

        if ($update) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete Data Siswa
     */
    public function delete_siswa($id_siswa)
    {
        $this->db->where('id', $id_siswa);
        $delete = $this->db->delete('tbl_siswa');

        if ($delete) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
