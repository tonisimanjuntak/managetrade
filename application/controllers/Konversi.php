<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konversi extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();

        $this->load->model('Konversi_model');
    }


    public function index()
    {
        $data['menu'] = 'konversi';
        $this->load->view('konversi/listdata', $data);
    }   

    public function tambah()
    {       
        $rscurrency = $this->db->query("select * from currency order by namacurrency");

        $data['rscurrency'] =$rscurrency;        
        $data['idkonversi'] = '';        
        $data['menu'] = 'konversi';  
        $this->load->view('konversi/form', $data);
    }

    public function edit($idkonversi)
    {       
        $idkonversi = $this->encrypt->decode($idkonversi);

        if ($this->Konversi_model->get_by_id($idkonversi)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('konversi');
            exit();
        };
        $rscurrency = $this->db->query("select * from currency order by namacurrency");

        $data['rscurrency'] =$rscurrency;        
        $data['idkonversi'] =$idkonversi;        
        $data['menu'] = 'konversi';
        $this->load->view('konversi/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Konversi_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->idkonversi;
                $row[] = $rowdata->namacurrencyutama;
                $row[] = $rowdata->namacurrencypasangan;
                $row[] = format_rupiah($rowdata->jumlahkonversi);
                $row[] = '<a href="'.site_url( 'konversi/edit/'.$this->encrypt->encode($rowdata->idkonversi) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('konversi/delete/'.$this->encrypt->encode($rowdata->idkonversi) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Konversi_model->count_all(),
                        "recordsFiltered" => $this->Konversi_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idkonversi)
    {
        $idkonversi = $this->encrypt->decode($idkonversi);  
        $rsdata = $this->Konversi_model->get_by_id($idkonversi);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('konversi');
            exit();
        };

        $hapus = $this->Konversi_model->hapus($idkonversi);
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
        redirect('konversi');        

    }

    public function simpan()
    {       
        $idkonversi             = $this->input->post('idkonversi');
        $currencyutama        = $this->input->post('currencyutama');
        $currencypasangan        = $this->input->post('currencypasangan');
        $jumlahkonversi        = untitik($this->input->post('jumlahkonversi'));
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idkonversi=='' ) {  
            $idkonversi = $this->db->query("SELECT create_idkonversi() as idkonversi")->row()->idkonversi;
            $data = array(
                            'idkonversi'   => $idkonversi, 
                            'currencyutama'   => $currencyutama, 
                            'currencypasangan'   => $currencypasangan, 
                            'jumlahkonversi'   => $jumlahkonversi, 
                        );
            $simpan = $this->Konversi_model->simpan($data);      
        }else{ 

            $data = array(
                            'currencyutama'   => $currencyutama, 
                            'currencypasangan'   => $currencypasangan, 
                            'jumlahkonversi'   => $jumlahkonversi,                      );
            $simpan = $this->Konversi_model->update($data, $idkonversi);
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
        redirect('konversi');   
    }
    
    public function get_edit_data()
    {
        $idkonversi = $this->input->post('idkonversi');
        $RsData = $this->Konversi_model->get_by_id($idkonversi)->row();

        $data = array( 
                            'idkonversi'     =>  $RsData->idkonversi,  
                            'currencyutama'     =>  $RsData->currencyutama,  
                            'currencypasangan'     =>  $RsData->currencypasangan,  
                            'jumlahkonversi'     =>  format_rupiah($RsData->jumlahkonversi),  
                        );

        echo(json_encode($data));
    }


}

/* End of file Konversi.php */
/* Location: ./application/controllers/Konversi.php */