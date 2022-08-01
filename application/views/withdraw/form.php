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
        <li class="breadcrumb-item"><a href="<?php echo(site_url('withdraw')) ?>">Withdraw</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('withdraw/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              
              <div class="card-body">

                  <div class="col-md-12">
                    <?php 
                      $pesan = $this->session->flashdata("pesan");
                      if (!empty($pesan)) {
                        echo $pesan;
                      }
                    ?>
                  </div> 

                  <input type="hidden" name="idwithdraw" id="idwithdraw">                      
                  <input type="hidden" name="idbalance" id="idbalance" class="form-control" value="<?php echo $idbalance ?>">

                  <div class="form-group row required">
                    <div class="col-12 text-muted">
                      <h3><?php echo $rowbalance->namabalance ?></h3> 
                      <p><?php echo $rowbalance->namabroker ?>, Saldo: <?php echo format_decimal($rowbalance->jumlahbalance,2).' '.$rowbalance->singkatan ?></p>                     
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Tgl Withdraw</label>
                    <div class="col-md-3">
                      <input type="date" name="tglwithdraw" id="tglwithdraw" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                  </div>                        
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Jumlah Withdraw</label>
                    <div class="col-md-3">
                      <input type="text" name="jumlahwithdraw" id="jumlahwithdraw" class="form-control dollar" autofocus="">
                    </div>
                  </div>             
                  <div class="form-group row">
                    <div class="col-12"><hr></div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                      <a href="<?php echo(site_url('withdraw')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
                      
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-12 mt-5 mb-3">
                      <h3 class="text-muted">Riwayat Withdraw</h3>
                    </div>
                    <div class="col-md-12">
                      <!-- datatable -->
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condesed" id="table">
                          <thead>
                            <tr class="bg-primary" style="">
                              <th style="width: 5%; text-align: center;">No</th>
                              <th style="text-align: center;">Tgl Withdraw</th>
                              <th style="text-align: center;">Nama Balance</th>
                              <th style="text-align: center;">Jumlah Withdraw</th>
                              <th style="text-align: center; width: 15%;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                          </tbody>              
                        </table>
                      </div>

                    </div>
                  </div>

              </div> <!-- ./card-body -->

              <div class="card-footer">
              </div>
            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
      </form>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>



<script type="text/javascript">
  
  var idwithdraw = "<?php echo($idwithdraw) ?>";
  var idbalance = "<?php echo($idbalance) ?>";
  var table;

  $(document).ready(function() {

    $('.select2').select2();

    //defenisi datatable
    table = $("#table").DataTable({ 
        "select": true,
        "processing": true, 
        "serverSide": true, 
        "order": [], 
         "ajax": {
            "url": "<?php echo site_url('withdraw/datatablesource')?>",
            "type": "POST",
            "data": function ( d ) {
                  d.idbalance = idbalance;
              }
        },
        "columnDefs": [
                        { "targets": [ 0 ], "orderable": false, "className": "dt-body-center" },
                        { "targets": [ 1 ], "className": "dt-body-center" },
                        { "targets": [ 2 ], "className": "dt-body-center" },
                        { "targets": [ 3 ], "className": "dt-body-center" },
                        { "targets": [ 4 ], "orderable": false, "className": "dt-body-center" },
        ],
 
    });


    //----------------------------------------------------------------- > validasi
    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        tglwithdraw: {
          validators:{
            notEmpty: {
                message: "tgl withdraw tidak boleh kosong"
            },
          }
        },
        idbalance: {
          validators:{
            notEmpty: {
                message: "idbalance tidak boleh kosong"
            },
          }
        },
        jumlahwithdraw: {
          validators:{
            notEmpty: {
                message: "jumlah withdraw tidak boleh kosong"
            },
          }
        },    
      }
    });
  //------------------------------------------------------------------------> END VALIDASI DAN SIMPAN


    $("form").attr('autocomplete', 'off');
    //$("#tanggal").mask("00-00-0000", {placeholder:"hh-bb-tttt"});
    //$("#jumlah").mask("000,000,000,000", {reverse: true});
  }); //end (document).ready
  

  $(document).on("click", "#hapus", function(e) {
    var link = $(this).attr("href");
    e.preventDefault();
    bootbox.confirm("Anda yakin ingin menghapus data ini ?", function(result) {
      if (result) {
        document.location.href = link;
      }
    });
  });  

</script>

</body>
</html>
