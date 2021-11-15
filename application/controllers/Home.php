<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Global_M','m');
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

		$search        = (isset($_GET['_search']) ? $_GET['_search'] : false);
		$global_search = (isset($_GET['global_search']) ? $_GET['global_search'] : false);
		$filters       = (isset($_GET['filters']) ? json_decode($_GET['filters'], true) : false);
		$rules         = ($search == 'true' ? $filters['rules'] : false);

		$where  = [];

		if ($search == 'true') {

			if(!$global_search){
				foreach($rules as $rule){
	
					$field     = $rule['field'];
					$ops       = "LIKE";
					$searchstr = $rule['data'];
					$searchstr = '%'.$searchstr.'%';
					
					$where[] = "$field $ops '$searchstr' ";
				}
				$where = implode(" AND ", $where);
			}elseif($global_search){
				$value = '%'.$global_search.'%';
				$where = " nama LIKE '".$value."' OR nik LIKE '".$value."' OR hp LIKE '".$value."' OR email LIKE '".$value."' OR alamat LIKE '".$value."' ";
			}

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
			$data['rows'][$i]['id']=$row->id;
			$data['rows'][$i]['cell']=array($row->nama, $row->nik, $row->hp, $row->email, $row->alamat);
			$i++;
		}
		
		echo json_encode($data);
	}

	public function get_by_id($id)
	{
		$data['pelanggan'] = $this->m->getById($id);
		$this->load->view('pelanggan/del-dialog', $data);
	}

	public function editurl()
	{
		$id = $this->input->post('jqGrid_id');
		$data = [
			'nama'   => $this->input->post('nama'),
			'nik'    => $this->input->post('nik'),
			'hp'     => $this->input->post('hp'),
			'email'  => $this->input->post('email'),
			'alamat' => $this->input->post('alamat')
		];

		$count = $this->m->cek($id);

		if($count >= 1){
			//update data
			$this->m->update($id, $data);
		}else{
			//add data
			$this->m->add($data);
		}

		return true;
	}

	public function delete()
	{
		$id = $this->input->post('id');
		$this->m->delete($id);
		
		return true;
		// return json_encode($_POST);
	}
}
