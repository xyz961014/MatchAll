<!doctype html>
<html lang="ch">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="static/bootstrap.min.css" rel="stylesheet">
    <link href="static/bootstrap-theme.min.css" rel="stylesheet">

<style>
    .glyphicon{
        color: white;
    }
</style>
    

    <title>TUFA</title>
  </head>
  <body>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×            
			    </button>
                <h4 class="modal-title" id="addModalLabel">
               模态框标题            
			    </h4>
            </div>
            <div class="modal-body">
                <div class="chgradio">
			        <label class="checkbox-inline">
     			        <input type="radio" name="chgradio" id="chgout" value="换下" checked> 换下
    			    </label>
   				    <label class="checkbox-inline">
      			    	<input type="radio" name="chgradio" id="chgin" value="换上"> 换上
                    </label>
                </div>
                <div class="goalradio">
			        <label class="checkbox-inline">
     			        <input type="radio" name="goalradio" id="goal" value="进球" checked> 进球
    			    </label>
   				    <label class="checkbox-inline">
      			    	<input type="radio" name="goalradio" id="penalty" value="点球"> 点球
                    </label>
			        <label class="checkbox-inline">
     			        <input type="radio" name="goalradio" id="owngoal" value="乌龙球"> 乌龙球
    			    </label>
			        <label class="checkbox-inline">
     			        <input type="radio" name="goalradio" id="penmiss" value="点球罚失"> 点球罚失
    			    </label>
                </div>
                <div class="rycradio">
			        <label class="checkbox-inline">
     			        <input type="radio" name="rycradio" id="yc" value="黄牌" checked> 黄牌
    			    </label>
   				    <label class="checkbox-inline">
      			    	<input type="radio" name="rycradio" id="rc" value="红牌"> 红牌
                    </label>
                </div>
                <br>
                <div class="form-group">
                    <label for="name">时间： </label>
                    <input type="text" class="form-control timeinput" id="timeinput" placeholder="请输入时间，伤停补时用+号表示。多个事件用空格隔开。" autofocus>
                </div>
		    </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭            
			    </button>
                <button type="button" class="btn btn-primary btnsubmit">
                提交            
			    </button>
            </div>
        </div>
    </div>
</div>
        
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="static/jquery.min.js"></script>
    <script src="static/bootstrap.min.js"></script>
    <script src="static/jcanvas.min.js"></script>
    <script src="static/underscore-min.js"></script>
    <div class="container">
