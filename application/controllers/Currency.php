<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('Currency_model');
    }

    public function index()
    {
        $data['menu'] = 'currency';
        $this->load->view('currency/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idcurrency'] = '';        
        $data['menu'] = 'currency';  
        $this->load->view('currency/form', $data);
    }

    public function edit($idcurrency)
    {       
        $idcurrency = $this->encrypt->decode($idcurrency);

        if ($this->Currency_model->get_by_id($idcurrency)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('currency');
            exit();
        };
        $data['idcurrency'] =$idcurrency;        
        $data['menu'] = 'currency';
        $this->load->view('currency/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Currency_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->singkatan;
                $row[] = $rowdata->namacurrency;
                $row[] = $rowdata->statusaktif;
                $row[] = '<a href="'.site_url( 'currency/edit/'.$this->encrypt->encode($rowdata->idcurrency) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('currency/delete/'.$this->encrypt->encode($rowdata->idcurrency) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Currency_model->count_all(),
                        "recordsFiltered" => $this->Currency_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idcurrency)
    {
        $idcurrency = $this->encrypt->decode($idcurrency);  
        $rsdata = $this->Currency_model->get_by_id($idcurrency);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('currency');
            exit();
        };

        $hapus = $this->Currency_model->hapus($idcurrency);
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
        redirect('currency');        

    }

    public function simpan()
    {       
        $idcurrency             = $this->input->post('idcurrency');
        $singkatan        = $this->input->post('singkatan');
        $namacurrency        = $this->input->post('namacurrency');
        $statusaktif        = $this->input->post('statusaktif');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idcurrency=='' ) {  
            $idcurrency = $this->db->query("SELECT create_idcurrency('".$singkatan."') as idcurrency")->row()->idcurrency;
            $data = array(
                            'idcurrency'   => $idcurrency, 
                            'singkatan'   => $singkatan, 
                            'namacurrency'   => $namacurrency, 
                            'statusaktif'   => $statusaktif, 
                        );
            $simpan = $this->Currency_model->simpan($data);      
        }else{ 

            $data = array(
                            'singkatan'   => $singkatan, 
                            'namacurrency'   => $namacurrency, 
                            'statusaktif'   => $statusaktif,                      
                        );
            $simpan = $this->Currency_model->update($data, $idcurrency);
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
        redirect('currency');   
    }
    
    public function get_edit_data()
    {
        $idcurrency = $this->input->post('idcurrency');
        $RsData = $this->Currency_model->get_by_id($idcurrency)->row();

        $data = array( 
                            'idcurrency'     =>  $RsData->idcurrency,  
                            'singkatan'     =>  $RsData->singkatan,  
                            'namacurrency'     =>  $RsData->namacurrency,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Currency.php */
/* Location: ./application/controllers/Currency.php */