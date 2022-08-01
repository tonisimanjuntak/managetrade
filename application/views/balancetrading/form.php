<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Balance Trading</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('balancetrading')) ?>">Balance Trading</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('balancetrading/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul"></h5>
              </div>
              <div class="card-body">

                  <div class="col-md-12">
                    <?php 
                      $pesan = $this->session->flashdata("pesan");
                      if (!empty($pesan)) {
                        echo $pesan;
                      }
                    ?>
                  </div> 

                  <?php  
                    $hideonedit ='';
                    if (!empty($idbalance)) {
                      $hideonedit = 'style="display: none;"';
                    }
                  ?>
                  <input type="hidden" name="idbalance" id="idbalance">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Balance</label>
                    <div class="col-md-9">
                      <input type="text" name="namabalance" id="namabalance" class="form-control" placeholder="Masukkan nama balance">
                    </div>
                  </div>                      
                  <div class="form-group row required" <?php echo $hideonedit ?> >
                    <label for="" class="col-md-3 col-form-label">Nama Broker</label>
                    <div class="col-md-9">
                      <select name="idbroker" id="idbroker" class="form-control">
                        <option value="">Pilih nama broker...</option>
                        <?php  
                          $rsjenisbroker = $this->db->query("select * from broker where statusaktif='Aktif' order by namabroker");
                          if ($rsjenisbroker->num_rows()>0) {
                            foreach ($rsjenisbroker->result() as $row) {
                              echo '
                                <option value="'.$row->idbroker.'">'.$row->namabroker.'</option>
                              ';
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>                      
                  <div class="form-group row required" <?php echo $hideonedit ?> >
                    <label for="" class="col-md-3 col-form-label">Nama Jenis Asset</label>
                    <div class="col-md-9">
                      <select name="idjenisasset" id="idjenisasset" class="form-control">
                        <option value="">Pilih nama jenis asset...</option>
                        <?php  
                          $rsjenisasset = $this->db->query("select * from jenisasset order by namajenisasset");
                          if ($rsjenisasset->num_rows()>0) {
                            foreach ($rsjenisasset->result() as $row) {
                              echo '
                                <option value="'.$row->idjenisasset.'">'.$row->namajenisasset.'</option>
                              ';
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>                      
                  <div class="form-group row required" <?php echo $hideonedit ?> >
                    <label for="" class="col-md-3 col-form-label">Jenis Balance</label>
                    <div class="col-md-9">
                      <select name="jenisbalance" id="jenisbalance" class="form-control">
                        <option value="">Pilih jenis balance</option>
                        <option value="Trading">Trading</option>
                        <option value="Investasi">Investasi</option>
                      </select>
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Aturan Trading</label>
                    <div class="col-md-9">
                      <textarea name="aturantrading" id="aturantrading" class="form-control" rows="3" placeholder="Masukkan aturan trading"></textarea>
                    </div>
                  </div>                      
                  <div class="form-group row required" <?php echo $hideonedit ?> >
                    <label for="" class="col-md-3 col-form-label">Currency</label>
                    <div class="col-md-9">
                      <select name="idcurrency" id="idcurrency" class="form-control">
                        <option value="">Pilih nama currency...</option>
                        <?php  
                          $rscurrency = $this->db->query("select * from currency where statusaktif='Aktif' order by namacurrency ");
                          if ($rscurrency->num_rows()>0) {
                            foreach ($rscurrency->result() as $row) {
                              echo '
                                <option value="'.$row->idcurrency.'">'.$row->namacurrency.'</option>
                              ';
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Max Kekalahan Per Trade (Currency)</label>
                    <div class="col-md-2">
                      <input type="text" name="maxlose" id="maxlose" class="form-control dollar">
                    </div>
                  </div>                      
                  <div class="form-group row required" <?php echo $hideonedit ?> >
                    <label for="" class="col-md-3 col-form-label">Tgl Buka Akun</label>
                    <div class="col-md-2">
                      <input type="date" name="tglbukaakun" id="tglbukaakun" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Status Aktif</label>
                    <div class="col-md-9">
                      <select name="statusaktif" id="statusaktif" class="form-control">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                      </select>
                    </div>
                  </div>
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('balancetrading')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idbalance = "<?php echo($idbalance) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idbalance != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("balancetrading/get_edit_data") ?>', 
              data        : {idbalance: idbalance}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idbalance").val(result.idbalance);
            $("#namabalance").val(result.namabalance);
            $("#idbroker").val(result.idbroker);
            $("#idjenisasset").val(result.idjenisasset);
            $("#jenisbalance").val(result.jenisbalance);
            $("#aturantrading").val(result.aturantrading);
            $("#idcurrency").val(result.idcurrency);
            $("#maxlose").val(result.maxlose);
            $("#tglbukaakun").val(result.tglbukaakun);
            $("#statusaktif").val(result.statusaktif);
          }); 


          $("#lbljudul").html("Edit Data Balance Trading");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Balance Trading");
          $("#lblactive").html("Tambah");
    }     

    //----------------------------------------------------------------- > validasi
    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        namabalance: {
          validators:{
            notEmpty: {
                message: "namabalance tidak boleh kosong"
            },
          }
        },
        idbroker: {
          validators:{
            notEmpty: {
                message: "idbroker tidak boleh kosong"
            },
          }
        },
        idpengguna: {
          validators:{
            notEmpty: {
                message: "idpengguna tidak boleh kosong"
            },
          }
        },
        jenisbalance: {
          validators:{
            notEmpty: {
                message: "jenisbalance tidak boleh kosong"
            },
          }
        },
        aturantrading: {
          validators:{
            notEmpty: {
                message: "aturantrading tidak boleh kosong"
            },
          }
        },
        idcurrency: {
          validators:{
            notEmpty: {
                message: "idcurrency tidak boleh kosong"
            },
          }
        },
        maxlose: {
          validators:{
            notEmpty: {
                message: "maxlose tidak boleh kosong"
            },
          }
        },
        tglbukaakun: {
          validators:{
            notEmpty: {
                message: "tglbukaakun tidak boleh kosong"
            },
          }
        },
        statusaktif: {
          validators:{
            notEmpty: {
                message: "statusaktif tidak boleh kosong"
            },
          }
        },      }
    });
  //------------------------------------------------------------------------> END VALIDASI DAN SIMPAN


    $("form").attr('autocomplete', 'off');
    //$("#tanggal").mask("00-00-0000", {placeholder:"hh-bb-tttt"});
    //$("#jumlah").mask("000,000,000,000", {reverse: true});
  }); //end (document).ready
  

</script>

</body>
</html>
