<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('backend/siswa_model');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
    }

    /**
     * Get All Data
     */
    public function index()
    {
        $siswa = $this->siswa_model->get_all();

        $response = array();

        foreach ($siswa->result() as $hasil) {
            $response[] = array(
                'id' => $hasil->id,
                'nama_siswa' => $hasil->nama_siswa,
                'alamat'     => $hasil->alamat,
                'gambar'     => $hasil->gambar
            );
        }

        header('Content-Type: application/json');
        echo json_encode(
            array(
                'success' => true,
                'message' => 'Get All Data Siswa',
                'data'    => $response
            )
        );
    }

    /**
     * Simpan Data
     */
    public function simpan()
    {
        //set validasi
        $this->form_validation->set_rules('nama_siswa', 'Nama Siswa', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat Siswa', 'required');

        if ($this->form_validation->run() == TRUE) {
            // Konfigurasi upload gambar
            $config['upload_path']   = './uploads';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('gambar')) {
                $upload_data = $this->upload->data();
                $gambar = $upload_data['file_name'];

                $data = array(
                    'nama_siswa' => $this->input->post("nama_siswa"),
                    'alamat'     => $this->input->post("alamat"),
                    'gambar'     => $gambar
                );

                $simpan = $this->siswa_model->simpan_siswa($data);

                if ($simpan) {
                    header('Content-Type: application/json');
                    echo json_encode(
                        array(
                            'success' => true,
                            'message' => 'Data Berhasil Disimpan!'
                        )
                    );
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(
                        array(
                            'success' => false,
                            'message' => 'Data Gagal Disimpan!'
                        )
                    );
                }
            } else {
                // Jika gagal upload gambar
                $error = array('error' => $this->upload->display_errors());
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'Upload Gambar Gagal: ' . $error['error']
                    )
                );
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    'success'    => false,
                    'message'    => validation_errors()
                )
            );
        }
    }

    /**
     * Detail Data Siswa
     */
    public function detail($id_siswa)
    {
        //get ID siswa from URL
        $id_siswa = $this->uri->segment(3);

        $siswa = $this->siswa_model->detail_siswa($id_siswa)->row();

        if ($siswa) {
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    'success' => true,
                    'data'    => array(
                        'nama_siswa' => $siswa->nama_siswa,
                        'alamat'     => $siswa->alamat,
                        'gambar'     => $siswa->gambar,
                        'id' => $siswa->id
                    )
                )
            );
        } else {
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    'success' => false,
                    'message' => 'Data Siswa Tidak Ditemukan!'
                )
            );
        }
    }

    /**
     * Update Data Siswa
     */
    public function update()
    {
        //set validasi
        $this->form_validation->set_rules('id', 'ID Siswa', 'required');
        $this->form_validation->set_rules('nama_siswa', 'Nama Siswa', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat Siswa', 'required');

        if ($this->form_validation->run() == TRUE) {

            $id['id'] = $this->input->post("id");
            $data = array(
                'nama_siswa' => $this->input->post("nama_siswa"),
                'alamat'     => $this->input->post("alamat"),
            );

            // Cek apakah ada gambar yang diunggah
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $data['gambar'] = $upload_data['file_name'];
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    $response = array(
                        'success' => false,
                        'message' => 'Upload Gambar Gagal: ' . $error['error']
                    );
                    return;
                }
            }

            $update = $this->siswa_model->update_siswa($data, $id);

            if ($update) {
                $response = array(
                    'success' => true,
                    'message' => 'Data Berhasil Diupdate!'
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Data Gagal Diupdate!'
                );
            }
        } else {
            $response = array(
                'success'    => false,
                'message'    => validation_errors()
            );
        }
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Delete Data Siswa
     */
    public function delete($id_siswa)
    {
        //get ID siswa from URL
        $id_siswa = $this->uri->segment(3);

        //delete data from model
        $delete = $this->siswa_model->delete_siswa($id_siswa);

        // if ($delete) {

        //     header('Content-Type: application/json');
        //     echo json_encode(
        //         array(
        //             'success' => true,
        //             'message' => 'Data Berhasil Dihapus!'
        //         )
        //     );
        // } else {

        //     header('Content-Type: application/json');
        //     echo json_encode(
        //         array(
        //             'success' => false,
        //             'message' => 'Data Gagal Dihapus!'
        //         )
        //     );
        // }
        if ($delete) {
            $response = array(
                'success' => true,
                'message' => 'Data Berhasil Dihapus!'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Data Gagal Dihapus!'
            );
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
