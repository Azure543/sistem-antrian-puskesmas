<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian extends CI_Controller
{


	function __construct()
	{
		parent::__construct();
		$this->load->library('fpdf');
	}

	public function index()
	{
		$nowDate = date('Y-m-d');

		$this->db->limit('1');
		$this->db->where('tgl_antrian', $nowDate);
		$this->db->order_by('no_antrian', 'DESC');
		$antrian = $this->db->get('antrian')->row();
		if ($antrian) {
			$data['no_antrian'] = $antrian->no_antrian;

		} else {
			$data['no_antrian'] = 0;
		}
		$this->load->view('user/antrian', $data);
	}

	public function getNoAntrian()
	{
		$noAntrian = 1;
		$nowDate = date('Y-m-d');

		$this->db->limit('1');
		$this->db->where('tgl_antrian', $nowDate);
		$this->db->order_by('no_antrian', 'DESC');
		$antrian = $this->db->get('antrian')->row();

		if ($antrian) {
			$no = $antrian->no_antrian;

		} else {
			$no = 0;
		}

		$no = $no + 1;
		$this->db->set('no_antrian', $no);
		$this->db->set('tgl_antrian', $nowDate);
		$getRow = $this->db->insert('antrian');

		$hasil = array("no" => $no);
		echo json_encode($hasil);
	}

	public function saveAntrian()
	{
		$id_poli = $this->input->post('id_poli');
		$no_antrian_poli = substr($this->input->post('no_antrian_poli'), 4);
		$id_pasien = $this->session->userdata('id_pasien');
		$tanggal = date("Y-m-d");

		// echo $tanggal; exit();

		$this->db->set('id_poli', $id_poli);
		$this->db->set('no_antrian_poli', $no_antrian_poli);
		$this->db->set('id_pasien', $id_pasien);
		$this->db->set('tgl_antrian_poli', $tanggal);
		$this->db->insert('antrian_poli');

		$no_antrian = $this->input->post('no_antrian');
		$this->db->set('no_antrian', $no_antrian + 1);
		$this->db->set('tgl_antrian', $tanggal);
		$this->db->insert('antrian');

		redirect(base_url());

	}

	public function cetak($id_antrian_poli = NULL)
	{
		$this->db->limit(1);
		$this->db->order_by('id_antrian', 'DESC');
		$this->db->where('id_antrian_poli', $id_antrian_poli);
		$this->db->join('kategori_poli', 'kategori_poli.id_poli=antrian_poli.id_poli');
		$data['row'] = $this->db->get('antrian_poli')->row();
		$this->load->view('user/cetak', $data);
	}

	public function cetak2()
	{
		require(APPPATH . "/libraries/fpdf.php");
		// print_r(dirname(__FILE__) . '/./tcpdf/tcpdf.php'); die();
		try {
			$pdf = new FPDF('l', 'mm', 'A5');
			// Menambah halaman baru
			$pdf->AddPage();
			// Setting jenis font
			$pdf->SetFont('Arial', 'B', 16);
			// Membuat string
			$pdf->Cell(190, 7, 'Daftar Harga Motor Dealer Maju Motor', 0, 1, 'C');
			// $pdf->SetFont('Arial','B',9);
			$pdf->Cell(190, 7, 'Jl. Abece No. 80 Kodamar, jakarta Utara.', 0, 1, 'C');

			// print_r($pdf); die();
			$path = './assets/pdf/' . date('YmdHis') . ".pdf";
			$pdf->Output('F', $path);
			http_response_code(200);
			header('Content-Length: ' . filesize($path));
			header("Content-Type: application/pdf");
			header('Content-Disposition: attachment; filename="downloaded.pdf"'); // feel free to change the suggested filename
			readfile($path);

			exit;
			// redirect(base_url($path));
			//     		$filename = 'pdf.pdf';
			//     		header('Content-type:application/pdf');
			// header('Content-disposition: inline; filename="'.$filename.'"');
			// header('content-Transfer-Encoding:binary');
			// header('Accept-Ranges:bytes');
			// $pdf->Output('I',$filename);
		} catch (Exception $e) {
			print_r($e->getMessage());
		}
	}
}
