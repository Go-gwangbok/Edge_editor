<div class="container" style="margin-top:-15px;">
  <div class="row">   
    <ol class="breadcrumb" style="background:white;">
          <li><a href="/">Home</a></li>                   
          <?php
            if($cate == 'writing'){
          ?>
            <li><a href="/service">Service</a></li>                         
            <li class="akacolor">Writing</li>
          <?php
            }elseif($cate == 'todo' || $cate == 'draft'){
          ?>
            <li><a href="/musedata/project/">Project</a></li>   
            <li><a href="/musedata/project/board/<?=$cate;?>/<?=$pj_id?>/<?=$this->session->userdata('id');?>"><?=$pjname;?></a></li>   
            <li class="akacolor">Todo</li>                         
          <?php
            }elseif($cate == 'com'){
          ?>
            <li><a href="/musedata/project/">Project</a></li>   
            <li><a href="/musedata/project/board/<?=$cate;?>/<?=$pj_id?>/<?=$this->session->userdata('id');?>"><?=$pjname;?></a></li>   
            <li class="akacolor">Completed</li>                         
          <?php
            }elseif($cate == 'tbd'){
          ?>             
            <li><a href="/musedata/project/">Project</a></li>   
            <li><a href="/musedata/project/board/<?=$cate;?>/<?=$pj_id?>/<?=$this->session->userdata('id');?>"><?=$pjname;?></a></li>    
            <li class="akacolor">T.B.D</li>                         
          <?php
            }elseif($cate == 'history'){
          ?>
            <li><a href="/musedata/project/">Project</a></li>   
            <li><a href="/musedata/project/board/<?=$cate;?>/<?=$pj_id?>/<?=$this->session->userdata('id');?>"><?=$pjname;?></a></li>   
            <li class="akacolor">History</li>
          <?php
            }                          
          ?>            
      </ol> <!-- Navi end -->
  </div>  

    <!-- <h2 style="margin-top:-10px;">Title</h2>  -->
  <div class="div-box-line-promp">
    <dl>
        <dt style="margin:0 10px 0 10px">Prompt [<?=$id;?>]</dt>
        <?php
          if($cate == 'todo' || $cate == 'draft'){
        ?>
          <dd style="margin:0 15px 0 25px"><textarea id="title" style="width:100%;" rows="2"><?=trim($title);?></textarea></dd>
        <?php
          } else {
        ?>
          <dd style="margin:0 15px 0 25px" id="prompt"><?=trim($title);?></dd>
        <?php
          }
        ?>                   
    </dl>       
  </div>    
  <br>

  <div id = "error"></div>

  <ul class="nav nav-tabs" id="myTab">    
    <?php
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
      <?php
      }else{
      ?>
      <li><a href="#<?=$element?>" data-toggle="tab"><?=$view_ele;?></a></li>  
      <?php      
      } 
      $i++;   
    }
    ?>
    <!-- Default -->    
    <div id="stopwatch" class="btn btn-default pull-right" disabled>Timer : 00:00</div>
    <div class="btn btn-default pull-right" style="margin-right:3px;" disabled>Word count : <?=$word_count;?></div>
    <?php
    if($cate != 'writing') { // Editor 하지만, Edge Writing에서 넘어온것은 표시안함!
      if($error_chk == 'RE' && $submit != 1){
      ?>
        <button class="btn btn-danger pull-right" id="errorlist" style="margin-right:10px;" disabled>Return</button>
      <?php
      }elseif($submit != 1){
      ?>
        <button class="btn btn-danger pull-right" id="errorlist" style="margin-right:10px;">Error</button>
      <?php
      }      
      if($discuss == 'Y' && $submit != 1){
      ?>
        <button class="btn btn-warning pull-right" id="discuss" style="margin-right:10px;">T.B.D</button>
      <?php
      }      
    } else { // writing service
    ?>
      <button class="btn btn-warning pull-right" id="w_discuss" style="margin-right:10px;">T.B.D</button>
    <?php
    }
    ?>   
  </ul>
  <br>