<?php 
$dbname = $_GET['Match'];
echo "<a href='schedule.php?Match=$dbname'>返回</a>";
require "session.php"; 
if ($right > 1) {
?>
        <br>
        <input id="validcheck" type="checkbox" onclick='onvalid()' />有效
        <br>
<?php
}
require 'TeamDict.php';
require 'dbinfo.php';
$id = $_GET["id"];
$conn = dbconnect($dbname);
$sql = "SELECT * FROM Info";
$res = $conn->query($sql);
while($row = $res->fetch_assoc()) {
    $matchname = $row['name'];
    $subname = $row['subname'];
    $maxonfield = $row['maxonfield'];
    $minonfield = $row['minonfield'];
    $matchclass = $row['class'];
    $enablekitnum = $row['enablekitnum'];
    $penalty = $row['penalty'];
    $ordinarytime = $row['ordinarytime'];
    $extratime = $row['extratime'];
    $penaltyround = $row['penaltyround'];
    $year = $row['year'];
}
echo "<div class='row' id='match'>";
$elifile = fopen($dbname.'.json','r') or die("Unable to open file!");
$eliinfo = json_decode(fgets($elifile));
$eliinfo = $eliinfo[1];
fclose($elifile);
$sql = "SELECT * FROM Match$id";
$result = $conn->query($sql);
if (!$result) {
    $sql0 = "CREATE TABLE `Match$id` (
        `Team` varchar(255) NOT NULL,
        `KitNumber` int(11) DEFAULT NULL,
        `Name` varchar(255) NOT NULL,
        `ExtraInfo` varchar(255) DEFAULT NULL,
        `EventType` varchar(255) NOT NULL,
        `EventTime` int(11) DEFAULT NULL,
        `StoppageTime` int(11) DEFAULT NULL,
        `EventID` int(11) NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (`EventID`)
) ";
    if ($conn->query($sql0) === TRUE) {
        echo "";
    } else {
        echo "Error creating table:".$conn->error;
    }
}
$sql = "SELECT * FROM Matches WHERE MatchID = $id";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
     $hometeam = $row['HomeTeam'];
     $awayteam = $row['AwayTeam'];
     $valid = $row['Valid'];
     $stage = $row['Stage'];
     $group = $row['GroupName'];
     $round = $row['Round'];
     $mtime = $row['MatchTime'];
     $level = $row['Level'];
}
if ($stage == 'Group') {
    $subtitle = $level."小组赛".$group."组第".$round."轮";
} else if ($stage == "League"){
    $subtitle = "第".$round."轮";
} else {
    $hometeam = $eliinfo->{$id}->hometeam;
    $awayteam = $eliinfo->{$id}->awayteam;
    $subtitle = $stage;
}
if (preg_match('/^MA.+/', $dbname)) {
    $hometeam = $dict[$hometeam];
    $awayteam = $dict[$awayteam];
}
$subname = str_replace('"', '\"', $subname);
$subtitle = $subname.$subtitle;
if ($right > 1) {
    $sql = "SELECT KitNumber,Name,ExtraInfo FROM Players WHERE Team = '".$hometeam."' ORDER BY KitNumber";
    if (preg_match('/^NANQI.+/', $dbname)) {
        $sql = "SELECT KitNumber,Name,ExtraInfo FROM Players WHERE Team = '".$hometeam."' ORDER BY Class,Name";
    } 
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $homeplayers[] = Array("Name"=>$row['Name'], "KitNumber"=>$row['KitNumber'], "ExtraInfo"=>$row['ExtraInfo']);
    }
    $sql = "SELECT KitNumber,Name,ExtraInfo FROM Players WHERE Team = '".$awayteam."' ORDER BY KitNumber";
    if (preg_match('/^NANQI.+/', $dbname)) {
        $sql = "SELECT KitNumber,Name,ExtraInfo FROM Players WHERE Team = '".$awayteam."' ORDER BY Class,Name";
    } 
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $awayplayers[] = Array("Name"=>$row['Name'], "KitNumber"=>$row['KitNumber'], "ExtraInfo"=>$row['ExtraInfo']);
    }
    
    echo "<div class='hometable col-lg-6 col-md-6'><table class='table table-bordered table-hover table-condensed'><caption>".$hometeam."\t<input type='checkbox' class='abandon' name='HomeAbandon' id='H~Abandon'>弃赛</caption><thead><tr><th>#</th><th>姓名</th><th>首发</th><th>换人</th><th>进球</th><th>红黄牌</th></tr></thead><tbody>";
    //echo $hometeam."首发:<input type='checkbox' class='abandon' name='HomeAbandon' id='H~Abandon'>弃赛<br>";
    $namefilter = '/[^\x7f-\xff\w+]|·+/';
        for($i = 0;$i<count($homeplayers);$i++) {
            $num = $homeplayers[$i]['KitNumber'];
            $cname = preg_replace($namefilter, '', $homeplayers[$i]['Name']);
            $name = preg_replace('/^\s+|\s+$/', '', $homeplayers[$i]['Name']);
            echo "<tr class='row".$num.$cname."'><td>".$num."</td><td>".$name."</td><td>";
            echo "<input class='firstcheck' type='checkbox' name='Homecheck' id='H~$num~".$name."' value='0'>";
            //echo $num."-".$homeplayers[$i]['Name'];
            echo "</td><td class='chg'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$name."~chg'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='goal'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$name."~goal'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='ryc'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$name."~ryc'><span class='glyphicon glyphicon-plus'></span></button> </td></tr>";
        }
    echo "</tbody></table></div>";
    echo "<div class='awaytable col-lg-6 col-md-6'><table class='table table-bordered table-hover table-condensed'><caption>".$awayteam."\t<input type='checkbox' class='abandon' name='AwayAbandon' id='A~Abandon'>弃赛</caption><thead><tr><th>#</th><th>姓名</th><th>首发</th><th>换人</th><th>进球</th><th>红黄牌</th></tr></thead><tbody>";
    //echo "<br>".$awayteam."首发:<input type='checkbox' class='abandon' name='AwayAbandon' id='A~Abandon'>弃赛<br>";
        for($i = 0;$i<count($awayplayers);$i++) {
            $num = $awayplayers[$i]['KitNumber'];
            $cname = preg_replace($namefilter, '', $awayplayers[$i]['Name']);
            $name = preg_replace('/^\s+|\s+$/', '', $awayplayers[$i]['Name']);
            echo "<tr class='row".$num.$cname."'><td>".$num."</td><td>".$name."</td><td>";
            echo "<input class='firstcheck' type='checkbox' name='Awaycheck' id='A~$num~".$name."' value='0'>";
            //echo $num."-".$awayplayers[$i]['Name'];
            echo "</td><td class='chg'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$name."~chg'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='goal'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$name."~goal'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='ryc'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$name."~ryc'><span class='glyphicon glyphicon-plus'></span></button> </td></tr>";
        }
    echo "</tbody></table></div>";
    echo "<br>";

}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Hnum = test_input($_POST['H']);
    $Anum = test_input($_POST['A']);
}
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?> 
<script>
var validbool = '<?=$valid?>';
var stage = '<?=$stage ?>';
if (stage == "Group" || stage == "League") {
    stage = false;
} else {
    stage == true;
}
<?php
if ($right > 1){
?>
function onvalid() {
    var validcheck = $("#validcheck");
    //console.log(validcheck[0].checked);
    if (validcheck[0].checked) {
        console.log('true');
        validbool = 1;
        $.get('showreport.php',{
            dbname: '<?=$dbname ?>',
            MatchID: "<?='Match'.$id ?>"
        }, function(data,state) {
            info = JSON.parse(data);
            var homename = "<?=$hometeam ?>";
            var awayname = "<?=$awayteam ?>";
            var dbname = '<?=$dbname ?>';
            var leastnum = "<?=$minonfield ?>";
            var [hflist, aflist, hevent, aevent, events, hg, ag, habandon, aabandon] = getevents(info, dbname, homename, awayname);
            var che = check(hflist, aflist, hevent, aevent, habandon, aabandon, leastnum);
            if (!che.right) {
                alert(homename + ':\n' + che.herr + '\n' + awayname + ':\n' + che.aerr);
                var validche = document.getElementById('validcheck'); 
                validche.checked = false;
            } else {
                $.get('checked.php',{
                    dbname: '<?=$dbname ?>',
                    MatchID: '<?=$id ?>',
                    Valid: validbool
                },function(data,state) {
                    console.log(data);
                })
            }
        });
    } else {
        console.log('false');
        validbool = 0;
        $.get('checked.php',{
            dbname: '<?=$dbname ?>',
            MatchID: '<?=$id ?>',
            Valid: validbool
        },function(data,state) {
        console.log(data);
        });
    }
}
$('#addModal').modal({
    keyboard: false,
	show: false
});
$('.timeinput').keydown(function(e) {
    if(e.keyCode==13){
        $(".btnsubmit").click();
    }
})
$('.addinfo').click(function() {
    validbool = 0;
    var id = $(this).attr('id');
    var pid = id.split('~');
    console.log(pid);
    if (pid[0] == 'H')
        var t = '<?=$hometeam?>';
    else if (pid[0] == 'A')
        var t = '<?=$awayteam?>';
    var title = t + '~' + pid[1] + '~' + pid[2];
    if (pid[3] == 'chg') {
        $('.chgradio').show();
        $('.goalradio').hide();
        $('.rycradio').hide();
        title += '~换人';
    } else if (pid[3] == 'goal') {
        $('.chgradio').hide();
        $('.goalradio').show();
        $('.rycradio').hide();
        title += '~进球';
    } else if (pid[3] == 'ryc') {
        $('.chgradio').hide();
        $('.goalradio').hide();
        $('.rycradio').show();
        title += '~红黄牌';
    }
    $('.modal-title').text(title);     
    $('.timeinput').val("");
    $('#addModal').modal('show');
})
$('.btnsubmit').click(function() {
    var title = $('.modal-title').text();
    var pt = title.split("~");
    console.log(pt);
    if (pt[3] == '换人')
        var type = $('input[name=chgradio]:checked').val();
    else if (pt[3] == '进球')
        var type = $('input[name=goalradio]:checked').val();
    else if (pt[3] == '红黄牌')
        var type = $('input[name=rycradio]:checked').val();
    console.log(type);
    var inputtxt = $('.timeinput').val();
    var input = inputtxt.split(/\s+/);
    console.log(input);
    validbool = 0;
    $.get('checked.php',{
        dbname: '<?=$dbname ?>',
        MatchID: '<?=$id ?>',
        Valid: validbool
    },function(data,state) {
        console.log(data);
        //showreport();
        var team = pt[0];
        var num = parseInt(pt[1]);
        var name = pt[2];
        for (var i = 0;i < input.length;i++) {
            if (/\+/.test(input[i])) {
                var parsetime = input[i].split("+");
                var time = parseInt(parsetime[0]);
                var stptime = parseInt(parsetime[1]);
            } else {
                var time = parseInt(input[i]);
                var stptime = 0;
            }
            console.log(time,stptime,num,team,name,type);
            $.get('additem.php',{
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: team,
                    KitNumber: num,
                    Name: name,
                    Type: type,
                    Time: time,
                    StoppageTime: stptime
            }, function(data, state) {
                console.log(data);
            })
        }
        window.setTimeout(function() {
            showreport();
        },200*input.length);

    })
    $('#addModal').modal('hide');
    })

$('.abandon').click(function () {
    validbool = 0;
    var id = $(this).attr('id');
    var pid = id.split('~');
    if (pid[0] == 'H')
        var ateam = '<?=$hometeam?>';
    else 
        var ateam = '<?=$awayteam?>';
    var Hab = document.getElementById(id);
    $.get('checked.php', {
            dbname: '<?=$dbname ?>',
            MatchID: '<?=$id ?>',
            Valid: validbool
        }, function(data, state) {
            if (Hab.checked) {
                $.get('additem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: ateam,
                    KitNumber: null,
                    Name: null,
                    Type: '弃赛',
                    Time: 0,
                    StoppageTime: 0
                }, function(data, state) {
                    console.log(data);
                    showreport();
                })
            } else {
                $.get('delitem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: ateam,
                    KitNumber: null,
                    Name: null,
                    Type: '弃赛',
                    Time: 0,
                    StoppageTime: 0
                }, function(data, state) {
                    console.log(data);
                    showreport();
                })
            }
    })
});
$(".firstcheck").click(function() {
    var id = $(this).attr('id');
    var parseid = id.split('~');
    var num = parseid[1];
    var name = parseid[2];
    var maxonfield = "<?=$maxonfield ?>";
    if (parseid[0] == 'H')
        var team = '<?=$hometeam?>';
    else if (parseid[0] == 'A')
        var team = '<?=$awayteam?>';
    var Hnum = $("input[name=Homecheck]");
    var hn = 0;
    for (k in Hnum) {
        if (Hnum[k].checked)
            hn++;
    }
    var Anum = $("input[name=Awaycheck]");
    var an = 0;
    for (k in Anum) {
        if (Anum[k].checked)
            an++;
    }
    var inst = document.getElementById(id); 
    if (inst.checked && (an > maxonfield || hn > maxonfield)) {
        inst.checked = false;
        alert('首发人数超过' + maxonfield.toString() + "人！");
    }
    else {
        validbool = 0;
        $.get('checked.php', {
            dbname: '<?=$dbname ?>',
            MatchID: '<?=$id ?>',
            Valid: validbool
        }, function(data, state) {
            console.log(data);
            console.log(team,num,name);
            if (inst.checked) {
                $.get('additem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: team,
                    KitNumber: num,
                    Name: name,
                    Type: '首发',
                    Time: 0,
                    StoppageTime: 0
            }, function(data, state) {
                console.log(data);
                showreport();
            })
            }
            else {
                 $.get('delitem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    EventID: inst.value,
                }, function(data, state) {
                    console.log(data);
                    showreport();
                })
            }
        })
    }
})
<?php
}
?>
</script>
<?php 
if ($right > 1) {
?>
<div class='input col-xs-12 penalty'>
    
    <div class='event form-group'>
    TIME: <input type="text" name="Time" value=0>
    STOPPAGETIME: <input type="text" name="StoppageTime" value=0>
    <br>
    KITNUM: <input title='event' type="text" name="KitNumber">
    NAME: <input title='event' type="text" name="Name">
    EVENTTYPE: <select title='event' name="EventType">
            <option> 进球
            <option> 换下
            <option> 换上
            <option> 黄牌	431
            <option> 红牌
            <option> 点球
            <option> 乌龙球
            <option> 点球罚失
               </select>
    <input title='event' type='button' value='Submit' onclick='ESubmit()'/>
    <br>
    </div>
    <div class='penalty form-group'>
    <h4> 点球决胜  </h4> 
    <label>球队：</label> 
        <select name="Team">
            <option><?php echo $hometeam;?>
            <option><?php echo $awayteam;?>
        </select>
    <br>
    <div>
    <label> 号码： </label> 
    <input title='penalty' class='penalty' type="text" name="KitNumber">
    </div>
    <div>
    <label> 姓名： </label>
    <input title='penalty' class='penalty' type="text" name="Name">
    </div>
    <div>
    <label> 事件： </label> 
               <select title='penalty' class='penalty' name="PenaltyType">
            <option> 点球决胜罚进
            <option> 点球决胜罚失
               </select>
    </div>
    
    <input title='penalty' class='penalty' type='button' value='Submit' onclick='PSubmit()'/>
    </div>
</div>
<br>
<div class='display col-xs-12 penalty'>
    <div class='homedisplay col-xs-6'>
        <div class="homefirst"></div>
        <div class="homeevent"></div>
    </div>
    <div class='awaydisplay col-xs-6'>
        <div class="awayfirst"></div>
        <div class="awayevent"></div>
    </div>
</div>
<?php
}
?>
<div class="col-xs-12">
<?php 
if ($right > 1) {
?>
<h5>注：对于同一时间发生的多个事件，双击将其排在最末。</h5>
<?php
}
?>
<a class="btn btn-default" id="pngdown">下载图片</a>
</div>
<canvas id="canvas"></canvas>
<script>
<?php 
if ($right > 1) {
?>
$('.event').hide();
var Kittext = $("input[name=KitNumber]");
var Nametext = $("input[name=Name]");
Kittext.keyup(function (e) {
    var num = e.target.value;
    var team = $("select[name=Team]");
    console.log(e.target);
    if (e.target.title == 'penalty') {
        console.log('pen');
        var numtext = $("input[name=KitNumber][title=penalty]");
        var nametext = $("input[name=Name][title=penalty]");
    } else if (e.target.title == 'event'){
        console.log('event');
        var numtext = $("input[name=KitNumber][title=event]");
        var nametext = $("input[name=Name][title=event]");
    }
    console.log(num.length);
    team = team.val();
    if (num.length == 0) {
        nametext.val("");
    } else {
        $.get('kitname.php',{
                dbname: '<?=$dbname ?>',
                MatchID: "<?='Match'.$id ?>",
                Team: team,
                KitNumber: num,
                Name: null,
                IsKit: true
            }, function(data,state) {
                console.log(data);
                var info = JSON.parse(data);
                nametext.val(info.name);
            })
    }
    
})
Nametext.keyup(function (e) {
    var name = e.target.value;
    console.log(name.length,name);
    var team = $("select[name=Team]");
    console.log(e.target);
    if (e.target.title == 'penalty') {
        console.log('pen');
        var numtext = $("input[name=KitNumber][title=penalty]");
        var nametext = $("input[name=Name][title=penalty]");
    } else if (e.target.title == 'event'){
        console.log('event');
        var numtext = $("input[name=KitNumber][title=event]");
        var nametext = $("input[name=Name][title=event]");
    }
    team = team.val();
    if (name.length == 0) {
        numtext.val("");
    } else {
        $.get('kitname.php',{
                dbname: '<?=$dbname ?>',
                MatchID: "<?='Match'.$id ?>",
                Team: team,
                KitNumber: null,
                Name: name,
                IsKit: false
            }, function(data,state) {
                console.log(data);
                var info = JSON.parse(data);
                numtext.val(info.kitnum);
            })
    }
    
})

function PSubmit() {
    validbool = 0;
    $.get('checked.php', {
        dbname: '<?=$dbname ?>',
        MatchID: '<?=$id ?>',
        Valid: validbool
    }, function(data, state) {
        console.log(data);
        showreport();
        var dbstr = '<?=$dbname ?>';
        var time = '<?=$extratime + $ordinarytime ?>';
        //if (dbstr.match("MANAN"))
        //    var time = 110;
        //else if (dbstr.match("MANYU"))
        //    var time = 60;
        //else if (dbstr.match("NANQI"))
        //    var time = 100;
        //else if (dbstr.match("FRESH"))
        //    var time = 80;
        var stptime = 0;
        var team = $("select[name=Team]");
        var num = $("input[name=KitNumber][title=penalty]");
        var name = $("input[name=Name][title=penalty]");
        var type = $("select[name=PenaltyType]");
        team = team.val();
        type = type.val();
        if (num.val().length == 0 || name.val().length == 0) {
            alert('不是有效球员！');
            return;
        }
        num = parseInt(num.val());
        name = name.val();
        console.log(time,stptime,num,name,type);
        $.get('additem.php',{
                dbname: '<?=$dbname ?>',
                MatchID: "<?='Match'.$id ?>",
                Team: team,
                KitNumber: num,
                Name: name,
                Type: type,
                Time: time,
                StoppageTime: stptime
        }, function(data, state) {
            console.log(data);
            console.log(state);
            showreport();
        })

    });
}

function ESubmit() {
    validbool = 0;
    $.get('checked.php',{
        dbname: '<?=$dbname ?>',
        MatchID: '<?=$id ?>',
        Valid: validbool
    },function(data,state) {
        console.log(data);
        showreport();
        var time = $("input[name=Time]");
        var team = $("select[name=Team]");
        var stptime = $("input[name=StoppageTime]");
        var num = $("input[name=KitNumber][title=event]");
        var name = $("input[name=Name][title=event]");
        var type = $("select[name=EventType]");
        team = team.val();
        if (time.val().length == 0) {
            time.val(0);
        }
        if (stptime.val().length == 0) {
            stptime.val(0);
        }
        time = parseInt(time.val());
        stptime = parseInt(stptime.val());
        type = type.val();
        if (num.val().length == 0 || name.val().length == 0) {
            alert('不是有效球员！');
            return;
        }
        num = parseInt(num.val());
        name = name.val();
        console.log(time,stptime,num,name,type);
        $.get('additem.php',{
                dbname: '<?=$dbname ?>',
                MatchID: "<?='Match'.$id ?>",
                Team: team,
                KitNumber: num,
                Name: name,
                Type: type,
                Time: time,
                StoppageTime: stptime
        }, function(data, state) {
            console.log(data);
            console.log(state);
            showreport();
        })

    })
}
<?php
}
?>

