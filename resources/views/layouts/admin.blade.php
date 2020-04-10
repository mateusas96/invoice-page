
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Invoice</title>
  <link rel="icon" href="{!! asset('./img/invoice_title.png')!!}"/> 

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
    <style>
        .btn {
            background-color: #3490dc;
            border: none;
            color: white;
            padding: 4px 13px;
            cursor: pointer;
            font-size: 10px;
        }
        .navbar {
            background: linear-gradient(to right, #ff5f6d, #ffc371);
        }
        .container{
            margin-top: 50px;
        }

    </style>
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/home" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="./img/invoice.png" alt="Invoice logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">Invoice tool</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a class="d-block">Hello, {{Auth::user()->name}}!</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-closed">
            <a class="nav-link">
            <i class="fas fa-scroll nav-icon red"></i>
              <p>
                Manage Invoices
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <router-link to="/my-invoices" class="nav-link">
                <i class="fas fa-file-invoice nav-icon green"></i>
                  <p>My Invoices</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/send-invoice" class="nav-link">
                <i class="fas fa-file-invoice-dollar nav-icon pink"></i>
                  <p>Send Invoice</p>
                </router-link>
              </li>
            </ul>
          </li>
          @can('isAdmin')
          <li class="nav-item">
            <router-link to="/users-management" class="nav-link">
            <i class="nav-icon fas fa-users-cog orange"></i>
              <p>
                Users Management
              </p>
            </router-link>
          </li>
          @endcan

          <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="nav-icon fas fa-user purple"></i>
              <p>
                Profile
                
              </p>
            </router-link>
          </li>
            <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt indigo"></i>
                        <p>
                            {{ __('Logout') }}
                        </p>
             </a>

            <form id="logout-form" action="{{ route('logout') }}" 
                method="POST" style="display: none;">
             @csrf
            </form>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol> -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <vue-progress-bar></vue-progress-bar>
        <router-view></router-view>
      </div><!-- /.container-fluid -->
      <main class="py-4">
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
  </footer>
</div>
<!-- ./wrapper -->
@yield('content')

<script src="{{ mix('/js/app.js') }}"></script>
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
<script>
function check(){
  axios.get('api/checkForUnpaidInvoices')
  .then(({data})=>{
    if(data.length!=0){
      Swal.fire({
        toast: true,
        position: 'top',
        icon: 'warning',
        title: 'You have unpaid invoices!',
        timer: 5000,
        showConfirmButton: false,
      })
    }
  })
}
check()
setInterval(function(){check()}, 15000)
</script>
@yield('javascripts')
</body>
</html>

