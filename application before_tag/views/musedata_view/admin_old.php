<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="akacolor">Project</li>   
      </ol>            
      <h3 style="margin-left:13px;">Muse Project List
        <?      
        if($cate == 'admin'){
          if(count($all_usr) == 0){
        ?>
          <a class="btn btn-default btn-sm pull-right" href="/musedata/project/new_pj" id="new_pj" role="button" style="border-color:#6799FF; color:#6799FF; margin-right:15px;" disabled><span class="glyphicon glyphicon-plus"></span> New Project</a>    
        <?
          }else{
        ?>
          <a class="btn btn-default btn-sm pull-right" href="/musedata/project/new_pj" id="new_pj" role="button" style="border-color:#6799FF; color:#6799FF; margin-right:15px;"><span class="glyphicon glyphicon-plus"></span> New Project</a>
        <?
          }
        }
        ?>       
      </h3>  
  </div>  <!-- Nav or Head title End -->
  <br>    

  <div class="row">
    <?    
      foreach ($pjlist as $rows) {
      $id = $rows->pj_id; // project id
      $name = $rows->name;
      $disc = $rows->disc;
      $date = substr($rows->add_date,0,16);
      $disc_length = strlen($disc);      
      $total_count = $rows->total_count;
    ?>
    
    <div class="col-lg-3" style="margin-bottom:20px;">      
      <div class="col-lg-12 line">      
        <h4 style="margin-top:15px;"><?=$name?>
          
          <!-- Button trigger modal -->
          <?
          if($cate == 'admin'){
          ?>
          <button class="btn btn-default btn-xs pull-right" id="pj_del" data-toggle="modal" data-target="#del<?=$id;?>" style="border-color:#BDBDBD; color:#BDBDBD;">
            <span class="glyphicon glyphicon-trash"></span> 
          </button> 
          <!-- Modal -->
          <div class="modal fade" id="del<?=$id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h5 class="modal-title" id="myModalLabel" style="color:black;">Delete</h5>
                </div>
                <div class="modal-body">              
                    <p style="color:black; font-size:15px;"><b><?=$name?></b>&nbsp; Are you sure?</p>              
                    <p style="color:red; font-size:14px;">When a project is deleted, all the data in the project will also be deleted!</p>             
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="del_pj" pjid="<?=$id;?>" >Yes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          <? } ?>
        </h4>
        
        <?
        if($disc_length > 115){
        ?>
        <p style="height:80px; word-wrap: break-word; width: 230px;"><span><?=substr($disc,0,120).'...'?></span></p>
        <?
        }else{
        ?>        
        <p style="height:80px; word-wrap: break-word; width: 230px;" class="line"><?=$disc?></p>        
        <?
        }
        ?>  

        <br>
        <p><span>Total : </span><span class="pull-right"><?=$total_count;?></span></p>     
        <p style="margin-top:-10px;"><span>Date : </span><span class="pull-right"><?=$date;?></span></p>     
          <?
          if($cate == 'admin'){ //admin
          ?>
          <!-- <a class="btn btn-danger btn-sm" href="/project/distribute/<?=$id;?>" role="button">Distribute &raquo;</a> -->
          <a href="/musedata/project/import/<?=$id;?>" role="button">
              <div class="col-lg-12 line text-center" style="margin-bottom:1px;">Import</div>
          </a>                
          <a href="/musedata/project/export/<?=$id;?>" role="button">
              <div class="col-lg-12 line text-danger text-center" style="margin-bottom:1px;">Export</div>
          </a>                
          <a href="/musedata/project/members/<?=$id;?>" role="button">
              <div class="col-lg-12 line text-success text-center" style="margin-bottom:1px;">Members</div>
          </a>                
          <a href="/musedata/project/stats/<?=$id;?>" role="button">
              <div class="col-lg-12 line text-center" style="margin-bottom:1px;">Stats</div>
          </a> 
          <!-- <a class="btn btn-default btn-sm pull-right" href="/project/status/<?=$id;?>" role="button">Status &raquo;</a> -->
          <?
          }else{ // editor
          ?>
            <a href="/musedata/project/board/todo/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button">
              <div class="col-lg-12 line text-danger" style="margin-bottom:1px;">To do</div>
            </a>                
          
            <a href="/musedata/project/board/com/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button">
              <div class="col-lg-12 line text-success" style="margin-bottom:1px;">Completed</div>
            </a>        
          
          <?
          if($cate == 'editor'){
          ?>
            <a href="/musedata/project/board/tbd/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button" style="width:78px; height:30px;">
              <div class="col-lg-12 line text-warning" style="margin-bottom:1px;">T.B.D <!-- <span class="badge pull-right"><?=$tbd;?></span> --></div>
            </a>
          <?
          }else if($tbd == 0 && $cate == 'editor'){
          ?>
            <a href="/musedata/project/board/tbd/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button" style="width:78px; height:30px;">
              <div class="col-lg-12 line text-warning" style="margin-bottom:1px;">T.B.D <span class="badge pull-right">00</span></div>
            </a>
          <?
          }
          ?>
          <a href="/musedata/project/board/history/<?=$id;?>/<?=$this->session->userdata('id');?>" role="button">
            <div class="col-lg-12 line" style="margin-bottom:1px;">History</div>
          </a>
          <?
          }
          ?>        
        <br>
      </div> <!--div col-12 -->
    </div> <!--div col-3 -->
    <?
    }
    ?>        
  </div> <!-- row -->  

</div>
<script type="text/javascript">
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
        url: '/musedata/project/del_project',
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