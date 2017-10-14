<aside class="main-sidebar hidden-print">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image"><img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image"></div>
            <div class="pull-left info">
              <p><?php echo $this->session->userdata('user')['username'] ?></p>
              <p class="designation">Admin</p>
            </div>
          </div>
          <!-- Sidebar Menu-->
          <ul class="sidebar-menu">
            <li class="active"><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i><span>Trang chủ</span></a></li>
            <li class="treeview"><a href="#"><i class="fa fa-file-text"></i><span>Quản lý biểu mẫu</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>list_bieumau"><i class="fa fa-circle-o"></i> Danh sách biểu mẫu</a></li>
                <li><a href="<?php echo base_url(); ?>them_bieumau"><i class="fa fa-circle-o"></i> Thêm biểu mẫu</a></li>
              </ul>
            </li>
            <li class="treeview"><a href="#"><i class="fa fa-list"></i><span>Quản lý biên bản</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>list_bienban"><i class="fa fa-circle-o"></i> Danh sách biên bản</a></li>
                <li><a href="<?php echo base_url(); ?>tao_bienban"><i class="fa fa-circle-o"></i>Tạo biên bản mới</a></li>
              </ul>
            </li>
            <li class="treeview"><a href="#"><i class="fa fa-bars"></i><span>Quản lý công trình</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>list_congtrinh"><i class="fa fa-circle-o"></i> Danh sách công trình</a></li>
                <li><a href="<?php echo base_url(); ?>them_congtrinh"><i class="fa fa-circle-o"></i>Thêm công trình</a></li>
              </ul>
            </li>
          </ul>
        </section>
      </aside>