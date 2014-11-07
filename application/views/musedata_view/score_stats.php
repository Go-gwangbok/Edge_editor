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
      <form id='fm' class="form-inline">
<!--
        <div class="form-group">
      <label for="firstname" class="col-sm-2 control-label">First Name</label>
      <div class="col-sm-10">
         <input type="text" class="form-control" id="firstname" 
            placeholder="Enter First Name">
      </div>
   </div>

   <div class="form-group">
      <label for="lastname" class="col-sm-2 control-label">Last Name</label>
      <div class="col-sm-10">
         <input type="text" class="form-control" id="lastname" 
            placeholder="Enter Last Name">
      </div>
   </div>
 -->
        <div class="form-group">
          <label class="control-label">Project 1</label>
          <div>
            <select class="form-control input-sm" id="pj1">
              <option value="">Select</option>
              <?php foreach($pjlist as $project) { ?>
                   <option value="<?=$project->pj_id?>"  <?php if ($project->pj_id == $pj1) echo "selected"; ?>><?=$project->name?></option>
              <?php } ?>
              </select>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">Project 2</label>
          <div>
            <select class="form-control input-sm" id="pj2">
              <option value="">Select</option>
              <?php foreach($pjlist as $project) { ?>
                   <option value="<?=$project->pj_id?>"  <?php if ($project->pj_id == $pj1) echo "selected"; ?>><?=$project->name?></option>
              <?php } ?>
              </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Editor 1</label>
          <div>
            <select class="form-control input-sm" id="editor1">
              <option value="">Select</option>
              </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Editor 2</label>
          <div>
            <select class="form-control input-sm" id="editor2">
              <option value="">Select</option>
              </select>
          </div>
        </div>
        <!--
        <div class="form-group">
           <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" />
        </div>
        -->
        <div class="form-group">
          <button type="button" class="btn btn-primary btn-sm" id="retrieve">Retrieve</button>
          <button type="button" class="btn btn-danger btn-sm" id="export">Export</button>
        </div> 
      </div>
      <br>
      <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">essay_id</th>
                    <th class="text-center">editor1</th>
                    <th class="text-center">score1</th>
                    <th class="text-center">score2</th>
                    <th class="text-center">editor2</th>
                </tr>
            </thead>
            <tbody id="stat_list">
                 <!-- Ajax -->     
            </tbody>
        </table>

      </form>
    </div>
  </div>

</div>
<script type="text/javascript">
$('select#pj1,select#pj2').change(function() {
  //alert($(this).val());
  //alert($(this).children("option:selected").text());
  var target_id = event.target.id;
  
  var pj_id = $('select#' + target_id + ' option:selected').val();
  if (pj_id == "") return;

  console.log(pj_id);
  $.ajax({
        type: "POST",
        url: '/musedata/project/get_editors_list',
        data: { pj_id : pj_id },
        dataType: 'json',
        success: function(json) {
          console.log(json['result']);              
          
            if(json['result']) {
              if (target_id == 'pj1') {
                displayEditorList('editor1', json['result']);  
              } else {
                displayEditorList('editor2', json['result']); 
              }
              
            }
            else
            {
              alert(json['result']);  
            }                            
        }
    }); 
});

function displayEditorList(select_id, editor_list) {
  //console.log('select_id: ' +  select_id);
  $('#'+select_id).empty();
  //var html = '';
  $('select#'+select_id).append('<option value="0">All</option>');
  for(var i = 0; i < editor_list.length; i++) {
    var editor_id = editor_list[i]['usr_id'];
    var editor_name = editor_list[i]['name'];
    //console.log('editor_id: ' +  editor_id);
    //console.log('editor_name: ' +  editor_name);
    $('select#'+select_id).append('<option value="' + editor_id + '" >' + editor_name + '</option>');
  }
}

