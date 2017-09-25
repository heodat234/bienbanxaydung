<style type="text/css">
  .error{ color: red; }
</style>
<div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1>Tạo biên bản</h1>
            <ul class="breadcrumb side">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li>Trang chủ</li>
              <li class="active"><a href="#">Tạo biên bản</a></li>
            </ul>
          </div>
          <div><a class="btn btn-primary btn-flat" href="#"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" href="#"><i class="fa fa-lg fa-refresh"></i></a><a class="btn btn-warning btn-flat" href="#"><i class="fa fa-lg fa-trash"></i></a></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="<?php echo base_url(); ?>insert_bienban" method="post" enctype="multipart/form-data">
              <div class="card">
                <div class="card-body">
                  <fieldset>
                    <legend>Tạo biên bản mới</legend>
                    <div><span class="success"><?php if(isset($b_Check) && $b_Check == false){echo "Tài khoản không đúng. Xin vui lòng đăng nhập lại !";}?></span></div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label" for="ten">Tên biên bản</label>
                      <div class="col-lg-10">
                        <input class="form-control" name="ten" id="ten" type="text" placeholder="tên biên bản" value="<?php echo set_value('ten')?>">
                        <div class="error" id="ten_error"><?php echo form_error('ten')?></div>
                      </div>
                    </div>    
                    <div class="form-group ">
                      <label class="col-lg-2 control-label" for="ten">Chọn biểu mẫu</label>
                      <div class="col-lg-10">
                        <select class="form-control" name="id_bieumau" id="select" onchange="load_bieumau()">
                          <?php foreach ($bieumaus as $bieumau) { ?>
                          <option value="<?php echo $bieumau->id ?>"><?php echo $bieumau->ten ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div> 
                  </fieldset>
                </div>
              </div>
             <div class="card">
                <div class="card-body">
                  <legend>Chi tiết biên bản</legend>
                  <div class="dulieu"></div>
                </div>
                <div class="form-group ">
                  <div class="col-lg-10 col-lg-offset-2">
                    <button class="btn btn-default" type="reset">Hủy bỏ</button>
                    <button class="btn btn-primary" type="submit">Gửi</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <script type="text/javascript">
        function load_bieumau() {
          var id = $('#select').val();
          $('.load').remove();
          $.ajax({
            type:'post',
            url:"<?php echo base_url(); ?>load_dulieu",
            data: {id: id},
            dataType: 'json',
            success: function(data){
              for (var i in data) {
                $('.dulieu').before( '<div class="form-group load"> <label class="col-lg-2 control-label">'+data[i].ten+'</label><div class="col-lg-10"><input class="form-control" name='+data[i].id+' id="ten" type='+data[i].loai+' placeholder=""></div></div>' );

              }
              
            }
          });

          
        }
      </script>