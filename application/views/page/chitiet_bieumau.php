<div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1>Chi tiết biểu mẫu</h1>
            <ul class="breadcrumb side">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li>Trang chủ</li>
              <li class="active"><a href="#">Chi tiết biểu mẫu</a></li>
            </ul>
          </div>
          <div><a class="btn btn-primary btn-flat" href="#"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" href="#"><i class="fa fa-lg fa-refresh"></i></a><a class="btn btn-warning btn-flat" href="#"><i class="fa fa-lg fa-trash"></i></a></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div style="text-align: center;"><h3><?php echo $ten_bieumau ?></h3></div>
              <div class="card-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th>Tên vùng dữ liệu</th>
                      <th>Loại dữ liệu</th>
                      <?php if ($type == 'excel') {
                        echo '<th>Vị trí xuất Excel (cột:hàng)</th>';
                      }else{
                        echo '<th>Tên biến</th>';
                      } ?>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // var_dump($dulieu);
                    if ($type == 'excel') {
                      for ($i=1; $i <= count($dulieu); $i++) { ?>
                        <tr>
                          <td><?php echo $dulieu[$i]['ten'] ?></td>
                          <td><?php echo $dulieu[$i]['loai'] ?></td>
                          <td>(<?php echo $dulieu[$i]['cot'].':'.$dulieu[$i]['hang'] ?>)</td>
                        </tr>
                      <?php } }else{ 
                          for ($i=1; $i <= count($dulieu); $i++) { ?>
                          <tr>
                            <td><?php echo $dulieu[$i]['ten'] ?></td>
                            <td><?php echo $dulieu[$i]['loai'] ?></td>
                            <td>(<?php echo $dulieu[$i]['search'] ?>)</td>
                        </tr>
                     <?php } } ?>
                  </tbody>
                </table>
               
              </div>
            </div>
          </div>
        </div>
      </div>