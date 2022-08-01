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
        <li class="breadcrumb-item active" id="lblactive">Entry Trade</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('trade/simpanforex')) ?>" method="post" id="form" enctype="multipart/form-data">                      
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

                  <input type="hidden" name="idtrade" id="idtrade">                      
                  <input type="hidden" name="idbalance" id="idbalance" class="form-control" value="<?php echo $idbalance ?>">

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
                    <div class="col-md-6">
                      
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Tgl Entry</label>
                        <div class="col-md-9">
                          <input type="datetime-local" name="tglentrytrade" id="tglentrytrade" class="form-control" value="<?php echo date('Y-m-d H:i:s') ?>">
                        </div>
                      </div> 
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Pair</label>
                        <div class="col-md-9">
                          <select name="idpair" id="idpair" class="form-control select2">
                            <option value="">Pilih pair...</option>
                            <?php  
                              $rspair = $this->db->query("select * from v_pair where idjenisasset = '".$rowbalance->idjenisasset."' order by namapair");
                              if ($rspair->num_rows()>0) {
                                foreach ($rspair->result() as $row) {
                                  echo '
                                      <option value="'.$row->idpair.'">'.$row->namapair.'</option>
                                  ';
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>                        

                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Qty/ Lot</label>
                        <div class="col-md-9">
                          <input type="text" name="qty" id="qty" class="form-control dollar">
                        </div>
                      </div>

                      


                               

                    </div> <!-- col-6 -->

                    <div class="col-md-6">
                      
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Entry Price</label>
                        <div class="col-md-9">
                          <input type="text" name="entryprice" id="entryprice" class="form-control pairprice">
                        </div>
                      </div>           

                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Jenis Strategy</label>
                        <div class="col-md-9">
                          <select name="idjenisstrategy" id="idjenisstrategy" class="form-control select2">
                            <option value="">Pilih jenis strategy...</option>
                            <?php  
                              $rsstrategy = $this->db->query("select * from jenisstrategy where statusaktif='Aktif' order by namajenisstrategy");
                              if ($rsstrategy->num_rows()>0) {
                                foreach ($rsstrategy->result() as $row) {
                                  echo '
                                    <option value="'.$row->idjenisstrategy.'">'.$row->namajenisstrategy.'</option>
                                  ';
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Foto Entry</label>
                        <div class="col-md-9">
                          <input type="file" name="fotoentry" id="fotoentry">
                          <input type="hidden" name="fotoentry_lama" id="fotoentry_lama">
                          <br><a href="" id="fotoentry_link"></a>
                        </div>
                      </div>

                    </div>

                  </div> <!-- row -->


                  <div class="form-group row">
                    <div class="col-12"><hr></div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                      <a href="<?php echo(site_url('trade')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
                      
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-12 mt-5 mb-3">
                      <h3 class="text-muted">Riwayat Trade</h3>
                    </div>
                    <div class="col-md-12">
                      <!-- datatable -->
                      <div class="table-responsive">
                        <table class="table table-bordered table-condesed" id="table">
                          <thead>
                            <tr class="bg-primary" style="">
                              <th style="width: 5%; text-align: center;">No</th>
                              <th style="text-align: center;">Tgl Trade</th>
                              <th style="text-align: center;">Strategy</th>
                              <th style="text-align: center;">Qty/ Lot</th>
                              <th style="text-align: center;">Entry Price</th>
                              <th style="text-align: center;">Exit Price</th>
                              <th style="text-align: center;">Lost/Profit</th>
                              <th style="text-align: center;">istradeclose</th>
                              <th style="text-align: center;">hasiltrade</th>
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
  
  var idtrade = "<?php echo($idtrade) ?>";
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
            "url": "<?php echo site_url('trade/datatablesource')?>",
            "type": "POST",
            "data": function ( d ) {
                  d.idbalance = idbalance;
              }
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
          if ( aData[7] == 'Ya' && aData[8] == 'Profit' ) {
            $('td', nRow).css('background-color', '#1CCD54');
            $('td', nRow).css('color', '#FAFDFB');
          } else if (aData[7] == 'Ya' && aData[8] == 'Lost' ) {
            $('td', nRow).css('background-color', '#E3646D');
            $('td', nRow).css('color', '#FAFDFB');
          } else {
            $('td', nRow).css('background-color', '#EAF1EC');
          }
        },
        "columnDefs": [
                        { "targets": [ 0 ], "orderable": false, "className": "dt-body-center" },
                        { "targets": [ 1 ], "className": "dt-body-center" },
                        { "targets": [ 2 ], "className": "dt-body-center" },
                        { "targets": [ 3 ], "className": "dt-body-center" },
                        { "targets": [ 4 ], "className": "dt-body-center" },
                        { "targets": [ 5 ], "className": "dt-body-center" },
                        { "targets": [ 6 ], "className": "dt-body-center" },
                        { "targets": [ 7 ], "className": "dt-body-center", "visible": false },
                        { "targets": [ 8 ], "className": "dt-body-center", "visible": false },
                        { "targets": [ 9 ], "orderable": false, "className": "dt-body-center" },
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
        tglentrytrade: {
          validators:{
            notEmpty: {
                message: "tgl topup tidak boleh kosong"
            },
          }
        },
        idpair: {
          validators:{
            notEmpty: {
                message: "nama pair tidak boleh kosong"
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
        qty: {
          validators:{
            notEmpty: {
                message: "jumlah topup tidak boleh kosong"
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