function getevents(info, dbname, homename, awayname) {
    var hflist = [];
    var aflist = [];
    var hevent = [];
    var aevent = [];
    var events = [];
    var hg = 0;
    var ag = 0;
    var habandon = false;
    var aabandon = false;
    for (var i = 0;i < info.length;i++) {
            var e = JSON.parse(info[i]);
            if (e.type != "弃赛") {
                var ptn = /(校友|教工|足特)/;
                var name = e.name.replace(/^\s+|\s+$/, '');
                e.name = name;
                var enablekitnum = "<?=$enablekitnum ?>";
                console.log("kit",enablekitnum);
                if (enablekitnum == "0") {
                    var txt = e.name;
                } else {
                    if (ptn.test(e.extrainfo)) {
                        if (/校友/.test(e.extrainfo)) {
                            var txt = e.kitnum + "-" + e.name + "(校友)";
                        }
                        else if (/教工/.test(e.extrainfo)) {
                            var txt = e.kitnum + "-" + e.name + "(教工)";
                        }
                        else if (/足特/.test(e.extrainfo)) {
                            var txt = e.kitnum + "-" + e.name + "(足特)";
                        }
                    }
                    else {
                        var txt = e.kitnum + "-" + e.name;
                    }
                }
                e.namestr = txt;
            }
            if (e.team == homename) {
                if (e.type == "首发") {
                    hflist.push(e);
                } else if (e.type == "弃赛") {
<?php 
                    if ($right > 1) {
?>

                    var Hhab = document.getElementById('H~Abandon');
                    Hhab.checked = true;
<?php
                    }
?>
                    habandon = true;
                } else {
                    hevent.push(e);
                    events.push(e);
                    if (e.type == "进球" || e.type == "点球") {
                        hg++;
                    }
                    if (e.type == "乌龙球") {
                        ag++;
                    }
                }
            }
            else if (e.team == awayname) {
                if (e.type == "首发") {
                    aflist.push(e);
                } else if (e.type == "弃赛") {
<?php 
                    if ($right > 1) {
?>
                    var Haab = document.getElementById('A~Abandon');
                    Haab.checked = true;
<?php
                    }
?>
                    aabandon = true
                } else {
                    aevent.push(e);
                    events.push(e);
                    if (e.type == "进球" || e.type == "点球") {
                        ag++;
                    }
                    if (e.type == "乌龙球") {
                        hg++;
                    }
                }
            }
        }
    for (var i = 0;i < hevent.length;i++) {
        if (hevent[i].stptime == 0) 
            var timestr = hevent[i].time.toString();
        else 
            var timestr = hevent[i].time.toString() + "+" + hevent[i].stptime.toString();
        hevent[i].timestr = timestr;
    }
    for (var i = 0;i < aevent.length;i++) {
        if (aevent[i].stptime == 0) 
            var timestr = aevent[i].time.toString();
        else 
            var timestr = aevent[i].time.toString() + "+" + aevent[i].stptime.toString();
        aevent[i].timestr = timestr;
    }
    return [hflist, aflist, hevent, aevent, events, hg, ag, habandon, aabandon]; 
}