<div class="tab-content">
  <!-- Original -->
  <?php  
  if($chk_orig == 'Y'){
  ?>  
   <div class="tab-pane div-box-line active" id="orig">
      <div class="col-md-12" style="margin-top:15px;">                
        
        <div>          
          <?php
            echo trim($re_raw_writing);             
          ?>      
        </div> 
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->
  <?php } ?> <!-- Original end -->

    
    <!-- Error detecting -->
    <?php    
    if($chk_detection == 'Y'){
    ?>      
    <div class="tab-pane div-box-line" id="detection">
      <div class="col-md-12" style="margin-top:15px;">  
        <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor" style="margin-bottom:20px;">

          <div class="btn-group">
            <a class="btn" data-edit="strikethrough" title="Strikethrough"><span class="glyphicon glyphicon-trash"></span> DEL</a>        
            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><span class="glyphicon glyphicon-refresh"></span> MOD</a>
            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><span class="glyphicon glyphicon-pencil"></span> INS</a>
            <!--<a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><span class="glyphicon glyphicon-refresh"></span> TYPO</a>-->

          </div> 

          <div class="btn-group pull-right">
            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)" ><i class="icon-undo"></i></a>
            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)" ><i class="icon-repeat"></i></a>
          </div> 
        </div> <!-- btn-toolbar -->

        <div id="editor">          
          <?php
            // Draft essay          
            if($cate == 'com' || $cate == 'draft' || $cate == 'tbd'){          
              echo trim($edit_writing);
            }else{ // New essay or T.B.D          
              echo trim($writing); 
            }
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
                    <?php // Draft essay
                    if($cate == 'com' || $cate == 'draft' || $cate == 'tbd' || $cate == 'writing'){
                    ?>
                      <textarea class="border text_box" id="critique" style="width:100%;" rows="7"><?=trim($critique);?></textarea>              
                    <?php
                    }else{ // New essay or T.B.D          
                    ?>
                      <textarea class="border text_box" id="critique" style="width:100%;" rows="7"></textarea>           
                    <?php } ?>   
                  </div>
                </div>            
              </div>           
            </div>
            <br>
            <!-- accordion end -->
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->
    <?php } ?>
    <!-- Error detecting end -->    

    <!-- Tagging start -->
    <?php
    if($chk_structure == 'Y'){
    ?>      
    <div class="tab-pane div-box-line" id="structure">        
      <div class="col-md-12">           
        <div class="col-md-12" style="margin-top:10px;"> 

          <div class="col-md-12"> 
            <div class="col-md-12" style="margin-top:20px; text-align:center" id="confbox">
              <h5>Confirm&nbsp;&nbsp;   
               <?php
                foreach ($tag_templet as $value) {
                  $tag_id = $value->tag_id;
                  $tag_name = $value->tag;
                  $appear_tag = $value->appear;
                ?>
                <button id="block" tag="<?=$tag_name;?>" type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-tasks"></span> <?=strtoupper($appear_tag)?></button>
                <?php } ?>                      
                <!-- <button id="block" tag="in" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> IN</button> -->                  
              </h5>
            </div>
            <hr style="border:1px dashed; border-color: #d6e9c6;">                         
          </div>       

          <!-- Tagging -->
          <?php
          foreach ($tag_templet as $value) {
            $tag_id = $value->tag_id;
            $tag_name = $value->tag;
            $appear_tag = $value->appear;
          ?>
          <button id="tag" tag="<?=strtolower($tag_name);?>" appear="<?=strtoupper($appear_tag);?>" type="button" class="btn btn-success btn-sm">&lt;<?=strtoupper($appear_tag);?>&gt;</button>
          <?php } ?>                      
          <!-- <button id="tag" tag="IN" type="button" class="btn btn-success btn-sm">&lt;IN&gt;</button> -->            
          <button id="all" type="button" class="btn btn-default btn-danger btn-sm pull-right" click="clear"><span class="glyphicon glyphicon-refresh"></span> Clear All</button>            
          <button id="redo" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;"><span class="glyphicon glyphicon-refresh"></span> Redo</button>
          <button id="undo" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;"><span class="glyphicon glyphicon-refresh"></span> Undo</button>            
 
      </div>  <!-- col-md-12 -->
    </div> <!-- col-md-12 -->              
    <hr class="text-box-hr">              
    <div class="col-md-12" id="hrline">                    
      <?php
      if($cate == 'com' || $cate == 'draft' || $cate == 'tbd' || $cate == 'writing'){
      ?>
      <div class="divtagging_box" id="tagging_box"><?=trim($tagging);?></div>          
      <?php
      }else{ // New or Writing
      ?>
      <div class="divtagging_box" id="tagging_box"><?=trim($writing);?></div>          
      <?php
      }
      ?>             
    </div>        
  </div> 
  <?php } ?>
  <!-- Tagging End -->
  
  <?php
  if($chk_sco1 == 'Y'){
  ?>          
  <!-- Score1 start -->   
  <div class="tab-pane div-box-line " id="sco1">
    <br>    
    <div class="col-md-12 ">           
      <div class="col-md-12 ">                         
        <div class="col-md-12 ">  
        <?php
        foreach ($score_templet as $value) {
          $score_id = $value->score_id;
          $score_name = $value->name;
        ?>
        <div class="row" style="margin-bottom:10px;">  
          <label for="inputEmail3" class="col-md-2 "><?=strtoupper($score_name)?></label>
          <div class="col-md-2">
            <input type="text" class="form-control score_val1" id="<?=strtolower($score_name);?>1" placeholder="0">
          </div>
        </div>
        <?php } ?>                 
        </div>
      </div>
    </div>              
  </div>  
  <?php } ?>
  <!-- Score1 End -->


  <!-- Score2 start -->   
  <?php
  if($chk_sco2 == 'Y'){
  ?>      
  <div class="tab-pane div-box-line " id="sco2">
    <br>    
    <div class="col-md-12 ">           
      <div class="col-md-12 ">                         
        <div class="col-md-12 ">  
        <?php
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
        <?php } ?>                 
        </div>
      </div>
    </div>              
  </div>  
  <?php } ?>
  <!-- Score2 End -->

    <!-- Submit button -->
    <div style="margin-top:8px;">
      <?php
      if($this->session->userdata('classify') == 1 && $cate == 'draft' || $cate == 'todo' || $cate == 'pj_draft' || $cate == 'tbd'){     
      ?>  
      <button class="btn btn-danger pull-right" id="submit">Submit</button>
      <button class="btn btn-primary" id="draft">Save Draft</button>
      <?php  
      }elseif($this->session->userdata('classify') == 1 && $cate == 'com'){          
      ?>
      <button class="btn btn-danger pull-right" id="editSubmit">Submit</button>
      <?php
      }elseif($this->session->userdata('classify') == 1 && $cate == 'writing'){
      ?>  
      <button class="btn btn-danger pull-right" id="w_submit" disabled>Submit</button>
      <button class="btn btn-primary" id="w_draft">Save Draft</button>
      <?php
      }
      ?>   
    </div> 
    <!-- hidden data -->
    <form> 
      <input type="hidden" id="raw_writing" value="<?=$writing;?>">
      <input type="hidden" id="re_raw_writing" value="<?=$re_raw_writing;?>">
      <input type="hidden" id="edit_writing" value="<?=$edit_writing;?>">
      <input type="hidden" id="h_title" value="<?=$title;?>">
      <input type="hidden" id="writing" value="<?=$writing;?>">
      <input type="hidden" id="h_critique" value="<?=$critique;?>">      
      <input type="hidden" id="h_tagging" value="<?=$tagging;?>">
      <!--
      <input type="hidden" id="score_templet" value="<?=$score_templet;?>">      
    -->
    </form>  
  </div>  
