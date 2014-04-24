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
    <li class="active"><a href="#orig" data-toggle="tab">Original</a></li>
    <li><a href="#error" data-toggle="tab">Error detecting</a></li>
    <li><a href="#tagging" data-toggle="tab">Tagging</a></li>    
    <li><a href="#scoring" data-toggle="tab">Scoring</a></li>        
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
  <!-- Error Original -->
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

    <!-- Error detecting -->
    <div class="tab-pane div-box-line" id="error">
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
        <?
         }else{ // Cate == T.B.D
        ?>
        <div class="btn-group">
          <a class="btn" data-edit="strikethrough" title="Strikethrough"><span class="glyphicon glyphicon-trash"></span> DEL</a>                
          <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)" ><span class="glyphicon glyphicon-refresh"></span> MOD</a>        
          <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)" ><span class="glyphicon glyphicon-pencil"></span> INS</a>            
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

  <!-- Tagging start -->
    <div class="tab-pane div-box-line" id="tagging">
        <!--<div class="div-box-line">-->
        <div class="col-md-12">           
          <div class="col-md-12" style="margin-top:10px;"> 

            <div class="col-md-12"> 
              <div class="col-md-12" style="margin-top:20px; text-align:center" id="confbox">
                <h5>Confirm&nbsp;&nbsp;    
                  <button id="block" tag="in" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> IN</button>
                  <button id="block" tag="bo" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> BO</button>
                  <button id="block" tag="co" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> CO</button>
                  <button id="block" tag="ts" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> TS</button>
                  <button id="block" tag="mi" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> MI</button>
                  <button id="block" tag="si" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> SI</button>
                  <button id="block" tag="tr" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> TR</button>
                  <button id="block" tag="ohter" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> Ohter</button>
                  <button id="block" tag="tp" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> TP</button>            
                  <button id="block" tag="tq" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> TQ</button>
                  <button id="block" tag="ex" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> EX</button>                
                </h5>
              </div>
              <hr style="border:1px dashed; border-color: #d6e9c6;">                         
            </div>        
                      
            <button id="tag" tag="IN" type="button" class="btn btn-success btn-sm">&lt;IN&gt;</button>
            <button id="tag" tag="BO" type="button" class="btn btn-success btn-sm">&lt;BO&gt;</button>
            <button id="tag" tag="CO" type="button" class="btn btn-success btn-sm">&lt;CO&gt;</button>
            <button id="tag" tag="TS" type="button" class="btn btn-success btn-sm">&lt;TS&gt;</button>
            <button id="tag" tag="MI" type="button" class="btn btn-success btn-sm">&lt;MI&gt;</button>
            <button id="tag" tag="SI" type="button" class="btn btn-success btn-sm">&lt;SI&gt;</button>
            <button id="tag" tag="TR" type="button" class="btn btn-success btn-sm">&lt;TR&gt;</button>
            <button id="tag" tag="Ohter" type="button" class="btn btn-success btn-sm">&lt;Ohter&gt;</button>
            <button id="tag" tag="TP" type="button" class="btn btn-success btn-sm">&lt;TP&gt;</button>
            <button id="tag" tag="TQ" type="button" class="btn btn-success btn-sm">&lt;TQ&gt;</button>
            <button id="tag" tag="EX" type="button" class="btn btn-success btn-sm">&lt;EX&gt;</button>            
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
      <div class="tab-pane div-box-line " id="scoring">
        <br>    
            <div class="col-md-12 ">           
              <div class="col-md-12 ">                         
                <div class="col-md-12 ">  
                <?                
                  $explode = explode(',', $scoring);
                  foreach ($explode as $row) {                      
                    $array = explode(':', $row);

                    $key = $array[0];
                    $value = $array[1];

                    switch ($key) {
                      case 'ibc':
                        $view_key = 'I/B/C';
                        break;
                        case 'thesis':
                        $view_key = 'Thesis';
                        break;
                        case 'topic':
                        $view_key = 'Topic';
                        break;
                        case 'coherence':
                        $view_key = 'Coherence';
                        break;
                        case 'transition':
                        $view_key = 'Transition';
                        break;
                        case 'mi':
                        $view_key = 'Main Idea';
                        break;
                        case 'si':
                        $view_key = 'Supporting Idea';                          
                        break;
                        case 'ibc':
                        $view_key = 'Supporting Idea';
                        break;
                        case 'style':
                        $view_key = 'Style';
                        break;
                        case 'usage':
                        $view_key = 'Usage';
                        break;
                      default:
                        $view_key = 'error';
                        break;
                    }

                  ?>
                  <div class="row" style="margin-bottom:10px;">  
                    <label for="inputEmail3" class="col-md-2 "><?=$view_key;?></label>
                    <div class="col-md-2">
                      <input type="text" class="form-control" id="<?=strtolower($key);?>" placeholder="0" value="<?=$value;?>">
                    </div>
                  </div>
                  <?
                  } // foreach end.
                  ?>                   
                </div>
              </div>
            </div>               
      </div>   

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
    <form> <!-- hidden data -->
      <input type="hidden" id="raw_writing" value="<?=$writing;?>">
      <input type="hidden" id="re_raw_writing" value="<?=$re_raw_writing;?>">
    </form>  
  </div>  