function showreport() {
<?php 
    if ($right > 1) {
?>
    var validcheck = document.getElementById('validcheck'); 
    if (validbool == 1) {
        validcheck.checked = true;
    } else {
        validcheck.checked = false;
    }
<?php
    }
?>
    $('.tbl').remove();
    $(".eventdisplay").remove();
    $.get('showreport.php',{
        dbname: '<?=$dbname ?>',
        MatchID: "<?='Match'.$id ?>"
    }, function(data,state) {
        info = JSON.parse(data);
        console.log(info);
        var homename = "<?=$hometeam ?>";
        var awayname = "<?=$awayteam ?>";
        var dbname = '<?=$dbname ?>';
        if (dbname.match(/^MA.+/)) {
            var HomeName = "<?=$dict2[$hometeam] ?>";
            var AwayName = "<?=$dict2[$awayteam] ?>";
        } else {
            var HomeName = homename;
            var AwayName = awayname;
        }
        var [hflist, aflist, hevent, aevent, events, hg, ag] = getevents(info, dbname, homename, awayname);
<?php 
        if ($right > 1) {
?>
        var homefirst = $(".homefirst");
        var awayfirst = $(".awayfirst");
        var homeevent = $(".homeevent");
        var awayevent = $(".awayevent");
        
        homefirst.text(homename + "首发：");
        awayfirst.text(awayname + "首发：");
        homefirst.hide();
        awayfirst.hide();
        homeevent.append($("<h4 class='eventdisplay'></h4>").text(homename + "点球决胜："));
        awayevent.append($("<h4 class='eventdisplay'></h4>").text(awayname + "点球决胜："));
        if ((stage || '<?=$dbname?>'.match("MANYU")) && hg == ag) {
            $(".penalty").show();
        } else {
            $(".penalty").hide();
        }
        for (var i = 0;i < hflist.length;i++) {
            var Hinst = document.getElementById('H~'+hflist[i].kitnum+'~'+hflist[i].name); 
            Hinst.checked = true;
            Hinst.value = hflist[i].eventid;
        } 
        for (var i = 0;i < aflist.length;i++) {
            var Ainst = document.getElementById('A~'+aflist[i].kitnum+'~'+aflist[i].name); 
            Ainst.checked = true;
            Ainst.value = aflist[i].eventid;
        }
        //$(".delplayer").click(function() {
        //    var id = $(this).attr('id');
        //    var id = id.split(".");
        //    validbool = 0;
        //    $.get('checked.php',{
        //        dbname: '<?=$dbname ?>',
        //        MatchID: '<?=$id ?>',
        //        Valid: validbool
        //    },function(data,state) {
        //        console.log(data);
        //        //console.log(id);
        //        $.get('delitem.php', {
        //            dbname: '<?=$dbname ?>',	431
        //            MatchID: "<?='Match'.$id ?>",
        //            Team: id[0],
        //            KitNumber: parseInt(id[1]),
        //            Name: id[2],
        //            Type: '首发',
        //            Time: null,
        //            StoppageTime:null

        //        }, function(data,state) {
        //            console.log(data,state);
        //            showreport();
        //        });

        //    })
        //            });
        for (var i = 0;i < hevent.length;i++) {
            if (hevent[i].stptime == 0) 
                var timestr = hevent[i].time.toString();
            else 
                var timestr = hevent[i].time.toString() + "+" + hevent[i].stptime.toString();
            hevent[i].timestr = timestr;
            var cname = hevent[i].name.replace(/[^\u4e00-\u9fa5\w]/g, '');
            var tb = " <a class='delevent' id='del\."+hevent[i].eventid+"'><span class='glyphicon glyphicon-remove'></span></a>" ;
            if (hevent[i].type == '进球' || hevent[i].type == '点球' || hevent[i].type == '乌龙球' || hevent[i].type == '点球罚失' ) {
                var Hcell = $('.hometable .row' + hevent[i].kitnum.toString() + cname + ' .goal');
                if (hevent[i].type == '进球') {
                    var lbl = "<span class='tbl label label-primary'>"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '点球') {
                    var lbl = "<span class='tbl label label-success'>点"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '乌龙球') {
                    var lbl = "<span class='tbl label label-danger'>乌龙"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '点球罚失') {
                    var lbl = "<span class='tbl label label-warning'>点失"+hevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (hevent[i].type == '换下' || hevent[i].type == '换上') {
                var Hcell = $('.hometable .row' + hevent[i].kitnum.toString() + cname + ' .chg');
                if (hevent[i].type == '换下') {
                    var lbl = "<span class='tbl label label-danger'><span class='glyphicon glyphicon-arrow-down'></span>"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '换上') {
                    var lbl = "<span class='tbl label label-success'><span class='glyphicon glyphicon-arrow-up'></span>"+hevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (hevent[i].type == '黄牌' || hevent[i].type == '红牌') {
                var Hcell = $('.hometable .row' + hevent[i].kitnum.toString() + cname + ' .ryc');
                if (hevent[i].type == '黄牌') {
                    var lbl = "<span class='tbl label label-warning'>"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '红牌') {
                    var lbl = "<span class='tbl label label-danger'>"+hevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (hevent[i].type == '点球决胜罚进' || hevent[i].type == '点球决胜罚失') {
                var txt = hevent[i].type + "\t" + hevent[i].timestr + "\'\t" + hevent[i].namestr; 
                tb = " <input type='button' class='delevent btn btn-sm btn-default' id='del\."+hevent[i].eventid+"' value='delete'>";
                var cont = $("<p class='eventdisplay'></p>").text(txt); 
                cont.append(tb);
                homeevent.append(cont);

            }
        }
        for (var i = 0;i < aevent.length;i++) {
            if (aevent[i].stptime == 0) 
                var timestr = aevent[i].time.toString();
            else 
                var timestr = aevent[i].time.toString() + "+" + aevent[i].stptime.toString();
            aevent[i].timestr = timestr;
            var cname = aevent[i].name.replace(/[^\u4e00-\u9fa5\w]/g, '');
            var tb = " <a class='delevent' id='del\."+aevent[i].eventid+"'><span class='glyphicon glyphicon-remove'></span></a>" ;
            if (aevent[i].type == '进球' || aevent[i].type == '点球' || aevent[i].type == '乌龙球' || aevent[i].type == '点球罚失' ) {
                var Hcell = $('.awaytable .row' + aevent[i].kitnum.toString() + cname + ' .goal');
                if (aevent[i].type == '进球') {
                    var lbl = "<span class='tbl label label-primary'>"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '点球') {
                    var lbl = "<span class='tbl label label-success'>点"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '乌龙球') {
                    var lbl = "<span class='tbl label label-danger'>乌龙"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '点球罚失') {
                    var lbl = "<span class='tbl label label-warning'>点失"+aevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (aevent[i].type == '换下' || aevent[i].type == '换上') {
                var Hcell = $('.awaytable .row' + aevent[i].kitnum.toString() + cname + ' .chg');
                if (aevent[i].type == '换下') {
                    var lbl = "<span class='tbl label label-danger'><span class='glyphicon glyphicon-arrow-down'></span>"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '换上') {
                    var lbl = "<span class='tbl label label-success'><span class='glyphicon glyphicon-arrow-up'></span>"+aevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (aevent[i].type == '黄牌' || aevent[i].type == '红牌') {
                var Hcell = $('.awaytable .row' + aevent[i].kitnum.toString() + cname + ' .ryc');
                if (aevent[i].type == '黄牌') {
                    var lbl = "<span class='tbl label label-warning'>"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '红牌') {
                    var lbl = "<span class='tbl label label-danger'>"+aevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (aevent[i].type == '点球决胜罚进' || aevent[i].type == '点球决胜罚失') {
                var txt = aevent[i].type + "\t" + aevent[i].timestr + "\'\t" + aevent[i].namestr; 
                tb = " <input type='button' class='delevent btn btn-sm btn-default' id='del\."+aevent[i].eventid+"' value='delete'>";
                var cont = $("<p class='eventdisplay'></p>").text(txt); 
                cont.append(tb);
                awayevent.append(cont);

            }
        }
        $(".delevent").click(function() {
            var id = $(this).attr('id');
            var id = id.split(".");
            validbool = 0;
            $.get('checked.php',{
                dbname: '<?=$dbname ?>',
                MatchID: '<?=$id ?>',
                Valid: validbool
            },function(data,state) {
                console.log(data);
                console.log(id);
                $.get('delitem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    EventID: parseInt(id[1]),
                }, function(data,state) {
                    console.log(data,state);
                    showreport();
                });
            })
            
        });
<?php
        }
?>
        //console.log(hflist,aflist,hevent,aevent);
        //REPORT PNG
        var revents = [];
        var simul = [];
        var penal = [];
        if (events.length > 0)
            simul = [events[0]];
        for (var i = 1;i < events.length;i++) {
            if (events[i].type.match(/点球决胜/)){
                penal.push(events[i]);
            } 
            else if (events[i].timestr == simul[0].timestr) {
                    simul.push(events[i]);
            } else {
                revents.push(simul);
                simul = [events[i]];
            }
        } 
        revents.push(simul);
        revents.push(penal);
        console.log("re",revents);

        var curY = 50;
        var hy = 240;
        $('canvas').attr("width", 1600);
        for (var i = 0;i < revents.length;i++) {
            var hside = [];
            var aside = [];
            for (var j = 0;j < revents[i].length;j++) {
                if (hg == ag) {
                    if (revents[i][j].team == homename) {
                        hside.push(revents[i][j]);
                    }
                    else if (revents[i][j].team == awayname) {
                        aside.push(revents[i][j]);
                    }
                } else {
                    if (revents[i][j].team == homename && !revents[i][j].type.match(/点球决胜/)) {
                        hside.push(revents[i][j]);
                    }
                    else if (revents[i][j].team == awayname && !revents[i][j].type.match(/点球决胜/)) {
                        aside.push(revents[i][j]);
                    }
                }
                
            }
            var halfh = Math.max(hside.length, aside.length) * 50 / 2 + 10;
            if (hside.length == 0 && aside.length == 0)
                halfh = -20;
            hy += 2 * halfh + 40;
        }
        console.log(hflist);
        hflist.sort(function(a,b) {return a.kitnum - b.kitnum;} );
        aflist.sort(function(a,b) {return a.kitnum - b.kitnum;} );
        var hfstr = '';
        var afstr = '';
        for (var i = 0;i < hflist.length;i++) {
            hfstr += hflist[i].namestr + '    ';
        }
        for (var i = 0;i < aflist.length;i++) {
            afstr += aflist[i].namestr + '    ';
        }
        //if (hfstr.length > afstr.length)
        //    var mstr = hfstr;
        //else
        //    var mstr = afstr;
        function drawfirst(color, layername, text, x, y) {
            $('canvas').drawText({
            layer: true,
            fillStyle: color,
            fontFamily: 'WenQuanYi Micro Hei',
            fontSize: 36,
            name: layername,
            text: text,
            fromCenter: false,
            x: x, y: y,
            align: 'left',
            maxWidth: 600
        });
        }
        function drawtitle(layername, text, x, y) {
            $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontStyle: 'bold',
            name: layername,
            x: x, y: y,
            fontSize: 50,
            fontFamily: 'simHei',
            text: text
        });
        }
        function drawcenter(font, size, style, layername, text, y) {
        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontStyle: style,
            name: layername,
            x: 800, y: y,
            fontSize: size,
            fontFamily: font,
            text: text
        })
        }
        function drawline(layername, x1, x2, y) {
            $('canvas').drawLine({
                    layer: true,
                    name: layername,
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    rounded: true,
                    x1: x1, y1: y,
                    x2: x2, y2: y,
                });
        }
        function drawtime(layername, time, x, y) {
            $('canvas').drawText({
                    layer: true,
                    name: layername,
                    fillStyle: '#000',
                    fontFamily: 'Trebuchet MS',
                    fontSize: 36,
                    text: time,
                    x: x, y: y,
                })

        }
        function drawname(color, layername, name, x, y) {
            $('canvas').drawText({
                        layer: true,
                        name: layername,
                        fillStyle: color,
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: name,
                        x: x, y: y,
                    });

        }
        function drawplayer(layername, name, x, y) {
            $('canvas').drawText({
                        layer: true,
                        name: layername,
                        fillStyle: '#000',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: name,
                        fromCenter: false,
                        x: x, y: y,
<?php
                        if ($right >1) {
?>
                        dblclick: function(layer) {
                            var item = layer.name.split("~");
                            console.log(item);
                            $.get('delitem.php', {
                               dbname: '<?=$dbname ?>',
                               MatchID: "<?='Match'.$id ?>",
                               EventID: parseInt(item[6]),
                            }, function(data,state) {
                                console.log(data,state);
                                $.get('additem.php', {
                                    dbname: '<?=$dbname ?>',
                                    MatchID: "<?='Match'.$id ?>",
                                    Team: item[0],
                                    KitNumber: parseInt(item[1]),
                                    Name: item[2],
                                    Type: item[3],
                                    Time: item[4],
                                    StoppageTime: item[5]
                                }, function(data,state) {
                                    console.log(data,state);
                                    location.reload();
                                });

                            });
                        }
<?php 
                        }
?>
                    }); 
        }
        function drawrect(layername, x, y, w, h) {
            $('canvas').drawRect({
                    layer: true,
                    name: layername,
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    x: x, y: y,
                    width: w,
                    height: h,
                    cornerRadius: 10
                });
        }
        function drawicon (layername, icon, x, y) {
            $('canvas').drawImage({
                    layer: true,
                    name: layername,
                    source: 'ReportElements/'+ icon +'.png',
                    x: x, y: y,
                    fromCenter: false
                });

        }
        drawfirst('rgba(0, 0, 0, 0)', 'measurehfirst', hfstr, 0, curY);
        drawfirst('rgba(0, 0, 0, 0)', 'measureafirst', afstr, 0, curY);
        var hf = Math.max($('canvas').measureText('measurehfirst').height, $('canvas').measureText('measureafirst').height) / 2;
        hy += Math.max(hf, 70) + hf;
        hy += 70;
        if ((stage || '<?=$dbname?>'.match("MANYU")) && hg == ag)  //点球决胜
            hy += 80;
        $('canvas').attr("height", hy);

        drawtitle('homename', HomeName, 400, 50);
        drawtitle('awayname', AwayName, 1200, 50);
        curY += $('canvas').measureText('homename').height + 10; 
        if ((stage || '<?=$dbname?>'.match("MANYU")) && hg == ag)  //点球决胜
            curY += 80;
        var subtitle = "<?=$subtitle ?>";
        drawcenter('simHei', 25, 'normal', 'subtitle', subtitle, curY);
        curY += $('canvas').measureText('subtitle').height + 20; 
        drawfirst('#000', 'homefirst', hfstr, 0, curY);
        drawfirst('#000', 'awayfirst', afstr, 1000, curY);
        var firstheight = Math.max($('canvas').measureText('homefirst').height, $('canvas').measureText('awayfirst').height);
        curY += firstheight / 2 - 10; 
        drawcenter('simHei', 38, 'normal', 'First', "首发阵容", curY);
        curY += Math.max($('canvas').measureText('First').height + 30, firstheight / 2); 
        $('canvas').drawImage({
            layer: true,
            name: 'starticon',
            source: 'ReportElements/START.png',
            x: 800, y: curY,
        });
        curY += 30;
        $('canvas').drawArc({
            layer: true,
            name: 'startpoint',
            fillStyle: 'black',
            x: 800, y: curY,
            radius: 8
        });
        var startY = curY;
        curY += 50;
        for (var i = 0;i < revents.length;i++) {
            var hside = [];
            var aside = [];
            for (var j = 0;j < revents[i].length;j++) {
                if (revents[i][j].team == homename && !revents[i][j].type.match(/点球决胜/)) {
                    if (revents[i][j].type == "乌龙球") 
                        aside.push(revents[i][j]);
                    else
                        hside.push(revents[i][j]);
                }
                else if (revents[i][j].team == awayname && !revents[i][j].type.match(/点球决胜/)) {
                    if (revents[i][j].type == "乌龙球") 
                        hside.push(revents[i][j]);
                    else
                        aside.push(revents[i][j]);
                }
            }
            var halfh = Math.max(hside.length, aside.length) * 50 / 2 + 10;
            if (hside.length == 0 && aside.length == 0)
                halfh = -20;
            console.log(halfh);
            curY += halfh;
            if (hside.length != 0) {
                drawline('htimeline' + i.toString(), 680, 800, curY);
                drawtime('htime' + i.toString(), hside[0].timestr, 740, curY - 20);
                var rectwidth = 0;
                var rectheight = hside.length * 50 + 10;
                for (var k = 0;k < hside.length;k++) {
                    drawname('rgba(0, 0, 0, 0)', 'measure' + hside[k].namestr, hside[k].namestr, 800, 0);
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + hside[k].namestr).width);
                }
                rectwidth += 100;
                drawrect('hrect' + i.toString(), 680 - rectwidth / 2, curY, rectwidth, rectheight);
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < hside.length;k++) {
                    if (hside[k].type == "进球") {
                        drawicon('hG' + i.toString() + k.toString(), 'G', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "点球") {
                        drawicon('hPG' + i.toString() + k.toString(), 'PG', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "点球罚失") {
                        drawicon('hPM' + i.toString() + k.toString(), 'PM', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "乌龙球") {
                        drawicon('hOG' + i.toString() + k.toString(), 'OG', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "换下") {
                        drawicon('hSO' + i.toString() + k.toString(), 'SO', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "换上") {
                        drawicon('hSI' + i.toString() + k.toString(), 'SI', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "黄牌") {
                        var y2c = false;
                        for (var l = 0;l < i;l++) {
                            for (var il = 0;il < revents[l].length;il++) {
                                if (revents[l][il].type == "黄牌" && revents[l][il].namestr == hside[k].namestr) {
                                    y2c = true;
                                    break;
                                }
                            }
                        }
                        if (y2c) {
                            drawicon('hY2C' + i.toString() + k.toString(), 'Y2C', 680 - rectwidth + 20, ey);
                        } else {
                            drawicon('hYC' + i.toString() + k.toString(), 'YC', 680 - rectwidth + 20, ey);
                        }
                    } else if (hside[k].type == "红牌") {
                        drawicon('hRC' + i.toString() + k.toString(), 'RC', 680 - rectwidth + 20, ey);
                    }
                    drawplayer(hside[k].team + "~" + hside[k].kitnum + "~" + hside[k].name + "~" + hside[k].type + "~" + hside[k].time + "~" + hside[k].stptime + "~" + hside[k].eventid , hside[k].namestr, 680 - rectwidth + 70, ey);
                    ey += 50;
                }
            } 
            if (aside.length != 0) {
                drawline('atimeline' + i.toString(), 800, 920, curY);
                drawtime('atime' + i.toString(), aside[0].timestr, 860, curY - 20);
                var rectwidth = 0;
                var rectheight = aside.length * 50 + 10;
                for (var k = 0;k < aside.length;k++) {
                    drawname('rgba(0, 0, 0, 0)', 'measure' + aside[k].namestr, aside[k].namestr, 800, 0);
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + aside[k].namestr).width);
                }
                rectwidth += 100;
                drawrect('arect' + i.toString(), 920 + rectwidth / 2, curY, rectwidth, rectheight);
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < aside.length;k++) {
                     if (aside[k].type == "进球") {
                        drawicon('aG' + i.toString() + k.toString(), 'G', 920 + 20, ey);
                    } else if (aside[k].type == "点球") {
                        drawicon('aPG' + i.toString() + k.toString(), 'PG', 920 + 20, ey);
                    } else if (aside[k].type == "点球罚失") {
                        drawicon('aPM' + i.toString() + k.toString(), 'PM', 920 + 20, ey);
                    } else if (aside[k].type == "乌龙球") {
                        drawicon('aOG' + i.toString() + k.toString(), 'OG', 920 + 20, ey);
                    } else if (aside[k].type == "换下") {
                        drawicon('aSO' + i.toString() + k.toString(), 'SO', 920 + 20, ey);
                    } else if (aside[k].type == "换上") {
                        drawicon('aSI' + i.toString() + k.toString(), 'SI', 920 + 20, ey);
                    } else if (aside[k].type == "黄牌") {
                        var y2c = false;
                        for (var l = 0;l < i;l++) {
                            for (var il = 0;il < revents[l].length;il++) {
                                if (revents[l][il].type == "黄牌" && revents[l][il].namestr == aside[k].namestr) {
                                    y2c = true;
                                    break;
                                }
                            }
                        }
                        if (y2c) {
                            drawicon('aY2C' + i.toString() + k.toString(), 'Y2C', 920 + 20, ey);
                        } else {
                            drawicon('aYC' + i.toString() + k.toString(), 'YC', 920 + 20, ey);
                        }
                    } else if (aside[k].type == "红牌") {
                        drawicon('aRC' + i.toString() + k.toString(), 'RC', 920 + 20, ey);
                    }
                    drawplayer(aside[k].team + "~" + aside[k].kitnum + "~" + aside[k].name + "~" + aside[k].type + "~" + aside[k].time + "~" + aside[k].stptime + "~" + aside[k].eventid, aside[k].namestr, 920 + 70, ey);
                    ey += 50;
                }
            }
            curY += halfh + 40;
        }
        if (((stage && '<?=$penalty?>'.match("淘汰赛")) || '<?=$penalty?>'.match("总是")) && hg == ag) { //点球决胜
            var phg = hg;
            var pag = ag;
            for (var i = 0;i < revents.length;i++) {
            var hside = [];
            var aside = [];
            for (var j = 0;j < revents[i].length;j++) {
                if (revents[i][j].team == homename && revents[i][j].type.match(/点球决胜/)) {
                    hside.push(revents[i][j]);
                }
                else if (revents[i][j].team == awayname && revents[i][j].type.match(/点球决胜/)) {
                    aside.push(revents[i][j]);
                }
            }
            var halfh = Math.max(hside.length, aside.length) * 45 / 2 + 10;
            if (hside.length == 0 && aside.length == 0)
                halfh = -20;
            curY += halfh;
            if (hside.length != 0) {
                drawline('phtimeline', 680, 800, curY);
                drawtime('phtime', hside[0].timestr, 740, curY - 20);
                var rectwidth = 0;
                var rectheight = hside.length * 50 + 10;
                for (var k = 0;k < hside.length;k++) {
                    drawname('rgba(0, 0, 0, 0)', 'measure' + hside[k].namestr, hside[k].namestr, 800, 0);
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + hside[k].namestr).width);
                }
                rectwidth += 100;
                drawrect('phrect', 680 - rectwidth / 2, curY, rectwidth, rectheight);
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < hside.length;k++) {
                    if (hside[k].type == "点球决胜罚进") {
                        phg++;
                        drawicon('phPG' + i.toString() + k.toString(), 'PG', 680 - rectwidth + 20, ey);
                    } else if (hside[k].type == "点球决胜罚失") {
                        drawicon('phPM' + i.toString() + k.toString(), 'PM', 680 - rectwidth + 20, ey);

                    }                    
                    drawplayer(hside[k].team + "~" + hside[k].kitnum + "~" + hside[k].name + "~" + hside[k].type + "~" + hside[k].time + "~" + hside[k].stptime + "~" + i.toString() + "~" + k.toString(), hside[k].namestr, 680 - rectwidth + 70, ey);
                    ey += 50;
                }

            } 
            if (aside.length != 0) {
                drawline('patimeline', 800, 920, curY);
                drawtime('patime', aside[0].timestr, 860, curY - 20);
                var rectwidth = 0;
                var rectheight = aside.length * 50 + 10;
                for (var k = 0;k < aside.length;k++) {
                    drawname('rgba(0, 0, 0, 0)', 'measure' + aside[k].namestr, aside[k].namestr, 800, 0);
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + aside[k].namestr).width);
                }
                rectwidth += 100;
                drawrect('parect', 920 + rectwidth / 2, curY, rectwidth, rectheight);
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < aside.length;k++) {
                    if (aside[k].type == "点球决胜罚进") {
                        pag++;
                        drawicon('paPG' + i.toString() + k.toString(), 'PG', 920 + 20, ey);
                    } else if (aside[k].type == "点球决胜罚失") {
                        drawicon('paPM' + i.toString() + k.toString(), 'PM', 920 + 20, ey);
                    }                     
                    drawplayer(aside[k].team + "~" + aside[k].kitnum + "~" + aside[k].name + "~" + aside[k].type + "~" + aside[k].time + "~" + aside[k].stptime + "~" + i.toString() + "~" + k.toString(), aside[k].namestr, 920 + 70, ey);
                    ey += 50;
                }
            }
            curY += halfh + 40;
        }
            var pscore = "(" + phg.toString() + ":" + pag.toString() + ")";
            drawcenter('Trebuchet MS', 60, 'bold', 'pscore', pscore, 120);
        }
        
        var score = hg.toString() + ":" + ag.toString();
        drawcenter('Trebuchet MS', 60, 'bold', 'score', score, 60);
        $('canvas').drawLine({
            layer: true,
            name: 'timeline',
            strokeStyle: '#000',
            strokeWidth: 6,
            rounded: true,
            x1: 800, y1: startY,
            x2: 800, y2: curY,
        });
        $('canvas').drawArc({
            layer: true,
            name: 'endpoint',
            fillStyle: 'black',
            x: 800, y: curY,
            radius: 8
        });
        curY += 20;
        $('canvas').drawImage({
            layer: true,
            name: 'endicon',
            source: 'ReportElements/END.png',
            x: 800, y: curY,
        });
        curY += 40;
        $('canvas').drawImage({
            layer: true,
            name: 'goalimage',
            source: 'ReportElements/icon_goal.png',
            x: 600, y: curY
        });
        $('canvas').drawImage({
            layer: true,
            name: 'pgimage',
            source: 'ReportElements/icon_point.png',
            x: 700, y: curY
        });
        $('canvas').drawImage({
            layer: true,
            name: 'pmimage',
            source: 'ReportElements/icon_point_1.png',
            x: 800, y: curY
        });
        $('canvas').drawImage({
            layer: true,
            name: 'ogimage',
            source: 'ReportElements/icon_w.png',
            x: 950, y: curY
        });
        $('canvas').drawText({
            layer: true,
            name: 'goaltext',
            fillStyle: '#111',
            x: 645, y: curY,
            fontSize: 24,
            fontFamily: 'simHei',
            text: "进球"
        })
        $('canvas').drawText({
            layer: true,
            name: 'pgtext',
            fillStyle: '#111',
            x: 745, y: curY,
            fontSize: 24,
            fontFamily: 'simHei',
            text: "点球"
        })
        $('canvas').drawText({
            layer: true,
            name: 'pmext',
            fillStyle: '#111',
            x: 870, y: curY,
            fontSize: 24,
            fontFamily: 'simHei',
            text: "点球罚失"
        })
        $('canvas').drawText({
            layer: true,
            name: 'ogtext',
            fillStyle: '#111',
            x: 1005, y: curY,
            fontSize: 24,
            fontFamily: 'simHei',
            text: "乌龙球"
        })

        window.setTimeout(function() {
            var pngdown = $('canvas').getCanvasImage('png');
            var pngbtn = document.getElementById("pngdown");
            pngbtn.href = pngdown;
            pngbtn.download = subtitle + HomeName + score + AwayName +".png";
        },500);
            //callback(subtitle, HomeName, score, AwayName);
        //var che = check(hflist, aflist, hevent, aevent, leastnum);
        });
}
showreport();
function check(hflist, aflist, hevent, aevent, habandon, aabandon, leastnum) {
    class Player {
        constructor(team, num, name, onpitch) {
            this.team = team;
            this.kitnum = num;
            this.name = name;
            this.onpitch = onpitch;
            this.yc = 0;
            this.rc = 0;
        } 
    }
    console.log(hflist,aflist,hevent,aevent);
    var right = true;
    var hplayer = [];
    var aplayer = [];
    var herrline = "";
    var aerrline = "";
    if (habandon || aabandon) {
        return {
            right:true,
            herr:herrline,
            aerr:aerrline
        };
    }
    if (hflist.length < leastnum) {
        right = false;
        herrline += '首发不足' + leastnum.toString() + '人！' + '\n';
    }
    if (aflist.length < leastnum) {
        right = false;
        aerrline += '首发不足' + leastnum.toString() + '人！' + '\n';
    }
    for (var i = 0;i < hflist.length;i++) { //存入主队首发
        hplayer.push(new Player(hflist[i].team, hflist[i].kitnum, hflist[i].name, true));
    }
    for (var i = 0;i < aflist.length;i++) { //存入客队首发
        aplayer.push(new Player(aflist[i].team, aflist[i].kitnum, aflist[i].name, true));
    }
    for (var i = 0;i < hevent.length;i++) { //遍历主队事件
        var hfound = false;
        for (var j = 0;j < hplayer.length;j++) {
            if (hplayer[j].kitnum == hevent[i].kitnum && hplayer[j].name == hevent[i].name) {
                hfound = true;
                if (hevent[i].type == "进球" || hevent[i].type == "点球" || hevent[i].type == "乌龙球" || hevent[i].type == "点球罚失") {
                    if (!hplayer[j].onpitch){
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟' + hevent[i].type +'时不是场上球员！' + '\n';
                        right = false;
                    }
                }
                else if (hevent[i].type == "换下") {
                    if (hplayer[j].onpitch) {
                        hplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟被' + hevent[i].type +'时不是场上球员！' + '\n';
                    }
                }
                else if (hevent[i].type == "换上") {
                    if (!hplayer[j].onpitch) {
                        hplayer[j].onpitch = true;
                    }
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟被' + hevent[i].type +'时是场上球员！' + '\n';
                    }
                }
                else if (hevent[i].type == "黄牌") {
                    if (hplayer[j].yc < 2 && hplayer[j].rc == 0) {
                        hplayer[j].yc++;
                        if (hplayer[j].yc == 2)
                            hplayer[j].onpitch = false;
                    } 
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！' + '\n';
                    }
                }
                else if (hevent[i].type == "红牌") {
                    if (hplayer[j].yc < 2 && hplayer[j].rc == 0) {
                        hplayer[j].rc++;
                        hplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！' + '\n';
                    }
                }
            }
        }
        if (!hfound) {
            hplayer.push(new Player(hevent[i].team, hevent[i].kitnum, hevent[i].name, false));
            if (hevent[i].type == "进球" || hevent[i].type == "点球" || hevent[i].type == "乌龙球" || hevent[i].type == "点球罚失") {
                right = false;
                herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟' + hevent[i].type +'时不是场上球员！' + '\n';
            }
            else if (hevent[i].type == "换下") {
                right = false;
                herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟被' + hevent[i].type +'时不是场上球员！' + '\n';
            }
            else if (hevent[i].type == "换上") {
                hplayer[j].onpitch = true;
            }
            else if (hevent[i].type == "黄牌") {
                if (hplayer[j].yc < 2 && hplayer[j].rc == 0) {
                    hplayer[j].yc++;
                    if (hplayer[j].yc == 2)
                        hplayer[j].onpitch = false;
                } 
                else {
                    right = false;
                    herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！' + '\n';
                }
            }
            else if (hevent[i].type == "红牌") {
                if (hplayer[j].yc < 2 && hplayer[j].rc == 0) {
                    hplayer[j].rc = 1;
                    hplayer[j].onpitch = false;
                }
                else {
                    right = false;
                    herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！' + '\n';
                }
            }  
        }
        //console.log(right,herrline);
    } 
    for (var i = 0;i < aevent.length;i++) { //遍历客队事件
        var afound = false;
        for (var j = 0;j < aplayer.length;j++) {
            if (aplayer[j].kitnum == aevent[i].kitnum && aplayer[j].name == aevent[i].name) {
                afound = true;
                if (aevent[i].type == "进球" || aevent[i].type == "点球" || aevent[i].type == "乌龙球" || aevent[i].type == "点球罚失") {
                    if (!aplayer[j].onpitch) {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟' + aevent[i].type +'时不是场上球员！' + '\n';
                    }
                }
                else if (aevent[i].type == "换下") {
                    if (aplayer[j].onpitch) {
                        aplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟被' + aevent[i].type +'时不是场上球员！' + '\n';
                    }
                }
                else if (aevent[i].type == "换上") {
                    if (!aplayer[j].onpitch) {
                        aplayer[j].onpitch = true;
                    }
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟被' + aevent[i].type +'时是场上球员！' + '\n';
                    }
                }
                else if (aevent[i].type == "黄牌") {
                    if (aplayer[j].yc < 2 && aplayer[j].rc == 0) {
                        aplayer[j].yc++;
                        if (aplayer[j].yc == 2)
                            aplayer[j].onpitch = false;
                    } 
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！' + '\n';
                    }
                }
                else if (aevent[i].type == "红牌") {
                    if (aplayer[j].yc < 2 && aplayer[j].rc == 0) {
                        aplayer[j].rc++;
                        aplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！' + '\n';
                    }
                }
                
            }
        }
        if (!afound) {
            aplayer.push(new Player(aevent[i].team, aevent[i].kitnum, aevent[i].name, false));
            if (aevent[i].type == "进球" || aevent[i].type == "点球" || aevent[i].type == "乌龙球" || aevent[i].type == "点球罚失") {
                right = false;
                aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟' + aevent[i].type +'时不是场上球员！' + '\n';
            }
            else if (aevent[i].type == "换下") {
                right = false;
                aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟被' + aevent[i].type +'时不是场上球员！' + '\n';
            }
            else if (aevent[i].type == "换上") {
                aplayer[j].onpitch = true;
            }
            else if (aevent[i].type == "黄牌") {
                if (aplayer[j].yc < 2 && aplayer[j].rc == 0) {
                    aplayer[j].yc++;
                    if (aplayer[j].yc == 2)
                        aplayer[j].onpitch = false;
                } 
                else {
                    right = false;
                    aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！' + '\n';
                }
            }
            else if (aevent[i].type == "红牌") {
                if (aplayer[j].yc < 2 && aplayer[j].rc == 0) {
                    aplayer[j].rc = 1;
                    aplayer[j].onpitch = false;
                }
                else {
                    right = false;
                    aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！' + '\n';
                }
            }  
        }
        //console.log(right,aerrline);
    }
    return {
        right:right,
        herr:herrline,
        aerr:aerrline
    };
}
</script>
<?php
$conn->close();
?>
        </div>
     </div>
  </body>
</html>

