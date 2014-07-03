<div class="container" style="margin-top:-15px;">
  <div class="row">   
    <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>                   
        <li><a href="/service/">Service</a></li>
        <li><a href="/service/serviceType/<?=$service_name;?>"><?=ucfirst($service_name);?></a></li>   
        <li><a href="/service/enter/<?=$service_name;?>/<?=$month?>/<?=$year;?>"><?=$year.' - '.$str_month?></a></li>   
        <?php if ($cate == 'tbd') { ?>
          <li  class="akacolor">T.B.D</li>   
        <?php } else if ($cate == "error") { ?>
          <li  class="akacolor">Error</li>   
        <?php } else { ?>
          <li  class="akacolor">Completed</li>   
        <?php } ?>
        <!--li class="akacolor"><?=ucfirst($kind_name);?></li-->
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
    <div id="stopwatch" class="btn btn-default pull-right" disabled>Timer : 00:00 </div>       
    <div class="btn btn-default pull-right" style="margin-right:3px;" disabled>Word count : <?=$word_count?></div>       
    <?php
    if($cate == 'error'){
    ?>
    <button class="btn btn-md btn-danger pull-right" id="not_error" style="margin-right:5px;">Return</button>
    <button class="btn btn-md btn-danger pull-right" id="yes" style="margin-right:5px;">Yes</button>     
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
            echo trim($raw_writing);             
          ?>      
        </div> 
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->
    <?php } ?>
    <!-- Original end -->

    <!-- Error detecting -->
    <?php
    if($chk_detection == 'Y'){
    ?>      
    <div class="tab-pane div-box-line" id="detection">
      <div class="col-md-12" style="margin-top:15px;">   
        <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor" style="margin-bottom:20px;">
          
          <?php
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
        <?php
         }else{ // Cate == T.B.D
        ?>

        <div id = "error">aaa</div>

        <div class="btn-group">
          <a class="btn" data-edit="strikethrough" title="Strikethrough"><span class="glyphicon glyphicon-trash"></span> DEL</a>                
          <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)" ><span class="glyphicon glyphicon-refresh"></span> MOD</a>        
          <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)" ><span class="glyphicon glyphicon-pencil"></span> INS</a>            
        </div>
        
        <div class="btn-group pull-right">
          <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)" ><i class="icon-undo"></i></a>
          <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)" ><i class="icon-repeat"></i></a>
        </div> 
        <?php
         }
        ?>
        </div> <!-- btn-toolbar -->      
        <!-- Error detecting -->
        <div id="editor">          
          <?php          
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
    <?php } ?>
    <!-- Error detecting end -->

    <!-- Tagging start -->
    <?php
    if($chk_structure == 'Y'){
    ?>      
    <div class="tab-pane div-box-line" id="structure">
        <!--<div class="div-box-line">-->
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
                <button id="block" tag="<?=$tag_name;?>" type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-tasks"></span> <?=strtoupper($appear_tag);?></button>
                <?php } ?>                      
                </h5>
              </div>
              <hr style="border:1px dashed; border-color: #d6e9c6;">                         
            </div>        
                      
            <?php
            foreach ($tag_templet as $value) {
              $tag_id = $value->tag_id;
              $tag_name = $value->tag;
              $appear_tag = $value->appear;
            ?>
            <button id="tag" tag="<?=strtolower($tag_name);?>" appear="<?=strtoupper($appear_tag);?>" type="button" class="btn btn-success btn-sm">&lt;<?=strtoupper($appear_tag);?>&gt;</button>
            <?php } ?>            

            <button id="all" type="button" class="btn btn-default btn-danger btn-sm pull-right" click="clear"><span class="glyphicon glyphicon-refresh"></span> Clear All</button>            
            <button id="redo" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;"><span class="glyphicon glyphicon-refresh"></span> Redo</button>
            <button id="undo" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;"><span class="glyphicon glyphicon-refresh"></span> Undo</button>            
   
          </div>  <!-- col-md-12 -->
        </div> <!-- col-md-12 -->              
        <hr class="text-box-hr">              
        <div class="col-md-12" id="hrline">                              
          <div class="divtagging_box" id="tagging_box"><?=trim($tagging);?></div>                    
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
      <div class="tab-pane div-box-line" id="sco2">
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
        if($cate == 'tbd'){     
        ?>  
        <button class="btn btn-danger pull-right" id="submit">Submit</button>
        <button class="btn btn-primary" id="draft">Save Draft</button>
        <?php  
        }
        ?>        
      </div> 
      <!-- Submit button end -->
      <!-- hidden data -->
      <form> 
        <input type="hidden" id="raw_writing" value="<?=$writing;?>">
        <input type="hidden" id="re_raw_writing" value="<?=$re_raw_writing;?>">
        <input type="hidden" id="edit_writing" value="<?=$edit_writing;?>">
        <input type="hidden" id="writing" value="<?=$writing;?>">
        <input type="hidden" id="h_critique" value="<?=$critique;?>">      
        <input type="hidden" id="h_tagging" value="<?=$tagging;?>">
        <input type="hidden" id="score_templet" value="<?=$score_templet;?>">      
      </form>  
  </div>  
