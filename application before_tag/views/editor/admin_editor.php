<div class="container" style="margin-top:-15px;">
  <div class="row">       
    <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>                   
        <li><a href="/musedata/project/">Project</a></li>   
        <?        
        if($cate == 'error'){ // Error List
        ?>
        <li><a href="/musedata/project/members/<?=$pj_id;?>">Members</a></li>   
        <li><a href="/musedata/project/errorlist/<?=$pj_id;?>">Error List</a></li>   
        <li class="akacolor">Error Essay</li>      
        <?
        }elseif($cate == 'tbd'){ // Project in T.B.D
        ?>
        <li><a href="/musedata/project/members/<?=$pj_id;?>">Members</a></li>           
        <li><a href="/musedata/project/board/tbd/<?=$pj_id;?>/<?=$usr_id?>">T.B.D</a></li>           
        <li class="akacolor">Essay</li>      
        <?
        }elseif($cate == 'history'){ // Project in T.B.D
        ?>
        <li><a href="/musedata/project/members/<?=$pj_id;?>">Members</a></li>           
        <li><a href="/musedata/project/board/history/<?=$pj_id;?>/<?=$usr_id?>">History</a></li>           
        <li class="akacolor">Essay</li>      
        <?
        }else{ // Export
        ?>
        <li><a href="/musedata/project/export/<?=$pj_id;?>">Export</a></li>             
        <li class="akacolor">Completed</li>         
        <?
        }
        ?>       
      </ol> 
  </div> <!-- Navi end -->      
  <div class="div-box-line-promp">
    <dl>
        <dt style="margin:0 10px 0 10px">Prompt</dt>              
        <dd style="margin:0 15px 0 25px" id="prompt"><?=trim($title);?></dd>
    </dl>       
  </div>    
  <br>
  <ul class="nav nav-tabs" id="myTab">    
    <?
    $chk_orig = 'N';
    $chk_detection = 'N';
    $chk_structure = 'N';
    $chk_sco1 = 'N';
    $chk_sco2 = 'N';
    $error = 'N';
    $active_ele = '';

    $i = 0;
    foreach ($templet as $rows) {
      $element = $rows->element;
      $view_ele = $rows->view_ele;

      switch ($element) {
        case 'orig':$chk_orig = 'Y'; break;
        case 'detection':$chk_detection = 'Y'; break;
        case 'structure':$chk_structure = 'Y'; break;
        case 'sco1':$chk_sco1 = 'Y'; break;
        case 'sco2':$chk_sco2 = 'Y'; break;        
        default: $error = 'Y';  break;
      }

      if($i == 0){
        $active_ele = $element;
      ?>
      <li class="active"><a href="#<?=$element;?>" data-toggle="tab"><?=$view_ele;?></a></li>
      <?
      }else{
      ?>
      <li><a href="#<?=$element?>" data-toggle="tab"><?=$view_ele;?></a></li>  
      <?      
      } 
      $i++;   
    }
    ?>
    <!-- Default -->    
    <div id="stopwatch" class="btn btn-default pull-right" disabled>Timer : 00:00</div>       
    <div class="btn btn-default pull-right" style="margin-right:3px;" disabled>Word count : <?=$word_count?></div>       
    <?
    if($cate == 'error'){
    ?>
    <button class="btn btn-md btn-danger pull-right" id="not_error" style="margin-right:5px;">Return</button>
    <button class="btn btn-md btn-danger pull-right" id="yes" style="margin-right:5px;">Yes</button>     
    <?
    }
    ?>    
  </ul>
  <br>
