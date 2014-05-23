<!-- Main jumbotron for a primary marketing message or call to action -->

<div class="background">
  <div class="container">
    <br>
    <h1 style="margin-left:40px; color:white;">Administrator<span style="font-size:18px;">2.0</span></h1>    
    <br>
    <?php
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
<?php }else{ ?>
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
                            <?php
                            if($this->session->userdata('is_login')){
                                if($this->session->userdata('classify') == 0){ //admin
                                    if($recent_notice != false){                                
                            ?>                                
                                <h4>
                                    <span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"><?=substr($recent_notice->date,0,-3);?></span>
                                    <a href="/notice/write" class="btn btn-xs btn-default pull-right"><b><span class="glyphicon glyphicon-pushpin"></span>&nbsp;&nbsp;Write</b></a>
                                </h4>   
                                
                                <?php
                                }else{ // notice가 아무것도 없을때!
                                ?>
                                
                                <h4><span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"></span>
                                    <a href="/notice/write" class="btn btn-xs btn-default pull-right"><b><span class="glyphicon glyphicon-pushpin"></span>&nbsp;&nbsp;Write</b></a>
                                </h4>   
                                
                                <?php
                                    }
                                }else{ //editor
                                    if($recent_notice != false){
                                ?>                                
                                
                                <h4>
                                    <span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"><?=substr($recent_notice->date,0,-3);?></span>                              
                                </h4>    
                                
                                <?php
                                }else{
                                ?>
                                
                                <h4>
                                    <span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px;"></span>                              
                                </h4>    

                                <?php
                                    }
                                }// not login
                            }else{
                                if($recent_notice != false){
                                ?>
                                <h4><span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px; text-align:right;"><?=substr($recent_notice->date,0,-3);?></span>                                
                                </h4>    
                                <?php
                                }else{
                                ?>
                                <h4><span class="glyphicon glyphicon-align-justify"></span>&nbsp;Recent notice&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:13px; text-align:right;"></span>                                
                                </h4>    
                                <?php
                                }
                            }?>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">
                            <dl>
                                <?php
                                if($recent_notice != false){
                                ?>
                                <dt><h4><?=substr($recent_notice->title,0,85).'...';?></h4></dt>
                                <dd><?=$recent_notice->contents;?></dd>
                                <?php } ?> <!-- notice가 아무것도 없을때! -->
                            </dl>
                        </div> <!-- /widget-content -->
                    
                    </div> <!-- /widget -->
                    <br/>
                    <hr>
                    <br/>
                    
                    <div class="widget widget-nopad stacked">
                                
                        <div class="widget-header">
                            <?php
                            if($this->session->userdata('is_login')){                              
                            ?>
                            <h4><span class="glyphicon glyphicon-list"></span>&nbsp;Notices
                                <a href="/notice" class="btn btn-xs btn-default pull-right"><b><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;More</b></a>
                            </h4>

                            <?php }else{?>
                            <h4><span class="glyphicon glyphicon-list"></span>&nbsp;Notices</h4>
                            <?php }?>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">
                            
                            <ul class="news-items">
                                <?php
                                foreach ($notice as $value) {
                                    $title = strip_tags($value->title);
                                    $contents = strip_tags($value->contents);
                                    $date = $value->date;
                                    $short_cont = substr($contents, 0,85).'...';
                                    $id = $value->id;
                                ?>
                                <li>                                    
                                    <div class="news-item-detail" href="/notice/contents/<?=$id?>" style="cursor:pointer;">
                                        <h5 class="news-item-preview akacolor"><?=substr($title,0,64).'...';?><h5>
                                        <p style="color:black;"><?=$short_cont?></p>
                                    </div>
                                    
                                    <div class="news-item-date text-right" style="margin-top:-20px;">
                                        <b><span class="news-item-day "><?=substr($date,0,-3);?></span></b>
                                    </div>
                                </li><br/>
                                <?php
                                }
                                ?>                              
                            </ul>
                            
                        </div> <!-- /widget-content -->
                    
                    </div> <!-- /widget --> 

                </div> <!-- /span6 -->
                
                
<!-- Quick -->  <div class="col-md-6">                   
                    
                    <div class="widget stacked">
                            
                        <div class="widget-header"> 
                            <?php
                            if($this->session->userdata('classify') == 0){ //admin
                            ?>
                                <h4><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Quick Shortcuts<span></span></h4>
                            <?php
                            }else{
                            ?>
                                <h4><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Project<span><a href="/musedata/project"><button class="btn btn-default btn-xs pull-right">More</button></a></span></h4>
                            <?php
                            }
                            ?>                           
                            
                        </div> <!-- /widget-header -->                        
                        
                        <div class="widget-content" style="margin-top:15px;">                            
                            <div class="pull-center">                                
                                <?php
                                if($this->session->userdata('is_login')){                                
                                    if($this->session->userdata('classify') == 0){ //admin
                                    ?>
                                    <div class="col-md-4 line" id="musedata" href="/musedata/project" style="height:70px; cursor:pointer;border-color:black;">                                        
                                        <h3 class="text-center" style="margin-top:10px;" ><span class="glyphicon glyphicon-inbox"></span></h3>
                                        <h5 class="text-center">&nbsp; Musedata</h5>
                                    </div>
                                    
                                    <div class="col-md-4 line" id="service" href="/service" style="height:70px; cursor:pointer;border-color:black;">                                    
                                        <h3 class="text-center" style="margin-top:10px;" ><span class="glyphicon glyphicon-globe"></span></h3>
                                        <h5 class="text-center">&nbsp; Service</h5>
                                    </div>                 

                                    <div class="col-md-4 line" id="new_editor" href="/setting/info" style="height:70px; cursor:pointer;border-color:black;">                                    
                                        <h3 class="text-center" style="margin-top:10px;" ><span class="glyphicon glyphicon-user"></span></h3>
                                        <h5 class="text-center">&nbsp; New Editor</h5>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <br>                                    
                                    <?php
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
                                        <div class="col-md-6 ">
                                            <div class="col-md-12  line" style="height:70px; ">
                                                <span class="glyphicon glyphicon-th-large " style="margin-top:3%"></span>
                                                <h5 class="text-center " style="margin-top:2%;"><?=$pjname;?></h5>
                                            </div>

                                            <div class="col-md-6 line " style="height:30px; cursor:pointer;">
                                                <h5 class="text-center todo" style="width:98px; margin-top:7%;" href="/musedata/project/board/todo/<?=$pj_id;?>/<?=$usr_id?>">To do</h5>
                                            </div>

                                            <div class="col-md-6 line " style="height:30px; cursor:pointer;">
                                                <h5 class="text-center comp" style="width:98px; margin-top:7%;" href="/musedata/project/board/com/<?=$pj_id;?>/<?=$usr_id?>">Completed</h5>
                                            </div>
                                        </div>                                        
                                    <?php
                                        }    
                                    ?>
                                    <br><br><br><br><br><br>
                                    <?php                               
                                    }                                                                        
                                }?>                                
                            </div> <!-- /shortcuts -->                          
                        </div> <!-- /widget-content -->
                        
                    </div> <!-- /widget -->                    
                    
                    <div class="widget stacked">
                            
                        <div class="widget-header">
                            
                            <h4><span class="glyphicon glyphicon-stats"></span>&nbsp;Total Chart</h4>
                        </div> <!-- /widget-header -->
                        
                        <div class="widget-content">                    
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="area-chart" class="chart-holder " style="height:210px; padding-top:40px;">                                    
                                        <?php
                                        if($this->session->userdata('is_login')){
                                            if($this->session->userdata('classify') == 0){ // admin
                                        ?>
                                        <dl class="dl-horizontal text-center" style="padding-right:10pxl">                                            
                                            <dt>To do&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #E2EAE9;"></span></dt>
                                            <dd id="todo"></dd>
                                            <dt>Draft&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #D4CCC5;"></span></dt>
                                            <dd id="draft"></dd>
                                            <dt>Completed&nbsp;&nbsp;<span class="glyphicon glyphicon-stop" style="color: #F7464A;"></span></dt>
                                            <dd id="done"></dd>
                                            <hr style="margin-left:70px;">
                                            <dt>TOTAL</dt>
                                            <dd id="total"></dd>
                                        </dl>
                                        <?php
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
                                        <?php
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
                                        <?php
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
                                    <?php
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
                                    <?php
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
<?php } ?>
<!-- javascript -->     
<script src="/public/js/Chart.js"></script>
<script type="text/javascript">
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
                        $('div#new_editor').css('background-color','#F15F5F');                        
                        $('div#new_editor h3').addClass('font-white');
                        $('div#new_editor h5').addClass('font-white');
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
                            color: "#E2EAE9"
                        },
                        {
                            value : draft,
                            color : "#D4CCC5"
                        },
                        {
                            value : done,
                            color : "#F7464A"
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
                        percentageInnerCutout : 40,
                        
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
    console.log(form_data);                
    $.ajax({
            type: "POST",
            url: '/sign/sign_in_proc',                
            data: form_data,
            dataType: 'json',
            success: function(json) {
                    console.log(json['status']);

                    if(json['status'] == 'true') {                                
                            window.location.replace('/'); // 리다이렉트할 주소
                    }else if(json['status'] == 'empty'){
                        alert("Please enter your email and password!");
                    }else if(json['status'] == 'false'){
                        alert("Incorrect username and password!");
                    }else if(json['status'] == 'wait'){
                        alert("It has not yet been approved! Please wait for approval.");
                    }
            }
    });
    return false;
}); 

$('div.news-item-detail').click(function(){
    //var href = $(this).attr('href');
    window.document.location = $(this).attr('href');
});

$('h5.todo').click(function(){
    //var href = $(this).attr('href');
    window.document.location = $(this).attr('href');
});

$('h5.comp').click(function(){
    //var href = $(this).attr('href');
    window.document.location = $(this).attr('href');
});

$('div#musedata').click(function(){
    //var href = $(this).attr('href');
    window.document.location = $(this).attr('href');
});

$('div#service').click(function(){
    //var href = $(this).attr('href');
    window.document.location = $(this).attr('href');
});

$('div#new_editor').click(function(){
    //var href = $(this).attr('href');
    window.document.location = $(this).attr('href');
});




</script>
