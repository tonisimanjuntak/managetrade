<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	

	public function __construct()
	{
		parent::__construct();
		$this->is_login();
		$this->load->model('Homeforex_model');
	}

	public function index()
	{
		$data["menu"] = "home";	
		$this->load->view("home", $data);
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

		$saldoawal  = $this->Homeforex_model->get_saldo_awal($tanggalawalbulan);

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


	public function loadpairwinrate()
	{
		$bulansekarang = date('m');
		$tahunsekarang = date('Y');
		$tanggalawalbulan = date('Y-m-01');
		$datapairwinrate = array();

		$rspairwinrate = $this->Homeforex_model->loadpairwinrate();
		if ($rspairwinrate->num_rows()>0) {
			foreach ($rspairwinrate->result() as $row) {
				if ($row->jumlahprofit==0) {
					$persenwinrate = 0;
				}else{
					$persenwinrate = ($row->jumlahprofit/($row->jumlahprofit+$row->jumlahlost))*100;
				}
				$persenwinrate = format_decimal($persenwinrate,2);

				array_push($datapairwinrate, 
							array(
								  'idpair' => $row->idpair, 
								  'namapair' => $row->namapair, 
								  'persenwinrate' => $persenwinrate, 
								  'jumlahtrading' => $row->jumlahtrading, 
								));
			}
		}

		echo json_encode($datapairwinrate);
	}


	public function loadstrategywinrate()
	{		
		$rsstrategy = $this->Homeforex_model->loadstrategywinrate();
		$datastrategy = array();
		$winrate =0;

		if ($rsstrategy->num_rows()>0) {
			$jumlahstrategy = $rsstrategy->num_rows();
			foreach ($rsstrategy->result() as $row) {
				if ($row->jumlahwin==0) {
					$winrate = 0;
				}else{
					$winrate = ($row->jumlahwin/ ($row->jumlahwin+$row->jumlahlost)) * 100;					
				}


				array_push($datastrategy, array(
											'idjenisstrategy' => $row->idjenisstrategy, 
											'namajenisstrategy' => $row->namajenisstrategy, 
											'jumlahwin' => $row->jumlahwin, 
											'jumlahlost' => $row->jumlahlost, 
											'winrate' => $winrate, 
											'jumlahtrading' => $row->jumlahwin+$row->jumlahlost, 
											'jumlahstrategy' => $jumlahstrategy, 
											));
			}
		}
		echo json_encode($datastrategy);
	}




}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */