<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require('vendor/autoload.php');

use GuzzleHttp\Client;

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://localhost/api-ci/siswa');
        $siswa_data = json_decode($response->getBody());

        $data['result'] = $siswa_data->data;

        // $response = $this->client->request('GET', 'http://localhost/api-ci/index.php/siswa');
        // $data['result'] = json_decode($response->getBody()->getContents(), true);

        $this->load->view('home', $data);
    }
}
