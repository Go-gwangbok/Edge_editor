<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/setting/info">Setting</a></li>   
        <li class="akacolor">Templet setting</li>   
      </ol>                        
  </div>  <!-- Nav or Head title End -->  
  <h3 class="text-center" id="title"><?=strtoupper($kind);?> Templet setting</h3>
  <br>    

  <div class="div-box-line-promp">
    <dl>
        <dt style="margin:0 10px 0 10px">Prompt</dt>              
        <dd style="margin:0 15px 0 25px" id="prompt"></dd>
    </dl>       
  </div>    
  <br>

  <ul class="nav nav-tabs" id="myTab">    
    <li class="active"><a href="#orig" data-toggle="tab">Original</a></li>
    <li><a href="#error" data-toggle="tab">Error detecting</a></li>
    <li><a href="#tagging" data-toggle="tab">Tagging</a></li>    
    <li><a href="#scoring" data-toggle="tab">Scoring</a></li>    
    <div id="stopwatch" class="btn btn-default pull-right" disabled>Timer : 00:00</div>    
	    <button class="btn btn-danger pull-right" id="errorlist" style="margin-right:10px;" disabled>Return</button>
	    <button class="btn btn-danger pull-right" id="errorlist" style="margin-right:10px;">Error</button>
	    <button class="btn btn-warning pull-right" id="discuss" style="margin-right:10px;">T.B.D</button>    
  </ul>
  <br>
<div class="tab-content">
  <!-- Error Original -->
   <div class="tab-pane div-box-line active" id="orig">
      <div class="col-md-12" style="margin-top:15px;">                
        
        <div style="height:200px;">          
          
        </div> 
        <br>      
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->

    <!-- Error detecting -->
    <div class="tab-pane div-box-line" id="error">
      <div class="col-md-12" style="margin-top:15px;">   
        
        <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor" style="margin-bottom:20px;">
          <div class="btn-group">
            <a class="btn" data-edit="strikethrough" title="Strikethrough"><span class="glyphicon glyphicon-trash"></span> DEL</a>        
            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><span class="glyphicon glyphicon-refresh"></span> MOD</a>
            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><span class="glyphicon glyphicon-pencil"></span> INS</a>            
          </div>
        </div> <!-- btn-toolbar -->
              
        <!-- Error detecting -->
        <div id="editor" style="height:200px;">          
          
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
                    
                      <textarea class="border text_box" id="critique" style="width:100%;" rows="7"></textarea>           
                    
                  </div>
                </div>            
              </div>           
            </div>
            <br>
            <!-- accordion end -->
      </div>  <!-- col-md-12 -->
    </div> <!-- tab-pane -->

  <!-- Tagging start -->
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
   
          </div>  <!-- col-md-12 -->
        </div> <!-- col-md-12 -->              
        <hr class="text-box-hr">              
        <div class="col-md-12" id="hrline">                    
          
          <div class="divtagging_box" id="tagging_box" style="height:200px;"></div>          
          
        </div>        
      </div>   
      <div class="tab-pane div-box-line " id="scoring">
        <br>    
            <div class="col-md-12 ">           
              <div class="col-md-12 ">                         
                <div class="col-md-12 ">                 

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
                               
                </div>
              </div>
            </div>
               
      </div>   

      <div style="margin-top:8px;">
        
        <button class="btn btn-danger pull-right" id="submit">Submit</button>
        <button class="btn btn-primary" id="draft">Save Draft</button>        
      </div>     
  </div>  
</div>
<script type="text/javascript">
</script>