$('button#retrieve').click(function(){
  var pj1_id = $('select#pj1 option:selected').val();
  var pj2_id = $('select#pj2 option:selected').val();

  if (pj1_id == "" || pj2_id == "") {
    alert('please, select project');
    return;
  }
  if (pj1_id == pj2_id) {
    alert('please, select different project');
    return;
  }

  var editor1_id = $('select#editor1 option:selected').val();
  var editor2_id = $('select#editor2 option:selected').val();

  if (editor1_id == 0 || editor2_id == 0) {
    if (!confirm('전체 에디터에 대해서 조회하시겠습니까?') ) {
      return;  
    }
    editor1_id = 0;
    editor2_id = 0;
  }
  else if (editor1_id == editor2_id) {
    alert("please, select different editor");
    return;
  }

    $.ajax({
        type: "POST",
        url: '/musedata/project/get_score_stats',
        data: { pj1_id : pj1_id,
                  pj2_id : pj2_id,
                  editor1_id : editor1_id,
                  editor2_id : editor2_id
          },
        dataType: 'json',
        success: function(json) {
          console.log(json['result']);              
          
            if(json['result']) {
                displayScoreStat(json['result']);  
           }
            else
            {
              alert(json['result']);  
            }                            
        }
    }); 

});

$('button#export').click(function(){
  var pj1_id = $('select#pj1 option:selected').val();
  var pj2_id = $('select#pj2 option:selected').val();

  if (pj1_id == "" || pj2_id == "") {
    alert('please, select project');
    return;
  }
  if (pj1_id == pj2_id) {
    alert('please, select different project');
    return;
  }

  var editor1_id = $('select#editor1 option:selected').val();
  var editor2_id = $('select#editor2 option:selected').val();

  if (editor1_id == 0 || editor2_id == 0) {
    if (!confirm('전체 에디터에 대해서 조회하시겠습니까?') ) {
      return;  
    }
    editor1_id = 0;
    editor2_id = 0;
  }
  else if (editor1_id == editor2_id) {
    alert("please, select different editor");
    return;
  }

  window.location = "/musedata/project/export_score_stats/"+pj1_id+"/"+pj2_id+"/"+editor1_id+"/"+editor2_id+"/";

  /***
  var data = { pj1_id : pj1_id,
                  pj2_id : pj2_id,
                  editor1_id : editor1_id,
                  editor2_id : editor2_id
          };
    console.log(data);

    $.post('/musedata/project/export_score_stats',data,function(retData){
      console.log(retData);
      var binUrl = retData.url;
      document.body.innerHTML += "<iframe src='" + binUrl + "' style='display: none;' ></iframe>";
        }); 
  ***/
});

function displayScoreStat(ScoreStatList) {

  var html = "";
  var idx = 0;

  $('tbody#stat_list').empty();

  for(var i = 0; i < ScoreStatList.length; i++) {
    idx++;
    var essay_id = ScoreStatList[i]['essay_id'];
    var id1 = ScoreStatList[i]['id1'];
    var usr_name1 = ScoreStatList[i]['usr_name1'];
    //var scoring1 = ScoreStatList[i]['scoring1'];
    var scoring1 = JSON.parse(ScoreStatList[i]['scoring1']);

    var id2 = ScoreStatList[i]['id2'];
    var usr_name2 = ScoreStatList[i]['usr_name2'];
    //var scoring2 = ScoreStatList[i]['scoring2'];
    var scoring2 = JSON.parse(ScoreStatList[i]['scoring2']);

    var style_str = 'info';
    if (scoring1.total_score == scoring2.total_score) {
      //alert(scoring1.total_score + " :: " + scoring1.total_score);
      style_str = 'success';
    } else if (scoring1.total_score - scoring2.total_score > 1 || scoring1.total_score - scoring2.total_score < -1) {
      //alert(idx + " red : " + scoring1.total_score + " :: " + scoring2.total_score);
      style_str = 'danger';
    }
    //console.log('editor_id: ' +  editor_id);
    //console.log('editor_name: ' +  editor_name);
    html += "<tr class='"+style_str+"'> <td class='text-center'>" + idx + 
                    "</td> <td class='text-center'>" + essay_id + 
                    "</td> <td class='text-center'><a href='/text_editor/comp/" + id1 + "/1' target=_blank>" + usr_name1 + "</a>" +
                    "</td> <td class='text-center'><a href='/text_editor/comp/" + id1 + "/1' target=_blank>" + scoring1.total_score +  "</a>" +
                    "</td> <td class='text-center'><a href='/text_editor/comp/" + id2 + "/1' target=_blank>" + scoring2.total_score +  "</a>" +
                    "</td> <td class='text-center'><a href='/text_editor/comp/" + id2 + "/1' target=_blank>" + usr_name2 +  "</a>" +
                    "</td> </tr>";

  }
  $('tbody#stat_list').append(html);
  //$('div#stat').removeClass('hide');
}

</script>