<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_M extends CI_Model {

	protected $tb_pelanggan;
	protected $tb_pesanan;

	public function __construct()
	{
		parent::__construct();
		$this->tb_pelanggan = 'pelanggan';
		$this->tb_pesanan   = 'pesanan';
	}


	//table pelanggan
	public function get_data($start, $limit, $where, $sidx, $sord)
	{
		$this->db->select('id,tgl_pesanan,nama,nik,hp,email,alamat');
		$this->db->order_by($sidx, $sord);
		if($where != NULL)$this->db->where($where,NULL,FALSE);
		$query = $this->db->get($this->tb_pelanggan, $limit, $start);
		return $query;
	}

	public function whereCount($where, $sidx, $sord)
	{
		$this->db->select('id,nama,nik,hp,email,alamat');
		$this->db->order_by($sidx, $sord);
		if($where != NULL)$this->db->where($where,NULL,FALSE);
		$query = $this->db->get($this->tb_pelanggan);
		return $query->num_rows();
	}

	public function all_count()
	{
		// $this->db->order_by('id', 'acs');
		return $this->db->get($this->tb_pelanggan)->num_rows();
	}

	public function add($data)
	{
		$this->db->insert($this->tb_pelanggan, $data);
		$id = $this->db->insert_id();

		return $id;
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update($this->tb_pelanggan, $data);
	}

	public function delete($id)
	{
		$this->db->where('pelanggan_id', $id);
		$this->db->delete($this->tb_pesanan);

		$this->db->where('id', $id);
		$this->db->delete($this->tb_pelanggan);
	}

	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->tb_pelanggan);
		return $query->row();
	}

	public function cek($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->tb_pelanggan);
		return $query->num_rows();
	}

	//table pesanan
	public function get_all_pesanan($pelanggan_id)
	{
		$this->db->where('pelanggan_id', $pelanggan_id);
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get($this->tb_pesanan);
		return $query->result();
	}

	public function addPesanan($data)
	{
		$this->db->insert($this->tb_pesanan, $data);
		
		return true;
	}

	public function sum_total_harga($pelanggan_id)
	{
		$this->db->select_sum('total_harga');
		$this->db->where('pelanggan_id', $pelanggan_id);
		$query = $this->db->get($this->tb_pesanan);
		$total = $query->row()->total_harga;

		$data = [
			'nama_produk' => '',
			'harga'       => '',
			'qty'         => 'Total',
			'total_harga' => "Rp. ".number_format($total,2, ".",".")
		];

		return $data;
	}

	public function deletePesanan($pelanggan_id)
	{
		$this->db->where('pelanggan_id', $pelanggan_id);
		$this->db->delete($this->tb_pesanan);
	}

}