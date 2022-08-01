<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardtrading extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->is_login();
		$this->load->model('Homeforex_model');
	}

	public function index()
	{
		$data["menu"] = "dashboardtrading";	
		$this->load->view("dashboardtrading", $data);
	}

	public function loadinfobox()
	{
		$bulansekarang = date('m');
		$tahunsekarang = date('Y');
		$tanggalawalbulan = date('Y-m-01');

		$lostprofit = $this->Homeforex_model->get_profit_sebulan($bulansekarang, $tahunsekarang);
		$saldoawal  = $this->Homeforex_model->get_saldo_awal($tanggalawalbulan);
		$persenprofit = format_decimal($lostprofit/$saldoawal*100,2);

		$jumlahtrading = $this->Homeforex_model->get_jumlah_trading($bulansekarang, $tahunsekarang);
		$profitrate = $this->Homeforex_model->get_winrate($bulansekarang, $tahunsekarang);
		$jumlahpelanggaran = $this->Homeforex_model->get_melanggar_aturan($bulansekarang, $tahunsekarang);

		echo json_encode( array('persenprofit' => $persenprofit, 'jumlahtrading' => $jumlahtrading, 'profitrate' => $profitrate, 'breakrole' => $jumlahpelanggaran) );

	}

	public function loadgrafikprofit()
	{
		$bulansekarang = date('m');
		$tahunsekarang = date('Y');
		$tanggalawalbulan = date('Y-m-01');
		$idpengguna = $this->session->userdata('idpengguna');

		$rstargetbalance  = $this->db->query("
									select * from v_targetbalance where statustarget='Masih Berjalan' limit 1
								");
		$rowtargetbalance = $rstargetbalance->row();

		$saldoawal = $rowtargetbalance->startingbalance;
		
		$dataprofit = array();
		$datatanggal = array();


		$rowprofit = $this->Homeforex_model->get_profit_harian($bulansekarang, $tahunsekarang)->row();
		$saldoberjalan = $saldoawal;
		$jumlahprofit = 0;
		$jumlahlost = 0;

		for ($tgl=1; $tgl <= 31 ; $tgl++) { 
			$field = 'tgl'.$tgl;
			$saldoberjalan += $rowprofit->$field;
			$datatanggal[] = $tgl;
			$dataprofit[] = $saldoberjalan;
			
			if ($rowprofit->$field>0) {
				$jumlahprofit += $rowprofit->$field;
			}

			if ($rowprofit->$field<0) {
				$jumlahlost += $rowprofit->$field;
			}
		}
		
		echo json_encode( array('dataprofit' => $dataprofit, 'datatanggal' => $datatanggal, 'jumlahprofit' => $jumlahprofit, 'jumlahlost' => $jumlahlost ) );
	}



}

/* End of file Dashboardtrading.php */
/* Location: ./application/controllers/Dashboardtrading.php */