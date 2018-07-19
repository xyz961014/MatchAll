<!doctype html>
<?php
$dbname = $_GET['Match'];
$team = $_GET['team'];
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
    <?php echo "<a href='./teammanage.php?Match=".$dbname."'>返回</a>"; ?>
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
var team = "<?=$team ?>";
$.get("showplayer.php", {
    time: d.getTime(),
    dbname: dbname,
    team: team
}, function(data, state) {
    var playerlist= JSON.parse(data);
    console.log(playerlist);
    if (playerlist.length >= 0) {
        var tableml = "<div>  <table class='table table-bordered table-hover table-condensed'> <caption>" + team + " 球员列表<a class='btn btn-default btn-sm pull-right' href='newplayer.php?dbname=" + dbname + "&team=" + team + "'>增加新球员</a></caption><thead><tr><th>姓名</th><th>班级</th><th>证件号码</th><th>电话号码</th><th>球衣号码</th><th>备注</th><th>出场次数</th><th>出场时间</th><th>进球</th><th>黄牌</th><th>红牌</th><th>是否报名</th><th>是否停赛</th><th>点球</th><th>点球罚失</th><th>乌龙球</th><th>编辑</th></tr></thead><tbody>";
        for (var i = 0;i < playerlist.length;i++) {
            //var nametag = playerlist[i]['Name'].replace(/^\s+|\s+$/, '');
            //nametag = nametag.replace(/[^\u4e00-\u9fa5\w]/g, '');
            if (playerlist[i]['Valid'] == '1')
                playerlist[i]['Valid'] = "是";
            else if (playerlist[i]['Valid'] == '0')
                playerlist[i]['Valid'] = "否";
            var tablerow = "<tr class='"+ playerlist[i]['PlayerID'] + "'><td class='name'>" + playerlist[i]['Name'] + "</td><td class='class'>" + playerlist[i]['Class'] + "</td><td class='idnumber'>" + playerlist[i]['IDNumber'] + "</td><td class='phonenumber'>" + playerlist[i]['PhoneNumber'] + "</td><td class='kitnumber'>" + playerlist[i]['KitNumber'] + "</td><td class='extrainfo'>" + playerlist[i]['ExtraInfo'] + "</td><td class='appearances'>" + playerlist[i]['Appearances'] + "</td><td class='minutes'>" + playerlist[i]['Minutes'] + "</td><td class='goals'>" + playerlist[i]['Goals'] + "</td><td class='yellowcards'>" + playerlist[i]['YellowCards'] + "</td><td class='redcards'>" + playerlist[i]['RedCards'] + "</td><td class='valid'>" + playerlist[i]['Valid'] + "</td><td class='suspension'>" + playerlist[i]['Suspension'] + "</td><td class='penalties'>" + playerlist[i]['Penalties'] + "</td><td class='penaltymiss'>" + playerlist[i]['Penaltymiss'] + "</td><td class='owngoals'>" + playerlist[i]['OwnGoals'] + "</td>";
            tablerow += "<td class='edit'><button type='button' class='playeredit btn btn-default btn-sm' id='E~" + playerlist[i]['PlayerID'] + "'><span class='glyphicon glyphicon-edit'></span></button></td></tr>";
            tableml += tablerow;
        }
        tableml += "</tbody></table></div>";
        $(".list").append(tableml);
    }
    $(".playeredit").click(function() {
        var btnedit = $(this);
        var id = $(this).attr('id');
        var pid = id.split('~');
        var edit = $("." + pid[1] + " .edit");
        edit.append("<button type='button' class='playerok" + pid[1] + " btn btn-default btn-sm' id='O~" + pid[1] + "'><span class='glyphicon glyphicon-ok'></span></button>");
        function editfield(nameid, fieldname) {
            var field = $("." + nameid + " ." + fieldname);
            var fieldtxt = field.text();
            field.empty();
            field.append("<input type='text' class='" + fieldname + nameid + " form-control input-sm' value='" + fieldtxt + "'>");
        }
        editfield(pid[1], "name");
        editfield(pid[1], "class");
        editfield(pid[1], "idnumber");
        editfield(pid[1], "phonenumber");
        editfield(pid[1], "kitnumber");
        editfield(pid[1], "extrainfo");
        var valid = $("." + pid[1] + " .valid");
        var validtxt = valid.text();
        valid.empty();
        valid.append("<select class='valid" + pid[1] + "'><option>是<option>否</select>");
        $(".valid" + pid[1]).val(validtxt);
        $(this).hide();

        $(".playerok" + pid[1]).click(function() {
            var id = $(this).attr("id");
            var pid = id.split("~");
            console.log(pid);
            var ok = $("." + pid[1] + " .edit");
            btnedit.show();
            $(this).remove();
            function fieldok(nameid, fieldname) {
                var fieldinput = $("." + fieldname + nameid);
                var field = $("." + nameid + " ." + fieldname);
                var fieldtxt = fieldinput.val();
                fieldinput.remove();
                field.append(fieldtxt);
                return fieldtxt;
            }
            var name = fieldok(pid[1], "name");
            var class1 = fieldok(pid[1], "class");
            var idnumber = fieldok(pid[1], "idnumber");
            var phonenumber = fieldok(pid[1], "phonenumber");
            var kitnumber = fieldok(pid[1], "kitnumber");
            var extrainfo = fieldok(pid[1], "extrainfo");
            var valid = fieldok(pid[1], "valid");
            if (valid == "是")
                valid = 1;
            else if (valid == "否")
                valid = 0;
            console.log(name,class1,idnumber,phonenumber,kitnumber,extrainfo,valid);
            $.get("checkduplicate.php", {
                        dbname: "<?=$dbname ?>",
                        table: "Players",
                        field: "KitNumber",
                        field2: "IDNumber",
                        value: kitnumber,
                        value2: idnumber,
                        extra: " AND Team = '<?=$team ?>' and PlayerID != " + pid[1]
                    }, function(data, state) {
                        console.log(data);
                        if (data == 1){
                            alert("重复的球衣号码！");
                            location.reload();
                        } else if (data == 2){
                            alert("重复的证件号码！");
                            location.reload();
                        } else {
                            $.post("editplayer.php", {
                                dbname: dbname,
                                playerid: parseInt(pid[1]),
                                name: name,
                                'class': class1,
                                idnumber: idnumber,
                                phonenumber: phonenumber,
                                kitnumber: parseInt(kitnumber),
                                extrainfo: extrainfo,
                                valid: valid
                            }, function(data, state) {
                                console.log(data, state);
                            });
                        }
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

