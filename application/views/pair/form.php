<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Pair</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('pair')) ?>">Pair</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('pair/simpan')) ?>" method="post" id="form">                      
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

                  <input type="hidden" name="idpair" id="idpair">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Pair</label>
                    <div class="col-md-9">
                      <input type="text" name="namapair" id="namapair" class="form-control" placeholder="Masukkan namapair">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Jenis Asset</label>
                    <div class="col-md-9">
                      <select name="idjenisasset" id="idjenisasset" class="form-control">
                        <option value="">Pilih jenis asset...</option>
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
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Status Aktif</label>
                    <div class="col-md-9">
                      <select name="statusaktif" id="statusaktif" class="form-control">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Harga Sekarang</label>
                    <div class="col-md-3">
                      <input type="text" name="pricenow" id="pricenow" class="form-control pairprice" value="0.000000">
                    </div>
                  </div>  
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('pair')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idpair = "<?php echo($idpair) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idpair != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Pair/get_edit_data") ?>', 
              data        : {idpair: idpair}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idpair").val(result.idpair);
            $("#namapair").val(result.namapair);
            $("#idjenisasset").val(result.idjenisasset);
            $("#statusaktif").val(result.statusaktif);
            $("#pricenow").val(result.pricenow);
          }); 


          $("#lbljudul").html("Edit Data Pair");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Pair");
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
        namapair: {
          validators:{
            notEmpty: {
                message: "nama pair tidak boleh kosong"
            },
          }
        },
        idjenisasset: {
          validators:{
            notEmpty: {
                message: "nama jenis asset tidak boleh kosong"
            },
          }
        },
        statusaktif: {
          validators:{
            notEmpty: {
                message: "status aktif tidak boleh kosong"
            },
          }
        },      
        pricenow: {
          validators:{
            notEmpty: {
                message: "harga sekarang tidak boleh kosong"
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
  

</script>

</body>
</html>
