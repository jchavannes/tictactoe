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

var Wins = [[0,3,6], [1,4,7], [2,5,8], [0,1,2], [3,4,5], [6,7,8], [0,4,8], [2,4,6]];
function TicTacToeGame(oldGame) {
    /**
     * @type moves {Array}
     * Example: [2,6,4,0,1,0,3,5,0]
     * Would result in:
     * ===
     *   O O O
     *   - X -
     *   X X -
     * ===
     * w/ middle X being the first move, top left O being the second, etc.
     */
    this.moves = [0,0,0,0,0,0,0,0,0];
    this.moveCount = 0; // Min moves = 5, Max moves = 9
    this.winner = 0; // 0 = in progress, 1 = X wins, 2 = O wins, 3 = draw
    this.winId = false;
    if (typeof oldGame != "undefined") {
        this.moves = oldGame.moves.slice(0);
        this.moveCount = oldGame.moveCount;
    }
}
TicTacToeGame.prototype.checkSideWon = function(side) {
    var winId, won, move, moveId;
    for (winId = 0; winId < 8; winId++) {
        won = true;
        for (move = 0; move < 3; move++) {
            moveId = Wins[winId][move];
            if (2 - this.moves[moveId] % 2 != side || this.moves[moveId] === 0) {
                won = false;
            }
        }
        if (won) {
            this.winId = winId;
            return Wins[winId];
        }
    }
    return false;
};
TicTacToeGame.prototype.checkWinner = function() {
    this.winner = 0;
    if (this.checkSideWon(1) !== false) this.winner = 1;
    else if (this.checkSideWon(2) !== false) this.winner = 2;
    else if (this.moveCount == 9) this.winner = 3;
    return this.winner;
};

function genGames(max) {
    $('.actions .message').hide().html("");
    $('.actions .loading').show();
    setTimeout(function() {
        Games.Data = [];
        var date = new Date().getTime();
        var incompleteGames = genMoves(new TicTacToeGame(), 1);
        var moveNum, g, i, moveGamesTemp, tempGames;
        for (moveNum = 2; moveNum <= 9; moveNum++) {
            moveGamesTemp = [];
            for (g = 0; g < incompleteGames.length; g++) {
                tempGames = genMoves(incompleteGames[g], moveNum);
                for (i = 0; i < tempGames.length; i++) {
                    if (tempGames[i].checkWinner() != 0) {
                        Games.Data.push(tempGames[i]);
                        tempGames.splice(i,1);
                        i--;
                    }
                    if (Games.Data.length >= max) break;
                }
                moveGamesTemp = moveGamesTemp.concat(tempGames);
                if (Games.Data.length >= max) break;
            }
            Games.totalChecked = moveGamesTemp.length;
            if (Games.Data.length >= max) break;
            incompleteGames = moveGamesTemp;
        }
        date = new Date().getTime() - date;
        $('.games .boards').html("");
        Games.showing = 0;
        $('.actions .loading').hide();
        $('.message').show().html("<p>Took "+(date/1000)+" seconds to generate "+Games.Data.length+" completed games.</p>");
        printGames();
    }, 100);
}
function genMoves(game, moveNum) {
    var games = [], i;
    for (i = 0; i < 9; i++) {
        if (game.moves[i] === 0) {
            var temp = new TicTacToeGame(game);
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
    }, 100)
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

var Games = (new function() {
    this.total = 0;
    this.showing = 0;
    this.Data = [];
});
var Browser = {
    agent: Navigator.userAgent,
    name: $.browser.chrome?"chrome" : $.browser.safari?"safari" : $.browser.mozilla?"mozilla" : $.browser.msie?"msie" : "unknown",
    version: $.browser.version,
    platform: navigator.platform
};