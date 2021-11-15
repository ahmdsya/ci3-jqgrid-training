<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_M extends CI_Model {

	protected $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = 'pelanggan';
	}

	public function get_data($start, $limit, $where, $sidx, $sord)
	{
		$this->db->select('id,nama,nik,hp,email,alamat');
		$this->db->order_by($sidx, $sord);
		if($where != NULL)$this->db->where($where,NULL,FALSE);
		$query = $this->db->get($this->table, $limit, $start);
		return $query;
	}

	public function whereCount($where, $sidx, $sord)
	{
		$this->db->select('id,nama,nik,hp,email,alamat');
		$this->db->order_by($sidx, $sord);
		if($where != NULL)$this->db->where($where,NULL,FALSE);
		$query = $this->db->get($this->table);
		return $query->num_rows();
	}

	public function add($data)
	{
		$form = [
			'nama' => $data['nama'],
			'nik' => $data['nik'],
			'hp' => $data['hp'],
			'email' => $data['email'],
			'alamat' => $data['alamat']
		];

		$this->db->insert($this->table, $form);
	}

	public function update($id, $data)
	{
		$form = [
			'nama' => $data['nama'],
			'nik' => $data['nik'],
			'hp' => $data['hp'],
			'email' => $data['email'],
			'alamat' => $data['alamat']
		];

		$this->db->where('id', $id);
		$this->db->update($this->table, $form);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		return $query->row();
	}

	public function all_count()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($this->table);
		return $query->num_rows();
	}

	public function cek($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		return $query->num_rows();
	}

}