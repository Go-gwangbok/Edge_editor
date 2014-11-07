<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="akacolor">Training</li>   
      </ol>            
      <h3 style="margin-left:13px;">Muse Base Training    
      </h3>  
  </div>  <!-- Nav or Head title End -->
  <br>    


    <div class="row clearfix">
        <div class="col-md-12 column">
            <form class="form-inline" role="form">
                <div class="form-group">
                    <select class="form-control" id="usr_id">
                    <?php foreach ($training_users as $user) { ?>    
                    <option value="<?=$user->usr_id;?>" <?php if ($user->usr_id == $usr_id) echo "selected"; ?>><?=$user->name;?></option>
                    <?php } ?>
                </select>
                </div>
                <div class="form-group">
                    <button type="button" id="select_usr" class="btn btn-default">조회</button>
                </div>
            </form>


        </div>
    </div>
    <br><br>


    <?php if ( !empty($training_result) ) { ?>

    <div class="row clearfix">
      <div class="col-md-12 column">

        <?php if ($qualified == true) { ?> 
        <h3 class="text-center" style="margin-top:-10px;" id="title">Conguration! You are qulified as a editor.</h3>
        <?php } else { ?>
        <h3 class="text-center" id="sub_title">In order to obtain the qualification you must pass at least two exams. <br>A passing score is 90.</h3>
        <?php }?>
        <br>

         <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center"  width="250">Title</th>
                    <th class="text-center">Score</th>
                    <th class="text-center">Passed</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($training_result as $training) { ?>
                <tr>
                    <td class="text-center">
                        <?=$training->id?>
                    </td>
                    <td class="text-center">
                        <?=$training->name?>
                    </td>
                    <td class="text-center">
                        <?=$training->score?>
                    </td>
                    <td class="text-center">
                        <?php if ($training->certificated == 1) { ?>Y<?php } else if ($training->certificated != null) { ?>N<?php } ?>
                    </td>
                    <td class="text-center">
                        <?php if ($training->score != null ) { ?><?=$training->created?><?php } ?>
                    </td>
                    <td class="text-center">
                      <?php if ( !empty($training->score) ) { ?>
                        <a href="/training/admin_result/<?=$usr_id?>/<?=$training->id?>/" class="btn btn-primary">View Result</a> 
                      <?php } ?>
                    </td>
                </tr>
                <?php } ?>
               
            </tbody>
        </table>

      </div>

    </div>
    <?php } ?>

<script type="text/javascript">

$('button#select_usr').click(function(){
    var usr_id = $('select#usr_id option:selected').val();
    window.location = "/training/admin_list/" + usr_id + "/";
});

</script>