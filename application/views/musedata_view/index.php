<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="akacolor">Project</li>   
      </ol>            
      <h3 style="margin-left:13px;">Muse Project List</h3>  
  </div>  <!-- Nav or Head title End -->
  <br>    

  <div class="row" id="chart">    
    <!-- ajax -->
  </div>  

</div>
<script src="/public/js/Chart.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('div#chart').css('display','none');
  $('div').fadeIn(1300);          

  var usr_id = "<?=$this->session->userdata('id');?>";
  console.log(usr_id);
  $.post('/musedata/project/get_project/',{usr_id:usr_id},function(json){
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
      var completed = values['completed'];
      var total = values['total_count'];
      
      var tbd = values['tbd'];
      var date = values['add_date'].substr(0,10);
      var todo = total - tbd - completed;
      var avg_total = total-tbd
      var avg = completed/avg_total*100;
      var kind_name = values['kind_name'];

      if(disc.length > 115){
        disc = disc.substr(0,115)+'...';
      }
      $('div#chart').append('<div class="col-lg-3" style="margin-bottom:20px;">'
                              +'<div class="col-lg-12 line">'
                                +'<h5 style="margin-top:10px;">'+pj_name+'</h5>'
                                +'<h6 class="text-right">'+kind_name.toUpperCase()+'&nbsp;&nbsp;&nbsp;'+date+'</h6>'
                              +'</div>'                              
                              +'<div class="col-lg-12 line href" style="height:180px;" href="/musedata/project/board/todo/'+pj_id+'/'+usr_id+'">'
                                +'<canvas  style="margin-left:28px; margin-top:5px; cursor: pointer;" id="pjc'+i+'" width="170" height="170"></canvas>'
                                  +'<p style="margin-top:-100px; margin-left:87px; font-size:17px; cursor: pointer;">'+avg.toFixed(1)+'%</p>'
                                +'</div>'
                              +'<div class="col-lg-12 line service_bg" style="height:72px;">'
                                +'<p class="font-white" style="margin-top:5px;">'+disc+'</p>'
                              +'</div>'
                            +'</div>');

      var ctx = document.getElementById("pjc"+i).getContext("2d");
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

    }); // each end
  }); // Post
}); // ready

$('div').delegate('div.href','click',function(){
  var href = $(this).attr('href');
  window.location.href = href;  
});

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