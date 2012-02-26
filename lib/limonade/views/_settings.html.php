<div id="settings" class="wrap">
  
  <h2>LimonadePHP <span>v<?php echo LIMONADE; ?></span> - APPLICATION SETTINGS</h2>
  
  <hr>
  
  <div id="options">
    <h3 id="options-info">OPTIONS <span>(Limonade)</span></h3>
    <?php $o = option(); ksort($o); ?>
    <?php if( ! empty($o) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
          <?php foreach($o as $key => $val): ?>
        <tr>
          <td><?php echo h($key); ?></td>
          <td class="code"><div><?php echo debug($val); ?></div></td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No Options set.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /Options -->


  <div id="server">
    <h3 id="server-info">SERVER <span>($_SERVER)</span></h3>
    <?php if( ! empty($_SERVER) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
          <?php 
          $s = $_SERVER; ksort($s);
          foreach($s as $key => $val): 
          ?>
        <tr>
          <td><?php echo h($key); ?></td>
          <td class="code"><div><?php echo debug($val); ?></div></td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No SERVER data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /SERVER -->

  <div id="get">
    <h3 id="get-info">GET <span>($_GET)</span></h3>
    <?php if( ! empty($_GET) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
          <?php foreach($_GET as $key => $val): ?>
        <tr>
          <td><?php echo h($key); ?></td>
          <td class="code"><div><?php echo debug($val); ?></div></td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No GET data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /GET -->

  <div id="post">
    <h3 id="post-info">POST <span>($_POST)</span></h3>
    <?php if( ! empty($_POST) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
          <?php foreach($_POST as $key => $val): ?>
        <tr>
          <td><?php echo h($key); ?></td>
          <td class="code"><div><?php echo debug($val); ?></div></td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No POST data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /POST -->

  <div id="sessions">
    <h3 id="session-info">SESSIONS <span>($_SESSION)</span></h3>
    <?php if( ! empty($_SESSION) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
          <?php foreach($_SESSION as $key => $val): ?>
        <tr>
          <td><?php echo h($key); ?></td>
          <td class="code"><div><?php echo debug($val); ?></div></td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No SESSION data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /SESSION -->


  <div id="cookies">
    <h3 id="cookie-info">COOKIES <span>($_COOKIE)</span></h3>
    <?php if( ! empty($_COOKIE) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($_COOKIE as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No cookie data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /COOKIES -->

  <div id="env">
    <h3 id="env-info">ENV <span>($_ENV)</span></h3>
    <?php if( ! empty($_ENV) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($_ENV as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No ENV data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /ENV -->

  <div id="files">
    <h3 id="files-info">FILES <span>($_FILES)</span></h3>
    <?php if( ! empty($_FILES) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($_FILES as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No FILES data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /FILES -->


  <div id="request">
    <h3 id="files-info">REQUEST <span>($_REQUEST)</span></h3>
    <?php if( ! empty($_REQUEST) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($_REQUEST as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No REQUEST data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /REQUEST -->

  <div id="put">
    <h3 id="put-info">PUT <span>(env()['PUT'])</span></h3>
    <?php 
      $p = env();
      if( ! empty($p['PUT']) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($p['PUT'] as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No PUT data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /PUT -->

  <div id="delete">
    <h3 id="delete-info">DELETE <span>(env()['DELETE'])</span></h3>
    <?php 
      $p = env();
      if( ! empty($p['DELETE']) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($p['DELETE'] as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No DELETE data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /DELETE -->

  <div id="head">
    <h3 id="head-info">HEAD <span>(env()['HEAD'])</span></h3>
    <?php 
      $p = env();
      if( ! empty($p['HEAD']) ): ?>
      <table class="req">
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
        <?php foreach($p['HEAD'] as $key => $val): ?>
          <tr>
            <td><?php echo h($key); ?></td>
            <td class="code"><div><?php echo debug($val); ?></div></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p class="no-data">No HEAD data.</p>
    <?php endif; ?>
    <div class="clear"></div>
  </div> <!-- /HEAD -->
  
  
  <p id="explanation">You're seeing this output because you have enabled <code>option('show_settings')</code>.</p>
  
</div> <!-- /#settings.wrap -->
<style type="text/css" media="screen">
  /* WRAP */
  #settings.wrap *{margin:0;padding:0;border:0;outline:0;}
  #settings.wrap { width:960px; background:#FFF;margin:2em auto;padding:30px 20px 20px 20px; border:1px solid #ccc; font-family:'Lucida Grande','Lucida Sans Unicode','Garuda'; }
  #settings.wrap div.clear{clear:both;}
  #settings.wrap code{font-family:'Lucida Console',monospace;font-size:12px;}
  #settings.wrap li{height:18px;}
  #settings.wrap ul{list-style:none;margin:0;padding:0;}
  #settings.wrap ol:hover{cursor:pointer;}
  #settings.wrap ol li{white-space:pre;}
  #settings.wrap #explanation{font-size:12px;color:#666;margin:20px 0 0 0; text-align:center;}
  #settings.wrap h2{margin:0;font-size:16px;color:#981919;text-align:center;padding-bottom:10px;border-bottom:1px solid #ccc;}
  #settings.wrap h2 span {color:#ccc; }
  /* BODY */
  #backtrace,#get,#post,#cookies, #server, #sessions, #files, #env, #delete, #head, #put, #options{width:980px;margin: 15px auto;}
  #settings.wrap p#nav{float:right;font-size:14px;}
  /* BACKTRACE */
  #settings.wrap a#expando{float:left;padding-left:5px;color:#666;font-size:14px;text-decoration:none;cursor:pointer;}
  #settings.wrap a#expando:hover{text-decoration:underline;}
  #settings.wrap h3{ float:left;width:200px;margin: 30px 0 10px 0;color:#981919;font-size:14px;font-weight:bold;}
  #settings.wrap h3 span { font-size: 10px; color: #ccc; } 
  #settings.wrap #nav a{color:#666;text-decoration:none;padding:0 5px;}
  #settings.wrap #backtrace li.frame-info{background:#f7f7f7;padding-left:10px;font-size:12px;color:#333;}
  #settings.wrap #backtrace ul{list-style-position:outside;border:1px solid #E9E9E9;border-bottom:0;}
  #settings.wrap #backtrace ol{width:920px;margin-left:50px;font:10px 'Lucida Console',monospace;color:#666;}
  #settings.wrap #backtrace ol li{border:0;border-left:1px solid #E9E9E9;padding:2px 0;}
  #settings.wrap #backtrace ol code{font-size:10px;color:#555;padding-left:5px;}
  #settings.wrap #backtrace-ul li{border-bottom:1px solid #E9E9E9;height:auto;padding:3px 0;}
  #settings.wrap #backtrace-ul .code{padding:6px 0 4px 0;}
  #settings.wrap #backtrace-output.hidden { display:none; }
  /* REQUEST DATA */
  #settings.wrap p.no-data{margin:30px 0 10px 0; float: left; padding-top:2px; font-size:12px;color:#666;}
  #settings.wrap table.req{width:980px;text-align:left;font-size:12px;color:#666;padding:0;border-spacing:0;border:1px solid #EEE;border-bottom:0;border-left:0;clear:both}
  #settings.wrap table.req tr th{padding:2px 10px;font-weight:bold;background:#F7F7F7;border-bottom:1px solid #EEE;border-left:1px solid #EEE;}
  #settings.wrap table.req tr td{padding:2px 20px 2px 10px;border-bottom:1px solid #EEE;border-left:1px solid #EEE;}
  /* HIDE PRE/POST CODE AT START */
  #settings.wrap .pre-context,
  #settings.wrap .post-context{display:none;}
  #settings.wrap table td.code{width:750px}
  #settings.wrap table td.code div{width:750px;overflow:hidden}
  #settings.wrap table td.code div span.null-value { color: #ccc;}
  #settings.wrap table td.code div pre { margin: 0; font-size: 100%; }
</style>