<div class="tab-content">
  <!-- Original -->
  <?
  if($chk_orig == 'Y'){
  ?>  
   <div class="tab-pane div-box-line active" id="orig">
      <div class="col-md-12" style="margin-top:15px;">                        
        <div>          
          <?
            echo trim($raw_writing);             
          ?>      
        </div> 
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->
  <? } ?>
  <!-- Original end -->

    <!-- Error detecting -->
    <?
    if($chk_detection == 'Y'){
    ?>      
    <div class="tab-pane div-box-line" id="detection">
      <div class="col-md-12" style="margin-top:15px;">   
        <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor" style="margin-bottom:20px;">
          
          <?
          if($cate != 'tbd'){
          ?>
          <div class="btn-group">
            <a class="btn" data-edit="strikethrough" title="Strikethrough" disabled><span class="glyphicon glyphicon-trash"></span> DEL</a>                
            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)" disabled><span class="glyphicon glyphicon-refresh"></span> MOD</a>        
            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)" disabled><span class="glyphicon glyphicon-pencil"></span> INS</a>            
          </div>   

          <div class="btn-group pull-right">
            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)" disabled><i class="icon-undo"></i></a>
            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)" disabled><i class="icon-repeat"></i></a>
          </div>  
        <?
         }else{ // Cate == T.B.D
        ?>
        <div class="btn-group">
          <a class="btn" data-edit="strikethrough" title="Strikethrough"><span class="glyphicon glyphicon-trash"></span> DEL</a>                
          <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)" ><span class="glyphicon glyphicon-refresh"></span> MOD</a>        
          <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)" ><span class="glyphicon glyphicon-pencil"></span> INS</a>            
        </div>

        <div class="btn-group pull-right">
            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)" ><i class="icon-undo"></i></a>
            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)" ><i class="icon-repeat"></i></a>
        </div> 
        <?
         }
        ?>
        </div> <!-- btn-toolbar -->      
        <!-- Error detecting -->
        <div id="editor">          
          <?
              echo trim($edit_writing);
          ?>      
        </div>

        <hr class="text-box-hr">      
            <div class="panel-group" id="accordion">          
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                      Critique
                    </a>
                  </h4>
                </div>
                
                <!-- Critique -->
                <div id="collapseOne" class="panel-collapse collapse in">
                  <div class="panel-body">                    
                      <textarea class="border text_box" id="critique" style="width:100%;" rows="7"><?=trim($critique);?></textarea>                                  
                  </div>
                </div>            
              </div>           
            </div>
            <br>
            <!-- accordion end -->
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->
    <? } ?>
    <!-- Error detecting end -->

    <!-- Tagging start -->
    <?
    if($chk_structure == 'Y'){
    ?>      
    <div class="tab-pane div-box-line" id="structure">
      <!--<div class="div-box-line">-->
      <div class="col-md-12">           
        <div class="col-md-12" style="margin-top:10px;"> 

          <div class="col-md-12"> 
            <div class="col-md-12" style="margin-top:20px; text-align:center" id="confbox">
              <h5>Confirm&nbsp;&nbsp;   
               <?
                foreach ($tag_templet as $value) {
                  $tag_id = $value->tag_id;
                  $tag_name = $value->tag;
                ?>
                <button id="block" tag="<?=$tag_name;?>" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> <?=strtoupper($tag_name);?></button>
                <? } ?>                      
                <!-- <button id="block" tag="in" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> IN</button> -->                  
              </h5>
            </div>
           <hr style="border:1px dashed; border-color: #d6e9c6;">                         
          </div>       

          <?
          foreach ($tag_templet as $value) {
            $tag_id = $value->tag_id;
            $tag_name = $value->tag;
          ?>
          <button id="tag" tag="<?=strtoupper($tag_name);?>" type="button" class="btn btn-success btn-sm">&lt;<?=strtoupper($tag_name);?>&gt;</button>
          <? } ?>       
               
          <button id="all" type="button" class="btn btn-default btn-danger btn-sm pull-right" click="clear"><span class="glyphicon glyphicon-refresh"></span> Clear All</button>            
          <button id="redo" tag="TR" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;"><span class="glyphicon glyphicon-refresh"></span> Redo</button>
          <button id="undo" tag="TR" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;"><span class="glyphicon glyphicon-refresh"></span> Undo</button>            
 
        </div>  <!-- col-md-12 -->
      </div> <!-- col-md-12 -->              
      <hr class="text-box-hr">              
    <div class="col-md-12" id="hrline">                          
      <div class="divtagging_box" id="tagging_box"><?=trim($tagging);?></div>                
    </div>        
  </div>   
  <? } ?>
  <!-- Tagging End -->

  <?
  if($chk_sco1 == 'Y'){
  ?>          
  <!-- Score1 start -->   
  <div class="tab-pane div-box-line " id="sco1">
    <br>    
    <div class="col-md-12 ">           
      <div class="col-md-12 ">                         
        <div class="col-md-12 ">  
          <?
          foreach ($score_templet as $value) {
            $score_id = $value->score_id;
            $score_name = $value->name;
          ?>
          <div class="row" style="margin-bottom:10px;">  
            <label for="inputEmail3" class="col-md-2 "><?=strtoupper($score_name)?></label>
            <div class="col-md-2">
              <input type="text" class="form-control score_val" id="<?=strtolower($score_name);?>" placeholder="0">
            </div>
          </div>
          <? } ?>                                  
        </div>
      </div>
    </div>               
  </div>  
  <? } ?>
  <!-- Score1 End --> 

  <!-- Score2 start -->   
  <?
  if($chk_sco2 == 'Y'){
  ?>      
  <div class="tab-pane div-box-line " id="sco2">
    <br>    
    <div class="col-md-12 ">           
      <div class="col-md-12 ">                         
        <div class="col-md-12 ">  
        <?
        foreach ($score_templet as $value) {
          $score_id = $value->score_id;
          $score_name = $value->name;
        ?>
        <div class="row" style="margin-bottom:10px;">  
          <label for="inputEmail3" class="col-md-2 "><?=strtoupper($score_name)?></label>
          <div class="col-md-2">
            <input type="text" class="form-control score_val2" id="<?=strtolower($score_name);?>2" placeholder="0">
          </div>
        </div>
        <? } ?>                 
        </div>
      </div>
    </div>              
  </div>  
  <? } ?>
  <!-- Score2 End -->

  <!-- Submit button -->
  <div style="margin-top:8px;">
    <?
    if($cate == 'tbd'){     
    ?>  
    <button class="btn btn-danger pull-right" id="submit">Submit</button>
    <button class="btn btn-primary" id="draft">Save Draft</button>
    <?  
    }
    ?>        
  </div> 
  <!-- Submit button end -->

    <!-- hidden data -->
    <form> 
      <input type="hidden" id="raw_writing" value="<?=$writing;?>">
      <input type="hidden" id="re_raw_writing" value="<?=$re_raw_writing;?>">
    </form>  
  </div>  
