<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Target Balance</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('targetbalance')) ?>">Target Balance</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('targetbalance/simpan')) ?>" method="post" id="form">                      
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
                    if (!empty($idtargetbalance)) {
                      $hideonedit = 'style="display: none;"';
                    }
                  ?>
                  <input type="hidden" name="idtargetbalance" id="idtargetbalance">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Balance</label>
                    <div class="col-md-9">
                      <select name="idbalance" id="idbalance" class="form-control select2">
                        <option value="">Pilih balance trading...</option>
                        <?php  
                          $rsbalance = $this->db->query("select * from balancetrading where statusaktif='Aktif' order by idbalance");
                          if ($rsbalance->num_rows()>0) {
                            foreach ($rsbalance->result() as $rowbalance) {
                              echo '
                                  <option value="'.$rowbalance->idbalance.'">'.$rowbalance->namabalance.'</option>
                              ';
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>               
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Tgl Mulai</label>
                    <div class="col-md-3">
                      <input type="date" name="tglmulai" id="tglmulai" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                  </div>               
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Saldo Awal</label>
                    <div class="col-md-3">
                      <input type="text" name="startingbalance" id="startingbalance" class="form-control dollar">
                    </div>
                  </div>

                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Target Balance</label>
                    <div class="col-md-3">
                      <input type="text" name="targettrading" id="targettrading" class="form-control dollar">
                    </div>
                  </div>

              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('targetbalance')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idtargetbalance = "<?php echo($idtargetbalance) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idtargetbalance != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("targetbalance/get_edit_data") ?>', 
              data        : {idtargetbalance: idtargetbalance}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idtargetbalance").val(result.idtargetbalance);
            $("#idbalance").val(result.idbalance).trigger('change');
            $("#tglmulai").val(result.tglmulai);
            $("#startingbalance").val(result.startingbalance);
            $("#targettrading").val(result.targettrading);
          }); 


          $("#lbljudul").html("Edit Data Target Balance");
          $("#lblactive").html("Edit");

    }else{
        
          $("#lbljudul").html("Tambah Data Target Balance");
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
        idbalance: {
          validators:{
            notEmpty: {
                message: "nama balance tidak boleh kosong"
            },
          }
        },
        tglmulai: {
          validators:{
            notEmpty: {
                message: "tgl mulai tidak boleh kosong"
            },
          }
        },
        startingbalance: {
          validators:{
            notEmpty: {
                message: "saldo awal tidak boleh kosong"
            },
          }
        }, 
        targettrading: {
          validators:{
            notEmpty: {
                message: "target balance tidak boleh kosong"
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
  

  $('#idbalance').change(function() {    
    var idbalance = $( this ).val(); 
    if (idtargetbalance=='') {
          getsaldoawal(idbalance);
       }   
  });

  function getsaldoawal(idbalance)
  {

    $.ajax({
      url: '<?php echo site_url("targetbalance/getsaldoawal") ?>',
      type: 'GET',
      dataType: 'json',
      data: {'idbalance': idbalance},
    })
    .done(function(result) {
      console.log(result);
      $('#startingbalance').val( numberWithCommas(result) );
    })
    .fail(function() {
      console.log("error get saldo awal");
    });

  }

</script>

</body>
</html>
