<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Konversi</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('konversi')) ?>">Konversi</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('konversi/simpan')) ?>" method="post" id="form">                      
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

                  <input type="hidden" name="idkonversi" id="idkonversi">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">currency Utama</label>
                    <div class="col-md-9">
                      <select name="currencyutama" id="currencyutama" class="form-control">
                        <option value="">Pilih currency</option>
                        <?php  
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
                    <label for="" class="col-md-3 col-form-label">currencypasangan</label>
                    <div class="col-md-9">
                      <select name="currencypasangan" id="currencypasangan" class="form-control">
                        <option value="">Pilih currency</option>
                        <?php  
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
                    <label for="" class="col-md-3 col-form-label">Jumlah Konversi</label>
                    <div class="col-md-3">
                      <input type="text" name="jumlahkonversi" id="jumlahkonversi" class="form-control rupiah">
                    </div>
                  </div>
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('konversi')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idkonversi = "<?php echo($idkonversi) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idkonversi != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Konversi/get_edit_data") ?>', 
              data        : {idkonversi: idkonversi}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idkonversi").val(result.idkonversi);
            $("#currencyutama").val(result.currencyutama);
            $("#currencypasangan").val(result.currencypasangan);
            $("#jumlahkonversi").val(result.jumlahkonversi);
          }); 


          $("#lbljudul").html("Edit Data Konversi");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Konversi");
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
        currencyutama: {
          validators:{
            notEmpty: {
                message: "currency utama tidak boleh kosong"
            },
          }
        },
        currencypasangan: {
          validators:{
            notEmpty: {
                message: "currency pasangan tidak boleh kosong"
            },
          }
        },
        jumlahkonversi: {
          validators:{
            notEmpty: {
                message: "jumlah konversi tidak boleh kosong"
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
