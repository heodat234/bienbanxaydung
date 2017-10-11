<div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1>Danh sách biên bản</h1>
            <ul class="breadcrumb side">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li>Trang chủ</li>
              <li class="active"><a href="#">Danh sách biên bản</a></li>
            </ul>
          </div>
          <div><a class="btn btn-primary btn-flat" href="<?php echo base_url(); ?>tao_bienban"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" href="<?php echo base_url(); ?>list_bienban"><i class="fa fa-lg fa-refresh"></i></a><a class="btn btn-warning btn-flat" href="<?php echo base_url(); ?>all_excel"><i class="fa fa-lg fa-file-excel-o"> Xuất tất cả</i></a></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      
                      <th>Tên biên bản</th>
                      <th>Ngày tạo</th>
                      <th>Sửa</th>
                      <th>In</th>
                      <th>Xuất File</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bienbans as $bienban): ?>
                        <tr>
                          <td id="ten_bb<?php echo $bienban->id;?>"><?php echo $bienban->ten_bienban ?></td>
                          <td id="ngay_cn<?php echo $bienban->id;?>"><?php echo date("d/m/Y H:i:s",strtotime($bienban->created_at))  ?></td>
                          <td><button class="btn btn-info btn-flat" data-toggle="modal" data-target="#edit" data-id='<?php echo $bienban->id;?>' data-name='<?php echo $bienban->ten_bienban;?>'><i class="fa fa-lg fa-eye"></i></button></td>
                          <td><a class="btn btn-info btn-flat" href=""><i class="fa fa-lg fa-print"></i></a></td>
                          <td><a class="btn btn-info btn-flat" href="<?php echo base_url().'export_excel/'.$bienban->id ?>"><i class="fa fa-lg fa-file-excel-o"></i></a></td>
                          <!-- <td><a class="btn btn-primary btn-flat" data-toggle="modal" href="#edit"><i class="fa fa-lg fa-pencil"></i></a></td> -->
                        </tr>                    
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
        <div class="modal fade" id="edit" data-backdrop='static'>
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
                             <div><b>Tên biên bản</b></div>
                             <div class="input-group">
                                <div class="input-group-addon iga2">
                                   <span class="glyphicon glyphicon-folder-open"></span>
                                </div>
                                <input type="hidden" name="id" value="">
                                <input type="text" class="form-control" name="name">
                             </div>
                          </div>
                       </div>
                       <div class="col-xs-12 col-sm-12 col-md-12" id="form-data">
                          
                       </div>
                    </div>
                 </div>
              </div>

              <div class="modal-footer">
                 <div class="form-group">
                    <button type="button" class="btn btn-sm btn-info" data-dismiss='modal' id="btn-sbmt"> Save <span class="glyphicon glyphicon-saved"></span></button>

                    <button type="button" data-dismiss="modal" class="btn btn-sm btn-default"> Cancel <span class="glyphicon glyphicon-remove"></span></button>
                 </div>
              </div>
              </form>
            </div>
          </div>
        </div>
<script type="text/javascript">
$('#edit').on('show.bs.modal', function(e) {
  //get data-id attribute of the clicked element
  var id_bb = $(e.relatedTarget).data('id');
  var ten_bb = $(e.relatedTarget).data('name');
  var route = "<?= base_url()?>bienban/edit_bien_ban/";
  //populate the textbox
  $(e.currentTarget).find('input[name="id"]').val(id_bb);
  $(e.currentTarget).find('input[name="name"]').val(ten_bb);

  $.ajax({
    url:route,
    type:'post',
    dataType:'json',
    data:{id:id_bb},
    success:function(data) { 
      $('#form-data').html(data);
    }
  });

  $('#btn-sbmt').one('click',function(){
  var route="<?= base_url()?>bienban/update_bien_ban/";
  var frm = new FormData($('form#edit-form')[0]);
  var changeName = frm.get('name');
      $.ajax({
      url:route,
      processData:false,
      contentType:false,
      type:'post',
      dataType:'json',
      data:frm,
      success:function(data) { 
        $('#ten_bb'+id_bb).html(changeName);
        $('#ngay_cn'+id_bb).html(data.created_at);
        $(e.relatedTarget).data('name',changeName);
      }
    });
  });
});

function showFile(fileName) {
    if (fileName.files && fileName.files[0]) {
        $('#f_in').val(fileName.files[0].name);
    }
}

</script>