</div>   
<script src="/public/js/jquery.timer.js"></script>
<script src="/public/wy/external/jquery.hotkeys.js"></script>   
<script src="/public/wy/external/google-code-prettify/prettify.js"></script> 
<script src="/public/wy/bootstrap-wysiwyg.js"></script>
<script type="text/javascript">
var chk_orig = '<?=$chk_orig;?>';
var chk_detection = '<?=$chk_detection;?>';
var chk_structure = '<?=$chk_structure;?>';
var chk_sco1 = '<?=$chk_sco1;?>';
var chk_sco2 = '<?=$chk_sco2;?>';

var cate = '<?=$cate;?>';
var draft_time = <?=$time;?>;
var score = '<?=$score1?>';
var score_second = '<?=$score2?>';
console.log(cate);
console.log(score);

var tagging_action_array = new Array();  
$(document).ready(function(){
  console.log('<?=$active_ele;?>');
  var active_ele = '<?=$active_ele;?>';
  $('div#'+active_ele).addClass('active');
  
  // Score 1
  var score_array = score.split(',');   
  $.each(score_array,function(i,value){
    var each_val = value;
    var score_name = each_val.split(':');
    //console.log(score_name[1]);
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
  }  // If end.

  tagging_action_array.push($('div#tagging_box').html());  
  $('button#undo').attr('disabled',true);    
  $('button#redo').attr('disabled',true);  
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

    if(cate == 'draft' || cate == 'tbd'){
      var currentTime = draft_time; // Current time in hundredths of a second 100 == 1  
          incrementTime = 70; // Timer speed in milliseconds      
    }else if(cate == 'admin_export' || cate == 'com' || cate == 'error' || cate == 'service'){ // Time을 멈춤!
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

  // error checking
  var erchk_data = {
    data : '<?=$edit_writing;?>',
    data_id : <?=$id;?>,
    type : <?=$type;?>
  }
  
  $.post('/errorchk/error_chk_post',erchk_data,function(json){
    var result = json['result'];
    console.log(result);    
    
    $('#error').empty();
    var error_list = '';
    
    if(result.length != 0){
      $.each(result,function(i,value){
        if(value != 'Replace Error' && value != '// Slash count Error'){
          error_list += value+' : ';
        }       
      });     

      $('#error').append('<span><font color="red">U tag count : </span></font>'+json['u_tag']+'<br>'
                +'<span><font color="red">// Slash count : </span></font>'+json['slash_tag']+'<br>'
                +'<span><font color="red">Error message : </span></font>'+error_list.slice(0,-2)+'<br>'
                ); 
    }   
  });

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
