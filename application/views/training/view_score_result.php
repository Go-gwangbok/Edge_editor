<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/training/">Training</a></li>
        <li class="akacolor">Test</li>   
      </ol>            
      <h3 style="margin-left:13px;">Muse Base Training    
      </h3>  
  </div>  <!-- Nav or Head title End -->
  <br>    

  <div class="div-box-line-promp">
    <dl>
        <dt style="margin:0 10px 0 10px">Prompt</dt>
          <dd style="margin:0 15px 0 25px" id="prompt"><?=trim($training_data->prompt);?></dd>
    </dl>       
  </div>    
  <br>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#orig" data-toggle="tab">Original</a></li>
            <li><a href="#editing" data-toggle="tab">Detecting</a></li>
            <li><a href="#tagging" data-toggle="tab">Tagging</a></li>
            <li><a href="#score" data-toggle="tab">Score</a></li>
            <?php if ($training_data->org_muse_score != "") { ?>
            <li><a href="#score2" data-toggle="tab">Org Score</a></li>
            <?php } ?>

            <div id="question_seq" class="btn btn-default pull-right" style="margin-right:10px;" disabled><?=$seq?> / <?=$question_count?></div>
          </ul> 
  <br>

  <div class="tab-content">
  <!-- Original -->
   <div class="tab-pane div-box-line active" id="orig">
      <div class="col-md-12" style="margin-top:15px;">                
        <div>          
          <?php
            echo trim($training_data->row_txt);             
          ?>      
        </div> 
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->

    <!-- Editing -->
   <div class="tab-pane div-box-line" id="editing">
      <div class="col-md-12" style="margin-top:15px;">                
        <div id="editor">          
          <?php
            echo trim($training_data->editing);             
          ?>      
        </div> 
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->

    <!-- Tagging  -->
   <div class="tab-pane div-box-line" id="tagging">
      <div class="col-md-12" style="margin-top:15px;">  

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
                <!-- <button id="block" tag="in" type="button" class="btn btn-default btn-sm" data-toggle="button"><span class="glyphicon glyphicon-tasks"></span> IN</button> -->                  
              </h5>
            </div>
          </div> 


        <hr class="text-box-hr">              
    <div class="col-md-12" id="hrline">             
        <div class="divtagging_box" id="tagging_box">          
          <?php
            echo trim($training_data->tagging);             
          ?>      
        </div> 
      </div>
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->

      <!-- Score1 start -->   
  <div class="tab-pane div-box-line" id="score">
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
            <input type="text" class="form-control score_val" id="<?=strtolower($score_name);?>" placeholder="0">
          </div>
        </div>
        <?php } ?>  
        <div class="row" style="margin-bottom:10px;">  
          <label for="inputEmail3" class="col-md-2 text-danger">Total Score</label>
          <div class="col-md-2">
            <input type="text" class="form-control" id="total_score" placeholder="<?=$training_data->score?>">
          </div>
        </div>    

        <div class="row" style="margin-bottom:10px;">  
          <label for="inputEmail3" class="col-md-2 text-danger">Origin Score</label>
          <div class="col-md-2">
            <input type="text" class="form-control" id="org_score" placeholder="<?=$training_data->org_score?>">
          </div>
        </div>           
        </div>
      </div>
    </div>              
  </div>

  <?php if ($training_data->org_muse_score != "") { ?>
      <!-- Org Score start -->   
  <div class="tab-pane div-box-line" id="score2">
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
            <input type="text" class="form-control score_val" id="org_<?=strtolower($score_name);?>" placeholder="0">
          </div>
        </div>
        <?php } ?>  
        <div class="row" style="margin-bottom:10px;">  
          <label for="inputEmail3" class="col-md-2 text-danger">Total Score</label>
          <div class="col-md-2">
            <input type="text" class="form-control" id="org_total_score" placeholder="<?=$training_data->score?>">
          </div>
        </div>             
        </div>
      </div>
    </div>              
  </div>
  <?php } ?>
</div>

<script type="text/javascript">
$(document).ready(function(){

  var score = '<?=$training_data->muse_score;?>';
  if (score != '') {
    var score_array = score.split(',');
    $.each(score_array,function(i,value){
      var each_val = value;
      var score_name = each_val.split(':');
      $('div').find('input#'+score_name[0].toLowerCase()).val(score_name[1]);
    }); 
  }

  <?php if ($training_data->org_muse_score != "") { ?>
  var org_score = '<?=$training_data->org_muse_score;?>';
  var org_score_array = org_score.split(',');
  $.each(org_score_array,function(i,value){
    var each_val = value;
    var score_name = each_val.split(':');
    $('div').find('input#org_'+score_name[0].toLowerCase()).val(score_name[1]);
  }); 
  <?php } ?>


  $('div').find('input#total_score').val(<?=$training_data->score?>);
  $('div').find('input#org_score').val(<?=$training_data->org_score?>);

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


}); // Ready end.

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


</script>