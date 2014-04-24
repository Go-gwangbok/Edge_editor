<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="active">Project List </li>   
      </ol>
      <h3 class="text-center">Project List</h3>
      <?      
      if($cate == 'project'){
        if(count($all_usr) == 0){
      ?>
        <a class="btn btn-default btn-sm pull-right" href="/project/new_pj" id="new_pj" role="button" style="border-color:#6799FF; color:#6799FF; margin-right:15px;" disabled><span class="glyphicon glyphicon-plus"></span> New Project</a>    
      <?
        }else{
      ?>
        <a class="btn btn-default btn-sm pull-right" href="/project/new_pj" id="new_pj" role="button" style="border-color:#6799FF; color:#6799FF; margin-right:15px;"><span class="glyphicon glyphicon-plus"></span> New Project</a>    
      <?
        }
      }
      ?>       
  </div>
  
  <br>    
  <div class="row">
    <?    
      foreach ($pjlist as $rows) {
      $id = $rows->pj_id; // project id
      $name = $rows->name;
      $disc = $rows->disc;
      $date = substr($rows->add_date,0,16);
      $disc_length = strlen($disc);
      $tbd = $rows->tbd;       
    ?>
  
    <div class="col-lg-3">
      <h4><?=$name?>
        <!-- Button trigger modal -->
        <?
        if($cate == 'project'){
        ?>
        <button class="btn btn-default btn-xs pull-right" id="pj_del" data-toggle="modal" data-target="#del<?=$id;?>" style="border-color:#BDBDBD; color:#BDBDBD;">
          <span class="glyphicon glyphicon-trash"></span> 
        </button> 
        <? } ?>
      </h4>

      <!-- Modal -->
      <div class="modal fade" id="del<?=$id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel" style="color:black;">Delete</h4>
            </div>
            <div class="modal-body">              
                <p style="color:black;"><b><?=$name?></b>&nbsp; Are you sure?</p>              
                <p style="color:red;">When a project is deleted, all the data in the project will also be deleted!</p>             
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="del_pj" pjid="<?=$id;?>" >Yes</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <?
      if($disc_length > 200){
      ?>
      <p style="height:80px;"><?=substr($disc,0,200).'...'?></p>
      <?
      }else{
      ?>
      <p style="height:80px;"><?=$disc;?></p>
      <?
      }
      ?>      
      <br>
      <div class="pull-right">        
        <?
        if($tbd == 0 && $cate != 'project'){ // Editor 에서만 보임
        ?>
          <a class="btn btn-default btn-sm" href="#" role="button" style="width:78px; height:30px;" disabled>T.B.D <span class="badge">00</span></a>&nbsp;&nbsp;                
        <?
        }elseif($cate != 'project'){ // Editor 에서만 보임
        ?>
          <a class="btn btn-default btn-sm" href="/project/board/discuss/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button" style="width:78px; height:30px;">T.B.D <span class="badge">0<?=$tbd;?></span></a>&nbsp;&nbsp;                
        <?
        }
        ?>        
        <span style="margin-top:3px;"><?=$date;?></span>        
      </div>
      <br>
      <hr>      
        <?
        if($cate == 'project'){ //admin
        ?>
        <!-- <a class="btn btn-danger btn-sm" href="/project/distribute/<?=$id;?>" role="button">Distribute &raquo;</a> -->
        <a class="btn btn-default btn-sm pull-right" href="/project/status/<?=$id;?>" role="button">Status &raquo;</a>
        <a class="btn btn-primary btn-sm" href="/project/import/<?=$id;?>" role="button">Import &raquo;</a>
        <a class="btn btn-danger btn-sm" href="/project/export/<?=$id;?>" role="button">Export &raquo;</a>
        <?
        }else{ // editor
        ?>
        <a class="btn btn-danger btn-sm " href="/project/board/todo/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button">To do</a>                
        <a class="btn btn-success btn-sm" href="/project/board/done/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button">Completed</a>        
        
        <a class="btn btn-primary btn-sm pull-right" href="/project/board/history/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button">History</a>
        <?
        }
        ?>        
      <br>
    </div>
    <?
    }
    ?>        
  </div>
</div>
<script>
$("button#pj_del").mouseover(function(){
  $(this).css("border-color","red");
  $(this).css("color","red");
  $(this).css("background","white");
});

$("button#pj_del").mouseout(function(){
  $(this).css("border-color","#BDBDBD");
  $(this).css("color","#BDBDBD");
  $(this).css("background","white");
});

$("a#new_pj").mouseover(function(){
  $(this).css("border-color","red");
  $(this).css("color","red");
  $(this).css("background","white");
});

$("a#new_pj").mouseout(function(){
  $(this).css("border-color","#6799FF");
  $(this).css("color","#6799FF");
  $(this).css("background","white");
});

$("button#del_pj").click(function(){
  var pjid = $(this).attr("pjid");
  console.log(pjid);
  $.ajax({
        type: "POST",
        url: '/project/del_project',
        data: { pj_id : pjid },
        dataType: 'json',
        success: function(json) {
          console.log(json['result']);              
          
            if(json['result']) {
              //alert('Project has been deleted!');
              location.reload();
            }
            else
            {
              alert(json['result']);  
              
            }                            
        }
    }); 
});

</script>