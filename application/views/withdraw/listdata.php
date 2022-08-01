<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Withdraw</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Withdraw</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        <div class="card-header">
          <h5 class="card-title">List Data Account</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <?php 
                $pesan = $this->session->flashdata("pesan");
                if (!empty($pesan)) {
                  echo $pesan;
                }
              ?>
            </div> 
            <div class="col-md-12">
              <!-- datatable -->
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-condesed" id="table">
                  <thead>
                    <tr class="bg-primary" style="">
                      <th style="width: 5%; text-align: center;">No</th>
                      <th style="text-align: center;">Nama Balance<br>Nama Broker</th>
                      <th style="text-align: center;">Jenis Asset<br>Jenis Ballance</th>
                      <th style="text-align: center;">Trade Terakhir</th>
                      <th style="text-align: center;">Saldo</th>
                      <th style="text-align: center; width: 15%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
                      if ($rsbalance->num_rows()>0) {
                        $no=1;
                        foreach ($rsbalance->result() as $row) {

                          if (empty($row->jumlahbalance)) {
                            $jumlahbalance = 0;
                          }else{
                            $jumlahbalance = $row->jumlahbalance;
                          };

                          if (empty($row->tgltradeterakhir)) {
                            $tgltradeterakhir = '-';
                          }else{
                            $tgltradeterakhir = tglindonesia($row->tgltradeterakhir);
                          };

                          echo '

                            <tr class="" style="">
                              <td style="width: 5%; text-align: center;">'.$no++.'</td>
                              <td style="text-align: center;">'.$row->namabalance.'<br>'.$row->namabroker.'</td>
                              <td style="text-align: center;">'.$row->namajenisasset.'<br>'.$row->jenisbalance.'</td>
                              <td style="text-align: center;">'.$tgltradeterakhir.'</td>
                              <td style="text-align: center;">'.$row->singkatan.' '.format_decimal($jumlahbalance,2).'</td>
                              <td style="text-align: center; width: 15%;"><a href="'.site_url( 'withdraw/tambah/'.$this->encrypt->encode($row->idbalance) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-plus-circle"></i> Tarik Saldo</a></td>
                            </tr>

                          ';
                        }
                      }
                    ?>
                  </tbody>              
                </table>
              </div>

            </div>



          </div> <!-- /.row -->
        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
  <!-- Main row -->




<?php $this->load->view("template/footer") ?>



<script type="text/javascript">


  $(document).ready(function() {


  }); //end (document).ready

  
  
  

</script>

</body>
</html>

