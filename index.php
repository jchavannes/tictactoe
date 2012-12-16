<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
<head>
	<meta name="author" content="jason chavannes <jason.chavannes@gmail.com>" />
	<meta name="license" content="<?php echo WEBROOT; ?>license.txt" />
	<title>Tic Tac Toe Test</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type='text/javascript'>
		var Wins = [[0,3,6], [1,4,7], [2,5,8], [0,1,2], [3,4,5], [6,7,8], [0,4,8], [2,4,6]];
		function tttGame() {
			this.moves = [0,0,0,0,0,0,0,0,0]; // X = odd moves, O = even moves
			this.winner = 0; // 0 = in progress, 1 = X wins, 2 = O wins, 3 = draw
			this.moveCount = 0; // Min moves = 5, Max moves = 9
			this.winId = false;
			this.checkSideWon = function(side) {
				for (var win in Wins) {
					var won = true;
					for (var move in Wins[win]) if (2-this.moves[Wins[win][move]]%2 != side || this.moves[Wins[win][move]] === 0) won = false;
					if (won) {
						this.winId = win;
						return Wins[win];
					}
				}
				return false;
			}
			this.checkWinner = function() {
				this.winner = 0;
				if (this.checkSideWon(1) !== false) this.winner = 1;
				else if (this.checkSideWon(2) !== false) this.winner = 2;
				else if (this.moveCount === 9) this.winner = 3;
				return this.winner;
			}
		}
		function genGames(max) {
			$('.actions .message').hide().html("");
			$('.actions .loading').show();
			setTimeout(function() {
				Games.Data = [];
				var date = new Date().getTime();
				var mainTemp = new tttGame();
				var mainTemp = genMoves(mainTemp, mainTemp.moveCount + 1);
				for (var moveNum = mainTemp[0].moveCount+1; moveNum <= 9; moveNum++) {
					var moreTemps = [];
					for (var g = 0; typeof mainTemp[g] != 'undefined'; g++) {
						var tempGames = genMoves(mainTemp[g], moveNum);
						for (var i = 0; typeof tempGames[i] != 'undefined'; i++) {
							if (tempGames[i].checkWinner() !== 0) {
								Games.Data.push(tempGames[i]);
								tempGames.splice(i,1);
								i--;
							}
							if (Games.Data.length >= max) break;
						}
						moreTemps = moreTemps.concat(tempGames);
						if (Games.Data.length >= max) break;
					}
					Games.totalChecked = moreTemps.length;
					if (Games.Data.length >= max) break;
					mainTemp = moreTemps;
				}
				date = new Date().getTime()-date;
				date = date;
				$('.games .boards').html("");
				Games.showing = 0;
				$('.actions .loading').hide();
				$('.message').show().html("<p>Took "+(date/1000)+" seconds to generate "+Games.Data.length+" completed games.</p>");
				printGames();
			}, 100);
		}
		function genMoves(game, moveNum) {
			var games = [];
			for (var i = 0; i < 9; i++) {
				if (game.moves[i] === 0) {
					var temp = cloneObj(game);
					temp.moves[i] = moveNum;
					temp.moveCount++;
					games.push(temp);
				}
			}
			return games;
		}
		function printGames(num) {
			if (isNaN(num)) num = 500;
			if (num > Games.Data.length) num = Games.Data.length;
			$('.status').html("<p>Loading...</p>");
			setTimeout(function() {
				for (var i = Games.showing; i < Games.showing+num  && typeof Games.Data[i] != 'undefined'; i++) printBoard(Games.Data[i]);
				Games.showing += num;
				if (Games.showing < Games.Data.length) {
					$('.status').html("<p><input type='button' value='Show 500 more (Showing "+Games.showing+" of "+Games.Data.length+")' /></p>");
					$('.status input[type=button]').click(function() {
						printGames(500);
					});
				} else {
					$('.status').html("Showing all "+Games.showing+" generated games.");
				}
			},10)
		}
		function printBoard(game) {
			var color = [['#fff','255,255,255'], ['#aaf','0,0,255'], ['#afa','0,255,0'], ['#ffa','255,0,0']];
			color = 'background:'+color[game.winner][0]+'; background-color:rgba('+color[game.winner][1]+',0.25)';
			var html = '<div class="board" style="'+color+';">';
			for(var i = 0; i < 9; i++) {
				var classText = (Wins[game.winId][0] === i || Wins[game.winId][1] === i || Wins[game.winId][2] === i) ? " style='font-weight:700; color:#000;'":"";
				var display = (game.moves[i]%2 !==0)?'X':'O';
				if (game.moves[i] == 0) display = '&nbsp;';
				else display += "<i>"+game.moves[i]+"</i>";
				html += '<b'+classText+'>'+display+'</b>';
			}
			html += '</div>';
			$('.games .boards').append(html);
		}
		function hideLogs() {
			$('.popup').show().hide();
			$('.games').show();
		}
		function cloneObj(obj) {
			if (typeof obj != 'object') return obj;
			var copy = {};
			for (var attr in obj) copy[attr] = cloneObj(obj[attr]);
			return copy;
		}

		var Games = (new function() {
			this.total = 0;
			this.showing = 0;
			this.Data = [];
		});
		var Browser = {
			agent: '<?php echo $_SERVER['HTTP_USER_AGENT']; ?>',
			name: $.browser.chrome?"chrome" : $.browser.safari?"safari" : $.browser.mozilla?"mozilla" : $.browser.msie?"msie" : "unknown",
			version: $.browser.version,
			platform: navigator.platform
		}
		$(document).ready(function() {
			$('.popup').hide();			
			$('form[name=gen]').submit(function(e) {
				e.preventDefault();
				var num = $('form[name=gen] input[type=text]').val();
				if (isNaN(num)) num = 500;
				genGames(num);
				return false;
			});
			genGames(500);
		});
	</script>
	<style type='text/css'>
		html {
			overflow-y: scroll;
		}
		html, body {
			margin: 0px;
		}
		a {
			color: #00E;
		}
		.popup {
			position: absolute;
			top: 0px;
			left: 0px;
			width: 100%;
			min-height: 100%;
			background: #fff;
			background: rgba(255,255,255,0.92);
			text-align: center;
		}
		.popup table {
			background: #fff;
		}
		.container {
			position: relative;
			width: 800px;
			margin: 0px auto;
		}
		.fixed {
			position: fixed;
			top: 0px;
			left: 0px;
			width: 100%;
		}
		.actions {
			padding: 20px;
			width: 750px;
			height: 235px;
			margin: 10px auto;
			background: #fff;
			background: rgba(255,255,255,0.92);
			border: 5px solid #ddd;
			border-color: rgba(0,0,0,0.15);
		}
		h1 {
			margin-top: 0px;
		}
		input[type=button], input[type=submit] {
			cursor: pointer;
		}
		.games {
			position: absolute;
			top: 295px;
			left: 0px;
			width: 100%;
			min-width: 800px;
			padding: 10px;
			text-align: center;
		}
		.board, .board b, .key span, .popup table {
			display: inline-block;
			*display:inline;zoom:1;
		}
		.board {
			width: 60px;
			height: 45px;
			overflow: hidden;
			margin: 2px;
			font-family:sans-serif;
		}
		.board b {
			width: 20px;
			height: 15px;
			border: 0px solid #000;
			border-width: 0px 1px 1px 0px;
			margin: 0px -1px -1px 0px;
			text-align: center;
			font-size: 11px;
			font-weight: 500;
			color: #333;
		}
		.board i {
			font-size: 8px;
		}
		.status {
			text-align: center;
		}
		.key span {
			padding: 0px 5px;
		}
	</style>
</head>
<body>
	<div class='games'><div class="boards"></div><div class='status'></div></div>
	<div class='container'>
		<div class='fixed'>
			<div class='actions'>
				<h1>Tic-Tac-Toe Outcome Generator</h1>
				<div class='info'>
					<p>There are 255,168 possible ways a tic-tac-toe game can unfold. It can take JavaScript minutes to calculate all of the outcomes and will leave your browser frozen while it's processing.  Instead of calulating all of them, you can select different quantities and see how your browser compares to others.</p>
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