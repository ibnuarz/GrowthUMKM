<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Keuangan extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        $this->load->model('Muser');
    }   

    public function index()
    {
        if (empty($this->session->userdata('username_user'))) {
            redirect('main/login');
        }
        $id_user = $this->session->userdata('user_id');
        $data['username_user'] = $this->session->userdata('username_user');
        $data['user'] = $this->Muser->getUserById($id_user);
        $data['totals'] = $this->Muser->getIncomeExpenseTotals($id_user);
        $data['differences'] = $this->Muser->getIncomeExpenseDifference($id_user);
        $data['keuangan'] = $this->Muser->getKeuanganByIdUser($id_user);
        $data['karyawan'] = $this->Muser->getKaryawanByIdUser($id_user);
        $data['kategori'] = $this->Muser->getAllKategori();
        $data['gajikaryawan'] = $this->Muser->getGajiKaryawanByIdUser($id_user);
        $this->load->view('user/layout/header', $data);
        $this->load->view('user/keuangan', $data);
        $this->load->view('user/layout/modal');
        $this->load->view('user/layout/footer');
    }

    public function addkeuangan() 
    {
        if (empty($this->session->userdata('username_user'))) {
            redirect('main/login');
        }
    
        if ($this->input->post()) {
            $this->form_validation->set_rules('catatan', 'Catatan Keuangan', 'required');
            $this->form_validation->set_rules('tanggal', 'Tanggal Input Data Keuangan', 'required');
            $this->form_validation->set_rules('nominal', 'Nominal Keuangan', 'required|numeric');
            $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
    
            if ($this->form_validation->run() == true) {
                $data_ukeuangan = array(
                    'catatan' => $this->input->post('catatan'),
                    'tanggal' => $this->input->post('tanggal'),
                    'nominal' => $this->input->post('nominal'),
                    'id_kategori' => $this->input->post('id_kategori'),
                    'id_user' => $this->input->post('id_user')
                );
    
                $this->Muser->insertKeuangan($data_ukeuangan);
    
                $this->session->set_flashdata('success_message', 'Data keuangan berhasil ditambahkan.');
    
                redirect('keuangan');
            } else {
                $this->load->view('error_view');
            }
        } else {
                $this->load->view('error_view');
        }
    }

    public function editkeuangan($id_keuangan) 
    {
        if (empty($this->session->userdata('username_user'))) {
            redirect('main/login');
        }
    
        $this->form_validation->set_rules('catatan', 'Catatan Keuangan', 'required');
        $this->form_validation->set_rules('nominal', 'Nominal Keuangan', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal Input Keuangan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
    
        if ($this->form_validation->run() == true) {
            // Form validation success, update the keuangan record
            $data_keuangan = array(
                'catatan' => $this->input->post('catatan'),
                'nominal' => $this->input->post('nominal'),
                'tanggal' => $this->input->post('tanggal'),
                'id_kategori' => $this->input->post('id_kategori')
            );
            $this->Muser->updateKeuangan($id_keuangan, $data_keuangan);
            redirect('keuangan/index');
        } else {
            $data['keuangan'] = $this->Muser->getKeuanganById($id_keuangan);
            $data['kategori'] = $this->Muser->getKategori();
            $this->load->view('user/layout/header', $data);
            $this->load->view('user/keuangan', $data);
            $this->load->view('user/layout/modal');
            $this->load->view('user/layout/footer');
        }
    }
    
    public function deleteKeuangan($id)
    {
        if (empty($this->session->userdata('username_user'))) {
            redirect('main/login');
        }
        $this->Muser->deleteKeuangan($id);
        redirect('keuangan');
    }
    

}