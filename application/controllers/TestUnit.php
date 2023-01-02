<?php

class TestUnit extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	private function tampil_nomor_antrian()
	{
		$nowDate = date('Y-m-d');

		$this->db->limit('1');
		$this->db->where('tgl_antrian', $nowDate);
		$this->db->order_by('no_antrian', 'DESC');
		$antrian = $this->db->get('antrian')->row();


		if ($antrian) {
			return is_numeric($data['no_antrian'] = $antrian->no_antrian);
		}
	}

	public function test_tampil_nomor_antrian()
	{
		$test = $this->tampil_nomor_antrian();
		$expected_result = 1;
		$test_name = "Menampilkan nomor antrian";

		echo $this->unit->run($test, $expected_result, $test_name);
	}
}
