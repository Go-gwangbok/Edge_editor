<div class="container">
    <h2 style="margin-top:-10px;">Title
    <!--<div id="stopwatch" class="btn btn-default pull-right" disabled>Timer : 00:00</div>-->
    </h2> 
  <div class="div-box-line-promp">
    <dl>
        <dt style="margin:0 10px 0 10px">Prompt</dt>              
        <dd style="margin:0 15px 0 25px" id="prompt"><?=trim($title);?></dd>
    </dl>       
  </div>    
  <br>

  <ul class="nav nav-tabs" id="myTab">    
    <li class="active"><a href="#error" data-toggle="tab">Error detecting</a></li>
    <li><a href="#tagging" data-toggle="tab">Tagging</a></li>    
    <li><a href="#scoring" data-toggle="tab">Scoring</a></li>    
    <div id="stopwatch" class="btn btn-default pull-right" disabled>Timer : 00:00</div>
    <?
    if($this->session->userdata('classify') == 1){    
      if($error_chk == 1){
      ?>
        <button class="btn btn-danger pull-right" id="errorlist" style="margin-right:10px;" disabled>Return</button>
      <?
      }else{
      ?>
        <button class="btn btn-danger pull-right" id="errorlist" style="margin-right:10px;">Error</button>
      <?
      }      
      if($discuss == 'Y'){
      ?>
        <button class="btn btn-warning pull-right" id="discuss" style="margin-right:10px;">T.B.D</button>
      <?
      }
      ?>
      
    <?
    }
    ?>   
  </ul>
  <br>
