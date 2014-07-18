var Wins = [[0,3,6], [1,4,7], [2,5,8], [0,1,2], [3,4,5], [6,7,8], [0,4,8], [2,4,6]];
function tttGame() {
    this.moves = [0,0,0,0,0,0,0,0,0]; // X = odd moves, O = even moves
    this.winner = 0; // 0 = in progress, 1 = X wins, 2 = O wins, 3 = draw
    this.moveCount = 0; // Min moves = 5, Max moves = 9
    this.winId = false;
    this.checkSideWon = function(side) {
        for (var win in Wins) {
            var won = true;
            for (var move in Wins[win]) {
                if (2-this.moves[Wins[win][move]]%2 != side || this.moves[Wins[win][move]] === 0) {
                    won = false;
                }
            }
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
        mainTemp = genMoves(mainTemp, mainTemp.moveCount + 1);
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
        for (var i = Games.showing; i < Games.showing+num && typeof Games.Data[i] != 'undefined'; i++) {
            printBoard(Games.Data[i]);
        }
        Games.showing += num;
        if (Games.showing < Games.Data.length) {
            var $window   = $(window);
            var $document = $(document);
            $window.scroll(function() {
                if ($window.scrollTop() + $window.height() == $document.height()) {
                    $window.unbind('scroll');
                    printGames(500);
                }
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