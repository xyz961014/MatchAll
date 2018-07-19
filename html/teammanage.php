<!doctype html>
<?php
$dbname = $_GET['Match'];
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

        <p class='list'></p>
<?php
if ($right > 1) {
?>
<script>
var d = new Date();
var dbname = "<?=$dbname ?>";
$.get("showteam.php", {
    time: d.getTime(),
    dbname: dbname
}, function(data, state) {
    var teamlist = JSON.parse(data);
    console.log(teamlist);
    if (teamlist.length >= 0) {
        var tableml = "<div>  <table class='table table-bordered table-hover table-condensed'> <caption>球队列表<a class='btn btn-default btn-sm pull-right' href='newteam.php?dbname=" + dbname + "'>增加新球队</a></caption><thead><tr><th>球队名称</th><th>球衣颜色</th><th>球队级别</th><th>所属小组</th><th>胜</th><th>平</th><th>负</th><th>进球</th><th>失球</th><th>积分</th><th>点球</th><th>黄牌</th><th>红牌</th><th>点球罚失</th><th>乌龙球</th><th>编辑</th></tr></thead><tbody>";
        for (var i = 0;i < teamlist.length;i++) {
            //var teamtag = teamlist[i]['TeamName'].replace(/^\s+|\s+$/, '');
            //teamtag = teamtag.replace(/[^\u4e00-\u9fa5\w]/g, '');
            var tablerow = "<tr class='"+ teamlist[i]['TeamID'] + "'><td class='teamname'><a href='playermanage.php?Match="+dbname+"&team="+teamlist[i]['TeamName']+"'>" + teamlist[i]['TeamName'] + "</a></td><td class='kitcolor'>" + teamlist[i]['KitColor'] + "</td><td class='level'>" + teamlist[i]['Level'] + "</td><td class='groupname'>" + teamlist[i]['GroupName'] + "</td><td class='win'>" + teamlist[i]['Win'] + "</td><td class='draw'>" + teamlist[i]['Draw'] + "</td><td class='lose'>" + teamlist[i]['Lose'] + "</td><td class='goal'>" + teamlist[i]['Goal'] + "</td><td class='concede'>" + teamlist[i]['Concede'] + "</td><td class='point'>" + teamlist[i]['Point'] + "</td><td class='penalty'>" + teamlist[i]['Penalty'] + "</td><td class='yellowcard'>" + teamlist[i]['YellowCard'] + "</td><td class='redcard'>" + teamlist[i]['RedCard'] + "</td><td class='penaltymiss'>" + teamlist[i]['Penaltymiss'] + "</td><td class='owngoal'>" + teamlist[i]['OwnGoal'] + "</td>";
            tablerow += "<td class='edit'><button type='button' class='teamedit btn btn-default btn-sm' id='E~" + teamlist[i]['TeamID'] + "'><span class='glyphicon glyphicon-edit'></span></button></td></tr>";
            tableml += tablerow;
        }
        tableml += "</tbody></table></div>";
        $(".list").append(tableml);
    }
    $(".teamedit").click(function() {
        var btnedit = $(this);
        var id = $(this).attr('id');
        var pid = id.split('~');
        var edit = $("." + pid[1] + " .edit");
        edit.append("<button type='button' class='teamok" + pid[1] + " btn btn-default btn-sm' id='O~" + pid[1] + "'><span class='glyphicon glyphicon-ok'></span></button>");
        function editfield(teamid, fieldname) {
            var field = $("." + teamid + " ." + fieldname);
            var fieldtxt = field.text();
            field.empty();
            field.append("<input type='text' class='" + fieldname + teamid + " form-control input-sm' value='" + fieldtxt + "'>");
        }
        editfield(pid[1], "kitcolor");
        editfield(pid[1], "level");
        editfield(pid[1], "groupname");
        $(this).hide();

        $(".teamok" + pid[1]).click(function() {
            var id = $(this).attr("id");
            var pid = id.split("~");
            console.log(pid);
            var ok = $("." + pid[1] + " .edit");
            btnedit.show();
            $(this).remove();
            function fieldok(teamid, fieldname) {
                var fieldinput = $("." + fieldname + teamid);
                var field = $("." + teamid + " ." + fieldname);
                var fieldtxt = fieldinput.val();
                fieldinput.remove();
                field.append(fieldtxt);
                return fieldtxt;
            }
            var kitcolor = fieldok(pid[1], "kitcolor");
            var level = fieldok(pid[1], "level");
            var groupname = fieldok(pid[1], "groupname");
            var teamname = $("." + pid[1] + " .teamname").text();
            console.log(teamname, level, kitcolor, groupname);
            $.post("editteam.php", {
                dbname: dbname,
                teamid: parseInt(pid[1]),
                kitcolor: kitcolor,
                level: level,
                groupname: groupname,
            }, function(data, state) {
                console.log(data, state);
            })
        })
    })
})
</script>
<?php
} else {
    echo "您没有权限查看这些内容！";
}
$conn->close();
?>
</div>
  </body>
</html>

