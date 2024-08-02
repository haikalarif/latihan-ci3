<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require('vendor/autoload.php');

use GuzzleHttp\Client;

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$client = new Client();
		$response = $client->request('GET', 'http://localhost/api-ci/siswa');
		$siswa_data = json_decode($response->getBody());

		$data['result'] = $siswa_data->data;

		$this->load->view('home', $data);
		// $this->load->view('welcome_message');
	}
}
