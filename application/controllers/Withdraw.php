<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();

        $this->load->model('Withdraw_model');
        $this->load->model('Balancetrading_model');
    }


    public function index()
    {
        $rsbalance = $this->db->query("select * from v_balancetrading 
                        where statusaktif='Aktif' and idpengguna='".$this->session->userdata('idpengguna')."' and idjenisasset='03'
                        order by tglbukaakun");

        $data['rsbalance'] = $rsbalance;
        $data['menu'] = 'withdraw';
        $this->load->view('withdraw/listdata', $data);
    }   

    public function tambah($idbalance)
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
            redirect('withdraw');
            exit();
        };


        $data['idwithdraw'] = '';        
        $data['rowbalance'] = $rsbalance->row();        
        $data['idbalance'] = $idbalance;        
        $data['menu'] = 'withdraw';  
        $this->load->view('withdraw/form', $data);
    }


    public function datatablesource()
    {
        $RsData = $this->Withdraw_model->get_datatables();
        $no = $_POST['start'];
        $data = array();
        $idbalance = $_POST['idbalance'];

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tglwithdraw);
                $row[] = $rowdata->namabalance.'<br>'.$rowdata->namabroker;
                $row[] = $rowdata->singkatan.' '.format_decimal($rowdata->jumlahwithdraw,2);
                $row[] = '<a href="'.site_url('withdraw/delete/'.$this->encrypt->encode($rowdata->idwithdraw).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Withdraw_model->count_all(),
                        "recordsFiltered" => $this->Withdraw_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idwithdraw, $idbalance)
    {
        $idwithdraw = $this->encrypt->decode($idwithdraw);  
        $idbalance = $this->encrypt->decode($idbalance);  

        $rsdata = $this->Withdraw_model->get_by_id($idwithdraw);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('withdraw');
            exit();
        };

        $hapus = $this->Withdraw_model->hapus($idwithdraw);
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
        redirect('withdraw/tambah/'.$this->encrypt->encode($idbalance));        

    }

    public function simpan()
    {       
        $idwithdraw             = $this->input->post('idwithdraw');
        $tglwithdraw        = $this->input->post('tglwithdraw');
        $idbalance        = $this->input->post('idbalance');
        $jumlahwithdraw        = untitik($this->input->post('jumlahwithdraw'));
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
            redirect('withdraw');
        }

        $idwithdraw = $this->db->query("SELECT create_idwithdraw('".date('Y-m-d')."', '".$idbalance."') as idwithdraw")->row()->idwithdraw;

        $data = array(
                        'idwithdraw'   => $idwithdraw, 
                        'tglwithdraw'   => $tglwithdraw, 
                        'idbalance'   => $idbalance, 
                        'jumlahwithdraw'   => $jumlahwithdraw, 
                        'idpengguna'   => $idpengguna, 
                        'tglinsert'   => $tglinsert, 
                        'tglupdate'   => $tglupdate, 
                    );
        $simpan = $this->Withdraw_model->simpan($data);      

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
        redirect('withdraw');   
    }


}

/* End of file Withdraw.php */
/* Location: ./application/controllers/Withdraw.php */