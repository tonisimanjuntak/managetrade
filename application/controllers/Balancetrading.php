<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balancetrading extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('Balancetrading_model');
    }


    public function index()
    {
        $data['menu'] = 'balancetrading';
        $this->load->view('balancetrading/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idbalance'] = '';        
        $data['menu'] = 'balancetrading';  
        $this->load->view('balancetrading/form', $data);
    }

    public function edit($idbalance)
    {       
        $idbalance = $this->encrypt->decode($idbalance);

        if ($this->Balancetrading_model->get_by_id($idbalance)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('balancetrading');
            exit();
        };
        $data['idbalance'] =$idbalance;        
        $data['menu'] = 'balancetrading';
        $this->load->view('balancetrading/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Balancetrading_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->namabalance.'<br>'.$rowdata->namabroker;
                $row[] = $rowdata->namajenisasset.'<br>'.$rowdata->jenisbalance;
                $row[] = $rowdata->singkatan;
                $row[] = $rowdata->maxlose;
                $row[] = $rowdata->tglbukaakun;
                $row[] = $rowdata->statusaktif;
                $row[] = '<a href="'.site_url( 'balancetrading/edit/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('balancetrading/delete/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Balancetrading_model->count_all(),
                        "recordsFiltered" => $this->Balancetrading_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idbalance)
    {
        $idbalance = $this->encrypt->decode($idbalance);  
        $rsdata = $this->Balancetrading_model->get_by_id($idbalance);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('balancetrading');
            exit();
        };

        $hapus = $this->Balancetrading_model->hapus($idbalance);
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
        redirect('balancetrading');        

    }

    public function simpan()
    {       
        $idbalance             = $this->input->post('idbalance');
        $namabalance        = $this->input->post('namabalance');
        $idbroker        = $this->input->post('idbroker');
        $idjenisasset        = $this->input->post('idjenisasset');
        $jenisbalance        = $this->input->post('jenisbalance');
        $aturantrading        = $this->input->post('aturantrading');
        $idcurrency        = $this->input->post('idcurrency');
        $maxlose        = $this->input->post('maxlose');
        $tglbukaakun        = $this->input->post('tglbukaakun');
        $statusaktif        = $this->input->post('statusaktif');
        $tglinsert          = date('Y-m-d H:i:s');
        $tglupdate          = date('Y-m-d H:i:s');
        $idpengguna        = $this->session->userdata('idpengguna');;

        if ( $idbalance=='' ) {  
            $idbalance = $this->db->query("SELECT create_idbalance('".date('Y-m-d')."') as idbalance")->row()->idbalance;
            $data = array(
                            'idbalance'   => $idbalance, 
                            'namabalance'   => $namabalance, 
                            'idbroker'   => $idbroker, 
                            'idpengguna'   => $idpengguna, 
                            'idjenisasset'   => $idjenisasset, 
                            'jenisbalance'   => $jenisbalance, 
                            'aturantrading'   => $aturantrading, 
                            'idcurrency'   => $idcurrency, 
                            'topup'   => 0, 
                            'withdraw'   => 0, 
                            'lostprofit'   => 0, 
                            'maxlose'   => $maxlose, 
                            'maxprofit'   => 0, 
                            'jumlahbalance'   => 0, 
                            'tglbukaakun'   => $tglbukaakun, 
                            'statusaktif'   => $statusaktif, 
                            'tglinsert'   => $tglinsert, 
                            'tglupdate'   => $tglupdate, 
                        );
            $simpan = $this->Balancetrading_model->simpan($data);      
        }else{ 

            $data = array(
                            'namabalance'   => $namabalance, 
                            'aturantrading'   => $aturantrading,
                            'maxlose'   => $maxlose, 
                            'statusaktif'   => $statusaktif,                      
                            'tglupdate'   => $tglupdate, 
                        );
            $simpan = $this->Balancetrading_model->update($data, $idbalance);
        }

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
        redirect('balancetrading');   
    }
    
    public function get_edit_data()
    {
        $idbalance = $this->input->post('idbalance');
        $RsData = $this->Balancetrading_model->get_by_id($idbalance)->row();

        $data = array( 
                            'idbalance'     =>  $RsData->idbalance,  
                            'namabalance'     =>  $RsData->namabalance,  
                            'idbroker'     =>  $RsData->idbroker,    
                            'idjenisasset'     =>  $RsData->idjenisasset,  
                            'jenisbalance'     =>  $RsData->jenisbalance,  
                            'aturantrading'     =>  $RsData->aturantrading,  
                            'idcurrency'     =>  $RsData->idcurrency,  
                            'maxlose'     =>  $RsData->maxlose,  
                            'maxprofit'     =>  $RsData->maxprofit,
                            'tglbukaakun'     =>  $RsData->tglbukaakun,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Balancetrading.php */
/* Location: ./application/controllers/Balancetrading.php */