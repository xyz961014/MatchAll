<!doctype html>
<?php
require "dbinfo.php";
$dbname = $_GET['Match'];
$conn = dbconnect($dbname);
?>
<html lang="ch">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">


    <title>TUFA</title>
  </head>
  <body>
    <?php echo "<a href='index.php?tab=".$dbname."'>返回</a>"; ?>
    <?php require "session.php";?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <div class="container">
        <p class="list"></p>
<?php
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
//print_r($arr);
?>
<script>
//console.log('load');
var d = new Date();
var dbname = '<?=$dbname?>';
$.getJSON(dbname + ".json",{
    time:d.getTime()
}, function(data,state) {
    var eliinfo = data[1];
})
$.get('showlist.php',{
    dbname: dbname,
    time: d.getTime()
}, function(data,state) {
    var matchlist = JSON.parse(data);
    console.log(matchlist);
    if (matchlist.length >= 0) {
        var tableml = "<div class='col-lg-12'><table class='table table-bordered table-hover table-condensed'><caption>赛程";
<?php 
        if ($right > 1) {
?>
        tableml += "<a class='btn btn-default btn-sm pull-right' href='newgame.php?dbname=" + dbname + "'>增加新比赛</a>";
<?php
        }
?>
        tableml += "</caption><thead><tr><th>场序</th><th>主队</th><th>比分</th><th>客队</th><th>时间</th><th>场地</th><th>进入</th>";
<?php 
        if ($right > 1) {
?>
        tableml += "<th>编辑</th>";
<?php
        }
?>
        tableml += "</tr></thead><tbody>";
        for (var i = 0;i < matchlist.length;i++) {
            if (matchlist[i]['Valid'] == "1") {
                var tablerow = "<tr class='" + matchlist[i]['MatchID'] + "'><td class='matchid'>" + matchlist[i]['MatchID'] + "</td><td class='hometeam'>" + matchlist[i]['HomeTeam'] + "</td><td>" + matchlist[i]['HomeGoal'] + ":" + matchlist[i]['AwayGoal'] + "</td><td class='awayteam'>" + matchlist[i]['AwayTeam'] + "</td><td class='matchtime'>" + matchlist[i]['MatchTime'] + "</td><td class='matchfield'>" + matchlist[i]['MatchField'] + "</td><td><a href='match.php?Match=" + dbname + "&id=" + matchlist[i]['MatchID'] + "'><span class='glyphicon glyphicon-new-window'></span></a></td>";
<?php 
                if ($right > 1) {
?>
                tablerow += "<td class='edit'><button type='button' class='gameeditdanger btn btn-default btn-sm disabled' id='E~" + matchlist[i]['MatchID'] + "'><span class='glyphicon glyphicon-edit'></span></button></td></tr>";
<?php
                }   
?>
            } else if (matchlist[i]['Valid'] == "0") {
                var tablerow = "<tr class='" + matchlist[i]['MatchID'] + "'><td class='matchid'>" + matchlist[i]['MatchID'] + "</td><td class='hometeam'>" + matchlist[i]['HomeTeam'] + "</td><td>VS</td><td class='awayteam'>" + matchlist[i]['AwayTeam'] + "</td><td class='matchtime'>" + matchlist[i]['MatchTime'] + "</td><td class='matchfield'>" + matchlist[i]['MatchField'] + "</td><td><a href='match.php?Match=" + dbname + "&id=" + matchlist[i]['MatchID'] + "'><span class='glyphicon glyphicon-new-window'></span></a></td>";
<?php 
                if ($right > 1) {
?>
                tablerow += "<td class='edit'><button type='button' class='gameedit btn btn-default btn-sm' id='E~" + matchlist[i]['MatchID'] + "'><span class='glyphicon glyphicon-edit'></span></button></td></tr>";
<?php
                }
?>
            }
            tableml += tablerow;
        }
        tableml += "</tbody></table></div>";
        $(".list").append(tableml);
    }
<?php 
    if ($right > 1) {
?>
    $(".gameeditdanger").click(function() {
        alert("不能编辑已经生效的比赛！");
    })
    $(".gameedit").click(function() {
            var btnedit = $(this);
            var id = $(this).attr('id');
            var pid = id.split('~');
            var edit = $("." + pid[1] + " .edit");
            edit.append("<button type='button' class='gameok" + pid[1] + " btn btn-default btn-sm' id='O~" + pid[1] + "'><span class='glyphicon glyphicon-ok'></span></button>");
            function editfield(matchid, fieldname, type) {
                var field = $("." + matchid + " ." + fieldname);
                var fieldtxt = field.text();
                if (type == "datetime-local") {
                    fieldtxt = fieldtxt.replace(" ", "T");
                }
                field.empty();
                field.append("<input type='" + type + "' class='" + fieldname + matchid + " form-control input-sm' value='" + fieldtxt + "'>");
            }
            //editfield(pid[1], "matchid", "number");
            editfield(pid[1], "matchtime", "datetime-local");
            editfield(pid[1], "matchfield", "text");
            editfield(pid[1], "hometeam", "text");
            editfield(pid[1], "awayteam", "text");
            
            $(this).hide();
    
            $(".gameok" + pid[1]).click(function() {
                var id = $(this).attr("id");
                var pid = id.split("~");
                console.log(pid);
                var ok = $("." + pid[1] + " .edit");
                btnedit.show();
                $(this).remove();
                function fieldok(matchid, fieldname, type=null) {
                    var fieldinput = $("." + fieldname + matchid);
                    var field = $("." + matchid + " ." + fieldname);
                    var fieldtxt = fieldinput.val();
                    if (type == "datetime-local") {
                        fieldtxt = fieldtxt.replace("T", " ");
                        var num = fieldtxt.match(/:/g).length;
                        if (num == 1)
                            fieldtxt += ":00";
                    }
                    fieldinput.remove();
                    field.append(fieldtxt);
                    return fieldtxt;
                }
                //var matchid = fieldok(pid[1], "matchid");
                var matchtime = fieldok(pid[1], "matchtime", "datetime-local");
                var matchfield = fieldok(pid[1], "matchfield");
                var hometeam = fieldok(pid[1], "hometeam");
                var awayteam = fieldok(pid[1], "awayteam");
                console.log(hometeam, awayteam, matchtime, matchfield);
                $.post("editgame.php", {
                    dbname: dbname,
                    matchid: parseInt(pid[1]),
                    hometeam: hometeam,
                    awayteam: awayteam,
                    matchtime: matchtime,
                    matchfield: matchfield
                }, function(data, state) {
                    console.log(data, state);
                })
            })
        })
<?php
    }
?>
    })
function onvalid(id) {
    var validcheck = document.getElementById(id);
    if (validcheck.checked) {
        console.log('true');
        var validbool = 1;
    } else {
        console.log('false');
        var validbool = 0;
    }
    $.get('checked.php',{
        dbname: '<?=$dbname ?>',
        MatchID: id,
        Valid: validbool
    },function(data,state) {
        console.log(data);
    })
}


</script>
<?php
$conn->close();
?>
</div>
  </body>
</html>

