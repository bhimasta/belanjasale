<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once( APPPATH . 'modules_core/base/controllers/admin_base.php' );

class Pembelian extends Admin_base {
	public function __construct() {
		// call the controller construct
		parent::__construct();
		// load model
		$this->load->model('m_user');
		$this->load->model('m_role');
		$this->load->model('m_in_order');
		// load user
		$this->load->helper('text');
		// page title
		$this->page_title();
	}

	public function index()
	{
		// user_auth
		$this->check_auth('R');

		// menu
		$data['menu'] = $this->menu();
		// user detail
		$data['user'] = $this->user;
		// load template
		$data['message'] = $this->session->flashdata('message');
		$data['title']		  = "Purchasing Receipt Order Pinaple SI";
		$data['main_content'] = "menu";
		$this->load->view('dashboard/admin/template', $data);
	}

	public function order()
	{
		// user_auth
		$this->check_auth('R');

		// menu
		$data['menu'] = $this->menu();
		// user detail
		$data['user'] = $this->user;
		// load template
		$data['message'] = $this->session->flashdata('message');
		$step = 'detail';
		$data['list_order'] = $this->m_in_order->get_list_order_by_step($step);
		$data['title']		  = "Receipt Purchasing Pinaple SI";
		$data['main_content'] = "order_list";
		$this->load->view('dashboard/admin/template', $data);
	}

	public function verifikasi()
	{
		// user_auth
		$this->check_auth('R');

		// menu
		$data['menu'] = $this->menu();
		// user detail
		$data['user'] = $this->user;
		// load template
		$data['message'] = $this->session->flashdata('message');
		$step = 'verifikasi';
		$data['list_order'] = $this->m_in_order->get_list_order_by_step($step);
		$data['title']		  = "Receipt Purchasing Pinaple SI";
		$data['main_content'] = "verifikasi_list";
		$this->load->view('dashboard/admin/template', $data);
	}

	public function input_order($order_id = null)
	{
		// user_auth
		$this->check_auth('R');

		// menu
		$data['menu'] = $this->menu();
		// user detail
		$data['user'] = $this->user;
		// load template
		$data['list_order'] = $this->m_in_order->get_list_order_by_id($order_id)
		;
		$data['message'] = $this->session->flashdata('message');
		$data['title']		  = "Pembelian Inventory Pinaple SI";
		// jika list order is Pembelian = 1 
		$data['main_content'] = "purchase";
		$this->load->view('dashboard/admin/template', $data);
	}

	public function create_in_order()
	{


        $this->load->helper('date');
        $datestring = '%Y-%m-%d %h:%i:%a';
        $time = time();
        $now = mdate($datestring, $time);

		//ambil barang yang ga da di tabel
		foreach ($_POST as $value) {
		$tgltransaksi = DateTime::createFromFormat('d/m/Y', $value['tanggal'])->format('Y-m-d');


			$input = array(
				'is_pembelian' => $value['isPembelian'],
				'remarks' => $value['remarks'],
				'petugas_order' => $value['petugas_order'],
				'step' => $value['step'],
				'tgl_transaksi' => $tgltransaksi,
				'tgl_order' => $now
				);
		}
		$data = $this->m_in_order->create_in_order($input);
		header('Content-Type: application/json');
	    echo json_encode($data);
	}

	public function update_order() 
	{


        $this->load->helper('date');
        $datestring = '%Y-%m-%d %h:%i:%a';
        $time = time();
        $now = mdate($datestring, $time);

		//ambil barang yang ga da di tabel
		foreach ($_POST as $value) {

			$order_id = $value['order_id'];			
			$input = array(
				'petugas_input' => $value['petugas_input'],
				'tgl_input' => $now,
				'step' => $value['step'],
				'jumlah' => $value['jumlah']
				);
		}
		$data = $this->m_in_order->update_order($order_id,$input);
		header('Content-Type: application/json');
	    echo json_encode($data);
	}

	public function save_detail_order()
	{
		//ambil barang yang ga da di tabel
		foreach ($_POST as $value) {
			$exp = DateTime::createFromFormat('d/m/Y', $value['exp_date'])->format('Y-m-d');

			$input = array(
				'order_id' => $value['order_id'],		
				'barcode' => $value['barcode'],
				'qty' => $value['qty'],
				'exp_date' => $exp,
				'harga_beli' => $value['harga_beli'],
				'sub_total' => $value['sub_total'],
				'kode_rak' => $value['kode_rak']
				);
		}
		$data = $this->m_in_order->save_detail_order($input);
		header('Content-Type: application/json');
	    echo json_encode($data);		
	}

	// page title
	public function page_title() {
		$data['page_title'] = 'Purchasing';
		$this->session->set_userdata($data);
	}
}
