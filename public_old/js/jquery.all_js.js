
  var text = '';  
  var result = '';
  var access = '';
  var token = '';
  var title = '';
  var w_id = '';
  var writing = '';
  var critique = '';
  var kind = '';

$(document).ready(function(){   

  var secret = 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C';
  var email = "<?=$this->session->userdata('email');?>";
  
  var secret_data = {
    secret : secret,
    //email : email
  };
  console.log(secret_data); 
  $.ajax({
    //url: "http://ec2-54-199-4-169.ap-northeast-1.compute.amazonaws.com/editor/auth",    
    url: '/writing/get_list',   
    type: 'post',   
    data: secret_data,
    dataType: 'json',
    success: function(json){      
      //console.log(json['access']);
      result = JSON.parse(json['result']);            
      access = JSON.parse(json['access']);    
      console.log(result);
      
      if(result['status']){
        $("<tr id='trow'>").appendTo('#writing_row');
        //$("tbody#writing_row").append('<tr>');
        $("tr#trow").append('<td class="text-center">1</td>');
        $("tr#trow").append('<td class="text-center">'+result['data']['title']+"</td>");
        

        //$("tbody#writing_row").append("<td>"+result['result'][i]['text']+"</td>");
        $("tr#trow").append('<td class="text-center">'+result['data']['kind']+"</td>");
        
        // 24hr 안에 해결해야 하는경우!
        if(result['data']['is_24hr'] == 1){
          $("tr#trow").append('<td class="text-center" id="timer"></td>');
          //$("tr#trow"+i).append('<td class="text-center">'+result['result'][i]['date_time']+"</td>");
          //$("tbody#writing_row").append('</tr>');
          //console.log(result['data'][i]['date_time']);          
          var db_date = result['data']['date'];
          // 2013-11-10 03:11:03
          var month = db_date.substring(5,7);
          var day = parseInt(db_date.substring(8,10))+1;            
          var year = db_date.substring(0,4);
          var time = db_date.substring(11,19);          
          var mon ="";

          switch(month)
          {
            case '01' : mon = "January"; break;
            case '02' : mon = "February"; break;
            case '03' : mon = "March"; break;
            case '04' : mon = "April"; break;
            case '05' : mon = "May"; break;
            case '06' : mon = "June"; break;
            case '07' : mon = "July"; break;
            case '08' : mon = "August"; break;
            case '09' : mon = "September"; break;
            case '10' : mon = "October"; break;
            case '11' : mon = "November"; break;
            case '12' : mon = "December"; break;                      
          }
          //console.log(mon);

          var countdate =  mon + ' ' + day +','+' '+ year +' '+ time; 
          //console.log(countdate);             
          
          $(function() {
              $('td#timer').countdown({                 
                  date: countdate       
                  //date: "November 7, 2013 16:03:26"
              });
          }); 
          $("tr#trow").append('<td class="text-center"><button class="btn btn-danger" id="adjust">Adjust</button></td>');
        }else{ // 24hr 이 아닌경우!!
          $("tr#trow").append('<td class="text-center">-- : -- : --</td>');
          $("tr#trow").append('<td class="text-center"><button class="btn btn-danger" id="adjust">Adjust</button></td>');
        } 
      }else{// 새로운 내용이 없을때
        alert('No new posts!');
      } 
    }
  })// ajax End 

  $('body').delegate('button#adjust', 'click', function()
  {   
    token = access['data']['token'];
    w_id = result['data']['id'];
    title = result['data']['title'];
    writing = result['data']['writing'];
    critique = result['data']['is_critique'];
    kind = result['data']['kind'];

    var data = {
      token: token,
      w_id: w_id
    };
    console.log(title);
    $.ajax({
        //url: 'http://192.168.0.22:8888/editor/editing/start',
        url:"/writing/write",
        type: 'POST',   
        data: data,
        dataType: 'json',
        success: function(json){
        var access = JSON.parse(json['result']);      
        console.log(access['status']);                  
        if(access['status']){                     
            $('<form action="/text/adjust" method="POST"/>')
              .append($('<input type="hidden" name="token" value="' + token + '">'))
              .append($('<input type="hidden" name="w_id" value="' + w_id + '">'))
              .append($('<input type="hidden" name="title" value="' + title + '">'))
              .append($('<input type="hidden" name="writing" value="' + writing + '">'))                    
              .append($('<input type="hidden" name="critique" value="' + critique + '">'))                    
              .append($('<input type="hidden" name="kind" value="' + kind + '">'))                    
              .appendTo($(document.body)) //it has to be added somewhere into the <body>
              .submit();
        }else{
          alert('status false');
        }
      }
    });
  });
});


