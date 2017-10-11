<style type="text/css">
  .error{ color: red; }
</style>
<div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1>Thêm biểu mẫu</h1>
            <ul class="breadcrumb side">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li>Trang chủ</li>
              <li class="active"><a href="#">Thêm biểu mẫu</a></li>
            </ul>
          </div>
          <div><a class="btn btn-primary btn-flat" href="#"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" href="#"><i class="fa fa-lg fa-refresh"></i></a><a class="btn btn-warning btn-flat" href="#"><i class="fa fa-lg fa-trash"></i></a></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url(); ?>them" method="post" enctype="multipart/form-data">
                      <fieldset>
                        <legend>Thêm biểu mẫu</legend>
                        <div><span class="success"><?php if(isset($b_Check) && $b_Check == false){echo "Tài khoản không đúng. Xin vui lòng đăng nhập lại !";}?></span></div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="ten">Tên biểu mẫu</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="ten" id="ten" type="text" placeholder="tên biểu mẫu" value="<?php echo set_value('ten')?>">
                            <div class="error" id="ten_error"><?php echo form_error('ten')?></div>
                          </div>
                        </div>    
                        <div class="form-group">
                          <label class="col-lg-2 control-label">File mẫu</label>
                          <div class="col-lg-10">
                            <input class="form-control" type="file" name="file" id="i_file" required="">
                            <div class="error" id="file_error"><?php echo form_error('file')?></div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="inputPassword">Mô tả</label>
                          <div class="col-lg-10">
                            <input class="form-control" name="mota" id="inputPassword" type="text" placeholder="Mô tả" value="<?php echo set_value('mota')?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-10 col-lg-offset-2">
                            <button class="btn btn-default" type="reset">Hủy bỏ</button>
                            <button class="btn btn-primary" id="i_submit" type="submit">Gửi</button>
                          </div>
                        </div>
                      </fieldset>
                    </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script type="text/javascript">
        $('#i_submit').click( function(e) {
           //kiem tra trinh duyet co ho tro File API
            if (window.File && window.FileReader && window.FileList && window.Blob)
            {
              // lay dung luong va kieu file tu the input file
                var ftype = $('#i_file')[0].files[0].type;
               // .alert( ftype);
               switch(ftype)
                {
                    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        break;
                    default:
                        alert('File chỉ được Excel hoặc Word');
                        e.preventDefault();
                }
         
            }else{
                alert("Please upgrade your browser, because your current browser lacks some new features we need!");
            }
        });
      </script>