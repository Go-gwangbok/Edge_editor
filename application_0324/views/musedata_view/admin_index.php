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
  <div class="row" id="chart">    
    <!-- Ajax -->     
  </div> 
  <div id="del_modals">

  </div>

</div>
<script src="/public/js/Chart.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var usr_id = "<?=$this->session->userdata('id');?>";
  console.log(usr_id);

  $.post('/musedata/project/admin_get_project/',null,function(json){
    console.log(json['data']);
    var result = json['data'];

    var options = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke : true,
      
      //String - The colour of each segment stroke
      segmentStrokeColor : "#fff",
      
      //Number - The width of each segment stroke
      segmentStrokeWidth : 2,
      
      //The percentage of the chart that we cut out of the middle.
      percentageInnerCutout : 45,
      
      //Boolean - Whether we should animate the chart 
      animation : true,
      
      //Number - Amount of animation steps
      animationSteps : 100,
      
      //String - Animation easing effect
      animationEasing : "easeOutBounce",
      
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate : true,

      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale : true,
      
      //Function - Will fire on animation completion.
      onAnimationComplete : null
    }

    

    $.each(result,function(i,values){
      var pj_id = values['pj_id'];
      var pj_name = values['name'];
      var disc = values['disc'];
      var completed = parseInt(values['completed']);
      var total = parseInt(values['total_count']);
      var tbd = values['tbd'];
      var date = values['add_date'].substr(0,10);
      var todo = values['todo'];
      var todo = total - completed;     

      if(total == 0){
        avg = 00;
      }else{
        var avg = completed/total*100;  
      }     

      if(disc.length > 115){
        disc = disc.substr(0,115)+'...';
      }     

      $('div#chart').append('<div class="col-lg-4" style="margin-bottom:20px;">'
                              +'<div class="col-lg-12 line">'                                
                                +'<h4 style="margin-top:13px;" class="akacolor"><span class="glyphicon glyphicon-list-alt"></span> '+pj_name
                                  +'<button class="btn btn-default btn-xs pull-right" id="pj_del" data-toggle="modal" data-target="#del'+pj_id+'" style="border-color:#BDBDBD; color:#BDBDBD;">'
                                    +'<span class="glyphicon glyphicon-trash"></span>'
                                  +'</button>'
                                +'</h4>'
                                +'<h6 class="text-right akacolor">'+date+'</h6>'
                              +'</div>'                              
                              +'<div class="col-lg-6 line" style="height:180px;">'
                                +'<canvas style="margin-top:15px;" id="pj'+i+'" width="150" height="150"></canvas>'
                                  +'<p style="margin-top:-91px; margin-left:51px; font-size:17px;">'+avg.toFixed(1)+'%</p>'
                              +'</div>'

                              +'<div class="col-lg-3 line service_bg pj_btn href" style="height:90px;" href="/musedata/project/import/'+pj_id+'">'
                                +'<span class="glyphicon glyphicon-import" style="color:#fff;"></span>'
                                +'<h6 class="font-white" style="margin-left:-5px; margin-top:-1px;">Import</h6>'
                              +'</div>'
                              +'<div class="col-lg-3 line service_bg pj_btn href" style="height:90px;" href="/musedata/project/export/'+pj_id+'">'
                              +'<span class="glyphicon glyphicon-new-window" style="color:#fff;"></span>'
                                +'<h6 class="font-white" style="margin-left:-6px; margin-top:-1px;">Export</h6>'                                
                              +'</div>'
                              +'<div class="col-lg-3 line service_bg pj_btn href" style="height:90px;" href="/musedata/project/members/'+pj_id+'">'
                              +'<span class="glyphicon glyphicon-user" style="color:#fff;"></span>'
                                +'<h6 class="font-white" style="margin-left:-12px; margin-top:-1px;">Members</h6>'                                
                              +'</div>'
                              +'<div class="col-lg-3 line service_bg pj_btn href" style="height:90px;" href="/musedata/project/stats/'+pj_id+'">'
                              +'<span class="glyphicon glyphicon-stats" style="color:#fff;"></span>'
                                +'<h6 class="font-white" style=" margin-top:-1px;">Stats</h6>'                                
                              +'</div>'
                            +'</div>');
      if(todo == 0){
        todo = 100;
      }

      var ctx = document.getElementById("pj"+i).getContext("2d");
      var data = [
                  {
                    value: parseInt(completed),
                    color:"#F7464A"
                  },
                  {
                    value : parseInt(todo),
                    color : "#E2EAE9"
                  }

                ]
      var myNewChart = new Chart(ctx).Doughnut(data,options);

      $('div#del_modals').append('<div class="modal fade" id="del'+pj_id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
                                    +'<div class="modal-dialog">'
                                      +'<div class="modal-content">'
                                        +'<div class="modal-header">'
                                          +'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
                                          +'<h5 class="modal-title" id="myModalLabel" style="color:black;">Delete</h5>'
                                        +'</div>'
                                        +'<div class="modal-body">'
                                            +'<p style="color:black; font-size:15px;"><b>'+name+'</b>&nbsp; Are you sure?</p>'
                                            +'<p style="color:red; font-size:14px;">When a project is deleted, all the data in the project will also be deleted!</p>'
                                        +'</div>'
                                        +'<div class="modal-footer">'
                                          +'<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>'
                                          +'<button type="button" class="btn btn-primary" id="del_pj" pjid="'+pj_id+'" >Yes</button>'
                                        +'</div>'
                                      +'</div>'
                                    +'</div>'
                                  +'</div>');
    }); // each end
  }); // Post
}); // ready

$('div').delegate('div.href','click',function(){
  var href = $(this).attr('href');  
  window.location.href = href;  
});

$('div').delegate('button#pj_del','mouseover',function(){
  $(this).css("border-color","red");
  $(this).css("color","red");
  $(this).css("background","white");
});

$('div').delegate('button#pj_del','mouseout',function(){
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


$('div').delegate('button#del_pj','click',function(){
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