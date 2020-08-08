<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
 <head profile="http://gmpg.org/xfn/11">
  <meta http-equiv="content-type" content="text-html; charset=Windows-1252" />
  <title><?php echo $ERROR; ?></title>
  <style type="text/css">
   <!--/*--><![CDATA[/*><!--*/
    body {
     background-color: #fff;
     font: 8pt/11pt Verdana;
     margin: 23px 18px;
     width: 380px;
     color: black;
    }
    a:link { color: red; }
    a:visited { color: #4e4e4e; }
    a#refresh { background: url(/static/err/refresh.gif) no-repeat 0 0; }
    a#search  { background: url(/static/err/search.gif)  no-repeat 0 0; }
    a#back    { background: url(/static/err/back.gif)    no-repeat 0 0; }
    a#refresh, a#search, a#back {
     padding: 0 0 3px 17px;
     border: 0 none;
     height: 18px;
    }
    h1 {
     background: url(/static/err/pagerror.gif) no-repeat 0 0;
     font: 13pt/15pt Verdana;
     padding-left: 36px;
     height: 33px;
    }
    h2 { font: 8pt/11pt Verdana; }
    li { margin: 2px 0; }
    hr { color: c0c0c0; }
   /*]]>*/-->
  </style>
 </head>
 <body>
  <h1><?php echo $ERROR ?></h1>
  <p><?php echo $DESC ?></p>
  <hr />
  <p>Please try the following:</p>
  <ul>
   <li>Click the <a href="javascript:location.reload()" id="refresh" target="_self">Refresh</a> button, or try again later.</li>
<?php
    foreach ($CHOICES as $i) {
        echo "   <li>" . $i . "</li>\n";
    }
?>
   <li>Click the <a href="javascript:history.back(1)" id="back" title="Go back to the previous page">Back</a> button to try another link.</li>
  </ul>
 </body>
</html>
<?php die(); ?>