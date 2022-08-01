<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homeforex_model extends CI_Model {

	public function get_profit_sebulan($bulan, $tahun)
	{
		$lostprofit = $this->db->query("
				select sum(lostprofit) as lostprofit from trade 
					where tglexittrade is not null and month(tglexittrade)='".$bulan."' and year(tglexittrade) = '".$tahun."' 
				")->row()->lostprofit;
		if ($lostprofit) {
			return $lostprofit;
		}else{
			return 0;
		}
	}

	public function get_saldo_awal($tanggal)
	{
		$lostprofit = $this->db->query("
				select sum(lostprofit) as lostprofit from v_trade 
					where tglexittrade is not null and idjenisasset='03' and tglexittrade >='".$tanggal."'
				")->row()->lostprofit;
		if ($lostprofit=='') {
			$lostprofit = 0;
		}

		$saldosekarang = $this->db->query("
			select sum(jumlahbalance) as saldosekarang from balancetrading where idjenisasset='03'
			")->row()->saldosekarang;
		if ($saldosekarang=='') {
			$saldosekarang = 0;
		}

		$saldoawal = $saldosekarang - $lostprofit;
		return $saldoawal;
	}

	public function get_jumlah_trading($bulan, $tahun)
	{
		$jumlahtrading = $this->db->query("
				select count(*) as jumlahtrading from v_trade 
					where tglexittrade is not null and month(tglexittrade)='".$bulan."' and year(tglexittrade) = '".$tahun."' 
				")->row()->jumlahtrading;
		if ($jumlahtrading) {
			return $jumlahtrading;
		}else{
			return 0;
		}
	}

	public function get_winrate($bulan, $tahun)
	{
		$wintrade = $this->db->query("
				select count(*) as wintrade from v_trade 
					where tglexittrade is not null and month(tglexittrade)='".$bulan."' and year(tglexittrade) = '".$tahun."' and lostprofit>0  and idjenisasset='03'
				")->row()->wintrade;
		if ($wintrade=='') {
			$wintrade = 0;
		}

		$losttrade = $this->db->query("
				select count(*) as losttrade from v_trade 
					where tglexittrade is not null and month(tglexittrade)='".$bulan."' and year(tglexittrade) = '".$tahun."' and lostprofit<0 and idjenisasset='03'
				")->row()->losttrade;
		if ($losttrade=='') {
			$losttrade = 0;
		}

		if ($wintrade==0) {
			$persenwinrate = 0;
		}else{
			$persenwinrate = ($wintrade/ ($wintrade+$losttrade)) * 100;
		}
		return $persenwinrate;
	}


	public function get_melanggar_aturan($bulan, $tahun)
	{

		$rsbalancetrading = $this->db->query("
					select * from v_balancetrading where idjenisasset='03'
				");
		$jumlahpelanggaran = 0;

		if ($rsbalancetrading->num_rows()>0) {
			foreach ($rsbalancetrading->result() as $row) {
				$melanggaraturan = $this->db->query("
						select count(*) as melanggaraturan from v_trade 
							where tglexittrade is not null and month(tglexittrade)='".$bulan."' and year(tglexittrade) = '".$tahun."' and lostprofit<0 and abs(lostprofit) > ".$row->maxlose." and idbalance='".$row->idbalance."'
						")->row()->melanggaraturan;
				if ($melanggaraturan=='') {
					$melanggaraturan = 0;
				}
				$jumlahpelanggaran += $melanggaraturan;
			}
		}
		
		return $jumlahpelanggaran;
	}

	public function get_profit_harian($bulan, $tahun)
	{
		$rsprofitharian = $this->db->query("
				SELECT 
					SUM(CASE WHEN DAY(tglexittrade)=1 THEN lostprofit ELSE 0 END ) AS tgl1,
					SUM(CASE WHEN DAY(tglexittrade)=2 THEN lostprofit ELSE 0 END ) AS tgl2,
					SUM(CASE WHEN DAY(tglexittrade)=3 THEN lostprofit ELSE 0 END ) AS tgl3,
					SUM(CASE WHEN DAY(tglexittrade)=4 THEN lostprofit ELSE 0 END ) AS tgl4,
					SUM(CASE WHEN DAY(tglexittrade)=5 THEN lostprofit ELSE 0 END ) AS tgl5,
					SUM(CASE WHEN DAY(tglexittrade)=6 THEN lostprofit ELSE 0 END ) AS tgl6,
					SUM(CASE WHEN DAY(tglexittrade)=7 THEN lostprofit ELSE 0 END ) AS tgl7,
					SUM(CASE WHEN DAY(tglexittrade)=8 THEN lostprofit ELSE 0 END ) AS tgl8,
					SUM(CASE WHEN DAY(tglexittrade)=9 THEN lostprofit ELSE 0 END ) AS tgl9,
					SUM(CASE WHEN DAY(tglexittrade)=10 THEN lostprofit ELSE 0 END ) AS tgl10,
					SUM(CASE WHEN DAY(tglexittrade)=11 THEN lostprofit ELSE 0 END ) AS tgl11,
					SUM(CASE WHEN DAY(tglexittrade)=12 THEN lostprofit ELSE 0 END ) AS tgl12,
					SUM(CASE WHEN DAY(tglexittrade)=13 THEN lostprofit ELSE 0 END ) AS tgl13,
					SUM(CASE WHEN DAY(tglexittrade)=14 THEN lostprofit ELSE 0 END ) AS tgl14,
					SUM(CASE WHEN DAY(tglexittrade)=15 THEN lostprofit ELSE 0 END ) AS tgl15,
					SUM(CASE WHEN DAY(tglexittrade)=16 THEN lostprofit ELSE 0 END ) AS tgl16,
					SUM(CASE WHEN DAY(tglexittrade)=17 THEN lostprofit ELSE 0 END ) AS tgl17,
					SUM(CASE WHEN DAY(tglexittrade)=18 THEN lostprofit ELSE 0 END ) AS tgl18,
					SUM(CASE WHEN DAY(tglexittrade)=19 THEN lostprofit ELSE 0 END ) AS tgl19,
					SUM(CASE WHEN DAY(tglexittrade)=20 THEN lostprofit ELSE 0 END ) AS tgl20,
					SUM(CASE WHEN DAY(tglexittrade)=21 THEN lostprofit ELSE 0 END ) AS tgl21,
					SUM(CASE WHEN DAY(tglexittrade)=22 THEN lostprofit ELSE 0 END ) AS tgl22,
					SUM(CASE WHEN DAY(tglexittrade)=23 THEN lostprofit ELSE 0 END ) AS tgl23,
					SUM(CASE WHEN DAY(tglexittrade)=24 THEN lostprofit ELSE 0 END ) AS tgl24,
					SUM(CASE WHEN DAY(tglexittrade)=25 THEN lostprofit ELSE 0 END ) AS tgl25,
					SUM(CASE WHEN DAY(tglexittrade)=26 THEN lostprofit ELSE 0 END ) AS tgl26,
					SUM(CASE WHEN DAY(tglexittrade)=27 THEN lostprofit ELSE 0 END ) AS tgl27,
					SUM(CASE WHEN DAY(tglexittrade)=28 THEN lostprofit ELSE 0 END ) AS tgl28,
					SUM(CASE WHEN DAY(tglexittrade)=29 THEN lostprofit ELSE 0 END ) AS tgl29,
					SUM(CASE WHEN DAY(tglexittrade)=30 THEN lostprofit ELSE 0 END ) AS tgl30,
					SUM(CASE WHEN DAY(tglexittrade)=31 THEN lostprofit ELSE 0 END ) AS tgl31
					FROM  v_trade
						WHERE MONTH(tglexittrade)=".$bulan." AND YEAR(tglexittrade)=".$tahun."
							AND idjenisasset='03'
			");
		return $rsprofitharian;
	}


	public function loadpairwinrate()
	{
		$query = "
			SELECT pair.idpair, pair.namapair, 
				SUM(CASE WHEN lostprofit > 0 THEN 1 ELSE 0 END) AS jumlahprofit,
				SUM(CASE WHEN lostprofit < 0 THEN 1 ELSE 0 END) AS jumlahlost,
				SUM(CASE WHEN lostprofit <> 0 THEN 1 ELSE 0 END) AS jumlahtrading
				FROM pair 
				LEFT JOIN trade ON trade.idpair = pair.idpair AND istradeclose='Ya' AND lostprofit<>0
			WHERE idjenisasset='03' and pair.statusaktif='Aktif'
				GROUP BY pair.idpair, pair.namapair
				ORDER BY jumlahtrading DESC, namapair
				LIMIT 4
			";
		return $this->db->query($query);
	}

	public function loadstrategywinrate()
	{
		$rsstrategy = $this->db->query("
			SELECT jenisstrategy.idjenisstrategy, jenisstrategy.namajenisstrategy,
					SUM(CASE WHEN lostprofit > 0 THEN 1 ELSE 0 END) AS jumlahwin,
					SUM(CASE WHEN lostprofit < 0 THEN 1 ELSE 0 END) AS jumlahlost
					FROM jenisstrategy
					LEFT JOIN trade ON trade.idjenisstrategy = jenisstrategy.idjenisstrategy AND trade.istradeclose='Ya'
					WHERE jenisstrategy.statusaktif='Aktif' and jenisstrategy.idjenisstrategy <> 'ICP01'
					GROUP BY jenisstrategy.idjenisstrategy, jenisstrategy.namajenisstrategy 
					ORDER BY jenisstrategy.namajenisstrategy 
							");
		return $rsstrategy;
	}
	
}

/* End of file Homeforex_model.php */
/* Location: ./application/models/Homeforex_model.php */