</div>   
<script src="/public/js/jquery.timer.js"></script>
<script src="/public/wy/external/jquery.hotkeys.js"></script>   
<script src="/public/wy/external/google-code-prettify/prettify.js"></script> 
<script src="/public/wy/bootstrap-wysiwyg.js"></script>
<script type="text/javascript" >
var cate = '<?=$cate;?>';
var draft_time = <?=$time;?>;
var score = '<?=$score1?>';
var score2 = '<?=$score2?>';
console.log(cate);
console.log(score);

$(document).ready(function(){
  console.log('<?=$active_ele;?>');
  var active_ele = '<?=$active_ele;?>';
  $('div#'+active_ele).addClass('active');
  
  // Score 1
  var score_array = score.split(',');   
  $.each(score_array,function(i,value){
    var each_val = value;
    var score_name = each_val.split(':');
    $('div').find('input#'+score_name[0].toLowerCase()).val(score_name[1]);    
  }); 

  // Score 2
  var score2_array = score2.split(',');   
  $.each(score2_array,function(i,value){
    var each_val2 = value;
    var score_name2 = each_val2.split(':');
    $('div').find('input#'+score_name2[0].toLowerCase()+'2').val(score_name2[1]);    
  }); 
});

if(cate == 'writing'){
  clearInterval(service_chk); //realtime service chk clear.  
  console.log('stop');
}

