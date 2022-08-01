<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Broker extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('Broker_model');
        $this->load->library('image_lib');
    }

    public function index()
    {
        $data['menu'] = 'broker';
        $this->load->view('broker/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idbroker'] = '';        
        $data['menu'] = 'broker';  
        $this->load->view('broker/form', $data);
    }

    public function edit($idbroker)
    {       
        $idbroker = $this->encrypt->decode($idbroker);

        if ($this->Broker_model->get_by_id($idbroker)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('broker');
            exit();
        };
        $data['idbroker'] =$idbroker;        
        $data['menu'] = 'broker';
        $this->load->view('broker/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Broker_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                if ($rowdata->statusaktif=='Aktif') {
                    $statusaktif = '<span class="badge badge-success">'.$rowdata->statusaktif.'</span>';
                }else{
                    $statusaktif = '<span class="badge badge-danger">'.$rowdata->statusaktif.'</span>';
                }

                if (!empty($rowdata->logobroker)) {
                    $logobroker = '<img src="'.base_url('uploads/broker/'.$rowdata->logobroker).'" alt="" style="width: 80%;">';
                }else{
                    $logobroker = '<img src="'.base_url('images/nofoto.png').'" alt="" style="width: 80%;">';
                }
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $logobroker;
                $row[] = $rowdata->namabroker;
                $row[] = $statusaktif;
                $row[] = '<a href="'.site_url( 'broker/edit/'.$this->encrypt->encode($rowdata->idbroker) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('broker/delete/'.$this->encrypt->encode($rowdata->idbroker) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Broker_model->count_all(),
                        "recordsFiltered" => $this->Broker_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idbroker)
    {
        $idbroker = $this->encrypt->decode($idbroker);  
        $rsdata = $this->Broker_model->get_by_id($idbroker);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('broker');
            exit();
        };

        $hapus = $this->Broker_model->hapus($idbroker);
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
        redirect('broker');        

    }

    public function simpan()
    {       
        $idbroker             = $this->input->post('idbroker');
        $namabroker        = $this->input->post('namabroker');
        $statusaktif        = $this->input->post('statusaktif');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idbroker=='' ) {  
            $idbroker = $this->db->query("SELECT create_idbroker('".$namabroker."') as idbroker")->row()->idbroker;

            $foto               = $this->upload_foto($_FILES, "file", "broker");     


            $data = array(
                            'idbroker'   => $idbroker, 
                            'namabroker'   => $namabroker, 
                            'logobroker'   => $foto, 
                            'statusaktif'   => $statusaktif, 
                        );
            $simpan = $this->Broker_model->simpan($data);      
        }else{ 

            $file_lama = $this->input->post('file_lama');
            $foto = $this->update_upload_foto($_FILES, "file", $file_lama, "broker");

            $data = array(
                            'namabroker'   => $namabroker, 
                            'logobroker'   => $foto, 
                            'statusaktif'   => $statusaktif, 
                        );
            $simpan = $this->Broker_model->update($data, $idbroker);
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
        redirect('broker');   
    }
    
    public function get_edit_data()
    {
        $idbroker = $this->input->post('idbroker');
        $RsData = $this->Broker_model->get_by_id($idbroker)->row();

        $data = array( 
                            'idbroker'     =>  $RsData->idbroker,  
                            'namabroker'     =>  $RsData->namabroker,
                            'logobroker'     =>  $RsData->logobroker,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                        );

        echo(json_encode($data));
    }

    

}

/* End of file Broker.php */
/* Location: ./application/controllers/Broker.php */