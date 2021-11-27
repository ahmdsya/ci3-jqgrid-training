<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Global_M','m');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->load->view('components/header');
		$this->load->view('pelanggan/home');
		$this->load->view('components/footer');
	}

	public function get_data()
	{
		$page  = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx  = isset($_GET['sidx'])?$_GET['sidx']:'id';  //field index
		$sord  = isset($_GET['sord'])?$_GET['sord']:'';    // default = asc

		$search  = (isset($_GET['_search']) ? $_GET['_search'] : false);
		$filters = (isset($_GET['filters']) ? json_decode($_GET['filters'], true) : false);
		$rules   = ($search == 'true' ? $filters['rules'] : false);

		$where  = [];

		if ($search == 'true') {

			foreach($rules as $rule){

				$field     = $rule['field'];
				$ops       = "LIKE";
				$searchstr = $rule['data'];
				$searchstr = '%'.$searchstr.'%';
				
				$where[] = "$field $ops '$searchstr' ";
			}
			$where = implode($filters['groupOp']." ", $where);
        }
				
		$start = $limit*$page - $limit;
		$start = ($start<0)?0:$start;
		
		$model  = $this->m->get_data($start, $limit, $where, $sidx, $sord);
		$result = $model->result();
		
		$rowcnt = ($search == 'true' ? $this->m->whereCount($where, $sidx, $sord) : $this->m->all_count());

		$data['page']    = $page;
		$data['total']   = ceil($rowcnt/$limit);
		$data['records'] = $rowcnt;
		    
		$i = 0;
		foreach($result as $row) {
			// $data['rows'][$i]['id']=$row->id;
			$data['rows'][$i]['cell']=array( $row->id,$row->tgl_pesanan,$row->nama, $row->nik, $row->hp, $row->email, $row->alamat);
			$i++;
		}
		
		echo json_encode($data);
	}

	public function add_dialog()
	{
		$this->load->view('pelanggan/add-dialog');
	}

	public function upd_dialog($id){
		$data['pelanggan'] = $this->m->getById($id);
		$data['pesanan']   = $this->m->get_all_pesanan($data['pelanggan']->id);

		$this->load->view('pelanggan/upd-dialog', $data);
	}

	public function del_dialog($id)
	{
		$data['pelanggan'] = $this->m->getById($id);
		$data['pesanan']   = $this->m->get_all_pesanan($data['pelanggan']->id);
		
		$this->load->view('pelanggan/del-dialog', $data);
	}

	public function store()
	{
		$this->form_validation->set_rules('tgl_pesanan', 'tgl pesanan', 'required');
		$this->form_validation->set_rules('nama', 'nama', 'required');
		$this->form_validation->set_rules('nik', 'nik', 'required|numeric');
		$this->form_validation->set_rules('hp', 'hp', 'required|numeric');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('alamat', 'alamat', 'required');
		

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'msg' => validation_errors()
			]);
		}else{
			$dataPelanggan = [
				'tgl_pesanan' => date('Y-m-d', strtotime($this->input->post('tgl_pesanan'))),
				'nama'        => $this->input->post('nama'),
				'nik'         => $this->input->post('nik'),
				'hp'          => $this->input->post('hp'),
				'email'       => $this->input->post('email'),
				'alamat'      => $this->input->post('alamat'),
			];

			$nama_produk = $this->input->post('nama_produk');
			$harga       = $this->input->post('harga');
			$qty         = $this->input->post('qty');

			$pelanggan_id = $this->m->add($dataPelanggan);

			for ($i=0; $i < count($nama_produk); $i++) { 
				$dataPesanan = [
					'pelanggan_id' => $pelanggan_id,
					'nama_produk'  => $nama_produk[$i],
					'harga'        => str_replace('.', '', $harga[$i]),
					'qty'          => $qty[$i],
					'total_harga'  => str_replace('.', '', $harga[$i]) * $qty[$i],
				];

				$this->m->addPesanan($dataPesanan);
			}

			//delete data pesanan yang kosong
			$this->db->where('nama_produk', '');
			$this->db->delete('pesanan');

			echo json_encode([
				'status' => 'success',
			]);
		}
		
	}

	public function update($id){

		$this->form_validation->set_rules('tgl_pesanan', 'tgl pesanan', 'required');
		$this->form_validation->set_rules('nama', 'nama', 'required');
		$this->form_validation->set_rules('nik', 'nik', 'required|numeric');
		$this->form_validation->set_rules('hp', 'hp', 'required|numeric');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('alamat', 'alamat', 'required');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'msg' => validation_errors()
			]);
		}else{
			$dataPelanggan = [
				'tgl_pesanan' => date('Y-m-d', strtotime($this->input->post('tgl_pesanan'))),
				'nama'        => $this->input->post('nama'),
				'nik'         => $this->input->post('nik'),
				'hp'          => $this->input->post('hp'),
				'email'       => $this->input->post('email'),
				'alamat'      => $this->input->post('alamat'),
			];
	
			//update data pelanggan
			$this->m->update($id, $dataPelanggan);
	
			//delete data pesanan
			$this->m->deletePesanan($id);
	
			//insert data pesanan baru
			$nama_produk = $this->input->post('nama_produk');
			$harga       = $this->input->post('harga');
			$qty         = $this->input->post('qty');
	
			for ($i=0; $i < count($nama_produk); $i++) { 
				$dataPesanan = [
					'pelanggan_id' => $id,
					'nama_produk'  => $nama_produk[$i],
					'harga'        => str_replace('.', '', $harga[$i]),
					'qty'          => $qty[$i],
					'total_harga'  => str_replace('.', '', $harga[$i]) * $qty[$i],
				];
	
				$this->m->addPesanan($dataPesanan);
			}
	
			//delete data pesanan yang kosong
			$this->db->where('nama_produk', '');
			$this->db->delete('pesanan');
	
			echo json_encode([
				'status' => 'success',
			]);
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		$this->m->delete($id);
		
		return true;
	}

	public function report()
	{
		$start = ( $_GET['start'] - 1) ?? 0;
		$limit =  $_GET['limit'] - $start;

		$this->db->select('id,tgl_pesanan,nama,nik,hp,email,alamat');
		$data['dataPelanggan'] = $this->db->get('pelanggan',$limit, $start)->result();

		foreach($data['dataPelanggan'] as $pelanggan){
			$pelanggan->relations = $this->db->get_where('pesanan', ['pesanan.pelanggan_id' => $pelanggan->id])->result();
		}

		$this->load->view('pelanggan/report', $data);
		// echo json_encode($data['dataPelanggan']);
	}

	
	//proses untuk tabel pesanan
	public function get_all_pesanan()
	{
		$pelanggan_id = (isset($_GET['pelanggan_id']) ? $_GET['pelanggan_id'] : 0);

		$data  = $this->m->get_all_pesanan($pelanggan_id);
		$total = $this->m->sum_total_harga($pelanggan_id);

		$return = [
			'userdata' => $total,
			'rows'     => $data
		];

		echo json_encode($return);

		// echo $pelanggan_id;
	}

	public function test()
	{
		
	}
}
