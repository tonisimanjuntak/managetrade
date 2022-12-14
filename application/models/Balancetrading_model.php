<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balancetrading_model extends CI_Model {

    var $tabelview = 'v_balancetrading';
    var $tabel     = 'balancetrading';
    var $idbalance = 'idbalance';

    var $column_order = array(null,'namabalance','namajenisasset','singkatan','maxlose','tglbukaakun','statusaktif', null );
    var $column_search = array('namabalance','namabroker','namajenisasset','jenisbalance','singkatan','statusaktif');
    var $order = array('idbalance' => 'desc'); // default order 


    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get();        
    }

    private function _get_datatables_query()
    {   
        $this->db->from($this->tabelview);
        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }
        
        // -------------------------> Proses Order by        
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }

    function count_filtered()
    {
        $this->db->select('count(*) as jlh');
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->row()->jlh;
    }
 
    public function count_all()
    {
        $this->db->select('count(*) as jlh');
        return $this->db->get($this->tabelview)->row()->jlh;
    }

    public function get_all()
    {
        return $this->db->get($this->tabelview);
    }

    public function get_by_id($idbalance)
    {
        $this->db->where('idbalance', $idbalance);
        return $this->db->get($this->tabelview);
    }

    public function hapus($idbalance)
    {
        $this->db->where('idbalance', $idbalance);      
        return $this->db->delete($this->tabel);
    }

    public function simpan($data)
    {       
        return $this->db->insert($this->tabel, $data);
    }

    public function update($data, $idbalance)
    {
        $this->db->where('idbalance', $idbalance);
        return $this->db->update($this->tabel, $data);
    }

}

/* End of file Balancetrading_model.php */
/* Location: ./application/models/Balancetrading_model.php */