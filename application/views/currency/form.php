<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Currency</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('Currency')) ?>">Currency</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('Currency/simpan')) ?>" method="post" id="form">                      
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

                  <input type="hidden" name="idcurrency" id="idcurrency">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Singkatan</label>
                    <div class="col-md-3">
                      <input type="text" name="singkatan" id="singkatan" class="form-control" placeholder="Masukkan singkatan">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Currency</label>
                    <div class="col-md-9">
                      <input type="text" name="namacurrency" id="namacurrency" class="form-control" placeholder="Masukkan nama currency">
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
                <a href="<?php echo(site_url('Currency')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idcurrency = "<?php echo($idcurrency) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idcurrency != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Currency/get_edit_data") ?>', 
              data        : {idcurrency: idcurrency}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idcurrency").val(result.idcurrency);
            $("#singkatan").val(result.singkatan);
            $("#namacurrency").val(result.namacurrency);
            $("#statusaktif").val(result.statusaktif);
          }); 


          $("#lbljudul").html("Edit Data Currency");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Currency");
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
        singkatan: {
          validators:{
            notEmpty: {
                message: "singkatan tidak boleh kosong"
            },
          }
        },
        namacurrency: {
          validators:{
            notEmpty: {
                message: "nama currency tidak boleh kosong"
            },
          }
        },
        statusaktif: {
          validators:{
            notEmpty: {
                message: "status aktif tidak boleh kosong"
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
