<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

<style>
  .info-box-icon {
    width: 130px !important;
  }
</style>
  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Dashboard Management </h4>
        <span>Bulan <?php echo bulan(date('m')).' '.date('Y') ?></span>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">Home</li>
      </ol>
      
    </div>
  </div>
  
  <div class="row" id="toni-content">

    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Persen Profit</span>
                  <span class="info-box-number">
                    <span id="persenprofit">0</span>
                    <small>%</small>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Jumlah Trading</span>
                  <span class="info-box-number">
                    <span id="jumlahtrading">0</span>
                    <small>Kali</small>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Profit Rate</span>
                  <span class="info-box-number">
                    <span id="profitrate">0</span>
                    <small>%</small>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Melanggar Aturan</span>
                  <span class="info-box-number">
                    <span id="breakrole">0</span>
                    <small>Kali</small>
                  </span>
                </div>
              </div>
            </div>


          </div>            
        </div> 
      </div> 
    </div>

    <div class="col-md-6">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Grafik Pertumbuhan Trading</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="height: 350px;">
          <div class="text-muted mb-1 text-bold" style="width: 100%;">Jumlah Profit = <span id="jumlahprofit"></span></div>
          <div class="text-muted mb-1 text-bold" style="width: 100%;">Jumlah Lost  = <span id="jumlahlost"></span></div>
          <div class="chart">
            <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Pair Winrate Alltime</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="min-height: 350px;">
          <div class="row">


            <div class="col-12">
              <table class="table">
                <tbody id="tbodypairwinrate">
                   
                </tbody>                
              </table>
            </div>
            <div class="col-12">
              <a href="<?php echo site_url('home/pairwinrate') ?>">Lihat Selengkapnya</a>
            </div>

          </div>
        </div>
        <!-- /.card-body -->
      </div>
    </div>


    <div class="col-12">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Strategy Winrate Alltime</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row" id="divstrategywinrate">
            
            

          </div>
        </div>
      </div>
    </div>


  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>

<script src="<?php echo(base_url()) ?>assets/adminlte/plugins/chart.js/Chart.min.js"></script>
<script src="<?php echo(base_url()) ?>assets/adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>


<script>

  

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: true
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    

    $(document).ready(function() {
      

      // Load Info Box
      $.ajax({
          url: '<?php echo site_url("home/loadinfobox") ?>',
          type: 'GET',
          dataType: 'json',
        })
        .done(function(resultloadinfo) {
          $('#persenprofit').html(resultloadinfo.persenprofit);
          $('#jumlahtrading').html(resultloadinfo.jumlahtrading);
          $('#profitrate').html(resultloadinfo.profitrate);
          $('#breakrole').html(resultloadinfo.breakrole);
        })
        .fail(function() {
          console.log("gagal load info box");
        });


      // Load Grafik
      $.ajax({
        url: '<?php echo site_url("home/loadgrafikprofit") ?>',
        type: 'GET',
        dataType: 'json',
      })
      .done(function(resultgrafik) {
        // console.log(resultgrafik);


        var areaChartData = {
          labels  : resultgrafik.datatanggal,
          datasets: [
            {
              label               : 'Saldo Forex',
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : resultgrafik.dataprofit
            },
          ]
        }

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        var lineChartData = $.extend(true, {}, areaChartData)
        lineChartData.datasets[0].fill = false;
        lineChartOptions.datasetFill = false

        var lineChart = new Chart(lineChartCanvas, {
          type: 'line',
          data: lineChartData,
          options: lineChartOptions
        })

        $('#jumlahprofit').html(resultgrafik.jumlahprofit);
        $('#jumlahlost').html(resultgrafik.jumlahlost);

      })
      .fail(function() {
        console.log("error");
      });



      //Load Pair Winrate
      $.ajax({
        url: '<?php echo site_url('home/loadpairwinrate') ?>',
        type: 'GET',
        dataType: 'json',
      })
      .done(function(resultpairwinrate) {
          //console.log(resultpairwinrate);

          $.each(resultpairwinrate, function(index, val) {
             
              var tbody = '';
              var persenwinrate = parseInt(val['persenwinrate']);
              var jumlahtrading = parseInt(val['jumlahtrading']);

              if (jumlahtrading>0 && persenwinrate < 50)  {
                tbody += '<tr style="color: red;">';
              }else if ( persenwinrate>50 ) {
                tbody += '<tr style="color: green;">';
              }else{
                tbody += '<tr style="">';
              }
              tbody += '<td style="width: 20%;">'+val['namapair']+'</td>';
              tbody += '<td>';
              tbody += '<div class="progress">';
              tbody += '<div class="progress-bar" style="width: '+val['persenwinrate']+'%"></div>';
              tbody += '</div>';
              tbody += '<span class="progress-description">';
              tbody += val['persenwinrate']+'% Winrate dari '+val['jumlahtrading']+' Kali Trading';
              tbody += '</span>';
              tbody += '</td>';
              tbody += '</tr>';

              $('#tbodypairwinrate').append(tbody);
          });

                  
      })
      .fail(function() {
        console.log("error");
      });
      
      


      
      
      
      



    });



    //strategy winrate
      $.ajax({
        url: '<?php echo site_url('home/loadstrategywinrate') ?>',
        type: 'GET',
        dataType: 'json',
      })
      .done(function(resultstrategy) {

        var nCount = 1;
        $.each(resultstrategy, function(index, val) {
          if ( parseInt(val['winrate']) >= 50 ) {
            $output = '<div class="col-6 col-md-3 text-center">';
            $output += '<input type="text" class="knob" value="'+val['winrate']+'" data-skin="tron" data-thickness="0.2" data-width="120"';
            $output += 'data-height="120" data-fgColor="#1A72A5">';
            $output += '<div class="knob-label">'+val['namajenisstrategy']+' <span class="badge badge-warning">'+val['jumlahtrading']+'</span></div>';
            $output += '</div>';
          }else{
            $output = '<div class="col-6 col-md-3 text-center">';
            $output += '<input type="text" class="knob" value="'+val['winrate']+'" data-skin="tron" data-thickness="0.2" data-width="120"';
            $output += 'data-height="120" data-fgColor="#B51C35">';
            $output += '<div class="knob-label">'+val['namajenisstrategy']+' <span class="badge badge-warning">'+val['jumlahtrading']+'</span></div>';
            $output += '</div>';
          }
         $('#divstrategywinrate').append($output);
          
          if (nCount==parseInt(val['jumlahstrategy'])) {
            console.log("jumlahstrategy="+val['jumlahstrategy']);
            $('.knob').knob({
              draw: function () {
                if (this.$.data('skin') == 'tron') {
                  var a   = this.angle(this.cv),
                      sa  = this.startAngle,
                      sat = this.startAngle,
                      ea,
                      eat = sat + a,
                      r   = true
                  this.g.lineWidth = this.lineWidth
                  this.o.cursor
                  && (sat = eat - 0.3)
                  && (eat = eat + 0.3)

                  if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value)
                    this.o.cursor
                    && (sa = ea - 0.3)
                    && (ea = ea + 0.3)
                    this.g.beginPath()
                    this.g.strokeStyle = this.previousColor
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
                    this.g.stroke()
                  }

                  this.g.beginPath()
                  this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
                  this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
                  this.g.stroke()

                  this.g.lineWidth = 2
                  this.g.beginPath()
                  this.g.strokeStyle = this.o.fgColor
                  this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
                  this.g.stroke()

                  return false
                }
              }
            });
          }
           nCount++;
        });
        // console.log(resultstrategy);
      })
      .fail(function() {
        console.log("error");
      });

    
    

</script>
  
  

</body>
</html>

