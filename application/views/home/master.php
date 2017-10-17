<?php 
if($this->session->has_userdata('user')) {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/main.css.map">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="http://github.com/malsup/media/raw/master/jquery.media.js?v0.92"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.metadata.js"></script> 
    <title>Xây Dựng</title>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
  </head>
  <body class="sidebar-mini fixed">
    <div class="wrapper">
      <?php echo isset($html_header) ? $html_header : ''; ?>
      <?php echo isset($html_menu) ? $html_menu : ''; ?>
      <?php echo isset($html_body) ? $html_body : ''; ?>
      <div class="clearfix"></div>
      <div style="text-align: center;" >
        <p class="copy-right">Design by <a href="http://hungminhit.com/">HungMinhITS</a></p>
      </div>
    </div>
    <!-- Javascripts-->
    <script src="<?php echo base_url(); ?>js/jquery-2.1.4.min.js"></script>
    <!-- <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script> -->
    <script src="<?php echo base_url(); ?>js/plugins/pace.min.js"></script>
    <script src="<?php echo base_url(); ?>js/main.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
  </body>
</html>
<?php 
}else{redirect(base_url('login'));}
?>