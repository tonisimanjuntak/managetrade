<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trade extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();

        $this->load->model('Trade_model');
        $this->load->model('Tradenonforex_model');
        $this->load->model('Balancetrading_model');
        $this->load->library('image_lib');
    }


    public function index()
    {
        $rsbalance = $this->db->query("
                    select * from v_balancetrading where statusaktif='Aktif' and 
                        idpengguna='".$this->session->userdata('idpengguna')."' 
                        order by tglbukaakun
                    ");

        $data['rsbalance'] = $rsbalance;
        $data['menu'] = 'trade';
        $this->load->view('trade/listdata', $data);
    }   

    public function entrytrade($idbalance)
    {       
        $idbalance = $this->encrypt->decode($idbalance);
        $rsbalance = $this->Balancetrading_model->get_by_id($idbalance);
        if ( $rsbalance->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('trade');
            exit();
        };

        $rowbalance = $rsbalance->row();
        $idjenisasset = $rowbalance->idjenisasset;

        $data['idtrade'] = '';        
        $data['rowbalance'] = $rowbalance;        
        $data['idbalance'] = $idbalance;        
        $data['menu'] = 'trade';  

        switch ($idjenisasset) {
            case '03':
                $this->load->view('trade/forex', $data);
                break;
            
            default:
                $this->load->view('trade/nonforex', $data);
                # code...
                break;
        }
    }


    public function exittrade($idtrade, $idbalance)
    {       
        $idtrade = $this->encrypt->decode($idtrade);
        $idbalance = $this->encrypt->decode($idbalance);


        $rstrade = $this->Trade_model->get_by_id($idtrade);
        if ( $rstrade->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data Trade tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('trade');
            exit();
        };


        $rsbalance = $this->Balancetrading_model->get_by_id($idbalance);
        if ( $rsbalance->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data Balance tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('trade');
            exit();
        };

        $rowtrade = $rstrade->row();
        $rowbalance = $rsbalance->row();
        $idjenisasset = $rowbalance->idjenisasset;

        $data['idtrade'] = '';        
        $data['rowbalance'] = $rowbalance;        
        $data['rowtrade'] = $rowtrade;        
        $data['idbalance'] = $idbalance;        
        $data['menu'] = 'trade';  

        switch ($idjenisasset) {
            case '03':
                break;
            
            default:
                # code...
                break;
        }
        $this->load->view('trade/forexexit', $data);
    }


    public function datatablesource()
    {
        $RsData = $this->Trade_model->get_datatables();
        $no = $_POST['start'];
        $data = array();
        $idbalance = $_POST['idbalance'];

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $fotoentry = '';
                $fotoexit = '';
                if (!empty($rowdata->fotoentry)) {
                    $fotoentry = '<br><a href="'.base_url('uploads/trade/entry/'.$rowdata->fotoentry).'" target="_blank">Gambar Entry</a>';
                }
                if (!empty($rowdata->fotoexit)) {
                    $fotoexit = '<br><a href="'.base_url('uploads/trade/exit/'.$rowdata->fotoexit).'" target="_blank">Gambar Exit</a>';
                }
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tglentrytrade).'<br>'.$rowdata->namapair;
                $row[] = $rowdata->namajenisstrategy;
                $row[] = format_decimal($rowdata->qty,2);
                $row[] = format_decimal($rowdata->entryprice,6).$fotoentry;
                $row[] = format_decimal($rowdata->exitprice,6).$fotoexit;
                $row[] = format_decimal($rowdata->lostprofit,2);
                $row[] = $rowdata->istradeclose;
                $row[] = $rowdata->hasiltrade;
                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url('trade/exittrade/'.$this->encrypt->encode($rowdata->idtrade).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-warning">Exit Trade</a>
                      <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('trade/delete/'.$this->encrypt->encode($rowdata->idtrade).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>

                ';
                //$row[] = '<a href="'.site_url('trade/delete/'.$this->encrypt->encode($rowdata->idtrade).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Trade_model->count_all(),
                        "recordsFiltered" => $this->Trade_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }


    public function datatablesource_nonforex()
    {
        $RsData = $this->Tradenonforex_model->get_datatables();
        $no = $_POST['start'];
        $data = array();
        $idbalance = $_POST['idbalance'];

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $fotoentry = '';
                $fotoexit = '';
                if (!empty($rowdata->fotoentry)) {
                    $fotoentry = '<br><a href="'.base_url('uploads/trade/entry/'.$rowdata->fotoentry).'" target="_blank">Gambar Entry</a>';
                }
                if (!empty($rowdata->fotoexit)) {
                    $fotoexit = '<br><a href="'.base_url('uploads/trade/exit/'.$rowdata->fotoexit).'" target="_blank">Gambar Exit</a>';
                }
                if ($rowdata->exitprice=='' || $rowdata->exitprice==0) {
                    $exitprice = $rowdata->pricenow;
                }else{
                    $exitprice = $rowdata->exitprice;
                }
                if ($rowdata->lostprofit!=0) {
                    $lostprofit = $rowdata->lostprofit;
                }else{
                    $lostprofit = ($rowdata->qty*$exitprice) - ($rowdata->qty*$rowdata->entryprice);
                }

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tglentrytrade).'<br>'.$rowdata->namapair;
                $row[] = $rowdata->namajenisstrategy;
                $row[] = format_decimal($rowdata->qty,2);
                $row[] = format_decimal($rowdata->entryprice,6).$fotoentry;
                $row[] = format_decimal($exitprice,6).$fotoexit;
                $row[] = format_decimal($rowdata->qty*$exitprice,2);
                $row[] = format_decimal($lostprofit,2);
                $row[] = $rowdata->istradeclose;
                $row[] = $rowdata->hasiltrade;
                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url('trade/exittrade/'.$this->encrypt->encode($rowdata->idtrade).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-warning">Exit Trade</a>
                      <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('trade/delete/'.$this->encrypt->encode($rowdata->idtrade).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>

                ';
                //$row[] = '<a href="'.site_url('trade/delete/'.$this->encrypt->encode($rowdata->idtrade).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Tradenonforex_model->count_all(),
                        "recordsFiltered" => $this->Tradenonforex_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idtrade, $idbalance)
    {
        $idtrade = $this->encrypt->decode($idtrade);  
        $idbalance = $this->encrypt->decode($idbalance);  

        $rsdata = $this->Trade_model->get_by_id($idtrade);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('trade');
            exit();
        };

        $hapus = $this->Trade_model->hapus($idtrade);
        if ($hapus) {       
            $pesan = '<div>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Berhasil!</strong> Data berhasil dihapus!
                        </div>
                    </div>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Data gagal dihapus karena sudah digunakan! <br>
                        </div>
                    </div>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('trade/entrytrade/'.$this->encrypt->encode($idbalance));        

    }

    public function simpanforex()
    {       
        $idtrade             = $this->input->post('idtrade');
        $tglentrytrade        = $this->input->post('tglentrytrade');
        $idbalance        = $this->input->post('idbalance');
        $idpair        = $this->input->post('idpair');
        $qty        = untitik($this->input->post('qty'));
        $entryprice        = untitik($this->input->post('entryprice'));
        $idjenisstrategy        = $this->input->post('idjenisstrategy');
        $idpengguna        = $this->session->userdata('idpengguna');
        $tglinsert          = date('Y-m-d H:i:s');
        $tglupdate          = date('Y-m-d H:i:s');

        if (empty($idpengguna)) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Session telah berakhir! <br>
                        </div>
                    </div>';
        
            $this->session->set_flashdata('pesan', $pesan);
            redirect(site_url());
        }

        if (empty($idbalance)) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Nama balance tidak ditemukan! <br>                            
                        </div>
                    </div>';
        
            $this->session->set_flashdata('pesan', $pesan);
            redirect('trade/entrytrade/'.$this->encrypt->encode($idbalance));   
        }

        $idtrade = $this->db->query("SELECT create_idtrade('".date('Y-m-d')."', '".$idbalance."') as idtrade")->row()->idtrade;
        $fotoentry               = $this->upload_foto($_FILES, "fotoentry", "trade/entry");     

        $data = array(
                        'idtrade'   => $idtrade, 
                        'tglentrytrade'   => $tglentrytrade, 
                        'idbalance'   => $idbalance, 
                        'idpair'   => $idpair, 
                        'qty'   => $qty, 
                        'entryprice'   => $entryprice, 
                        'exitprice'   => 0, 
                        'lostprofit'   => 0, 
                        'istradeclose'   => 'Tidak', 
                        'idjenisstrategy'   => $idjenisstrategy, 
                        'fotoentry'   => $fotoentry, 
                        'idpengguna'   => $idpengguna, 
                        'tglinsert'   => $tglinsert, 
                        'tglupdate'   => $tglupdate, 
                    );
        $simpan = $this->Trade_model->simpan($data);      

        if ($simpan) {
            $pesan = '<div>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Berhasil!</strong> Data berhasil disimpan!
                        </div>
                    </div>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Data gagal disimpan! <br>
                            Pesan Error : '.$eror['code'].' '.$eror['message'].'
                        </div>
                    </div>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('trade/entrytrade/'.$this->encrypt->encode($idbalance));   
    }


    public function simpanforexexit()
    {       
        $idtrade             = $this->input->post('idtrade');
        $idbalance        = $this->input->post('idbalance');

        $tglexittrade        = $this->input->post('tglexittrade');
        $hasiltrade        = $this->input->post('hasiltrade');
        $exitprice        = untitik($this->input->post('exitprice'));
        $lostprofit        = untitik($this->input->post('lostprofit'));
        $tglupdate          = date('Y-m-d H:i:s');

        if (empty($this->session->userdata('idpengguna'))) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Session telah berakhir! <br>
                        </div>
                    </div>';
        
            $this->session->set_flashdata('pesan', $pesan);
            redirect(site_url());
        }

        if (empty($idtrade)) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Trade tidak ditemukan! <br>                            
                        </div>
                    </div>';
        
            $this->session->set_flashdata('pesan', $pesan);
            redirect('trade');
        }

        if (empty($idbalance)) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Nama balance tidak ditemukan! <br>                            
                        </div>
                    </div>';
        
            $this->session->set_flashdata('pesan', $pesan);
            redirect('trade/entrytrade/'.$this->encrypt->encode($idbalance));   
        }

        $fotoexit_lama = $this->input->post('fotoexit_lama');
        $fotoexit = $this->update_upload_foto($_FILES, "fotoexit", $fotoexit_lama, "trade/exit");
        if ($hasiltrade=='Lost') {
            $lostprofit = $lostprofit * -1; //lost berarti minus
        }
        $data = array(
                        'tglexittrade'   => $tglexittrade, 
                        'hasiltrade'   => $hasiltrade, 
                        'exitprice'   => $exitprice, 
                        'lostprofit'   => $lostprofit, 
                        'fotoexit'   => $fotoexit, 
                        'istradeclose'   => 'Ya', 
                        'tglupdate'   => $tglupdate, 
                    );
        $simpan = $this->Trade_model->update($data, $idtrade);      

        if ($simpan) {
            $pesan = '<div>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Berhasil!</strong> Data berhasil disimpan!
                        </div>
                    </div>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Data gagal disimpan! <br>
                            Pesan Error : '.$eror['code'].' '.$eror['message'].'
                        </div>
                    </div>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('trade/entrytrade/'.$this->encrypt->encode($idbalance));   
    }




}

/* End of file Trade.php */
/* Location: ./application/controllers/Trade.php */