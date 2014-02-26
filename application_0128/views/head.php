<!DOCTYPE html>
<html>
  <head>
    <title>Administrator</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content=""> 

    <link href="/public/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="/public/css/bootmetro-tiles.css" rel="stylesheet" media="screen">
    <link href="/public/css/bootstrap-responsive.css" rel="stylesheet" media="screen">

    <!-- Custom styles for this template -->
    <link href="/public/css/customize.css" rel="stylesheet" media="screen">  
    <style type="text/css">
        body{padding-top: 80px;}       
    </style>  

    <!-- <link rel="apple-touch-icon" href="//mindmup.s3.amazonaws.com/lib/img/apple-touch-icon.png" /> -->
    <!-- <link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet"> -->
    <link href="/public/wy/index.css" rel="stylesheet"> 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <!-- <script src="/public/wy/external/jquery.hotkeys.js"></script> -->
    <!--<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>-->
    <!-- <script src="/public/wy/external/google-code-prettify/prettify.js"></script> -->
    <!-- <script src="/public/wy/bootstrap-wysiwyg.js"></script> -->
    <script src="/public/js/bootstrap.js"></script>       
    <script src="/public/js/jquery.selection.js"></script>            

  </head>
  
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">      
      <a class="navbar-brand" href="/">Administrator</a>
    </div>        
    
    <div class="navbar-collapse collapse">          
      <ul class="nav navbar-nav">
      <?
      if($this->session->userdata('is_login')){      
        if($cate == 'todolist' || $cate == 'writing' || $cate == 'draft' || $cate =='disc'){                  
          if($this->session->userdata('classify') == 0){ 
          ?>
          <li class="active"><a href="/project">Project</a></li>
          <!-- <li class="active"><a href="/todolist">Distribute</a></li> -->
          <li><a href="/status">Status</a></li>              
          <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }else{
          ?>
          <li><a href="/project">Project</a></li>
          <li class="active"><a href="/todo">To Do List</a></li>
          <li><a href="/done">Completed</a></li>            
          <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }              
        }elseif($cate == 'status'|| $cate == 'admin'){        
          if($this->session->userdata('classify') == 0){
          ?>
          <li><a href="/project">Project</a></li>
          <!-- <li><a href="/todo">Distribute</a></li> -->
          <li class="active"><a href="/status">Status</a></li>              
          <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }else{
          ?>
            <li><a href="/project">Project</a></li>
            <li><a href="/todo">To Do List</a></li>
            <li><a href="/done">Completed</a></li>    
            <li><a href="/notice/notice_list">Notice</a></li>                    
          <?
          }                          
        }elseif($cate == 'mydone'){        
          if($this->session->userdata('classify') == 0){
          ?>
          <li><a href="/project">Project</a></li>
          <!-- <li><a href="/todo">Distribute</a></li> -->
          <li class="active"><a href="/status">Status</a></li>              
          <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }else{
          ?>
            <li><a href="/project">Project</a></li>
            <li><a href="/todo">To Do List</a></li>
            <li class="active"><a href="/done">Completed</a></li>            
            <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }                          
        }else if($cate == 'notice'){        
          if($this->session->userdata('classify') == 0){
          ?>
          <li><a href="/project">Project</a></li>
          <!-- <li><a href="/todo">Distribute</a></li> -->
          <li><a href="/status">Status</a></li>              
          <li class="active"><a href="/notice/notice_list">Notice</a></li>            
          <?
          }else{
          ?>
            <li><a href="/project">Project</a></li>
            <li><a href="/todo">To Do List</a></li>
            <li><a href="/done">Completed</a></li>            
            <li class="active"><a href="/notice/notice_list">Notice</a></li>            
          <?
          }                                              
        }elseif($cate == 'project'){        
          if($this->session->userdata('classify') == 0){
          ?>
          <li class="active"><a href="/project">Project</a></li>
          <!-- <li><a href="/todo">Distribute</a></li> -->
          <li><a href="/status">Status</a></li>              
          <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }else{
          ?>
            <li class="active"><a href="/project">Project</a></li>
            <li><a href="/todo">To Do List</a></li>
            <li><a href="/done">Completed</a></li>            
            <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }                                      
        }else{        
          if($this->session->userdata('classify') == 0){
          ?>
          <li><a href="/project">Project</a></li>
          <!-- <li><a href="/todo">Distribute</a></li>-->
          <li><a href="/status">Status</a></li>              
          <li><a href="/notice/notice_list">Notice</a></li>            
          <?
          }else{
          ?>
            <li><a href="/project">Project</a></li>
            <li><a href="/todo">To Do List</a></li>
            <li><a href="/done">Completed</a></li>            
            <li><a href="/notice/notice_list">Notice</a></li>            
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
      }else{     
        ?>
        <li><a href="/project">Project</a></li>
        <li><a href="/todo">To Do List</a></li>
        <li><a href="/done">Completed</a></li>            
        <li><a href="/notice/notice_list">Notice</a></li>            
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