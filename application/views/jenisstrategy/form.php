<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Jenis Strategy</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('jenisstrategy')) ?>">Jenis Strategy</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('jenisstrategy/simpan')) ?>" method="post" id="form">                      
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

                  <input type="hidden" name="idjenisstrategy" id="idjenisstrategy">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Strategy</label>
                    <div class="col-md-9">
                      <input type="text" name="namajenisstrategy" id="namajenisstrategy" class="form-control" placeholder="Masukkan nama jenis strategy">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Deskripsi</label>
                    <div class="col-md-9">
                      <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi"></textarea>
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
                <a href="<?php echo(site_url('jenisstrategy')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idjenisstrategy = "<?php echo($idjenisstrategy) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idjenisstrategy != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("jenisstrategy/get_edit_data") ?>', 
              data        : {idjenisstrategy: idjenisstrategy}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idjenisstrategy").val(result.idjenisstrategy);
            $("#namajenisstrategy").val(result.namajenisstrategy);
            $("#deskripsi").val(result.deskripsi);
            $("#statusaktif").val(result.statusaktif);
          }); 


          $("#lbljudul").html("Edit Data Jenis Strategy");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Jenis Strategy");
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
        namajenisstrategy: {
          validators:{
            notEmpty: {
                message: "namajenisstrategy tidak boleh kosong"
            },
          }
        },
        deskripsi: {
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