// Timer
function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
}

var count = 0,
    timer = $.timer(function() {
        count++;
        $('#counter').html(count);
    });
timer.set({ time : 1000, autostart : true });


// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

var Example1 = new (function() {

    if(cate == 'draft' || cate == 'tbd'){
      var currentTime = draft_time; // Current time in hundredths of a second 100 == 1  
          incrementTime = 70; // Timer speed in milliseconds      
    }else if(cate == 'admin_export' || cate == 'com' || cate == 'error' || cate == 'history'){ // Time을 멈춤!
      var currentTime = draft_time; // Current time in hundredths of a second 100 == 1  
      var incrementTime = 0; // Timer speed in milliseconds       
    }else{      
      var currentTime = 0; // Current time in hundredths of a second 100 == 1  
      incrementTime = 70; // Timer speed in milliseconds      
    }    

    var $stopwatch, // Stopwatch element on the page            
        updateTimer = function() {            
            $stopwatch.html('Timer : ' + formatTime(currentTime));
            currentTime += incrementTime / 10;
        },
        init = function() {
            console.log(draft_time);
            $stopwatch = $('#stopwatch');
            Example1.Timer = $.timer(updateTimer, incrementTime, true);
        };
    this.resetStopwatch = function() {          
        this.Timer.stop().once();
    };      
    $(init);          
});

var tagging_div = new Array();
var undo_div = new Array();
var redo_tagging_div = '';
var undo_tagging_div = '';
var i = 0;
var count = 0;
var mi_count = 1;
var si_count = 1;
var bo_count = 1;
$('button#tag').click(function(){          
  var tag = $(this).attr('tag');
  var mytext = $.selection('html');
  //console.log(mytext);  
  
  var div = $("div#tagging_box");  
  var data = div.html();
  console.log(data);
  switch(tag)
  {
  case 'IN': color = "in"; break;
  case 'BO': 
            tag = 'BO'+bo_count;
            color = "bo"; 
            bo_count++;
            break;
  case 'CO': color = "co"; break;
  case 'TS': color = "ts"; break;
  case 'MI': 
            tag =  'MI'+mi_count;
            color = "mi"; 
            mi_count++;
            break;
  case 'SI': 
            tag =  'SI'+si_count;
            color = "si";
            si_count++;
            break;
  case 'TR': color = "tr"; break;
  case 'Ohter': color = "ohter"; break;
  case 'TP': color = "tp"; break;
  case 'TQ': color = "tq"; break;
  case 'EX': color = "ex"; break;          
  default: alert('selection Error');
  }   
  var undo_div_box = $("div#tagging_box").html();
  undo_div.push(undo_div_box);          

  div.html(data.replace(mytext,'<span class="'+color+'" id = "action'+i+'" tag = "'+tag+'">&#60'+ tag +'&#62' + mytext + '&#60/' + tag + '&#62 </span>'));                     
  
  var div_tagging_box = $("div#tagging_box").html();          
  tagging_div.push(div_tagging_box);
  i++;
  count++;     
});
  
$("button#undo").click(function(){          
    if(i > 0){            
      var ii = i-1;        
      undo_tagging_div = undo_div[ii];

      $("div#tagging_box").remove();
      
      $("div#hrline").append('<div class="divtagging_box" id="tagging_box">'+undo_tagging_div+'</div>');          
      i--;      
    }
});      

$("button#redo").click(function(){         
  if(count > i){
    redo_tagging_div = tagging_div[i];
    console.log(tagging_div.length);
    $("div#tagging_box").remove();
    
    $("div#hrline").append('<div class="divtagging_box" id="tagging_box">'+redo_tagging_div+'</div>');          
    i++;
    }
});

