<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entrytrade extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->is_login();

        $this->load->model('Entrytrade_model');
        $this->load->model('Balancetrading_model');
    }


    public function index()
    {
        $rsbalance = $this->db->query("select * from v_balancetrading where statusaktif='Aktif' order by tglbukaakun");

        $data['rsbalance'] = $rsbalance;
        $data['menu'] = 'entrytrade';
        $this->load->view('entrytrade/listdata', $data);
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
            redirect('entrytrade');
            exit();
        };


        $data['idtopup'] = '';        
        $data['rowbalance'] = $rsbalance->row();        
        $data['idbalance'] = $idbalance;        
        $data['menu'] = 'entrytrade';  
        $this->load->view('entrytrade/form', $data);
    }


    public function datatablesource()
    {
        $RsData = $this->Entrytrade_model->get_datatables();
        $no = $_POST['start'];
        $data = array();
        $idbalance = $_POST['idbalance'];

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tgltopup);
                $row[] = $rowdata->namabalance.'<br>'.$rowdata->namabroker;
                $row[] = $rowdata->singkatan.' '.format_decimal($rowdata->jumlahtopup,2);
                $row[] = '<a href="'.site_url('entrytrade/delete/'.$this->encrypt->encode($rowdata->idtopup).'/'.$this->encrypt->encode($rowdata->idbalance) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Entrytrade_model->count_all(),
                        "recordsFiltered" => $this->Entrytrade_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idtopup, $idbalance)
    {
        $idtopup = $this->encrypt->decode($idtopup);  
        $idbalance = $this->encrypt->decode($idbalance);  

        $rsdata = $this->Entrytrade_model->get_by_id($idtopup);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('entrytrade');
            exit();
        };

        $hapus = $this->Entrytrade_model->hapus($idtopup);
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
        redirect('entrytrade/tambah/'.$this->encrypt->encode($idbalance));        

    }

    public function simpan()
    {       
        $idtopup             = $this->input->post('idtopup');
        $tgltopup        = $this->input->post('tgltopup');
        $idbalance        = $this->input->post('idbalance');
        $jumlahtopup        = untitik($this->input->post('jumlahtopup'));
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
            redirect('entrytrade');
        }

        $idtopup = $this->db->query("SELECT create_idtopup('".date('Y-m-d')."', '".$idbalance."') as idtopup")->row()->idtopup;

        $data = array(
                        'idtopup'   => $idtopup, 
                        'tgltopup'   => $tgltopup, 
                        'idbalance'   => $idbalance, 
                        'jumlahtopup'   => $jumlahtopup, 
                        'idpengguna'   => $idpengguna, 
                        'tglinsert'   => $tglinsert, 
                        'tglupdate'   => $tglupdate, 
                    );
        $simpan = $this->Entrytrade_model->simpan($data);      

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
        redirect('entrytrade');   
    }

}

/* End of file Entrytrade.php */
/* Location: ./application/controllers/Entrytrade.php */