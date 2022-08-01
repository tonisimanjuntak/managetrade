  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-warning elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo(base_url()) ?>" class="brand-link navbar-navy text-light text-sm">
      <img src="<?php echo(base_url()) ?>assets/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">SITRADE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url("images/nofoto_l.png") ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="<?php echo(site_url()) ?>" class="nav-link <?php echo ($menu=='dashboardtrading') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard Trading
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?php echo(site_url('home')) ?>" class="nav-link <?php echo ($menu=='home') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard Management
              </p>
            </a>
          </li>

          <?php  
            $menudropdown = array("pengguna", "currency", "jenisasset", "konversi", "pair", "balancetrading", "jenisstrategy", "broker", "targetbalance");
            if (in_array($menu, $menudropdown)) {
              $dropdownselected = true;
            }else{
              $dropdownselected = false;
            }
          ?>

          <li class="nav-item has-treeview <?php echo ($dropdownselected) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?php echo ($dropdownselected) ? 'active' : '' ?>">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("pengguna")) ?>" class="nav-link <?php echo ($menu=='pengguna') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Pengguna</p>
                    </a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("currency")) ?>" class="nav-link <?php echo ($menu=='currency') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Currency</p>
                    </a>
                  </li>
              
                  
                  <!-- <li class="nav-item">
                    <a href="<?php echo(site_url("jenisasset")) ?>" class="nav-link <?php echo ($menu=='jenisasset') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Jenisasset</p>
                    </a>
                  </li> -->
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("konversi")) ?>" class="nav-link <?php echo ($menu=='konversi') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Konversi</p>
                    </a>
                  </li>
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("pair")) ?>" class="nav-link <?php echo ($menu=='pair') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Pair</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("jenisstrategy")) ?>" class="nav-link <?php echo ($menu=='jenisstrategy') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Jenis Strategy</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("broker")) ?>" class="nav-link <?php echo ($menu=='broker') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Broker</p>
                    </a>
                  </li>
              
                  <li class="nav-item">
                    <a href="<?php echo(site_url("balancetrading")) ?>" class="nav-link <?php echo ($menu=='balancetrading') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Balance Trading</p>
                    </a>
                  </li>


                  <li class="nav-item">
                    <a href="<?php echo(site_url("targetbalance")) ?>" class="nav-link <?php echo ($menu=='targetbalance') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Target Ballance</p>
                    </a>
                  </li>
                  
              


            </ul>
          </li>
          

          <?php  
            $menudropdown = array("topup", "withdraw", "trade");
            if (in_array($menu, $menudropdown)) {
              $dropdownselected = true;
            }else{
              $dropdownselected = false;
            }
          ?>

          <li class="nav-item has-treeview <?php echo ($dropdownselected) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?php echo ($dropdownselected) ? 'active' : '' ?>">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Transaksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                  
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("topup")) ?>" class="nav-link <?php echo ($menu=='topup') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Topup</p>
                    </a>
                  </li>
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("withdraw")) ?>" class="nav-link <?php echo ($menu=='withdraw') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Withdraw</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("trade")) ?>" class="nav-link <?php echo ($menu=='trade') ? 'active' : '' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Trade</p>
                    </a>
                  </li>

            </ul>
          </li>


          <li class="nav-item">
            <a href="<?php echo(site_url('Login/keluar')) ?>" class="nav-link text-danger">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
          
          
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