</div>   

<script src="/public/wy/external/jquery.hotkeys.js"></script>   
<script src="/public/wy/external/google-code-prettify/prettify.js"></script> 
<script src="/public/wy/bootstrap-wysiwyg.js"></script>
<script src="/public/js/jquery.timer.js"></script>
<script src="/public/js/jquery.cleditor.js"></script>
<script type="text/javascript" >
var chk_orig = '<?=$chk_orig;?>';
var chk_detection = '<?=$chk_detection;?>';
var chk_structure = '<?=$chk_structure;?>';
var chk_sco1 = '<?=$chk_sco1;?>';
var chk_sco2 = '<?=$chk_sco2;?>';

var cate = '<?=$cate;?>';
var draft_time = <?=$time;?>;
var score = '<?=$score1;?>';
var score_second = '<?=$score2;?>';
console.log(cate);

function getAnchorOffset() {
  if (window.getSelection) {                      //only work if supported
     var selection = window.getSelection ();      //get the selection object     
     var anchorOffsetProp = selection.anchorOffset;   //get the offset
     console.log( "Anchor Offset: \n" + anchorOffsetProp.toString());                                 
     }
} 

(function($) { 
  // Define the hello button
  $.cleditor.buttons.hello = {
    name: "hello",
    image: "hello.gif",
    title: "Hello World",
    command: "inserthtml",
    popupName: "hello",
    popupClass: "cleditorPrompt",
    popupContent: "Enter your name:<br><input type=text size=10><br><input type=button value=Submit>",
    buttonClick: helloClick
  };
 
  // Add the button to the default controls before the bold button
  $.cleditor.defaultOptions.controls = $.cleditor.defaultOptions.controls
    .replace("bold", "hello bold");
 
  // Handle the hello button click event
  function helloClick(e, data) {
 
    // Wire up the submit button click event
    $(data.popup).children(":button")
      .unbind("click")
      .bind("click", function(e) {
 
        // Get the editor
        var editor = data.editor;
 
        // Get the entered name
        var name = $(data.popup).find(":text").val();
 
        // Insert some html into the document
        var html = "Hello " + name;
        editor.execCommand(data.command, html, null, data.button);
 
        // Hide the popup and set focus back to the editor
        editor.hidePopups();
        editor.focus();
 
      });
 
  }
 
})(jQuery);      

