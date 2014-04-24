<!-- Main jumbotron for a primary marketing message or call to action -->

<div class="background">
  <div class="container">
    <br>
    <h1 style="margin-left:40px; color:white;">Administrator<span style="font-size:18px;">2.0</span></h1>    
    <br>
    <?
    if(!$this->session->userdata('is_login')){
    ?>   
    <form class="form-inline" style="margin-left:40px;" role="form">                        
        <div class="form-group">
        <input type="text" class="form-control" id="inputEmail" placeholder="Email address">
        </div>
        <div class="form-group">
        <input type="password" class="form-control" id="inputPassword" placeholder="Password">          
        </div>
        <button class="btn btn-md btn-primary" type="submit" id="SignIn">Sign in</button>
    </form>

  </div>
</div>
<? }else{ ?>
    </div>
</div>
<br>
<br>

<div class="container">

    <div class="row">

        <div class="main">

            <div class="container">

              <div class="row">
                
                <div class="col-md-6 col-xs-12">

                    <div class="widget stacked">
                        
                        <div class="widget-header">
                            <?
                            if($this->session->userdata('is_login')){
                                if($this->session->userdata('classify') == 0){ //admin
                                    if($recent_notice != false){                                
                            ?>                                
                                <h4>
                                    <span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"><?=substr($recent_notice->date,0,-3);?></span>
                                    <a href="/notice" class="btn btn-xs btn-default pull-right"><b><span class="glyphicon glyphicon-pushpin"></span>&nbsp;&nbsp;Write</b></a>
                                </h4>   
                                
                                <?
                                }else{ // notice가 아무것도 없을때!
                                ?>
                                
                                <h4><span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"></span>
                                    <a href="/notice" class="btn btn-xs btn-default pull-right"><b><span class="glyphicon glyphicon-pushpin"></span>&nbsp;&nbsp;Write</b></a>
                                </h4>   
                                
                                <?
                                    }
                                }else{ //editor
                                    if($recent_notice != false){
                                ?>                                
                                
                                <h4>
                                    <span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"><?=substr($recent_notice->date,0,-3);?></span>                              
                                </h4>    
                                
                                <?
                                }else{
                                ?>
                                
                                <h4>
                                    <span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"></span>                              
                                </h4>    

                                <?
                                    }
                                }// not login
                            }else{
                                if($recent_notice != false){
                                ?>
                                <h4><span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px; text-align:right;"><?=substr($recent_notice->date,0,-3);?></span>                                
                                </h4>    
                                <?
                                }else{
                                ?>
                                <h4><span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px; text-align:right;"></span>                                
                                </h4>    
                                <?
                                }
                            }?>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">
                            <dl>
                                <?
                                if($recent_notice != false){
                                ?>
                                <dt><h4><?=substr($recent_notice->title,0,85).'...';?></h4></dt>
                                <dd><?=$recent_notice->contents;?></dd>
                                <? } ?> <!-- notice가 아무것도 없을때! -->
                            </dl>
                        </div> <!-- /widget-content -->
                    
                    </div> <!-- /widget -->
                    <br/>
                    <hr>
                    <br/>
                    
                    <div class="widget widget-nopad stacked">
                                
                        <div class="widget-header">
                            <?
                            if($this->session->userdata('is_login')){                              
                            ?>
                            <h4><span class="glyphicon glyphicon-list"></span>&nbsp;Notices
                                <a href="/notice" class="btn btn-xs btn-default pull-right"><b><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;More</b></a>
                            </h4>

                            <?}else{?>
                            <h4><span class="glyphicon glyphicon-list"></span>&nbsp;Notices</h4>
                            <?}?>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">
                            
                            <ul class="news-items">
                                <?
                                foreach ($notice as $value) {
                                    $title = strip_tags($value->title);
                                    $contents = strip_tags($value->contents);
                                    $date = $value->date;
                                    $short_cont = substr($contents, 0,85).'...';
                                    $id = $value->id;
                                ?>
                                <li>                                    
                                    <div class="news-item-detail">                                      
                                        <a href="/notice/contents/<?=$id?>" class="news-item-title"><?=substr($title,0,64).'...';?></a>
                                        <p class="news-item-preview" ><?=$short_cont?></p>
                                    </div>
                                    
                                    <div class="news-item-date text-right" style="margin-top:-20px;">
                                        <b><span class="news-item-day "><?=substr($date,0,-3);?></span></b>
                                    </div>
                                </li><br/>
                                <?
                                }
                                ?>                              
                            </ul>
                            
                        </div> <!-- /widget-content -->
                    
                    </div> <!-- /widget --> 

                </div> <!-- /span6 -->
                
                
<!-- Quick -->  <div class="col-md-6">  
                    
                    
                    <div class="widget stacked">
                            
                        <div class="widget-header">                            
                            <h4><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Project<span><a href="/musedata/project"><button class="btn btn-default btn-xs pull-right">More</button></a></span></h4>
                        </div> <!-- /widget-header -->

                        <div class="widget-content">
                            
                            <div class="pull-center">                                
                                <?
                                if($this->session->userdata('is_login')){
                                ?>   
                                
                                    <?
                                    if($this->session->userdata('classify') == 0){ //admin
                                    ?>
                                    <h3>
                                    <a href="/project" class="shortcut btn btn-default btn-lg">
                                        <i class="glyphicon glyphicon-inbox"></i>&nbsp; Project
                                    </a>
                                    
                                    <a href="/status" class="shortcut btn btn-default btn-lg">
                                        <i class="glyphicon glyphicon-eye-open"></i> Status
                                    </a>

                                    <a href="/notice/notice_list" class="shortcut btn btn-default btn-lg">
                                        <i class="glyphicon glyphicon-bullhorn"></i> Notice
                                    </a>

                                    <a href="/neweditor" class="shortcut btn btn-default btn-lg" id="newEditor" disabled>
                                        <i class="glyphicon glyphicon-user"></i> New Editor
                                    </a>
                                    </h3>
                                    <?
                                    }else{ // Editor
                                        $usr_id = $this->session->userdata('id');
                                        
                                        foreach ($pj_list as $value) {
                                            $pjname = $value->name;
                                            $pj_id = $value->pj_id;
                                            $disc = substr($value->disc,0,45);

                                            // 프로젝트 이름이 길면 줄여서 표시함!
                                            if(strlen($pjname) > 14){
                                                $pjname = substr($pjname,0,14).'...';
                                            }                                           
                                    ?>
                                            <div class="col-md-4 line line-radius">
                                                <p class="text-center" style="margin-top:5px;"><?=$pjname;?></p>
                                                <a class="btn btn-default btn-xs" style="width:75px; margin-bottom:8px;" href="/musedata/project/board/todo/<?=$pj_id;?>/<?=$usr_id?>">Todo</a>
                                                <a class="btn btn-default btn-xs" style="width:74px; margin-bottom:8px;" href="/musedata/project/board/com/<?=$pj_id;?>/<?=$usr_id?>">Completed</a>                                                
                                            </div>                                        
                                    <?
                                        }                                    
                                    }
                                    ?> 
                                    <br><br>
                                <?}else{?>
                                <h3>
                                    <a href="#" class="shortcut btn btn-default btn-lg" disabled>
                                        <i class="glyphicon glyphicon-pencil"></i> To do
                                    </a>
                                    
                                    <a href="#" class="shortcut btn btn-default btn-lg" disabled>
                                        <i class="glyphicon glyphicon-edit"></i> Completed
                                    </a>                           

                                    <a href="#" class="shortcut btn btn-default btn-lg" disabled>
                                        <i class="glyphicon glyphicon-bullhorn"></i> Notice
                                    </a>         

                                    <a href="#" class="shortcut btn btn-default btn-lg" disabled>
                                        <i class="glyphicon glyphicon-edit"></i> Memo
                                    </a>
                                    
                                </h3>
                                <?}?>
                                
                            </div> <!-- /shortcuts -->  
                        
                        </div> <!-- /widget-content -->
                        
                    </div> <!-- /widget -->
                    <br/>
                    <br/>
                    
                    <div class="widget stacked">
                            
                        <div class="widget-header">
                            
                            <h4><span class="glyphicon glyphicon-stats"></span>&nbsp;Chart</h4>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">                    
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="area-chart" class="chart-holder " style="height:210px; padding-top:40px;">                                    
                                        <?
                                        if($this->session->userdata('is_login')){
                                            if($this->session->userdata('classify') == 0){ // admin
                                        ?>
                                        <dl class="dl-horizontal text-center" style="padding-right:10pxl">                                            
                                            <dt>To do&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #DF4D4D;"></span></dt>
                                            <dd id="todo"></dd>
                                            <dt>Draft&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #E2EAE9;"></span></dt>
                                            <dd id="draft"></dd>
                                            <dt>Completed&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #D4CCC5;"></span></dt>
                                            <dd id="done"></dd>
                                            <hr style="margin-left:70px;">
                                            <dt>TOTAL</dt>
                                            <dd id="total"></dd>
                                        </dl>
                                        <?
                                            }else{ // editor
                                        ?>
                                          <dl class="dl-horizontal text-center" style="padding-right:10pxl">                                            
                                            <dt>To do&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #DF4D4D;"></span></dt>
                                            <dd id="todo"></dd>
                                            <dt>Draft&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #E2EAE9;"></span></dt>
                                            <dd id="draft"></dd>
                                            <dt>Completed&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #D4CCC5;"></span></dt>
                                            <dd id="done"></dd>
                                            <hr style="margin-left:70px;">
                                            <dt>TOTAL</dt>
                                            <dd id="total"></dd>
                                        </dl>
                                        <?
                                            }
                                        }else{
                                        ?>
                                        <dl class="dl-horizontal text-center" style="padding-right:10pxl">
                                            <dt>To do</dt>
                                            <dd>0</dd>
                                            <dt>Draft</dt>
                                            <dd>0</dd>
                                            <dt>Completed</dt>
                                            <dd>0</dd>
                                            <dt>TOTAL</dt>
                                            <dd>0</dd>
                                        </dl>
                                        <?
                                        }
                                        ?>
                                        
                                    </div>              
                                </div> <!-- Chart -->

                                <div class="col-md-6">

                                    <div id="area-chart" class="chart-holder text-center">
                                        
                                    <canvas id="myChart" width="200" height="200"></canvas>

                                    </div>              
                                </div>      
                            </div>  
                        </div> <!-- /widget-content -->
                      
                    </div> <!-- /widget -->
                    <hr>             
<!-- Table -->
                    <br/>
                    <div class="widget stacked widget-table action-table ">
                            
                        <div class="widget-header">
                            
                            <h4><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Editor list</h4>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">
                            
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <!-- <th class="td-actions"></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                    foreach ($usr_list as $value) {
                                        $name = $value->name;
                                        $email = $value->email;
                                    ?>
                                    <tr>
                                        <td><?=$name?></td>
                                        <td><?=$email?></td>
                                        <!-- <td class="td-actions">
                                            <a href="#" class="btn btn-xs btn-primary" disabled>
                                                <i class="btn-icon-only icon-ok"></i>                                       
                                            </a>
                                        </td> -->
                                    </tr>
                                    <?
                                    }
                                    ?>
                                 </tbody>
                            </table>
                            
                        </div> <!-- /widget-content -->
                    
                    </div> <!-- /widget -->
                                        
                  </div> <!-- /span6 -->
                
              </div> <!-- /row -->

            </div> <!-- /container -->
            
        </div> <!-- /main -->        

    </div> <!-- row -->

</div> <!-- container -->
<? } ?>
<!-- javascript -->     
<script src="/public/js/Chart.js"></script>
<script type="text/javascript">

