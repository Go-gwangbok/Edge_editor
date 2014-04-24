<!DOCTYPE html>
<html>
  <head>
    <title>Administrator2.0</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content=""> 

    <link href="/public/css/bootstrap.css" rel="stylesheet" media="screen">    
    
    <!-- Custom styles for this template -->
    <link href="/public/css/customize.css" rel="stylesheet" media="screen">  
    <style type="text/css">
        body{padding-top: 80px;}       
    </style>  

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
    <link href="/public/wy/index.css" rel="stylesheet"> 
    <link href="/public/css/bootstrap-select.css" rel="stylesheet"> 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>    
    <script src="/public/js/bootstrap.js"></script>       
    <script src="/public/js/bootstrap-select.js"></script>       
    <script src="/public/js/jquery.selection.js"></script>
    
    <?
    if($this->session->userdata('classify') == 1 && $this->session->userdata('writing') == 1){
    ?>
    <script src="/public/js/service_chk.js"></script>
    <? } ?>        
    <script src="/public/js/countdown.js"></script> 
    <script>
    // Timer
// function formatTime(time) {
//     var min = parseInt(time / 6000),
//         sec = parseInt(time / 100) - (min * 60),
//         hundredths = pad(time - (sec * 100) - (min * 6000), 2);
//     //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
//     return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
// }

// var count = 0,
//     timer = $.timer(function() {
//         count++;
//         //$('#counter').html(count);
//     });
// timer.set({ time : 1000, autostart : true });


// // Common functions
// function pad(number, length) {
//     var str = '' + number;
//     while (str.length < length) {str = '0' + str;}
//     return str;
// }

// var stop_time = 0;    
// var currentTime = 0; // Current time in hundredths of a second 100 == 1  
// var Example1 = new (function() {    
//       window.onblur = repeat_stop;
//       window.onfocus = repeat_start;    
      
//       var incrementTime = 70; // Timer speed in milliseconds            

//       updateTimer = function() {            
//           $stopwatch.html('Timer : ' + formatTime(currentTime));
//           currentTime += incrementTime / 10;

//       },
      
//       init = function() {            
//           $stopwatch = $('#stopwatch');
//           Example1.Timer = $.timer(updateTimer, incrementTime, true);
//       };

//       this.resetStopwatch = function() {          
//           this.Timer.stop().once();
//       };  
//       $(init);             
// });   

// function repeat_stop(){
//     console.log('Out');
//     Example1.resetStopwatch(); // stop timer    

//     var time = $('#stopwatch').text().substr(8);  
//     var time_array = time.split(':');
//     var min = parseInt(time_array[0]);
//     var second = parseInt(time_array[1]);

//     min = min * 6000;
//     second = second * 100;
//     stop_time = min+second;      
// }

// function repeat_start(){
//     console.log('In');
//     var time = $('#stopwatch').text().substr(8);  

//     var time_array = time.split(':');
//     var min = parseInt(time_array[0]);
//     var second = parseInt(time_array[1]);

//     min = min * 6000;
//     second = second * 100;
//     currentTime = min+second;          
//     $(init);             
// }