// $('button#abc').click(function(){
//   $('#dd')
//     // insert before string '<strong>'
//     // <strong> を選択テキストの前に挿入
//     .selection('insert', {text: '<strong style="color:red;">', mode: 'before'})
//     // insert after string '</strong>'
//     // </strong> を選択テキストの後に挿入
//     .selection('insert', {text: '</strong>', mode: 'after'});
// });
var tagging_action_array = new Array();  
$(document).ready(function(){      
  var active_ele = '<?=$active_ele;?>';
  $('div#'+active_ele).addClass('active');
  
  // Score 1
  var score_array = score.split(',');   
  $.each(score_array,function(i,value){
    var each_val = value;
    var score_name = each_val.split(':');
    $('div').find('input#'+score_name[0].toLowerCase()+'1').val(score_name[1]);    
  }); 

  // Score 2
  var score2_array = score_second.split(',');   
  $.each(score2_array,function(i,value){
    var each_val2 = value;
    var score_name2 = each_val2.split(':');
    $('div').find('input#'+score_name2[0].toLowerCase()+'2').val(score_name2[1]);    
  }); 
  
  // Tagging Mapping
  if(chk_structure == 'Y'){
    // function type org or appear.
    $('span').removeAttr('tag');
    var tagOrg = all_tagsArray('org');
    var tagAppear = all_tagsArray('appear');    
    var taggingData = $.trim($('div#tagging_box').html());
    //alert(taggingData);    
    
    // <IN></IN>, <TR></TR>... 기존에 있던 태그 삭제 후 Mapping한다.       
    $.each(tagOrg,function(i,value){
      var className = value;          
      var spanCount = $('span.'+className).length;    
      var span_str = '<span class="'+className+'">';

      for(j = 0; j < spanCount; j++){
        var taggingData = $.trim($('div#tagging_box').html());    
        if(spanCount > 1){            
          taggingData = taggingData.replace('&lt;'+tagAppear[i]+(j+1)+'&gt;',''); // 앞 태그
          taggingData = taggingData.replace('&lt;/'+tagAppear[i]+(j+1)+'&gt;',''); // 뒤 태그  

          get_span = $('span.'+className+':eq('+j+')').html()
          get_span_replace = get_span.replace('&lt;'+tagAppear[i]+'&gt;',''); // 앞 태그
          get_span_replace = get_span_replace.replace('&lt;/'+tagAppear[i]+'&gt;',''); // 뒤 태그            
          if(j == 0){
            taggingData = taggingData.replace(span_str + $('span.'+className+':eq('+j+')').html(), span_str + '&lt;'+tagAppear[i]+'&gt;'+get_span_replace+'&lt;/'+tagAppear[i]+'&gt;');
          }else{
            taggingData = taggingData.replace(span_str + $('span.'+className+':eq('+j+')').html(), span_str + '&lt;'+tagAppear[i]+(j+1)+'&gt;'+get_span_replace+'&lt;/'+tagAppear[i]+(j+1)+'&gt;');
          }          
          $.trim($('div#tagging_box').html(taggingData));          
        }else{
          taggingData = taggingData.replace('&lt;'+tagAppear[i]+'&gt;',''); // 앞 태그
          taggingData = taggingData.replace('&lt;/'+tagAppear[i]+'&gt;',''); // 뒤 태그
          $.trim($('div#tagging_box').html(taggingData));
          var get_span = $('span.'+className+':eq('+j+')').html();          
          taggingData = taggingData.replace(span_str + get_span, span_str + '&lt;'+tagAppear[i]+'&gt;'+get_span+'&lt;/'+tagAppear[i]+'&gt;');          
          $.trim($('div#tagging_box').html(taggingData));
        } // If end.                 
      } // For end. 
    }); // Each end.      
  } // If end.

  tagging_action_array.push($('div#tagging_box').html());  
  $('button#undo').attr('disabled',true);    
  $('button#redo').attr('disabled',true);    

  if(chk_detection == 'Y'){    
    var editing = $.trim($('div#editor').html());
    var critique = $.trim($('textarea#critique').val());
  }else{
    if(cate == 'draft' || cate == 'com' || cate == 'tbd'){
      var editing = $.trim($('input#edit_writing').val());
      var critique = $.trim($('input#h_critique').val());
    }else{ //new
      var editing = $.trim($('input#writing').val());
      var critique = $.trim($('input#h_critique').val());
    }
  }

}); // Ready end.

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
        //$('#counter').html(count);
    });
timer.set({ time : 1000, autostart : true });


// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

var Example1 = new (function() {

    if(cate == 'draft'){
      var currentTime = draft_time; // Current time in hundredths of a second 100 == 1  
          incrementTime = 70; // Timer speed in milliseconds      
    }else if(cate == 'admin' || cate == 'com'){
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


function selectionGet()
{
  // for webkit, mozilla, opera
  if (window.getSelection)
    return window.getSelection();
  // for ie
  else if (document.selection && document.selection.createRange && document.selection.type != "None")
    return document.selection.createRange();
}

function checkValidationOfSelectedText(selectedText)
{
  var all_tag_array = all_tagsArray('appear'); // Array 
  var all_orgtag = all_tagsArray('org'); // Array  
  
  var tagging = $.trim($('div#tagging_box').html());

  var result = true;

  $.each(all_tag_array,function(i,value)
  {
    var tag = value;
    var re = new RegExp('span class="'+all_orgtag[i]+'"', "ig"); 

    var match = tagging.match(re);
    if(match != null)
    {
      for(j = 0; j < match.length; j++)
      {
        var startRe, endRe, startMatchCnt, endMatchCnt;

        if (j == 0)
        {
          startRe = new RegExp('&lt;'+ tag +'&gt;', "ig");
          endRe = new RegExp('&lt;/'+ tag +'&gt;', "ig");          
        }
        else
        {
          startRe = new RegExp('&lt;'+ tag + (j+1) + '&gt;', "ig");
          endRe = new RegExp('&lt;/'+ tag + (j+1) + '&gt;', "ig");          
        }

        var startMatch = selectedText.match(startRe);
        var endMatch = selectedText.match(endRe);

        startMatchCnt = endMatchCnt = 0;

        if (startMatch != null && startMatch.length > 0)
        {
          startMatchCnt = startMatch.length;
        }
        if (endMatch != null && endMatch.length > 0)
        {
          endMatchCnt = endMatch.length;
        }
        //alert("tag : " + tag + ", start: " + startMatchCnt + ", end: " + endMatchCnt);
        if (startMatchCnt != endMatchCnt)
        {
            result = false;
            break;
        }
      }
    }
  });

  return result;
}

// for redo & undo of tagging_action_array
var undo_num = 1;
var redo_num = 0;

// Tag Wrap
$('button#tag').click(function(){  
  $('span').removeAttr('style');        
  var tag = $(this).attr('tag'); // Original Tag name
  var get_appear_tag = $(this).attr('appear'); // Appear Tag name
  //var select_text = $.selection('html');
  //alert(select_text);
  // var select_a = $.selection('html');
  // console.log(select_a); 
  
  //console.log($("div#tagging_box").html().indexOf(select_text));
  //console.log($("div#tagging_box").html().anchorOffset(select_text));
  
    var div = $("div#tagging_box");  
    var tagging_data = $("div#tagging_box").html(); 

    var reg = new RegExp('span class="'+tag+'"', "ig");  
    var match_reg = tagging_data.match(reg);

    var tTag = "span";
    var tAttr = "class";
    var tVal = tag;

    // for webkit, mozilla, opera     
    if (window.getSelection)
    {
        var selObj = selectionGet(), selRange, newElement, documentFragment;

        if (selObj.anchorNode && selObj.getRangeAt)
        {
            selRange = selObj.getRangeAt(0);

            //var selectedText = selRange.toString();

            var selectedText = $('<div></div>').append(selRange.cloneContents()).html();

            //alert("selectedText : " + selectedText);

            if(selectedText == '')
            {
                alert('selection Error : text is not selected');
                return;
            }

            //result = checkValidationOfSelectedText(selectedText);
            //alert(result);
            if (!checkValidationOfSelectedText(selectedText))
            {
                alert('selection Error : selection range is invalid');
                return;
            }

            // create to new element
            newElement = document.createElement(tTag);

            // extract to the selected text
            documentFragment = selRange.extractContents();

            // add the contents to the new element
            newElement.appendChild(documentFragment);

      var replaced_text = "";
            if(match_reg != null)
            {  // 적어도 두번째 같은 태그를 사용함!
                var match_reg_cnt = match_reg.length;    
                replaced_text = '&#60'+get_appear_tag+(match_reg_cnt+1)+'&#62'+selectedText+'&#60/'+get_appear_tag+(match_reg_cnt+1)+'&#62';
            }
            else
            { // 같은 태그를 한번도 사용한적이 없음!
                replaced_text = '&#60'+get_appear_tag+'&#62'+selectedText+'&#60/'+get_appear_tag+'&#62';
            }
            //alert(replaced_text);

            newElement.innerHTML = replaced_text;
            
            // add the attribute to the new element
            $(newElement).attr(tAttr,tVal);

            selRange.insertNode(newElement);
            selObj.removeAllRanges();

            // if the attribute is "style", change styles to around tags
            //if(tAttr=="style")
            //  affectStyleAround($(newElement),tVal);
            // for other attributes
            //else
            //  affectStyleAround($(newElement),false);
        }
    }
    // for ie
    else if (document.selection && document.selection.createRange && document.selection.type != "None")
    {
        var range = document.selection.createRange();
        var selectedText = range.htmlText;

        var newText = '<'+tTag+' '+tAttr+'="'+tVal+'">'+selectedText+'</'+tTag+'>';
    
        document.selection.createRange().pasteHTML(newText);
    }

    var replace_tagging_text = $("div#tagging_box").html(); 
    while (undo_num > 1)
    {
        tagging_action_array.pop();
        undo_num--;
    }

    tagging_action_array.push(replace_tagging_text); // Replace 될때 마다 push.
    undo_num = 1;
    $('button#undo').attr('disabled',false);    
    $('button#redo').attr('disabled',true);

  return;


  /***
  if(select_text == ''){
    alert('selection Error');
  }else{
    var div = $("div#tagging_box");    
    var tagging_data = $("div#tagging_box").html(); 

    var reg = new RegExp('span class="'+tag+'"', "ig");  
    var match_reg = tagging_data.match(reg);

    if(match_reg != null){  // 적어도 두번째 같은 태그를 사용함!
      var match_reg = match_reg.length;    
      $("div#tagging_box").html(tagging_data.replace(select_text,'<span class="'+tag+'">&#60'+get_appear_tag+(match_reg+1)+'&#62'+select_text+'&#60/'+get_appear_tag+(match_reg+1)+'&#62</span>'));
    }else{ // 같은 태그를 한번도 사용한적이 없음!
      $("div#tagging_box").html(tagging_data.replace(select_text,'<span class="'+tag+'">&#60'+get_appear_tag +'&#62'+select_text+'&#60/'+get_appear_tag+'&#62</span>'));      
    }
    var replace_tagging_text = $("div#tagging_box").html();          
    tagging_action_array.push(replace_tagging_text); // Replace 될때 마다 push.
    $('button#undo').attr('disabled',false);    
    $('button#redo').attr('disabled',true);   
  }    
  ***/
});

//Error Undo

$('button#undo').click(function(){  
  
  console.log('-------undo--------');
  
  var array_num = tagging_action_array.length - 1;    
  var num = array_num - undo_num;
  $('div#tagging_box').html(tagging_action_array[num]);
  if( num > 0){
  $('button#redo').attr('disabled',false);    
    undo_num++;      
    redo_num = num;
  }else if(num == 0){
    $(this).attr('disabled',true);
    $('button#redo').attr('disabled',false);    
    redo_num = 0;
    undo_num = array_num; //num을 0으로 만들기 위해.
  }    
});

//Error Redo
$('button#redo').click(function(){  
  console.log('-------redo--------');
  //console.log(redo_num);  
  var array_num = tagging_action_array.length - 1;  
  var num = redo_num + 1;
  $('div#tagging_box').html(tagging_action_array[num]);  
  if( array_num > num){
    $('button#undo').attr('disabled',false);
    redo_num++;    
  }else if(num == array_num){
    $(this).attr('disabled',true);
    $('button#undo').attr('disabled',false);    
    undo_num = 1;
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

// Confirm Btn
$("button#block").click(function(){  
  $('span').removeAttr('style');  
  var tag = $(this).attr('tag');   
  var styles = {
      backgroundColor : "#f15e22",
      color: "white"
    };
  $('span.'+tag).css(styles); 
  $('span.'+tag+' *').css(styles); // * 특정 요소 하위의 모든 요소를 선택할 수 있는 방법. 
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
  var scoreNames = $('input.'+input_className).map(function() {        
      var classname = $(this).attr('id');
      var score_name = classname.slice(0, -1);             
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

function all_tagsArray(type){
  var all_tags_orgname = new Array();
  var all_tags_appearname = new Array();
  $.ajax({
          type: "POST",
          url: "/text_editor/get_all_tag",
          async:false,          
          dataType: "json",
          success: function (json) {
           var all_tags = json['all_tag']; 

           $.each(all_tags,function(i,value){
            var tag = value['tag'];
            var appear_tag = value['appear'].toUpperCase();
            all_tags_orgname.push(tag);
            all_tags_appearname.push(appear_tag);
           });
          }
      });

    if(type == 'org'){
      return all_tags_orgname;     
    }else if(type == 'appear'){
      return all_tags_appearname;   
    }
      
}

function hidden_score(h_score){
  var hidden_score_obj = {};
  var sco_explod = h_score.split(',');
  $.each(sco_explod,function(i,value){        
      var key_value = value.split(':');
      hidden_score_obj[key_value[0]] = key_value[1];
  });  
  return hidden_score_obj;
}

function get_save_data(){ 
  Example1.resetStopwatch(); // stop timer
  var time = $('div#stopwatch').text().substr(8);  
  var min = parseInt(time.substr(0,2));
  var second = parseInt(time.substr(3));

  min = min * 6000;
  second = second * 100;
  var total_time = min+second;

  if(chk_detection == 'Y'){    
    var editing = $.trim($('div#editor').html());
    var critique = $.trim($('textarea#critique').val());
  }else{
    if(cate == 'draft' || cate == 'com' || cate == 'tbd'){
      var editing = $.trim($('input#edit_writing').val());
      var critique = $.trim($('input#h_critique').val());
    }else{ //new
      var editing = $.trim($('input#writing').val());
      var critique = $.trim($('input#h_critique').val());
    }
  }


  if ( $('textarea#title').length > 0 ) {
    var title = $.trim($('textarea#title').val());
  } else {
    /**
    var title = '<?=str_replace(Array("\r\n","\n","\r"), Array("\\","\\",""),$title);?>';
    **/
    var title = $('input#h_title').val();
  }

  $('span').removeAttr('style'); // Confirm으로 인해 span css를 삭제하고 DB에 저장한다!
  $('br').removeAttr('style'); // Confirm으로 인해 br css를 삭제하고 DB에 저장한다!
  if(chk_structure == 'Y'){            
    var tagging = $.trim($('div#tagging_box').html());
    console.log(tagging);    
  }else{
    if(cate == 'draft' || cate == 'com' || cate == 'tbd'){
      var tagging = $.trim($('input#h_tagging').val());
    }else{ // new
      var tagging = $.trim($('input#writing').val());
    }
  }

  if(chk_sco1 == 'Y'){
    var score_obj = make_obj_score('score_val1'); // Score1
    var score1 = JSON.stringify(score_obj);    
  }else{ 
    var score_obj = hidden_score(score); // Score1
    var score1 = JSON.stringify(score_obj);        
  }

  if(chk_sco2 == 'Y'){
    var score_obj2 = make_obj_score('score_val2'); // Score2
    var score2 = JSON.stringify(score_obj2);    
  }else{
    var score_obj2 = hidden_score(score_second); // Score2
    var score2 = JSON.stringify(score_obj2);       
  } 

  var all_tag_array = all_tagsArray('appear'); // Array  
  var all_orgtag = all_tagsArray('org'); // Array  
  
  $.each(all_tag_array,function(i,value){
    var tag = value;    
    var re = new RegExp('span class="'+all_orgtag[i]+'"', "ig");  
    var match = tagging.match(re);

    if(match != null){
      for(j = 0; j < match.length; j++){
        if(match.length == 1){
          tagging = tagging.replace('&lt;'+tag+'&gt;',''); // 앞 태그
          tagging = tagging.replace('&lt;/'+tag+'&gt;',''); // 뒤 태그  
        }else{
          if(j == 0){
            tagging = tagging.replace('&lt;'+tag+'&gt;',''); // 앞 태그
            tagging = tagging.replace('&lt;/'+tag+'&gt;',''); // 뒤 태그  
          }else{
            tagging = tagging.replace('&lt;'+tag+(j+1)+'&gt;',''); // 앞 태그
            tagging = tagging.replace('&lt;/'+tag+(j+1)+'&gt;',''); // 뒤 태그    
          }
        }
      }        
    }      
  });  

  var type = '<?=$type;?>';   
  var data = {            
        data_id: <?=$id;?>,
        title : title,         
        editing: editing,
        critique: critique,
        tagging: tagging,
        type: type,
        score1 : score1,
        score2 : score2,
        time : total_time
  }  
  return data;
  //return match;
}


// 결과 전송
$('button#draft').click(function(){    
  var data = get_save_data();
  console.log(data);
  
  $.ajax(
  {
    url: '/text_editor/draft_save', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {      
      if(json['status'])
      {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');
        window.history.back();
        //location.reload();
        //window.location.replace('/essaylist'); // 리다이렉트할 주소
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
  var data = get_save_data();  
  data['pj_id'] = '<?=$pj_id;?>';
  console.log(data);  
  $.ajax(
  {
    url: '/text_editor/submit', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    { 
      console.log(json['error_chk']);
      console.log(json['status']);

      if(json['error_chk'] == 'garbage_update_error'){
        alert('garbage_update_error');
      }else if(json['error_chk'] == 'final_update_error'){
        alert('final_update_error');
      }else{
        if(json['status'])
        {
          // 정상적으로 처리됨
          alert('It’s been successfully processed!');
          window.history.back();
          //window.location.replace('/essaylist'); // 리다이렉트할 주소
        }else{
          alert('all_list --> draft DB Error');
        }  
      }
    }
  });
});

$('button#editSubmit').click(function()
{ 
  var data = get_save_data();  
  console.log(data);
  
  $.ajax(
  {
    url: '/text_editor/editsubmit', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {
      if(json['status'])
      {
        // 정상적으로 처리됨
        alert('It’s been modified!');
        window.history.back();
        //window.location.replace('/essaylist/done'); // 리다이렉트할 주소
      }
      else
      {
        alert('all_list --> draft DB Error');
      }
    }
  });
});      

// writing js
var conf = '';
var conf_cri = '';

$('#critique').on("propertychange input textInput", function() {
    var charLimit = 10;
    var critique = $('textarea#critique').val();  
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;
  
    if (remaining < charLimit && remaining > 0) {       
       $('#w_submit').prop('disabled', true);                  

    } else if (remaining < 0) {        
        $('#w_submit').prop('disabled', false);               
    } 
});

// Service Draft
$('button#w_draft').click(function(){    
  var data = get_save_data();
  data['kind'] = '<?=$kind?>';
  data['raw_writing'] = $('input#raw_writing').val();
  data['title'] = $('input#h_title').val();
  data['word_count'] = '<?=$word_count?>';
  console.log(data);
  
  $.ajax(
  {
    url: '/service/draft_save', // 포스트 보낼 주소

    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {      
      if(json['status'])
      {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');
        //window.history.back();
        //location.reload();
        //window.location.replace('/essaylist'); // 리다이렉트할 주소
      }
      else
      {
        alert('all_list --> draft DB Error');
      }
    }
  });
});  

// Service Submit.
$("button#w_submit").click(function(){    

  var data = get_save_data();  
  data['token'] = '<?=$token;?>';  
  data['kind'] = '<?=$kind?>';
  data['raw_writing'] = $('input#raw_writing').val();
  data['title'] = $('input#h_title').val();
  data['word_count'] = '<?=$word_count?>';
  console.log(data);

  $.ajax({    
    url: '/service/w_submit',
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json){      
      var access = json['result'];      
      console.log(access);                  
      if(access == true){  
        alert('It’s been successfully processed!');
        window.location.href = '/service';
        //window.location = "/essaylist/done";
      }else if(access == 'error_chk'){
        $('#error').empty();
        var errors = String(json['error_chk']);
        //alert('error_chk = ' + errors);
        errors = errors.replace(/&lt/g, '<').replace(/&gt/g, '>');
        //alert('error_chk = ' + errors);
        var errorList = errors.split(",");
        //alert(errorList[0]);
        //alert(errorList[1]);
        var error_msg = "<span><font color=\"red\"> ERROR : </font></span><br>";
        $.each(errorList, function(i, value) {
          error_msg += "<span>" + (i+1) + ". " + value + "</span><br>";
        });
        error_msg += "<br>";

        $('#error').append(error_msg);

        alert("editting tagging error is dectected!!!");

        return false;

      }else if(access == 'localdb'){
        alert('local db insert error');
      }else{
        alert('Curl Error');
      }
    }
  });       
});

//Error List button
$('button#errorlist').click(function(){
  console.log('error');
  var error_data = {
    data_id: <?=$id;?>    
  }
  console.log(error_data);
  $.post('/musedata/project/error',error_data,function(json){            
      if(json['result']){
          window.location = "/musedata/project/board/<?=$cate;?>/<?=$pj_id?>/<?=$this->session->userdata('id')?>";
      }else{
        alert('DB Error --> error_proc');
      }
  });
});

// To be discuss button
$('button#discuss').click(function(){  
  var data = {            
    data_id : <?=$id;?>    
  }  
  console.log(data);
  $.post('/musedata/project/discuss',data,function(json){          
    if(json['result']){        
        window.location = "/musedata/project/board/<?=$cate;?>/<?=$pj_id?>/<?=$this->session->userdata('id')?>";
    }else{
      alert('DB Error --> error_proc');
    }
  });
});

// To be discuss button
$('button#w_discuss').click(function(){  
  var data = {
    token : '<?=$token;?>', 
    data_id : <?=$id;?>    
  }  
  console.log(data);
  $.post('/service/discuss',data,function(json){          
    if(json['result']){
        alert(json['result']);        
        //window.history.back();
    }else{
      alert('DB Error --> error_proc');
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