</div>   
<script type="text/javascript" src="/public/js/jquery.timer.js"></script>
<script src="/public/wy/external/jquery.hotkeys.js"></script>   
<script src="/public/wy/external/google-code-prettify/prettify.js"></script> 
<script src="/public/wy/bootstrap-wysiwyg.js"></script>
<script>
var cate = '<?=$cate;?>';
var draft_time = <?=$time;?>;
console.log(cate);

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

// 결과 전송
$('button#draft').click(function(){     
  var editing = $('div#editor').html();
  var critique = $('textarea#critique').val();    
  var tagging = $('div#tagging_box').html();       
  var type = '<?=$type;?>';

  var ibc = $('input#ibc').val();
  var thesis = $('input#thesis').val();
  var topic = $('input#topic').val();
  var coherence = $('input#coherence').val();
  var transition = $('input#transition').val();
  var mi = $('input#mi').val();
  var si = $('input#si').val();
  var style = $('input#style').val();
  var usage = $('input#usage').val();

  Example1.resetStopwatch(); // stop timer
  var time = $('div#stopwatch').text().substr(8);  
  var min = parseInt(time.substr(0,2));
  var second = parseInt(time.substr(3));

  min = min * 6000;
  second = second * 100;
  var total_time = min+second;  
  // console.log(total_time);
  var scoring = JSON.stringify({ibc: ibc, thesis: thesis, topic: topic, coherence: coherence, transition: transition, mi: mi, si: si, style: style, usage: usage});  
  var data = {            
    essay_id: <?=$id;?>,            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    scoring : scoring,
    time : total_time
  }
  //console.log(data);
  
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

  var ibc = $('input#ibc').val();
  var thesis = $('input#thesis').val();
  var topic = $('input#topic').val();
  var coherence = $('input#coherence').val();
  var transition = $('input#transition').val();
  var mi = $('input#mi').val();
  var si = $('input#si').val();
  var style = $('input#style').val();
  var usage = $('input#usage').val();
  
  Example1.resetStopwatch(); // stop timer
  var time = $('div#stopwatch').text().substr(8);  
  var min = parseInt(time.substr(0,2));
  var second = parseInt(time.substr(3));

  min = min * 6000;
  second = second * 100;
  var total_time = min+second;  
  //console.log(total_time);
  var scoring = JSON.stringify({ibc: ibc, thesis: thesis, topic: topic, coherence: coherence, transition: transition, mi: mi, si: si, style: style, usage: usage});  
  var data = {            
    essay_id: <?=$id;?>, // Table column id.            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    scoring : scoring,
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
