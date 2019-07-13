<?php
$baseURL = ($this->session->userdata('user_type') == 'admin') ? base_url('admin') : base_url();
?>
<style type="text/css">
    .right {
  transform: rotate(-90deg);
  -webkit-transform: rotate(-90deg);
}
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}
</style>
<ul class="nav nav-tabs">
    <li class="<?php if($name=='index'){echo 'active';}?>"><a href="<?= $baseURL?>">Home</a></li>
    <?php
    $this->load->model('Common_model');
    $parentModules = $this->common_model->getNavbarByClause(['parentId'=>0]);
    $aAttr = '';
    $permissionArry = json_decode($this->session->userdata('permissions'), true);
    if($parentModules) {
        foreach ($parentModules as $pModule) {
            if($permissionArry && in_array($pModule['id'], $permissionArry)) {
                
                $baseLink = ($this->session->userdata('user_type') == 'admin') ? base_url('admin/'.$pModule['permalink']) : base_url($pModule['permalink']);
                $childModules = $this->common_model->getNavbarByClause(['parentId' => $pModule['id']]);
                if($childModules) 
                    $aAttr  = 'data-toggle="dropdown" dropdown-toggle';
                ?>
                <li class="<?= ($this->router->fetch_method() == $pModule['permalink']) ? 'active' : '' ?>">
                    <a href="<?= $baseLink;?>" <?= $aAttr; ?> ><?= $pModule['module'].((count($childModules)>0) ? '<span class="caret"></span>' :'') ?></a>
                    <?php
                    if(count($childModules)>0){
                        echo '<ul class="dropdown-menu">';
                        foreach ($childModules as $cModule) {
                            $baseLink = ($this->session->userdata('user_type') == 'admin') ? base_url('admin/'.$cModule['permalink']) : base_url($cModule['permalink']);
                            if(in_array($cModule['id'], $permissionArry))
                                if($cModule['module']== 'Online Leads')
                                {
                                     echo '<li class="dropdown-submenu" ><a href="#" class="test" tabindex="-1">'.$cModule['module'].'<span class="caret right"></span></a>';
                                     ?>
                                    <ul class="dropdown-menu">
          <li><a tabindex="-1" href="<?php echo base_url().'admin/acres99_leads'?>">99 Acres</a></li>
          <li><a tabindex="-1" href="<?php echo base_url().'admin/magicbricks_leads'?>">Magic Bricks</a></li>
          <!--<li><a tabindex="-1" href="<?php //echo base_url().'admin/commonfloor_leads'?>">Common Floor</a></li>-->

        </ul></li>
                                     <?php
                                }
                                else
                                {
                                echo '<li><a href="'.$baseLink.'">'.$cModule['module'].'</a></li>';
                                }
                        }
                        echo '</ul>';
                    }
                    ?>
                </li>
                <?php
            }
        }
    }
    ?>
    
    <button class="btn btn-success"><a href="javascript:history.go(-1)">GO BACK</a></button>
</ul>
<script>
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
</script>