if (!window.x) {
        x = {};
    }

    x.Selector = {};
    x.Selector.getSelected = function() {
        var t = '';
        if (window.getSelection) {
            t = window.getSelection();
        } else if (document.getSelection) {
            t = document.getSelection();
        } else if (document.selection) {
            t = document.selection.createRange().text;
        }
        return t;
    }

    var tagging_div = new Array();
    var redo_tagging_div = '';
    var i = 0;
    var count = 0;
    $('button#tag').click(function(){
          //$('#result').text($.selection());
          
          //console.log(div_tagging_box);          
          var tag = $(this).attr('tag');
          var mytext = $.selection();
          
          //console.log(tag);
          var div = $("div#tagging_box");  
          switch(tag)
          {
          case 'TR':color = "tr"; break;
          case 'IN': color = "in"; break;
          case 'TS': color = "ts"; break;
          case 'MO1': color = "mo1"; break;
          case 'MO2': color = "mo2"; break;
          case 'BO1': color = "bo1"; break;
          case 'BO2': color = "bo2"; break;
          case 'SI1': color = "si1"; break;
          case 'SI2': color = "si2"; break;
          case 'EX': color = "ex"; break;
          case 'CO': color = "co"; break;
          case 'MT1': color = "mt1"; break;
          case 'MT2': color = "mt2"; break;
          default: alert('selection Error');
          }   
          div.html(div.html().replace(mytext,'<span class="'+color+'" id = "action'+i+'" tag = "'+tag+'"> &#60'+ tag +'&#62' + mytext + '&#60/' + tag + '&#62 </span>'));           
          var div_tagging_box = $("div#tagging_box").clone().html();
          //console.log(div_tagging_box);          
          tagging_div.push(div_tagging_box);
          i++;
          count++;
          
      });

      //var span_tag = '';
      $("button#undo").click(function(){          
          if(i > 0){
            $("span#action"+(i-1)).replaceWith(function() { 
              var contents = $(this).text(); 
              var tag = $(this).attr('tag');
              //console.log(contents);
              var front = '<'+tag+'>';
              var back = '</'+tag+'>';            
              var result = contents.replace(front,'');
              var result = result.replace(back,'');            
              //console.log(result);
              return result;
            });          
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
          $("div#hrline").html('<div class="divtagging_box" id="tagging_box">'+tt+'<br/></div>');
          $(this).html('<span class="glyphicon glyphicon-refresh"></span> All Redo</button>');
          $(this).attr('click','redo');
        
        }else{
          $("div#tagging_box").html(clear_storage);
          $(this).attr('click','clear');
          $(this).html('<span class="glyphicon glyphicon-refresh"></span> All Clear</button>');
        }       
        
        
      });


// $('button#tag').click(function(){
//   var el = $(this).attr('tag');
//   //console.log(el);      
//   $('#tagging_box')  
//     .selection('insert', {text: '<'+el+'>', mode: 'before'})          
//     .selection('insert', {text: '</'+el+'>', mode: 'after'});         
    
// });     

var classify_tr = true;  
var classify_in = true;  
var classify_ts = true;  
var classify_mo1 = true;  
var classify_mo2 = true;  
var classify_bo1 = true;  
var classify_bo2 = true;  
var classify_si1 = true;  
var classify_si2 = true;  
var classify_ex = true;  
var classify_co = true;  
var classify_mt1 = true;  
var classify_mt2 = true;  

