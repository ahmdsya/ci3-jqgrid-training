<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
		$sidx  = ($_GET['sidx'] == "" ? 'nama' : $_GET['sidx']);  //field index
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
		// $start = ($start<0)?0:$start;
		
		$model  = $this->m->get_data($start, $limit, $where, $sidx, $sord);
		$result = $model->result();
		
		$rowcnt = ($search == 'true' ? $this->m->whereCount($where, $sidx, $sord) : $this->m->all_count());

		$data['page']    = $page;
		$data['total']   = ceil($rowcnt/$limit);
		$data['records'] = $rowcnt;
		// $data['rows']    = $result;
		    
		$i = 0;
		foreach($result as $row) {
			// $data['rows'][$i]['id']=$row->id;
			$data['rows'][$i]['cell']=array( 
								$row->id,
								$row->tgl_pesanan,
								strtoupper($row->nama), 
								$row->nik, $row->hp, 
								strtoupper($row->email), 
								strtoupper($row->alamat));
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
		$start   = ($_GET['start'] - 1) ?? 0;
		$limit   = $_GET['limit'] - $start;
		$getData = json_decode(base64_decode($_GET['data']));
		$myData  = array_slice($getData, $start, $limit);
		$sidx    = isset($_GET['sidx'])?$_GET['sidx']:'nama';
		$sord    = isset($_GET['sord'])?$_GET['sord']:'';
		$type    = isset($_GET['type'])?$_GET['type']:false;

		$pelangganID = [];

		foreach($myData as $data){
			$pelangganID[] = (int)$data->id;
		}
		
		$this->db->select('id,tgl_pesanan,nama,nik,hp,email,alamat');
		$this->db->where_in('id', $pelangganID);
		$this->db->order_by($sidx, $sord);
		$dataPelanggan = $this->db->get('pelanggan')->result();

		foreach($dataPelanggan as $pelanggan){
			$pelanggan->relations = $this->db->get_where('pesanan', ['pesanan.pelanggan_id' => $pelanggan->id])->result();
		}

		$data = [
			'dataPelanggan' => $dataPelanggan
		];

		if($type == "stimulsoft"){

			$this->load->view('pelanggan/report', $data);

		}elseif($type == "excel"){

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$sheet->setCellValue('A1', 'NO');
			$sheet->setCellValue('B1', 'Tanggal Pesanan');
			$sheet->setCellValue('C1', 'Nama Lengkap');
			$sheet->setCellValue('D1', 'NIK');
			$sheet->setCellValue('E1', 'HP');
			$sheet->setCellValue('F1', 'Email');
			$sheet->setCellValue('G1', 'Alamat');

			$no  = 1;
			$col = 2;

			foreach($data['dataPelanggan'] as $row){
				$sheet->setCellValue('A'.$col, $no++);
				$sheet->setCellValue('B'.$col, $row->tgl_pesanan);
				$sheet->setCellValue('C'.$col, $row->nama);
				$sheet->setCellValue('D'.$col, $row->nik);
				$sheet->setCellValue('E'.$col, $row->hp);
				$sheet->setCellValue('F'.$col, $row->email);
				$sheet->setCellValue('G'.$col, $row->alamat);
				$col++;
			}

			$writer = new Xlsx($spreadsheet);
			$filename = 'Report';
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
	
			$writer->save('php://output');
		}
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

	public function dummy()
	{
		for ($i=0; $i < 100000; $i++) { 

			$faker = Faker\Factory::create('id_ID');
			// $faker::setDefaultTimezone('Asia/Jakarta');

			$dataPelanggan = [
				'tgl_pesanan' => $faker->date($format = 'Y-m-d', $max = 'now'),
				'nama' => $faker->name,
				'nik' => rand(),
				'hp' => $faker->e164PhoneNumber,
				'email' => $faker->email,
				'alamat' => $faker->address
			];
	
			$pelanggan_id = $this->m->add($dataPelanggan);
			
			for ($j=1; $j < 4; $j++) { 

				$harga = rand ( 10000 , 99999 );

				$dataPesanan = [
					'pelanggan_id' => $pelanggan_id,
					'nama_produk' => "Produk $j",
					'harga' => $harga,
					'qty' => $j,
					'total_harga' => $harga*$j
				];

				$this->m->addPesanan($dataPesanan);
			}
		}

		echo "OK";
	}

	public function trancate()
	{
		$this->db->truncate('pelanggan');
		$this->db->truncate('pesanan');

		echo "OK";
	}
}