// tooltip
$('[data-toggle="tooltip"]').tooltip({
    'placement': 'top'
});

$(document).ready(function(){
    $.ajax({
            type: "POST",
            url: '/index/chart',                
            //data: {classify:'aa'},
            dataType: 'json',
            success: function(json) {
                    console.log(json['new_editor']);

                    // new_editor
                    if(json['new_editor']){

                        $('a#newEditor').attr('disabled',false);
                        $('a#newEditor').attr('class','shortcut btn btn-danger btn-lg');
                    }
                    // Chart
                    var todo = parseInt(json['chart'][0]);                    
                    var draft = parseInt(json['chart'][1]);
                    var done = parseInt(json['chart'][2]);
                    var total = parseInt(json['chart'][3]);

                    $("dd#todo").text(todo);
                    $("dd#draft").text(draft);
                    $("dd#done").text(done);
                    $("dd#total").text(total);

                    var ctx = document.getElementById("myChart").getContext("2d");
                    
                    console.log(todo);
                    var data = [
                        {
                            value: todo,
                            color: "#DF4D4D"
                        },
                        {
                            value : draft,
                            color : "#E2EAE9"
                        },
                        {
                            value : done,
                            color : "#D4CCC5"
                        }

                    ]

                    var options = {
                        //Boolean - Whether we should show a stroke on each segment
                        segmentShowStroke : true,
                        
                        //String - The colour of each segment stroke
                        segmentStrokeColor : "#fff",
                        
                        //Number - The width of each segment stroke
                        segmentStrokeWidth : 5,
                        
                        //The percentage of the chart that we cut out of the middle.
                        percentageInnerCutout : 35,
                        
                        //Boolean - Whether we should animate the chart 
                        animation : true,
                        
                        //Number - Amount of animation steps
                        animationSteps : 100,
                        
                        //String - Animation easing effect
                        animationEasing : "easeOutBounce",
                        
                        //Boolean - Whether we animate the rotation of the Doughnut
                        animateRotate : true,

                        //Boolean - Whether we animate scaling the Doughnut from the centre
                        animateScale : false,
                        
                        //Function - Will fire on animation completion.
                        onAnimationComplete : null
                    }

                    new Chart(ctx).Doughnut(data,options);
            }
        }); //ajax end
});

$('#SignIn').click(function()
{        
    var form_data = {                
            email: $("#inputEmail").val(),                                                      
            pass: $("#inputPassword").val()             
    };
    //console.log(form_data);                
    $.ajax({
            type: "POST",
            url: '/sign/sign_in_proc',                
            data: form_data,
            dataType: 'json',
            success: function(response) {
                    if(response['status'] == 'true') {                                
                            window.location.replace('/'); // 리다이렉트할 주소
                    }else if(response['status'] == 'empty'){
                        alert("Please enter your email and password!");
                    }else if(response['status'] == 'false'){
                        alert("Incorrect username and password!");
                    }else if(response['status'] == 'wait'){
                        alert("It has not yet been approved! Please wait for approval.");
                    }
            }
    });
    return false;
}); 




</script>
