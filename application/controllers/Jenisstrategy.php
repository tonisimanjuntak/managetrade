<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisstrategy extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('Jenisstrategy_model');
    }

    public function index()
    {
        $data['menu'] = 'jenisstrategy';
        $this->load->view('jenisstrategy/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idjenisstrategy'] = '';        
        $data['menu'] = 'jenisstrategy';  
        $this->load->view('jenisstrategy/form', $data);
    }

    public function edit($idjenisstrategy)
    {       
        $idjenisstrategy = $this->encrypt->decode($idjenisstrategy);

        if ($this->Jenisstrategy_model->get_by_id($idjenisstrategy)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('jenisstrategy');
            exit();
        };
        $data['idjenisstrategy'] =$idjenisstrategy;        
        $data['menu'] = 'jenisstrategy';
        $this->load->view('jenisstrategy/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Jenisstrategy_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->namajenisstrategy;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->statusaktif;
                $row[] = '<a href="'.site_url( 'jenisstrategy/edit/'.$this->encrypt->encode($rowdata->idjenisstrategy) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('jenisstrategy/delete/'.$this->encrypt->encode($rowdata->idjenisstrategy) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Jenisstrategy_model->count_all(),
                        "recordsFiltered" => $this->Jenisstrategy_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idjenisstrategy)
    {
        $idjenisstrategy = $this->encrypt->decode($idjenisstrategy);  
        $rsdata = $this->Jenisstrategy_model->get_by_id($idjenisstrategy);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('jenisstrategy');
            exit();
        };

        $hapus = $this->Jenisstrategy_model->hapus($idjenisstrategy);
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
        redirect('jenisstrategy');        

    }

    public function simpan()
    {       
        $idjenisstrategy             = $this->input->post('idjenisstrategy');
        $namajenisstrategy        = $this->input->post('namajenisstrategy');
        $deskripsi        = $this->input->post('deskripsi');
        $statusaktif        = $this->input->post('statusaktif');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idjenisstrategy=='' ) {  
            $idjenisstrategy = $this->db->query("SELECT create_idjenisstrategy('".$namajenisstrategy."') as idjenisstrategy")->row()->idjenisstrategy;
            $data = array(
                            'idjenisstrategy'   => $idjenisstrategy, 
                            'namajenisstrategy'   => $namajenisstrategy, 
                            'deskripsi'   => $deskripsi, 
                            'statusaktif'   => $statusaktif, 
                        );
            $simpan = $this->Jenisstrategy_model->simpan($data);      
        }else{ 

            $data = array(
                            'namajenisstrategy'   => $namajenisstrategy, 
                            'deskripsi'   => $deskripsi, 
                            'statusaktif'   => $statusaktif,                      
                        );
            $simpan = $this->Jenisstrategy_model->update($data, $idjenisstrategy);
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
        redirect('jenisstrategy');   
    }
    
    public function get_edit_data()
    {
        $idjenisstrategy = $this->input->post('idjenisstrategy');
        $RsData = $this->Jenisstrategy_model->get_by_id($idjenisstrategy)->row();

        $data = array( 
                            'idjenisstrategy'     =>  $RsData->idjenisstrategy,  
                            'namajenisstrategy'     =>  $RsData->namajenisstrategy,  
                            'deskripsi'     =>  $RsData->deskripsi,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                        );

        echo(json_encode($data));
    }

}

/* End of file Jenisstrategy.php */
/* Location: ./application/controllers/Jenisstrategy.php */