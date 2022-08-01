<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisasset extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('Jenisasset_model');
    }


    public function index()
    {
        $data['menu'] = 'jenisasset';
        $this->load->view('jenisasset/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idjenisasset'] = '';        
        $data['menu'] = 'jenisasset';  
        $this->load->view('jenisasset/form', $data);
    }

    public function edit($idjenisasset)
    {       
        $idjenisasset = $this->encrypt->decode($idjenisasset);

        if ($this->Jenisasset_model->get_by_id($idjenisasset)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('jenisasset');
            exit();
        };
        $data['idjenisasset'] =$idjenisasset;        
        $data['menu'] = 'jenisasset';
        $this->load->view('jenisasset/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Jenisasset_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->idjenisasset;
                $row[] = $rowdata->namajenisasset;
                $row[] = '<a href="'.site_url( 'jenisasset/edit/'.$this->encrypt->encode($rowdata->idjenisasset) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('jenisasset/delete/'.$this->encrypt->encode($rowdata->idjenisasset) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Jenisasset_model->count_all(),
                        "recordsFiltered" => $this->Jenisasset_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idjenisasset)
    {
        $idjenisasset = $this->encrypt->decode($idjenisasset);  
        $rsdata = $this->Jenisasset_model->get_by_id($idjenisasset);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('jenisasset');
            exit();
        };

        $hapus = $this->Jenisasset_model->hapus($idjenisasset);
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
        redirect('jenisasset');        

    }

    public function simpan()
    {       
        $idjenisasset             = $this->input->post('idjenisasset');
        $namajenisasset        = $this->input->post('namajenisasset');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idjenisasset=='' ) {  
            $idjenisasset = $this->db->query("SELECT create_idjenisasset() as idjenisasset")->row()->idjenisasset;
            $data = array(
                            'idjenisasset'   => $idjenisasset, 
                            'namajenisasset'   => $namajenisasset, 
                        );
            $simpan = $this->Jenisasset_model->simpan($data);      
        }else{ 

            $data = array(
                            'namajenisasset'   => $namajenisasset,                      );
            $simpan = $this->Jenisasset_model->update($data, $idjenisasset);
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
        redirect('jenisasset');   
    }
    
    public function get_edit_data()
    {
        $idjenisasset = $this->input->post('idjenisasset');
        $RsData = $this->Jenisasset_model->get_by_id($idjenisasset)->row();

        $data = array( 
                            'idjenisasset'     =>  $RsData->idjenisasset,  
                            'namajenisasset'     =>  $RsData->namajenisasset,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Jenisasset.php */
/* Location: ./application/controllers/Jenisasset.php */