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
            <li><a href="#score" data-toggle="tab">Score</a></li>

            <?php if ($seq < $question_count) { ?>
              <div id="next" class="btn btn-primary pull-right" style="margin-right:5px;"> Next </div>
            <?php } else { ?>
              <div id="finish" class="btn btn-danger pull-right" style="margin-right:5px;"> Finish </div>
            <?php } ?>
            <div id="prev" class="btn btn-primary pull-right" style="margin-right:10px;" <?php if ($seq == 1) {?>disabled<?php } ?>>Prev</div>
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
        </div>
      </div>
    </div>              
  </div>  

  <!-- Submit button -->
    <div style="margin-top:8px;">
      <button class="btn btn-danger pull-right" id="submit">Save</button>
    </div> 


</div>

<script type="text/javascript">
var changed = false;

$(document).ready(function(){

  var score = '<?=$training_data->muse_score;?>';
  var score_array = score.split(',');
  $.each(score_array,function(i,value){
    var each_val = value;
    var score_name = each_val.split(':');
    $('div').find('input#'+score_name[0].toLowerCase()).val(score_name[1]);    
  }); 
  <?php if ($training_data->score > 0) { ?>
    $('div').find('input#total_score').val(<?=$training_data->score?>);
  <?php } ?>

}); // Ready end.

$( "input[type='text']" ).change(function() {
  changed = true;
});


$('button#submit').click(function(){
   var score_obj = make_obj_score('score_val'); // Score1
   var muse_score = JSON.stringify(score_obj);
   console.log(muse_score);

   var score = $('#total_score').val();
   console.log("total_score : " + score );

   changed = false;

   
   var data = {            
        id: <?=$training_data->id;?>,
        tr_id: <?=$tr_id;?>,
        seq: <?=$seq;?>,
        muse_score: muse_score,
        score : score
        };    

    console.log("data : " + data );
 $.ajax(
  {
    url: '/training/save_training_result_detail',
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {      
      if(json['status'])
      {
        // 정상적으로 처리됨
        alert('It’s been successfully processed!');
      }
      else
      {
        alert('all_list --> draft DB Error');
      }
    }
  });
 });

$('div#next').click(function(){
  if (changed) {
    if (!confirm("There is/are changed value. \nDo you go next without saving?")) {
      return false;
    }
  }
    window.location = "/training/test/<?=$tr_id?>/<?=$seq+1?>/";
});

$('div#prev').click(function(){
  if (changed) {
    if (!confirm("There is/are changed value. \nDo you go prev without saving?")) {
      return false;
    }
  }
    window.location = "/training/test/<?=$tr_id?>/<?=$seq-1?>/";
});

$('div#finish').click(function(){
  if (changed) {
    if (!confirm("There is/are changed value. \nDo you finish this test without saving?")) {
      return false;
    }
  }
    window.location = "/training/finish/<?=$tr_id?>/";
});

// Score
function make_obj_score(input_className){
  var obj = {};
  var scoreNames = $('input.'+input_className).map(function() {        
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
  /***
  var avail_nums = ["0", "1", "2", "3", "4", "5", "6"];
  $.each(scoreNames,function(i,value){
    if (avail_nums.indexOf(scoreNums[i]) != -1) {
      obj[value] = scoreNums[i];
    } else {
      obj[value] = "";
    }
  }); 
  ***/

  return obj;
}



</script>