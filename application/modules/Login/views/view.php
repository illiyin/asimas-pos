<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>POS Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>
    <!-- normalize & reset style -->
    <link rel="stylesheet" href="<?=base_url();?>assets/css/normalize.min.css"  type='text/css'>
    <link rel="stylesheet" href="<?=base_url();?>assets/css/reset.min.css"  type='text/css'>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/js/notify/pnotify.custom.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/Style-Dark.css" rel="stylesheet">
    <style media="screen">
    body {
            background: url(<?=base_url()?>assets/img/login.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
         }
    </style>
  </head>
  <body>
 <div class="modal fade" id="login-modal" tabindex="-1" role="dialog">
   <div class="modal-dialog">
        <div class="loginmodal-container">
          <img src="<?php echo base_url()?>assets/img/logo.png" alt="logo"  style='max-height: 45px; max-width: 200px; margin: 0 auto'>
          <h1>Silahkan Login</h1><br>
           
          <form id="myform" action="<?php echo base_url('Login/Master/do_login')?>/" method="post"> 
            <input type="text" autofocus name="email" id="email" value="<?php echo isset($email)?$email:''?>" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
           
            <input type="submit" name="submit" id="aLogin" class="login loginmodal-submit" value="Login">
          </form>
          <div class="login-help">
           &copy; <?php echo date("Y");?> POS
          </div>

        </div>
     </div>
   </div>

  <!-- jQuery -->
  <script type="text/javascript" src="<?=base_url()?>assets/js/jquery-2.2.2.min.js"></script>
  <!-- waves material design effect -->
  <script type="text/javascript" src="<?=base_url()?>assets/js/waves.min.js"></script>
  <!-- Bootstrap Core JavaScript -->
  <script type="text/javascript" src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
  
  <script src="<?php echo base_url(); ?>assets/js/notify/pnotify.custom.min.js"></script>


  <script type="text/javascript">
  $(document).ready(function() {
     $('#login-modal').modal('show').on('hide.bs.modal', function (e) {
        e.preventDefault();
     });
  });
  </script>

  <script type="text/javascript">
  $("#myform").on('submit', function(e){
      e.preventDefault();
      var notifText = 'Username/Password anda salah!';
      var action = "<?php echo base_url('Login/Master/do_login')?>/";
      var param = $('#myform').serialize();
      
      $.ajax({
        type: 'post',
        url: action,
        data: param,
        dataType: 'json',
        beforeSend: function() { 
          // tambahkan loading
          $('#aLogin').html('Sedang Login...');
        },
        success: function (data) {
          if(data.status == '0'){
            console.log("status: " + data.status);
            $('#aLogin').html('Login');
            new PNotify({
                        title: 'Login Gagal',
                        text: notifText,
                        type: 'error',
                        hide: true,
                        delay: 5000,
                        styling: 'bootstrap3'
                      });
          }
          else if(data.status == '1') {
            var defRedir = "<?php echo base_url('index/modul/Master_pegawai-master-index')?>";
            var logRedir = "<?php echo base_url('index/login')?>";
            var redir = "<?php echo urldecode($redir)?>";
            if(redir != '') {
              if(redir != logRedir) {
                defRedir = redir;
              }
            }
            window.location.replace(defRedir);
          }
        }
      });
    });
  </script>
 </body>
</html>
