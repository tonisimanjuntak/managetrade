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
        <li class="breadcrumb-item active" id="lblactive">Akhiri Target</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('targetbalance/simpantargetselesai')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul">Akhiri Target</h5>
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
                  <input type="hidden" name="idtargetbalance" id="idtargetbalance" value="<?php echo $rowtargetbalance->idtargetbalance ?>">    
                  <input type="hidden" name="idbalance" id="idbalance" value="<?php echo $rowtargetbalance->idbalance ?>">    

                  <div class="row">
                    <div class="col-md-6">
                        
                        <div class="form-group row required">
                          <label for="" class="col-md-3 col-form-label">Tgl Selesai</label>
                          <div class="col-md-9">
                            <?php  
                              if (!empty($rowtargetbalance->tglselesai)) {
                                echo '
                                  <input type="date" name="tglselesai" id="tglselesai" class="form-control" value="'.date('Y-m-d', strtotime($rowtargetbalance->tglselesai)).'">
                                ';
                              }else{
                                echo '
                                  <input type="date" name="tglselesai" id="tglselesai" class="form-control" value="'.date('Y-m-d').'">
                                ';
                              }
                            ?>
                          </div>
                        </div>               
                        <div class="form-group row required">
                          <label for="" class="col-md-3 col-form-label">Saldo Akhir</label>
                          <div class="col-md-7">
                            <input type="text" name="endingbalance" id="endingbalance" class="form-control dollar" value="<?php echo $rowtargetbalance->endingbalance ?>">
                          </div>
                          <div class="col-md-2">
                            <span class="btn btn-info btn-sm" id="btngetsaldo"><i class="fa fa-sync"></i></span>
                          </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                      <div class="card">
                        <div class="card-body">
                          
                          <table class="table">
                            <tbody>
                              <tr>
                                <td style="width: 25%;">Nama Balance</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 70%;"><?php echo $rowtargetbalance->namabalance ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Tgl Mulai</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 70%;"><?php echo tglindonesia($rowtargetbalance->tglmulai) ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Saldo Awal</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 70%;"><?php echo $rowtargetbalance->singkatan.' '.format_decimal($rowtargetbalance->startingbalance,2) ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Target Balance</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 70%;"><?php echo $rowtargetbalance->singkatan.' '.format_decimal($rowtargetbalance->targettrading,2) ?></td>
                              </tr>

                            </tbody>
                          </table>  

                        </div>
                      </div>

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
  

  $(document).ready(function() {

    $('.select2').select2();

    //----------------------------------------------------------------- > validasi
    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        tglselesai: {
          validators:{
            notEmpty: {
                message: "tgl selesai tidak boleh kosong"
            },
          }
        },
        endingbalance: {
          validators:{
            notEmpty: {
                message: "saldo akhir tidak boleh kosong"
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

  $('#btngetsaldo').click(function() {
    
    var idbalance = $('#idbalance').val();

    $.ajax({
      url: '<?php echo site_url("targetbalance/getsaldoawal") ?>',
      type: 'GET',
      dataType: 'json',
      data: {'idbalance': idbalance},
    })
    .done(function(result) {
      $('#endingbalance').val( numberWithCommas(result) );
    })
    .fail(function() {
      console.log("error get saldo akhir");
    });

  });

</script>

</body>
</html>
