<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/training/">Training</a></li>
        <li class="akacolor">Result</li>   
      </ol>            
      <h3 style="margin-left:13px;">Muse Base Training    
      </h3>  
  </div>  <!-- Nav or Head title End -->
  <br>    

    <div class="row clearfix">
      <div class="col-md-12 column">
        <?php if ($training_result->score >= 90) { ?> 
        <h3 class="text-center" style="margin-top:-10px;" id="title">Conguration! You passed this test.</h3>
        <?php } else { ?>
        <h3 class="text-center" id="sub_title">You didn't pass this test.</h3>
        <?php }?>
        <br>

         <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center"  width="500">Title</th>
                    <th class="text-center">Score</th>
                    <th class="text-center">Passed</th>
                    <th class="text-center">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <?=$training_result->name?>
                    </td>
                    <td class="text-center">
                        <?=$training_result->score?>
                    </td>
                    <td class="text-center">
                        <?php if ($training_result->certificated == 1) { ?>Y<?php } else if ($training_result->certificated != null) { ?>N<?php } ?>
                    </td>
                    <td class="text-center">
                        <?=$training_result->created?>
                    </td>
                </tr>
               
            </tbody>
        </table>
      </div>

 <br>    

    <div class="row clearfix">
      <div class="col-md-12 column">
         <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center"  width="500">Prompt</th>
                    <th class="text-center">Orig_Score</th>
                    <th class="text-center">Editor_Score</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($training_result_detail as $training_rd) { ?>
                <tr>
                    <td class="text-center">
                        <?=$training_rd->seq?>
                    </td>
                    <td class="text-center">
                        <?=$training_rd->prompt?>
                    </td>
                    <td class="text-center">
                        <?=$training_rd->org_score?>
                    </td>
                    <td class="text-center">
                        <?=$training_rd->score?>
                    </td>
                    <td class="text-center">
                        <?=$training_rd->created?>
                    </td>
                    <td class="text-center">
                        <a href="/training/view_score_result/<?=$training_result->tr_id?>/<?=$training_rd->seq?>/<?php if ($classify == 0) echo $training_rd->usr_id . "/"; ?>"" class="btn btn-primary" style="margin-top:9px;">View Your Score</a><br>
                        <?php if ($training_rd->trdata_id <= 51) { ?>
                        <a href="/training/view_criterion_result/<?=$training_result->tr_id?>/<?=$training_rd->seq?>/<?php if ($classify == 0) echo $training_rd->usr_id . "/"; ?>" class="btn btn-primary" target='criterion' style="margin-top:9px;">View Criterion Results</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
               
            </tbody>
        </table>
      </div>

    </div>

<script type="text/javascript">

$('button#select_usr').click(function(){
    var usr_id = $('select#usr_id option:selected').val();
    window.location = "/admin/commute/" + usr_id + "/";
});

</script>