<div class="tab-content">

    <div class="tab-pane div-box-line active" id="error">
      <div class="col-md-12" style="margin-top:15px;">   
      <!-- Error detecting -->
      <div style="margin-bottom:10px;">
        <?
        if($this->session->userdata('classify') == 1){ // Editor
        ?>
        <button class="btn btn-default" style="border:0px;" id="mod"><span class="glyphicon glyphicon-refresh"></span> MOD</button>
        <button class="btn btn-default" style="border:0px;" id="del"><span class="glyphicon glyphicon-trash"></span> DEL</button>
        <button class="btn btn-default" style="border:0px;" id="ins" data-toggle="button"><span class="glyphicon glyphicon-pencil"></span> INS</button>
        <!-- <button class="btn btn-default" style="border:0px;" id="tbd"><span class="glyphicon glyphicon-warning-sign"></span> T.B.D Zone</button> -->
        <button class="btn btn-default btn-danger btn-sm pull-right" id="clear" status="clear"><span class="glyphicon glyphicon-refresh"></span> Clear All</button>            
        <button class="btn btn-default pull-right" style="border:0px;" id="edi_redo" ><span class="glyphicon glyphicon-chevron-right"></span> </button>
        <button class="btn btn-default pull-right" style="border:0px;" id="edi_undo" ><span class="glyphicon glyphicon-chevron-left"></span> </button>        
        
        <?
        }else{ // Admin
        ?>
        <button class="btn btn-default" style="border:0px;" id="mod" disabled><span class="glyphicon glyphicon-refresh"></span> MOD</button>
        <button class="btn btn-default" style="border:0px;" id="del" disabled><span class="glyphicon glyphicon-trash"></span> DEL</button>
        <button class="btn btn-default" style="border:0px;" id="ins" data-toggle="button" disabled><span class="glyphicon glyphicon-pencil"></span> INS</button>
        <!-- <button class="btn btn-default" style="border:0px;" id="tbd"><span class="glyphicon glyphicon-warning-sign"></span> T.B.D Zone</button> -->
        <button class="btn btn-default btn-danger btn-sm pull-right" id="clear" disabled><span class="glyphicon glyphicon-refresh"></span> Clear All</button>   
        <button class="btn btn-default pull-right" style="border:0px;" id="edi_redo" disabled><span class="glyphicon glyphicon-chevron-right"></span> </button>
        <button class="btn btn-default pull-right" style="border:0px;" id="edi_undo" disabled><span class="glyphicon glyphicon-chevron-left"></span> </button>        
                 
        <?
        }
        ?>        
      </div>
      
      <div id="editor" ContentEditable="false"> 
        <?
          if($cate == 'mydone' || $cate == 'draft' || $cate == 'admin' || $cate == 'pj_draft'){          
            echo nl2br(trim($edit_writing));          
          }else{                  
            echo trim($writing); 
         }
        ?>      
      </div>    

        <hr class="text-box-hr"> <!-- 하단 라인 -->     
          <div class="panel-group" id="accordion">          
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    Critique
                  </a>
                </h4>
              </div>
              
              <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                  <?
                  if($cate == 'mydone' || $cate == 'draft' || $cate == 'admin' || $cate == 'pj_draft'){
                  ?>
                    <textarea class="border text_box" id="critique" style="width:100%;" rows="7"><?=trim($critique);?></textarea>              
                  <?
                  }else{
                  ?>
                    <textarea class="border text_box" id="critique" style="width:100%;" rows="7"></textarea>           
                  <?
                  }
                  ?> 
                </div>
              </div>            
            </div>           
          </div> <!-- accordion end -->
        <br>
        
      </div>  
    </div>

    <!-- accordion tagging -->

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
            <?
            if($this->session->userdata('classify') == 0 || $cate == 'admin'){        
            ?>           
            <button id="tag" tag="IN" type="button" class="btn btn-success btn-sm" disabled>&lt;IN&gt;</button>
            <button id="tag" tag="BO" type="button" class="btn btn-success btn-sm" disabled>&lt;BO&gt;</button>
            <button id="tag" tag="CO" type="button" class="btn btn-success btn-sm" disabled>&lt;CO&gt;</button>
            <button id="tag" tag="TS" type="button" class="btn btn-success btn-sm" disabled>&lt;TS&gt;</button>
            <button id="tag" tag="MI" type="button" class="btn btn-success btn-sm" disabled>&lt;MI&gt;</button>
            <button id="tag" tag="SI" type="button" class="btn btn-success btn-sm" disabled>&lt;SI&gt;</button>
            <button id="tag" tag="TR" type="button" class="btn btn-success btn-sm" disabled>&lt;TR&gt;</button>
            <button id="tag" tag="Ohter" type="button" class="btn btn-success btn-sm" disabled>&lt;Ohter&gt;</button>
            <button id="tag" tag="TP" type="button" class="btn btn-success btn-sm" disabled>&lt;TP&gt;</button>
            <button id="tag" tag="TQ" type="button" class="btn btn-success btn-sm" disabled>&lt;TQ&gt;</button>
            <button id="tag" tag="EX" type="button" class="btn btn-success btn-sm" disabled>&lt;EX&gt;</button>            
            <button id="all" type="button" class="btn btn-default btn-sm pull-right" disabled><span class="glyphicon glyphicon-refresh"></span> Clear All</button>            
            <button id="redo" tag="TR" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;" disabled><span class="glyphicon glyphicon-refresh"></span> Redo</button>
            <button id="undo" tag="TR" type="button" class="btn btn-default btn-sm pull-right" style="margin-right:5px;" disabled><span class="glyphicon glyphicon-refresh"></span> Undo</button>            
            <? }else{ ?>            
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

            <? } ?>             
          </div>   
        </div>               
        <hr class="text-box-hr">              
        <div class="col-md-12" id="hrline">                    
          <?
          if($cate == 'mydone' || $cate == 'draft' || $cate == 'admin' || $cate == 'pj_draft'){
          ?>
          <div class="divtagging_box" id="tagging_box"><?=trim($tagging);?></div>          
          <?
          }else{
          ?>
          <div class="divtagging_box" id="tagging_box"><?=trim($writing);?></div>          
          <?
          }
          ?>             
        </div>        
      </div>   
      <div class="tab-pane div-box-line " id="scoring">
        <br>    
          <div class="col-md-12 ">           
            <div class="col-md-12 ">                         
              <div class="col-md-12 ">  
              <?
              if($cate == 'todo' || $cate == 'writing'){
              ?>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputEmail3" class="col-md-2 ">I/B/C</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="ibc" placeholder="0">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Thesis</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="thesis" placeholder="0">
                  </div>
                </div>
              
                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Topic</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="topic" placeholder="0">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputEmail3" class="col-md-2 control-label">Coherence</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="coherence" placeholder="0">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Transition</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="transition" placeholder="0">
                  </div>
                </div>
              
                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Main Idea</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="mi" placeholder="0">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Supporting Idea</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="si" placeholder="0">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Style</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="style" placeholder="0">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Usage</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="usage" placeholder="0">
                  </div>
                </div> 
               
                <? }else{ ?> 
               
                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputEmail3" class="col-md-2 ">I/B/C</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="ibc" placeholder="0" value="<?=$ibc;?>">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Thesis</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="thesis" placeholder="0" value="<?=$thesis;?>">
                  </div>
                </div>
              
                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Topic</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="topic" placeholder="0" value="<?=$topic;?>">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputEmail3" class="col-md-2 control-label">Coherence</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="coherence" placeholder="0" value="<?=$coherence;?>">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Transition</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="transition" placeholder="0" value="<?=$transition;?>">
                  </div>
                </div>
              
                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Main Idea</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="mi" placeholder="0" value="<?=$mi;?>">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Supporting Idea</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="si" placeholder="0" value="<?=$si;?>">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Style</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="style" placeholder="0" value="<?=$style;?>">
                  </div>
                </div>

                <div class="row" style="margin-bottom:10px;">  
                  <label for="inputPassword3" class="col-md-2 control-label">Usage</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="usage" placeholder="0" value="<?=$usage;?>">
                  </div>
                </div>

                <? } ?>                  

              </div>
            </div>
          </div>               
      </div> <!-- Scoring Div -->

      <div style="margin-top:8px;">
        <?
        if(($this->session->userdata('classify') == 1 && $cate == 'draft') || ($this->session->userdata('classify') == 1 && $cate == 'todo') || ($this->session->userdata('classify') == 1 && $cate == 'pj_draft')){     
        ?>  
        <button class="btn btn-danger pull-right" id="submit">Submit</button>
        <button class="btn btn-primary" id="draft">Save Draft</button>
        <?  
        }elseif($this->session->userdata('classify') == 1 && $cate == 'mydone'){          
        ?>
        <button class="btn btn-danger pull-right" id="editSubmit">Submit</button>
        <?
        }elseif($this->session->userdata('classify') == 1 && $cate == 'writing'){
        ?>  
        <button class="btn btn-danger pull-right" id="w_submit" disabled>Submit</button>
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
<script>
//$("[rel='tooltip']").tooltip().mouseover();;      
$("[rel='tooltip']").tooltip()

var edi_html_array = new Array();  
$(document).ready(function(){
  edi_html_array.push($('div#editor').html());
  //console.log(edi_html_array[0]);
  $('button#edi_undo').attr('disabled',true);    
  $('button#edi_redo').attr('disabled',true);    
});

function replaceSelectedText(replacementText,selText,mode) {
  var sel, range, selText, mode;
  if (window.getSelection) {
    sel = window.getSelection();
    //console.log(sel);
    if (sel.rangeCount) {
      range = sel.getRangeAt(0);
      //console.log(range);
      range.deleteContents();          
      if(mode == 'mod'){
        range.insertNode(document.createTextNode("<mod>"+replacementText+"</mod>"));          
        var raw = $('div#editor').html();  
        var replacetext = '<a href="#" rel="tooltip" data-original-title="'+ selText +'">'+replacementText+'</a>';            
        $('div#editor').html(raw.replace("&lt;mod&gt;"+replacementText+"&lt;/mod&gt;",replacetext));   
        return true;
      }else if(mode == 'del'){        
        range.insertNode(document.createTextNode("<del>"+replacementText+"</del>"));          
        var raw = $('div#editor').html();          
        var replacetext = '<del>'+replacementText+'</del>';            
        $('div#editor').html(raw.replace("&lt;del&gt;"+replacementText+"&lt;/del&gt;",replacetext));   
      }else if(mode == 'tbd'){
        range.insertNode(document.createTextNode("<tbd>"+replacementText+"</tbd>"));          
        var raw = $('div#editor').html();          
        var replacetext = '<tbd style="background-color: #BCE55C;">'+replacementText+'</tbd>';            
        $('div#editor').html(raw.replace("&lt;tbd&gt;"+replacementText+"&lt;/tbd&gt;",replacetext));   
      }
    }
  }else if (document.selection && document.selection.createRange) {
    range = document.selection.createRange();
    range.text = replacementText;
  }
}  

function insertHtmlAtCursor(html) {
  var range, node;
  if (window.getSelection && window.getSelection().getRangeAt) {
      range = window.getSelection().getRangeAt(0);
      node = range.createContextualFragment(html);
      range.insertNode(node);
  } else if (document.selection && document.selection.createRange) {
      document.selection.createRange().pasteHTML(html);
  }
}

// Modify Button
$('button#mod').click(function(){  
  var selText = $.selection('html');     
  if(selText){
    var replacementText = window.prompt('Before :'+ selText, "Text");    
    if(replacementText != null){      
      var replacetext = replaceSelectedText(replacementText,selText,'mod');    
      //console.log(replacetext);
      edi_html_array.push($('div#editor').html());
      $('button#edi_undo').attr('disabled',false);    
      $('button#edi_redo').attr('disabled',true);    
      $("[rel='tooltip']").tooltip()      
    }    
  }else{
    alert("문장이나 단어를 선택하세요!");
  }   
});

//Del Button
$('button#del').click(function(){  
  var selText = $.selection();   
  //console.log(selText);
  if(selText){    
    replaceSelectedText(selText,selText, 'del');        
    edi_html_array.push($('div#editor').html());
    //console.log($('div#editor').html());
    //console.log(edi_html_array.length);
    $('button#edi_undo').attr('disabled',false);    
    $('button#edi_redo').attr('disabled',true);    
  }else{
    alert("문장이나 단어를 선택하세요!");
  }   
});

//Ins Button
var ins_btn = true;
$('button#ins').click(function(){      
  if(ins_btn){    
    var ContentEditable = $('div#editor').attr('ContentEditable','TRUE');  

    $('div#editor').mouseup(function() {    
      var replacementText = window.prompt('Insert data :', "Text");        
      if(replacementText != null){
        var html = '<ins>'+replacementText+'</ins>';
        insertHtmlAtCursor(html);  
        edi_html_array.push($('div#editor').html());
      }  
    });
    ins_btn = false;
  }else{    
    $('div#editor').attr('ContentEditable','false').unbind('mouseup');      
    ins_btn = true;
  }
  $('button#edi_undo').attr('disabled',false);    
  $('button#edi_redo').attr('disabled',true);    
});

//Error edi_undo
var ii = 1;
var redo_num = 0;

$('button#edi_undo').click(function(){  
  
  console.log('-------undo--------');
  //console.log(ii);
  var array_num = edi_html_array.length - 1;    
  var num = array_num - ii;
  $('div#editor').html(edi_html_array[num]);
  if( num > 0){
  $('button#edi_redo').attr('disabled',false);    
    ii++;      
    redo_num = num;
  }else if(num == 0){
    $(this).attr('disabled',true);
    $('button#edi_redo').attr('disabled',false);    
    redo_num = 0;
    ii = array_num; //num을 0으로 만들기 위해.
  }  
  // console.log(num);     
  // console.log(ii);  
  
});

//Error edi_redo
$('button#edi_redo').click(function(){  
  console.log('-------redo--------');
  //console.log(redo_num);  
  var array_num = edi_html_array.length - 1;  
  var num = redo_num + 1;
  $('div#editor').html(edi_html_array[num]);  
  if( array_num > num){
    $('button#edi_undo').attr('disabled',false);
    redo_num++;    
  }else if(num == array_num){
    $(this).attr('disabled',true);
    $('button#edi_undo').attr('disabled',false);    
    ii = 1;
  }
  // console.log(array_num);  
  // console.log(redo_num);  
  // console.log(num);  
});

//Clear all Button
$('button#clear').click(function(){  
  var status = $(this).attr('status');
  console.log(status);
  if(status == 'clear'){
    var rawWritting = $("input#re_raw_writing").val();          
    $('div#editor').html(rawWritting);  
    var cate = '<?=$cate;?>';    
    if(cate == 'todo'){
      $(this).attr('status','clear');    
    }else{
      $(this).html('<span class="glyphicon glyphicon-refresh"></span> Redo All');   
      $(this).attr('status','allredo');      
    }    
  }else{    
    $('div#editor').html(edi_html_array[0]);      
    $(this).html('<span class="glyphicon glyphicon-refresh"></span> Clear All');  
    $(this).attr('status','clear');
    $("[rel='tooltip']").tooltip()      
  }
});


//TBD Zone button
// $('button#tbd').click(function(){
//   var selText = $.selection();   
//   console.log(selText);
//   if(selText){    
//     replaceSelectedText(selText,selText, 'tbd');        
//   }else{
//     alert("문장이나 단어를 선택하세요!");
//   }
// });

// Timer
var cate = '<?=$cate;?>';
var draft_time = <?=$time;?>;

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
  if(cate == 'draft'){
    var currentTime = draft_time; // Current time in hundredths of a second 100 == 1  
    var incrementTime = 70; // Timer speed in milliseconds       
  }else if(cate == 'admin' || cate == 'mydone'){
    var currentTime = draft_time; 
    var incrementTime = 0; 
  }else{
    var incrementTime = 70; 
    var currentTime = 0; 
  }    

  var $stopwatch, // Stopwatch element on the page        
      updateTimer = function() {
          $stopwatch.html('Timer : ' + formatTime(currentTime));
          currentTime += incrementTime / 10;
      },
      init = function() {
          $stopwatch = $('#stopwatch');
          Example1.Timer = $.timer(updateTimer, incrementTime, true);
      };
  this.resetStopwatch = function() {
      //currentTime = 0;
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
  var div = $("div#tagging_box");  
  var data = div.html();
  
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
    var rawWritting = $("input#re_raw_writing").val();        
    //console.log(rawWritting);
    $("div#hrline").html('<div class="divtagging_box" id="tagging_box">'+rawWritting+'<br/></div>');
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
  console.log(time);
  console.log(min);
  console.log(second);
  console.log(total_time);

  var data = {            
    essay_id: <?=$id;?>,            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    ibc: ibc,
    thesis: thesis,
    topic: topic,
    coherence: coherence,
    transition: transition,
    mi: mi,
    si: si,
    style: style,
    usage: usage,
    time : total_time
  }
  console.log(data);
  
  $.ajax(
  {
    url: '/text/draft_save', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json) {      
      console.log(json['status']);
      if(json['status']) {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');
        //window.history.back();
        //location.reload();
        window.location.replace('/todo'); // 리다이렉트할 주소
      }
      else {
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
  console.log(time);
  console.log(min);
  console.log(second);
  console.log(total_time);

  var data = {            
    essay_id: <?=$id;?>,            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    ibc: ibc,
    thesis: thesis,
    topic: topic,
    coherence: coherence,
    transition: transition,
    mi: mi,
    si: si,
    style: style,
    usage: usage,
    time : total_time
  }
  console.log(data);
  
  $.ajax(
  {
    url: '/text/submit', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {      
      if(json['status'] == 'true')
      {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');
        window.history.back();
        //window.location.replace('/essaylist'); // 리다이렉트할 주소
      }
      else
      {
        alert('all_list --> draft DB Error');
      }
    }
  });
});

$('button#editSubmit').click(function()
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

  var data = {            
    essay_id: <?=$id;?>,            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type,
    ibc: ibc,
    thesis: thesis,
    topic: topic,
    coherence: coherence,
    transition: transition,
    mi: mi,
    si: si,
    style: style,
    usage: usage
  }
  console.log(data);
  
  $.ajax(
  {
    url: '/text/editsubmit', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {
      if(json['status'] == 'true')
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

// Writing Submit.
$("button#w_submit").click(function(){  
  var token = '<?=$token;?>';
  var id = <?=$id;?>;
  var editing = $.trim($('div#editor').html());
  var raw_writing = $('input#raw_writing').val(); 
  var critique = $('textarea#critique').val();    
  var tagging = $('div#tagging_box').html();
  var kind = '<?=$kind;?>';
  var title = $('dd#prompt').text();

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
  console.log(time);
  console.log(min);
  console.log(second);
  console.log(total_time);

  var submit_data = {
    token: token,
    w_id: id,
    kind: kind,
    title: title,
    raw_writing: raw_writing,
    editing: editing,
    critique: critique,
    tagging: tagging,
    ibc: ibc,
    thesis: thesis,
    topic: topic,
    coherence: coherence,
    transition: transition,
    mi: mi,
    si: si,
    style: style,
    usage: usage,
    time : total_time
  };
  console.log(submit_data); 
  $.ajax({
    //url:"http://192.168.0.22:8888/editor/editing/done",
    url: '/writing/submit',
    type: 'POST',         
    data: submit_data,
    dataType: 'json',
    success: function(json){
      var access = JSON.parse(json['result']);            
      if(access['status']){  
        alert('It’s been successfully processed!');
        window.history.back();
        //window.location = "/essaylist/done";
      }else if(access == 'localdb'){
        alert('local db insert error');
      }
    }
  });       
});

//Error List button

$('button#errorlist').click(function(){   
  var error_data = {            
    essay_id: <?=$id;?>    
  }
  console.log(error_data);
  $.post('/project/error',error_data,function(json){      
    //console.log(json['result']);
    if(json['result']){
        window.location = "/todo";

    }else{
      alert('DB Error --> error_proc');
    }
  });
});

// To be discuss button
$('button#discuss').click(function(){  
  var data = {            
    essay_id: <?=$id;?>  
  }
  //console.log(data);
  $.post('/project/discuss',data,function(json){      
    //console.log(json['result']);
    if(json['result']){
        window.location = "/todo";
    }else{
      alert('DB Error --> error_proc');
    }
  });
});

</script>                   