var clear_storage = '';

$("button#all").click(function(){
  var v = $(this).attr('click');      
  
  if(v == 'clear'){
    clear_storage = $("div#tagging_box").html();
    var contents = $("div#tagging_box").remove();        
    var tt = $("input#re_raw_writing").val();        
    console.log(tt);
    $("div#hrline").html('<div class="divtagging_box" id="tagging_box">'+tt+'<br/></div>');
    $(this).html('<span class="glyphicon glyphicon-refresh"></span> Redo All</button>');
    $(this).attr('click','redo');
  
  }else{
    $("div#tagging_box").html(clear_storage);
    $(this).attr('click','clear');
    $(this).html('<span class="glyphicon glyphicon-refresh"></span> Clear All</button>');
  } 
});

var classify_in = true;  
var classify_bo = true;  
var classify_co = true;  
var classify_ts = true;  
var classify_mi = true;  
var classify_si = true;  
var classify_tr = true;  
var classify_ohter = true;  
var classify_tp = true;  
var classify_tq = true;  
var classify_ex = true;  

$("button#block").click(function(){  
  var tag = $(this).attr('tag');   

  switch(tag)
  {
  case 'in':
    if(classify_in){   
      $('span.'+tag).css("backgroundColor","#B7F0B1"); 
      classify_in = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_in = true;
    } break;
  case 'bo': if(classify_bo){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_bo = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_bo = true;
    } break;
  case 'co': if(classify_co){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_co = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_co = true;
    } break;
  case 'ts': if(classify_ts){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_ts = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_ts = true;
    } break;
  case 'mi': if(classify_mi){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_mi = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_mi = true;
    } break;
  case 'si': if(classify_si){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_si = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_si = true;
    } break;
  case 'tr': if(classify_tr){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_tr = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_tr = true;
    } break;
  case 'ohter': if(classify_ohter){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_ohter = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_ohter = true;
    } break;
  case 'tp': if(classify_tp){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_tp = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_tp = true;
    } break;
  case 'tq': if(classify_tq){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_tq = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_tq = true;
    }break;
  case 'ex': if(classify_ex){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_ex = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_ex = true;
    }break;  
  default: alert('selection Error');
  }    

});      
      
$("span#mouse").hover(
  function(){
    $(this).addClass("my-hover");
  },
  function(){
    $(this).removeClass("my-hover");
});     

// Score
function make_obj_score(input_className){
  var obj = {};
  var scoreNames = $('input.score_val').map(function() {    
    var score_name = $(this).attr('id');        
    return score_name;
  }).get();

  var scoreNums = $('input.'+input_className).map(function() {        
    var sco = $(this).val();    
    return sco;
  }).get();  

  $.each(scoreNames,function(i,value){
    obj[value] = scoreNums[i];    
  });  

  return obj;
}

// 결과 전송
$('button#draft').click(function(){     
  var editing = $('div#editor').html();
  var critique = $('textarea#critique').val();    
  var tagging = $('div#tagging_box').html();       
  var type = '<?=$type;?>';

  var score_obj = make_obj_score('score_val'); // Score1
  var score1 = JSON.stringify(score_obj);    

  var score_obj2 = make_obj_score('score_val2'); // Score1
  var score2 = JSON.stringify(score_obj2);    

  Example1.resetStopwatch(); // stop timer
  var time = $('div#stopwatch').text().substr(8);  
  var min = parseInt(time.substr(0,2));
  var second = parseInt(time.substr(3));

  min = min * 6000;
  second = second * 100;
  var total_time = min+second;  
  // console.log(total_time);  
  var data = {            
    essay_id: <?=$id;?>,            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    score1 : score1,
    score2 : score2,
    time : total_time
  }
  console.log(data);
  
  $.ajax(
  {
    url: '/text_editor/admin_draft_save', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {      
      console.log(json['status']);
      if(json['status'])
      {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');        
        window.location.replace('/musedata/project/board/tbd/<?=$pj_id;?>/<?=$usr_id?>'); // 리다이렉트할 주소
      }
      else
      {
        alert('all_list --> draft DB Error');
      }
    }
  });
});  


