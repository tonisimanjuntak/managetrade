<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    
    
	public function is_login()
    {
        $idpengguna = $this->session->userdata('idpengguna');
        if (empty($idpengguna)) {
            $pesan = '<div class="alert alert-danger">Session telah berakhir. Silahkan login kembali . . . </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('Login'); 
            exit();
        }
    } 


    public function upload_foto($file, $nama, $folder)
    {

        if (!empty($file[$nama]['name'])) {
            $config['upload_path']          = 'uploads/'.$folder.'/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['remove_space']         = TRUE;
            $config['max_size']             = '2000KB';

            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload($nama)) {
                $foto = $this->upload->data('file_name');
                $size = $this->upload->data('file_size');
                $ext  = $this->upload->data('file_ext'); 
             }else{
                 $foto = "";
             }

        }else{
            $foto = "";
        }
        return $foto;
    }

    public function update_upload_foto($file, $nama, $file_lama, $folder)
    {
        if (!empty($file[$nama]['name'])) {
            $config['upload_path']          = 'uploads/'.$folder.'/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['remove_space']         = TRUE;
            $config['max_size']            = '2000KB';
            

            $this->load->library('upload', $config);           
            if ($this->upload->do_upload($nama)) {
                $foto = $this->upload->data('file_name');
                $size = $this->upload->data('file_size');
                $ext  = $this->upload->data('file_ext'); 
            }else{
                $foto = $file_lama;
            }          
        }else{          
            $foto = $file_lama;
        }

        return $foto;
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */