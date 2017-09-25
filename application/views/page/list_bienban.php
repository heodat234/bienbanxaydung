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
          <div><a class="btn btn-primary btn-flat" href="#"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" href="#"><i class="fa fa-lg fa-refresh"></i></a><a class="btn btn-warning btn-flat" href="#"><i class="fa fa-lg fa-trash"></i></a></div>
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
                      <th>Xuất Excel</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bienbans as $bienban): ?>
                        <tr>
                         
                          <td><?php echo $bienban->ten_bienban ?></td>
                          <td><?php echo date("d/m/Y H:i:s",strtotime($bienban->created_at))  ?></td>
                          <td><a class="btn btn-info btn-flat" href=""><i class="fa fa-lg fa-eye"></i></a></td>
                          <td></td>
                          <td></td>
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
        <!-- <div class="modal fade" id="edit">
           <div class="modal-dialog">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss='modal' aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
                 <h4 class="modal-title" style="font-size: 32px; padding: 12px;"> Recover Your Password </h4>
              </div>
              <form method="post" action="forgot-password">
              <input type="hidden" name="_token" value="">
              <div class="modal-body">
                 <div class="container-fluid">
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="form-group">
                             <div class="input-group">
                                <div class="input-group-addon iga2">
                                   <span class="glyphicon glyphicon-envelope"></span>
                                </div>
                                <input type="email" class="form-control" placeholder="Enter Your E-Mail " name="email">
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>

              <div class="modal-footer">
                 <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-info"> Save <span class="glyphicon glyphicon-saved"></span></button>

                    <button type="button" data-dismiss="modal" class="btn btn-lg btn-default"> Cancel <span class="glyphicon glyphicon-remove"></span></button>
                 </div>
              </div>
              </form>
            </div>
          </div>
        </div> -->