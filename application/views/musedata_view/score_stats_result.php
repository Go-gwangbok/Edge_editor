<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/musedata/project/">Project</a></li>  
        <li class="akacolor">Score Stats</li>   
      </ol>            
      <h3 style="margin-left:13px;">Muse Score Stats
      </h3>  
  </div>  <!-- Nav or Head title End -->
  <br>    
  
  <div class="row" >
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">essay_id</th>
                    <th class="text-center">score1</th>
                    <th class="text-center">score2</th>
                </tr>
            </thead>
            <tbody>
                <?php $idx = 0; foreach ($result as $score_stat) { $idx++; 
                  $score_array = explode(',', $score_stat['scoring1']);
                  foreach($score_array as $score) {
                    $score = str_replace("\"", "", $score);
                    $score = str_replace("{", "", $score);
                    $score = str_replace("}", "", $score);
                    $score_name = explode(':', $score);
                    //echo "[".$score_name[0]."] ";
                    if ($score_name[0] == "total_score") {
                      $score1 = $score_name[1];
                    }
                  } 

                  $score_array = explode(',', $score_stat['scoring2']);
                  foreach($score_array as $score) {
                    $score = str_replace("\"", "", $score);
                    $score = str_replace("{", "", $score);
                    $score = str_replace("}", "", $score);
                    $score_name = explode(':', $score);
                    if ($score_name[0] == "total_score") {
                      $score2 = $score_name[1];
                    }
                  } 
                  ?>
                <tr>
                    <td class="text-center">
                        <?=$idx ?>
                    </td>
                    <td class="text-center">
                        <?=$score_stat['essay_id']?>
                    </td>
                    <td class="text-center">
                        <?=$score1?>
                    </td>
                    <td class="text-center">
                        <?=$score2?>
                    </td>
                </tr>
                <?php } ?>
               
            </tbody>
        </table>


    </div>
  </div>

</div>