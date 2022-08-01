<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Trade</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('trade')) ?>">Trade</a></li>
        <li class="breadcrumb-item active" id="lblactive">Exit Trade</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('trade/simpanforexexit')) ?>" method="post" id="form" enctype="multipart/form-data">                      
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

                  <input type="hidden" name="idtrade" id="idtrade" value="<?php echo($rowtrade->idtrade) ?>">                      
                  <input type="hidden" name="idbalance" id="idbalance" class="form-control" value="<?php echo $rowtrade->idbalance ?>">

                  <div class="row">
                    <div class="col-8 text-muted">
                      <h3><?php echo $rowbalance->namabalance ?></h3> 
                      <p><?php echo $rowbalance->namabroker ?>, Saldo: <?php echo format_decimal($rowbalance->jumlahbalance,2).' '.$rowbalance->singkatan ?></p>                     
                    </div>
                    <div class="col-4 text-right">
                      <?php  
                        $logobroker = '';
                        if (!empty($rowbalance->logobroker)) {
                          echo '
                            <img src="'.base_url('uploads/broker/'.$rowbalance->logobroker).'" alt="" style="width: 60px;">
                          ';
                        }
                      ?>
                    </div>

                    <div class="col-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="text-muted mb-3">Informasi Entry Trade</h5>
                          <table class="table table-bordered table-condesed">
                            <tbody>
                              <tr>
                                <td style="width: 10%;">Nama Pair</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 35%;"><?php echo $rowtrade->namapair  ?></td>
                                <td style="width: 10%;">Tgl Entry</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 35%;"><?php echo tglindonesia($rowtrade->tglentrytrade)  ?></td>
                              </tr>
                              <tr>
                                <td style="width: 10%;">Qty/ Lot</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 35%;"><?php echo $rowtrade->qty  ?></td>
                                <td style="width: 10%;">Nama Strategy</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 35%;"><?php echo $rowtrade->namajenisstrategy  ?></td>
                              </tr>
                            </tbody>
                          </table>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Tgl Exit</label>
                        <div class="col-md-9">
                          <input type="datetime-local" name="tglexittrade" id="tglexittrade" class="form-control" value="<?php echo date('Y-m-d H:i:s') ?>">
                        </div>
                      </div> 
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Hasil Trade</label>
                        <div class="col-md-9">
                          <select name="hasiltrade" id="hasiltrade" class="form-control">
                            <option value="">Pilih hasil trade...</option>
                            <option value="Profit" <?php echo ($rowtrade->hasiltrade=='Profit' ? 'selected="selected"' : '') ?> >Profit</option>
                            <option value="Lost" <?php echo ($rowtrade->hasiltrade=='Lost' ? 'selected="selected"' : '') ?> >Lost</option>
                          </select>
                        </div>
                      </div> 

                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Exit Price</label>
                        <div class="col-md-9">
                          <input type="text" name="exitprice" id="exitprice" class="form-control pairprice" value="<?php echo $rowtrade->exitprice ?>">
                        </div>
                      </div>     
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Lost/ Profit</label>
                        <div class="col-md-9">
                          <input type="text" name="lostprofit" id="lostprofit" class="form-control dollar" value="<?php echo format_decimal(abs($rowtrade->lostprofit),2) ?>">
                        </div>
                      </div>           


                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Foto Exit</label>
                        <div class="col-md-9">
                          <input type="file" name="fotoexit" id="fotoexit">
                          <input type="hidden" name="fotoexit_lama" id="fotoexit_lama" value="<?php echo($rowtrade->fotoexit) ?>">
                          <br><a href="<?php echo base_url('uploads/trade/exit/'.$rowtrade->fotoexit)  ?>" id="fotoexit_link" target="_blank"><?php echo($rowtrade->fotoexit) ?></a>
                        </div>
                      </div>

                               

                    </div> <!-- col-6 -->


                  </div> <!-- row -->


                  <div class="form-group row">
                    <div class="col-12"><hr></div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                      <a href="<?php echo(site_url('trade/entrytrade/'.$this->encrypt->encode($idbalance))) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
                      
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
  
  var idtrade = "<?php echo($idtrade) ?>";
  var idbalance = "<?php echo($idbalance) ?>";
  var table;

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
        tglexittrade: {
          validators:{
            notEmpty: {
                message: "tgl topup tidak boleh kosong"
            },
          }
        },
        hasiltrade: {
          validators:{
            notEmpty: {
                message: "hasil trade tidak boleh kosong"
            },
          }
        },
        exitprice: {
          validators:{
            notEmpty: {
                message: "exit price tidak boleh kosong"
            },
          }
        },    
        lostprofit: {
          validators:{
            notEmpty: {
                message: "lost/ profit tidak boleh kosong"
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