// // var on = navigator.onLine;
// // if(!on){
// //   alert('wow');
// // }
// // console.log(on);
// // alert(navigator.onLine);
    </script>
  </head>
  
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">      
      <a class="navbar-brand" href="/">Administrator</a>
      <!-- <input type="hidden" id="stopwatch"></input> -->
    </div>        
    
    <div class="navbar-collapse collapse">          
      <ul class="nav navbar-nav">
      <?
      if($this->session->userdata('is_login')){      
        
        if($cate == 'musedata'){                  
          if($this->session->userdata('classify') == 0){
          ?>
            <li class="active"><a href="/musedata/project">Data</a></li>          
            <li id="service_chk"><a href="/service">Service</a></li>            
            <li><a href="/setting/info">Setting</a></li>
            <li><a href="/notice">Notice</a></li>            
          <?
          }else{
            if($this->session->userdata('musedata') == 1){

          ?>
            <li class="active"><a href="/musedata/project">Data</a></li>
          <?
            }
            if($this->session->userdata('writing') == 1){
          ?>
            <li id="service_chk"><a href="/service">Service</a></li>
          <?
            }
          ?>         
            <li><a href="/notice">Notice</a></li>            
          <?
          }               
        }elseif($cate == 'service'){        
          if($this->session->userdata('classify') == 0){ // ADmin
          ?>
          <li><a href="/musedata/project">Data</a></li>          
          <li class="active" id="service_chk"><a href="/service">Service</a></li>              
          <li><a href="/setting/info">Setting</a></li>
          <li><a href="/notice">Notice</a></li>            
          <?
          }else{ // Editor
            if($this->session->userdata('musedata') == 1){
            ?>
              <li><a href="/musedata/project">Data</a></li>
            <?
            }
            if($this->session->userdata('writing') == 1){
            ?>
              <li class="active" id="service_chk"><a href="/service">Service</a></li>
            <?
            }
            ?>            
            <li><a href="/notice">Notice</a></li>                    
          <?
          }                          
        }elseif($cate == 'setting'){        
          if($this->session->userdata('classify') == 0){ // Admin
          ?>
          <li><a href="/musedata/project">Data</a></li>          
          <li><a href="/service">Service</a></li>              
          <li class="active"><a href="/setting/info">Setting</a></li>
          <li><a href="/notice">Notice</a></li>            
          <?
          }                          
        }else if($cate == 'notice'){ // Notice head
          if($this->session->userdata('classify') == 0){ // Admin
          ?>
            <li><a href="/musedata/project">Data</a></li>
            <li><a href="/service">Service</a></li>              
            <li><a href="/setting/info">Setting</a></li>
            <li class="active"><a href="/notice">Notice</a></li>            
          <?
          }else{ // Editor
            if($this->session->userdata('musedata') == 1){
          ?>
            <li><a href="/musedata/project">Data</a></li>
          <?
            }
            if($this->session->userdata('writing') == 1){
          ?>
            <li id="service_chk"><a href="/service">Service</a></li>
          <?
            }
          ?>
            <li class="active"><a href="/notice">Notice</a></li>            
          <?
          }                                 
        }else{ // index head    
          if($this->session->userdata('classify') == 0) { // Admin
          ?>
            <li><a href="/musedata/project">Data</a></li>
            <li id="service_chk"><a href="/service">Service</a></li>            
            <li><a href="/setting/info">Setting</a></li>              
            <li><a href="/notice">Notice</a></li>  
            <!-- <li><a href="/pay/pay">Pay</a></li>           -->
          <?
          }else{ // Editor
            if($this->session->userdata('musedata') == 1){
            ?>
              <li><a href="/musedata/project">Data</a></li>
            <?
            }
            if($this->session->userdata('writing') == 1){
            ?>
              <li id="service_chk"><a href="/service">Service</a></li>            
            <?
            }
          ?>           
            <li><a href="/notice">Notice</a></li>            
          <?
          }                                      
        }
        ?>
       </ul> 
        <form class="navbar-form navbar-right">
          <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<?=$this->session->userdata('nickname');?>&nbsp;&nbsp;
          <a href="/sign/logout" class="btn btn-success">Logout</a>          
        </form>
      <?
      }else{ // Not Sign in     
        ?>
        <li><a href="/">Data</a></li>
        <li><a href="/">Service</a></li>        
        <li><a href="/">Notice</a></li>            
        </ul> 
        <form class="navbar-form navbar-right">
          <a href="index.php/signup" class="btn btn-success">Sign Up</a>          
        </form>
      <?      
      } 
      ?> 
    </div><!--/.navbar-collapse -->            
  </div>        
</div>     
<!-- head End -->