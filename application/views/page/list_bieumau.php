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
                      <th>Dữ liệu</th>
                      <th>Xem chi tiết</th>
                      <th>Sửa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bieumaus as $bieumau): ?>
                        <tr>
                         
                          <td><?php echo $bieumau->ten ?></td>
                          <td><?php echo $bieumau->file ?></td>
                          <td><?php echo $bieumau->dulieu ?></td>
                          <td><a class="btn btn-info btn-flat" href="<?php echo base_url().'chitiet_bieumau/'.$bieumau->id ?>"><i class="fa fa-lg fa-eye"></i></a></td>
                          <td><a class="btn btn-primary btn-flat" href="#"><i class="fa fa-lg fa-pencil"></i></a></td>
                        </tr>                    
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>