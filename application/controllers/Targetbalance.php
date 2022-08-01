<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Targetbalance extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->load->model('Targetbalance_model');
    }


    public function index()
    {
        $data['menu'] = 'targetbalance';
        $this->load->view('targetbalance/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idtargetbalance'] = '';        
        $data['menu'] = 'targetbalance';  
        $this->load->view('targetbalance/form', $data);
    }

    public function edit($idtargetbalance)
    {       
        $idtargetbalance = $this->encrypt->decode($idtargetbalance);
        $rstargetbalance = $this->Targetbalance_model->get_by_id($idtargetbalance);
        if ($rstargetbalance->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('targetbalance');
            exit();
        };
        if ($rstargetbalance->row()->statustarget=='Selesai') {
        	$pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Upps!</strong> Data tidak dapat diubah lagi karena status target sudah selesai! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('targetbalance');
            exit();
        }

        $data['idtargetbalance'] =$idtargetbalance;        
        $data['menu'] = 'targetbalance';
        $this->load->view('targetbalance/form', $data);
    }

    public function targetselesai($idtargetbalance)
    {       
        $idtargetbalance = $this->encrypt->decode($idtargetbalance);
        $rstargetbalance = $this->Targetbalance_model->get_by_id($idtargetbalance);
        if ($rstargetbalance->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('targetbalance');
            exit();
        };

        $data['idtargetbalance'] =$idtargetbalance;        
        $data['rowtargetbalance'] =$rstargetbalance->row();        
        $data['menu'] = 'targetbalance';
        $this->load->view('targetbalance/targetselesai', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Targetbalance_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
            	if ($rowdata->statustarget=='Masih Berjalan') {
            		$statustarget = '<a href="'.site_url( 'targetbalance/targetselesai/'.$this->encrypt->encode($rowdata->idtargetbalance) ).'"><span class="badge badge-success">'.$rowdata->statustarget.'</span></a>';
            		$tglselesai = '';
            	}else{
            		$statustarget = '<a href="'.site_url( 'targetbalance/targetselesai/'.$this->encrypt->encode($rowdata->idtargetbalance) ).'"><span class="badge badge-warning">'.$rowdata->statustarget.'</span></a>';
            		$tglselesai = tglindonesia($rowdata->tglselesai).'<br>'.$rowdata->singkatan.' '.$rowdata->endingbalance;
            	}
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->namabalance.'<br>'.$rowdata->namabroker;
                $row[] = $rowdata->namajenisasset.'<br>'.$rowdata->jenisbalance;
                $row[] = $rowdata->targettrading;
                $row[] = tglindonesia($rowdata->tglmulai).'<br>'.$rowdata->singkatan.' '.$rowdata->startingbalance;
                $row[] = $tglselesai;
                $row[] = $statustarget;
                $row[] = '<a href="'.site_url( 'targetbalance/edit/'.$this->encrypt->encode($rowdata->idtargetbalance) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('targetbalance/delete/'.$this->encrypt->encode($rowdata->idtargetbalance) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Targetbalance_model->count_all(),
                        "recordsFiltered" => $this->Targetbalance_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idtargetbalance)
    {
        $idtargetbalance = $this->encrypt->decode($idtargetbalance);  
        $rstargetbalance = $this->Targetbalance_model->get_by_id($idtargetbalance);
        if ($rstargetbalance->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('targetbalance');
            exit();
        };



        $hapus = $this->Targetbalance_model->hapus($idtargetbalance);
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
        redirect('targetbalance');        

    }

    public function simpan()
    {       
        $idtargetbalance             = $this->input->post('idtargetbalance');
        $idbalance        = $this->input->post('idbalance');
        $tglmulai        = $this->input->post('tglmulai');
        $startingbalance        = $this->input->post('startingbalance');
        $targettrading        = $this->input->post('targettrading');
        $tglinsert          = date('Y-m-d H:i:s');
        $tglupdate          = date('Y-m-d H:i:s');
        $idpengguna        = $this->session->userdata('idpengguna');;

        if ( $idtargetbalance=='' ) {  
            $idtargetbalance = $this->db->query("SELECT create_idtargetbalance('".date('Y-m-d')."') as idtargetbalance")->row()->idtargetbalance;
            $data = array(
                            'idtargetbalance'   => $idtargetbalance, 
                            'idbalance'   => $idbalance, 
                            'tglmulai'   => $tglmulai, 
                            'startingbalance'   => $startingbalance, 
                            'targettrading'   => $targettrading, 
                            'endingbalance'   => 0, 
                            'idpengguna'   => $idpengguna, 
                            'tglinsert'   => $tglinsert, 
                            'tglupdate'   => $tglupdate, 
                            'statustarget'   => 'Masih Berjalan', 
                        );
            $simpan = $this->Targetbalance_model->simpan($data);      
        }else{ 

            $data = array(
                            'idbalance'   => $idbalance, 
                            'tglmulai'   => $tglmulai, 
                            'startingbalance'   => $startingbalance, 
                            'targettrading'   => $targettrading, 
                            'tglupdate'   => $tglupdate, 
                        );
            $simpan = $this->Targetbalance_model->update($data, $idtargetbalance);
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
        redirect('targetbalance');   
    }


    public function simpantargetselesai()
    {       
        $idtargetbalance             = $this->input->post('idtargetbalance');
        $idbalance        = $this->input->post('idbalance');
        $tglselesai        = $this->input->post('tglselesai');
        $endingbalance        = $this->input->post('endingbalance');
        $tglinsert          = date('Y-m-d H:i:s');
        $tglupdate          = date('Y-m-d H:i:s');
        $idpengguna        = $this->session->userdata('idpengguna');


        $data = array(
                        'tglselesai'   => $tglselesai, 
                        'endingbalance'   => $endingbalance, 
                        'tglupdate'   => $tglupdate, 
                        'statustarget'   => 'Selesai', 
                    );
        $simpan = $this->Targetbalance_model->update($data, $idtargetbalance);


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
        redirect('targetbalance');   
    }
    
    public function get_edit_data()
    {
        $idtargetbalance = $this->input->post('idtargetbalance');
        $RsData = $this->Targetbalance_model->get_by_id($idtargetbalance)->row();

        $data = array( 
                            'idtargetbalance'     =>  $RsData->idtargetbalance,  
                            'idbalance'     =>  $RsData->idbalance,  
                            'tglmulai'     =>  $RsData->tglmulai,    
                            'startingbalance'     =>  $RsData->startingbalance,  
                            'targettrading'     =>  $RsData->targettrading,  
                        );
        echo(json_encode($data));
    }


    public function getsaldoawal()
    {
    	$idbalance = $this->input->get('idbalance');
    	$idpengguna = $this->session->userdata('idpengguna');

    	$saldoawal = $this->db->query("select jumlahbalance from balancetrading where idpengguna='".$idpengguna."' and idbalance='".$idbalance."'")->row()->jumlahbalance;
    	if (empty($saldoawal)) {
    		$saldoawal='0';
    	}

    	echo json_encode($saldoawal);
    }

}

/* End of file Targetbalance.php */
/* Location: ./application/controllers/Targetbalance.php */