$("button#block").click(function(){
  //alert('a');
  var tag = $(this).attr('tag');  
  
  // if(classify_in){                
  //       $('span.'+tag).css("backgroundColor","black"); 
  //       classify_in = false;
  //     }else{
  //       var tag = $(this).attr('tag');  
  //       //console.log(tag); 
  //       $('span.'+tag).css("backgroundColor",""); 
  //       classify_in = true;
  // }  

  switch(tag)
  {
  case 'tr':
    if(classify_tr){   
      $('span.'+tag).css("backgroundColor","#B7F0B1"); 
      classify_tr = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_tr = true;
    } break;
  case 'in': if(classify_in){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_in = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_in = true;
    } break;
  case 'ts': if(classify_ts){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_ts = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_ts = true;
    } break;
  case 'mo1': if(classify_mo1){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_mo1 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_mo1 = true;
    } break;
  case 'mo2': if(classify_mo2){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_mo2 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_mo2 = true;
    } break;
  case 'bo1': if(classify_bo1){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_bo1 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_bo1 = true;
    } break;
  case 'bo2': if(classify_bo2){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_bo2 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_bo2 = true;
    } break;
  case 'si1': if(classify_si1){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_si1 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_si1 = true;
    } break;
  case 'si2': if(classify_si2){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_si2 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_si2 = true;
    } break;
  case 'ex': if(classify_ex){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_ex = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_ex = true;
    }break;
  case 'co': if(classify_co){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_co = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_co = true;
    }break;
  case 'mt1': if(classify_mt1){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_mt1 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_mt1 = true;
    } break;
  case 'mt2': if(classify_mt2){   
      $('span.'+tag).css("backgroundColor","black"); 
      classify_mt2 = false;
    }else{     
      $('span.'+tag).css("backgroundColor",""); 
      classify_mt2 = true;
    } break;
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

  var data = {            
    essay_id: "<?=$id;?>",            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type
  }
  console.log(data);
  
  $.ajax(
  {
    url: '/text/draft_save', // 포스트 보낼 주소
    type: 'POST',         
    data: data,
    dataType: 'json',
    success: function(json)
    {
      if(json['status'] == 'true')
      {
        // 정상적으로 처리됨
        alert('정상적으로 저장되었습니다!');
        window.location.replace('/essaylist'); // 리다이렉트할 주소
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

  var data = {            
    essay_id: "<?=$id;?>",            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type
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
        alert('정상적으로 제출되었습니다!');
        window.location.replace('/essaylist'); // 리다이렉트할 주소
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

  var data = {            
    essay_id: "<?=$id;?>",            
    editing: editing,
    critique: critique,
    tagging: tagging,
    type: type
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
        alert('정상적으로 제출되었습니다!');
        window.location.replace('/essaylist/done'); // 리다이렉트할 주소
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

/*$('#editing').on("propertychange input textInput", function() {
    var charLimit = 10;
    var editing = $('textarea#editing').val();
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;    
  
    if (remaining < charLimit && remaining > 0) {
       // No characters entered so disable the button
       //$('#submit').prop('disabled', true);           
       conf = 'true';
       console.log(conf);       
       conform_submit();
    } else if (remaining < 0) {        
        //$('#submit').prop('disabled', false);       
        conf = 'false';
        console.log(conf);
        conform_submit();
    } 
});*/

$('#critique').on("propertychange input textInput", function() {
    var charLimit = 10;
    var critique = $('textarea#critique').val();  
    var remaining = charLimit - $(this).val().replace(/\s+/g," ").length;
  
    if (remaining < charLimit && remaining > 0) {       
       $('#w_submit').prop('disabled', true);           
       // conf_cri = 'true';       
       // conform_submit();

    } else if (remaining < 0) {        
        $('#w_submit').prop('disabled', false);       
        // conf_cri = 'false';        
        // conform_submit();
    } 
});

// function conform_submit(){
//  if(conf == 'false' && conf_cri == 'false'){   
//    $('#w_submit').prop('disabled', false);
//  }else{
//    $('#w_submit').prop('disabled', true);            
//  }
//}


$("button#w_submit").click(function(){
  //alert('aa');
  var token = '<?=$token;?>';
  var id = "<?=$id;?>";
  var editing = $('div#editor').html();
  var raw_writing = $('input#raw_writing').val(); 
  var critique = $('textarea#critique').val();    
  var tagging = $('div#tagging_box').html();
  var kind = '<?=$kind;?>';
  var title = '<?=$title?>';

  var submit_data = {
    token: token,
    w_id: id,
    kind: kind,
    title: title,
    raw_writing: raw_writing,
    editing: editing,
    critique: critique,
    tagging: tagging
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
      console.log(json);                  
      if(access['status']){           
        window.location = "/essaylist/done";
      }else if(access == 'localdb'){
        alert('local db insert error');
      }
    }
  });       
});


