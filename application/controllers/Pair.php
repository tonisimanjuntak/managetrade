<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pair extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->is_login();

        $this->load->model('Pair_model');
    }

    public function index()
    {
        $data['menu'] = 'pair';
        $this->load->view('pair/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idpair'] = '';        
        $data['menu'] = 'pair';  
        $this->load->view('pair/form', $data);
    }

    public function edit($idpair)
    {       
        $idpair = $this->encrypt->decode($idpair);

        if ($this->Pair_model->get_by_id($idpair)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('pair');
            exit();
        };
        $data['idpair'] =$idpair;        
        $data['menu'] = 'pair';
        $this->load->view('pair/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Pair_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->idpair;
                $row[] = $rowdata->namapair;
                $row[] = $rowdata->namajenisasset;
                $row[] = format_decimal($rowdata->pricenow,6);
                $row[] = $rowdata->statusaktif;
                $row[] = '<a href="'.site_url( 'pair/edit/'.$this->encrypt->encode($rowdata->idpair) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('pair/delete/'.$this->encrypt->encode($rowdata->idpair) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pair_model->count_all(),
                        "recordsFiltered" => $this->Pair_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idpair)
    {
        $idpair = $this->encrypt->decode($idpair);  
        $rsdata = $this->Pair_model->get_by_id($idpair);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pair');
            exit();
        };

        $hapus = $this->Pair_model->hapus($idpair);
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
        redirect('pair');        

    }

    public function simpan()
    {       
        $idpair             = $this->input->post('idpair');
        $namapair        = $this->input->post('namapair');
        $idjenisasset        = $this->input->post('idjenisasset');
        $statusaktif        = $this->input->post('statusaktif');
        $pricenow        = untitik($this->input->post('pricenow'));
        $tglinsert          = date('Y-m-d H:i:s');
        $tglupdate          = date('Y-m-d H:i:s');

        if ( $idpair=='' ) {  
            $idpair = $this->db->query("SELECT create_idpair('".$namapair."') as idpair")->row()->idpair;
            $data = array(
                            'idpair'   => $idpair, 
                            'namapair'   => $namapair, 
                            'idjenisasset'   => $idjenisasset, 
                            'statusaktif'   => $statusaktif, 
                            'pricenow'   => $pricenow, 
                            'tglinsert'   => $tglinsert, 
                            'tglupdate'   => $tglupdate, 
                        );
            $simpan = $this->Pair_model->simpan($data);      
        }else{ 

            $data = array(
                            'namapair'   => $namapair, 
                            'idjenisasset'   => $idjenisasset, 
                            'statusaktif'   => $statusaktif,    
                            'pricenow'   => $pricenow, 
                            'tglinsert'   => $tglinsert, 
                            'tglupdate'   => $tglupdate,                   
                        );
            $simpan = $this->Pair_model->update($data, $idpair);
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
        redirect('pair');   
    }
    
    public function get_edit_data()
    {
        $idpair = $this->input->post('idpair');
        $RsData = $this->Pair_model->get_by_id($idpair)->row();

        $data = array( 
                            'idpair'     =>  $RsData->idpair,  
                            'namapair'     =>  $RsData->namapair,  
                            'idjenisasset'     =>  $RsData->idjenisasset,  
                            'pricenow'     =>  $RsData->pricenow,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Pair.php */
/* Location: ./application/controllers/Pair.php */