$('button#submit').click(function()
{     
  var editing = $('div#editor').html();
  var critique = $('textarea#critique').val();    
  var tagging = $('div#tagging_box').html();       
  var type = '<?=$type;?>';

  // Score
  var score_obj = make_obj_score('score_val'); // Score1
  var score1 = JSON.stringify(score_obj);    

  var score_obj2 = make_obj_score('score_val2'); // Score1
  var score2 = JSON.stringify(score_obj2);     
  
  Example1.resetStopwatch(); // stop timer
  var time = $('div#stopwatch').text().substr(8);  
  var min = parseInt(time.substr(0,2));
  var second = parseInt(time.substr(3));

  min = min * 6000;
  second = second * 100;
  var total_time = min+second;  
  //console.log(total_time);  
  var data = {            
    essay_id: <?=$id;?>, // Table column id.            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    score1 : score1,
    score2 : score2,
    time : total_time
  }
  //console.log(data);  
  $.ajax(
  {
    url: '/text_editor/admin_submit', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {      
      if(json['status'] == 'true')
      {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');        
        window.location.replace('/musedata/project/board/tbd/<?=$pj_id;?>/<?=$usr_id?>'); // 리다이렉트할 주소
      }
      else
      {
        alert('all_list --> draft DB Error');
      }
    }
  });
});      

// Error Yes Button
$('button#yes').click(function(){
  var essay_id = '<?=$essay_id;?>'; 
  data = {
    essay_id : essay_id 
  }
  console.log(data);
  $.post('/errordata/error_yes',data,function(json){
    console.log(json['result']);
    if(json['result']){
      window.location = "/musedata/project/errorlist/<?=$pj_id;?>";
    }else{
      alert('DB Error --> error_yes');
    }
  });
});

// Error return button
$('button#not_error').click(function(){
  var essay_id = '<?=$essay_id;?>';  
  data = {
    essay_id : essay_id    
  }
  console.log(data);

  $.post('/errordata/error_return',data,function(json){
    console.log(json['result']);
    if(json['result']){
      window.location = "/musedata/project/errorlist/<?=$pj_id;?>";
    }else{
      alert('DB Error --> error_return');
    }
  });
});


// Editor
  $(function(){
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
            'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
            'Times New Roman', 'Verdana'],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function (idx, fontName) {
          fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
      });
      $('a[title]').tooltip({container:'body'});
      $('.dropdown-menu input').click(function() {return false;})
        .change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
        .keydown('esc', function () {this.value='';$(this).change();});

      $('[data-role=magic-overlay]').each(function () { 
        var overlay = $(this), target = $(overlay.data('target')); 
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
      if ("onwebkitspeechchange"  in document.createElement("input")) {
        var editorOffset = $('#editor').offset();
        $('#voiceBtn').css('position','absolute').offset({top: editorOffset.top, left: editorOffset.left+$('#editor').innerWidth()-35});
      } else {
        $('#voiceBtn').hide();
      }
  };

  function showErrorAlert (reason, detail) {
    var msg='';
    if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
    else {
      console.log("error uploading file", reason, detail);
    }
    $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
     '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
  };
  
  initToolbarBootstrapBindings();  
  $('#editor').wysiwyg({ fileUploadError: showErrorAlert} );
    window.prettyPrint && prettyPrint();
  });

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-37452180-6', 'github.io');
  ga('send', 'pageview');

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "http://connect.facebook.net/en_GB/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
}
(document, 'script', 'facebook-jssdk'));

!function(d,s,id){
  var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){
    js=d.createElement(s);
    js.id=id;
    js.src="http://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js,fjs);
  }
}
(document,"script","twitter-wjs");
</script>                   
