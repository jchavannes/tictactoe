<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
<head>
	<meta name="author" content="jason chavannes <jason.chavannes@gmail.com>" />
	<meta name="license" content="<?php echo WEBROOT; ?>license.txt" />
	<title>Tic Tac Toe Test</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">
        var Browser = {
            agent: '<?php echo $_SERVER['HTTP_USER_AGENT']; ?>',
            name: $.browser.chrome?"chrome" : $.browser.safari?"safari" : $.browser.mozilla?"mozilla" : $.browser.msie?"msie" : "unknown",
            version: $.browser.version,
            platform: navigator.platform
        };
    </script>
	<script type='text/javascript' src="ttt.js"></script>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<div class='games'><div class="boards"></div><div class='status'></div></div>
	<div class='container'>
		<div class='fixed'>
			<div class='actions'>
				<h1>Tic-Tac-Toe Outcome Generator</h1>
				<div class='info'>
                    <p>There are 255,168 possible ways a tic-tac-toe game can unfold. To calculate all of them can take some browsers minutes and will leave your browser frozen while it's processing.</p>
                    <p>Instead of calculating all of them, you can select different quantities.  Try on different browsers &amp; devices to see how they compare.</p>
				</div>
				<form name='gen'>
					<input type='submit' value='Generate outcomes' />
					<input type='text' size='7' value='500' /> (max. 255168)
				</form>
				<div class='message'></div>
				<div class='loading'><p><img src='loading.gif' /> Loading (may take some time)...</p></div>
				<div class='key'>
					<span style='background:#aaf; background-color:rgba(0,0,255,.3);'>X wins</span>
					<span style='background:#afa; background-color:rgba(0,255,0,.3);'>O wins</span>
					<span style='background:#faa; background-color:rgba(255,0,0,.3);'>draw</span>
					<a href='php.txt' target='_blank'>php version</a>
				</div>
			</div>
		</div>
	</div>

	<div class='popup'>

	</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23518512-5']);
  _gaq.push(['_setDomainName', 'weliveweb.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>