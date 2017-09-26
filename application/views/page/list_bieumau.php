<div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1>Danh sách biểu mẫu</h1>
            <ul class="breadcrumb side">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li>Trang chủ</li>
              <li class="active"><a href="#">Danh sách biểu mẫu</a></li>
            </ul>
          </div>
          <div><a class="btn btn-primary btn-flat" href="#"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" href="#"><i class="fa fa-lg fa-refresh"></i></a><a class="btn btn-warning btn-flat" href="#"><i class="fa fa-lg fa-trash"></i></a></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      
                      <th>Tên biểu mẫu</th>
                      <th>File mẫu</th>
                      <th>Mô tả</th>
                      <th>Xem chi tiết</th>
                      <th>Sửa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bieumaus as $bieumau): ?>
                        <tr>
                         
                          <td id="ten_bm<?php echo $bieumau->id?>"><?php echo $name=$bieumau->ten ?></td>
                          <td id="file_bm<?php echo $bieumau->id?>"><?php echo $file=$bieumau->file ?></td>
                          <td id="mota_bm<?php echo $bieumau->id?>"><?php echo $desc=$bieumau->mota ?></td>
                          <td><a class="btn btn-info btn-flat" href="<?php echo base_url().'chitiet_bieumau/'.$bieumau->id ?>"><i class="fa fa-lg fa-eye"></i></a></td>
                          <td><a class="btn btn-primary btn-flat" data-toggle="modal" data-name="<?php echo $name;?>" data-file="<?php echo $file;?>" data-id="<?php echo $bieumau->id;?>" data-desc="<?php echo $desc;?>" href="#edit"><i class="fa fa-lg fa-pencil"></i></a></td>
                        </tr>                    
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
        <div class="modal fade" id="edit">
           <div class="modal-dialog">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss='modal' aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
                 <h4 class="modal-title" style="font-size: 20px; padding: 12px;"> Sửa biểu mẫu </h4>
              </div>
              <form method="post" id="edit-form">
              <div class="modal-body">
                 <div class="container-fluid">
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="form-group">
                             <div class="input-group">
                                <div class="input-group-addon iga2">
                                   <span class="glyphicon glyphicon-user"></span>
                                </div>
                                <input type="hidden" name="id" value="">
                                <input type="text" class="form-control" name="name">
                             </div>
                          </div>
                          <div class="form-group">
                             <div class="input-group">
                                <div class="input-group-addon iga2">
                                   <span class="glyphicon glyphicon-comment"></span>
                                </div>
                                <input type="text" class="form-control" name="desc">
                             </div>
                          </div>
                          <div class="form-group">
                             <div class="input-group">
                                <div class="input-group-addon iga2">
                                   <span class="glyphicon glyphicon-file"></span>
                                </div>
                                <input type="text" class="form-control" name="file" disabled="" id="f_in">
                                <div class="input-group-addon iga2">
                                   <label class="glyphicon">Browse...<input onchange="showFile(this);" type="file" id="file_input" name="file_input" style="display: none;"></label>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>

              <div class="modal-footer">
                 <div class="form-group">
                    <button type="button" class="btn btn-sm btn-info" data-dismiss="modal" id="btn-sbmt"> Save <span class="glyphicon glyphicon-saved"></span></button>

                    <button type="button" data-dismiss="modal" class="btn btn-sm btn-default"> Cancel <span class="glyphicon glyphicon-remove"></span></button>
                 </div>
              </div>
              </form>
            </div>
          </div>
        </div>
<script type="text/javascript">
function showFile(fileName) {
    if (fileName.files && fileName.files[0]) {
        $('#f_in').val(fileName.files[0].name);
    }
}
//triggered when modal is about to be shown
$('#edit').on('show.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    var id_bm = $(e.relatedTarget).data('id');
    var name = $(e.relatedTarget).data('name');
    var file = $(e.relatedTarget).data('file');
    var desc = $(e.relatedTarget).data('desc');

    //populate the textbox
    $(e.currentTarget).find('input[name="name"]').val(name);
    $(e.currentTarget).find('input[name="id"]').val(id_bm);
    $(e.currentTarget).find('input[name="desc"]').val(desc);
    $(e.currentTarget).find('input[name="file"]').val(file);

    $('#btn-sbmt').one('click',function(){
    var route="<?= base_url()?>bieumau/edit_bieu_mau/";
    var frm = new FormData($('form#edit-form')[0]);
        $.ajax({
        url:route,
        processData:false,
        contentType:false,
        type:'post',
        dataType:'json',
        data:frm,
        success:function(data) { 
          //console.log(data[0].file);
          var changeName = document.getElementsByName("name")[0].value;
          var changeFile = data[0].file;
          var changeDesc = document.getElementsByName("desc")[0].value;
          $('#ten_bm'+id_bm).html(changeName);
          $('#file_bm'+id_bm).html(changeFile);
          $('#mota_bm'+id_bm).html(changeDesc);
          // $('#ten_bm'+id_bm).html(document.getElementsByName("name")[0].value);
          // $('#file_bm'+id_bm).html(document.getElementsByName("file")[0].value);
          // $('#mota_bm'+id_bm).html(document.getElementsByName("desc")[0].value);
          $('#edit-form').removeData();
          $('#file_input').val('');
          $(e.relatedTarget).data('name',changeName);
          $(e.relatedTarget).data('file',changeFile);
          $(e.relatedTarget).data('desc',changeDesc);
        }
    });
  });
});

</script>