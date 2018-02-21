<!doctype html>
<html lang="ch">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">

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
                    <input type="text" class="form-control timeinput" id="name" placeholder="请输入时间，伤停补时用+号表示。多个事件用空格隔开。">
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
    <div class="container">
        <div class="row" id="match">
        
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <input id="validcheck" type="checkbox" onclick='onvalid()' />有效
<br>
<?php
require 'TeamDict.php';
$id = $_GET["id"];
$servername = "localhost";
$username = "root";
$password = "961014";
$dbname = $_GET['Match'];
echo "<a href='schedule.php?Match=$dbname'>返回</a>";
$elifile = fopen($dbname.'.json','r') or die("Unable to open file!");
$eliinfo = json_decode(fgets($elifile));
$eliinfo = $eliinfo[1];
fclose($elifile);
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_query($conn,'set names utf8');
if ($conn->connect_error) {
    die("Connection failed:".$conn->connect_error);
}
$sql = "SELECT * FROM Match$id";
$result = $conn->query($sql);
if (!$result) {
    $sql0 = "CREATE TABLE Match$id (
      `Team` varchar(255) NOT NULL,
      `KitNumber` int(11) DEFAULT NULL,
      `Name` varchar(255) NOT NULL,
      `ExtraInfo` varchar(255) DEFAULT NULL,
      `EventType` varchar(255) NOT NULL,
      `EventTime` int(11) DEFAULT NULL,
      `StoppageTime` int(11) DEFAULT NULL
    )";
    if ($conn->query($sql0) === TRUE) {
        echo "Table$id created"."<br>";
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
}
if ($stage != 'Group') {
    //print_r($eliinfo->{$id});
    $hometeam = $eliinfo->{$id}->hometeam;
    $awayteam = $eliinfo->{$id}->awayteam;
}
if (preg_match('/^MA.+/', $dbname)) {
    $hometeam = $dict[$hometeam];
    $awayteam = $dict[$awayteam];
}
$sql = "SELECT KitNumber,Name,ExtraInfo FROM Players WHERE Team = '".$hometeam."' ORDER BY KitNumber";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $homeplayers[] = Array("Name"=>$row['Name'], "KitNumber"=>$row['KitNumber'], "ExtraInfo"=>$row['ExtraInfo']);
}
$sql = "SELECT KitNumber,Name,ExtraInfo FROM Players WHERE Team = '".$awayteam."' ORDER BY KitNumber";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $awayplayers[] = Array("Name"=>$row['Name'], "KitNumber"=>$row['KitNumber'], "ExtraInfo"=>$row['ExtraInfo']);
}

echo "<div class='hometable col-lg-6 col-md-6'><table class='table table-bordered table-hover table-condensed'><caption>".$hometeam."\t<input type='checkbox' class='abandon' name='HomeAbandon' id='H~Abandon'>弃赛</caption><thead><tr><th>#</th><th>姓名</th><th>首发</th><th>换人</th><th>进球</th><th>红黄牌</th></tr></thead><tbody>";
//echo $hometeam."首发:<input type='checkbox' class='abandon' name='HomeAbandon' id='H~Abandon'>弃赛<br>";
    for($i = 0;$i<count($homeplayers);$i++) {
        $num = $homeplayers[$i]['KitNumber'];
        echo "<tr class='row".$num."'><td>".$num."</td><td>".$homeplayers[$i]['Name']."</td><td>";
        echo "<input type='checkbox' name='Homecheck' id='H$num' value='$num' onclick='homecheck($num)'>";
        //echo $num."-".$homeplayers[$i]['Name'];
        echo "</td><td class='chg'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$homeplayers[$i]['Name']."~chg'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='goal'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$homeplayers[$i]['Name']."~goal'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='ryc'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$homeplayers[$i]['Name']."~ryc'><span class='glyphicon glyphicon-plus'></span></button> </td></tr>";
    }
echo "</tbody></table></div>";
echo "<div class='awaytable col-lg-6 col-md-6'><table class='table table-bordered table-hover table-condensed'><caption>".$awayteam."\t<input type='checkbox' class='abandon' name='AwayAbandon' id='A~Abandon'>弃赛</caption><thead><tr><th>#</th><th>姓名</th><th>首发</th><th>换人</th><th>进球</th><th>红黄牌</th></tr></thead><tbody>";
//echo "<br>".$awayteam."首发:<input type='checkbox' class='abandon' name='AwayAbandon' id='A~Abandon'>弃赛<br>";
    for($i = 0;$i<count($awayplayers);$i++) {
        $num = $awayplayers[$i]['KitNumber'];
        echo "<tr class='row".$num."'><td>".$num."</td><td>".$awayplayers[$i]['Name']."</td><td>";
        echo "<input type='checkbox' name='Awaycheck' id='A$num' value='$num' onclick='awaycheck($num)'>";
        //echo $num."-".$awayplayers[$i]['Name'];
        echo "</td><td class='chg'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$awayplayers[$i]['Name']."~chg'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='goal'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$awayplayers[$i]['Name']."~goal'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='ryc'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$awayplayers[$i]['Name']."~ryc'><span class='glyphicon glyphicon-plus'></span></button> </td></tr>";
    }
echo "</tbody></table></div>";
echo "<br>";
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
console.log(stage);
function onvalid() {
    var validcheck = $("#validcheck");
    //console.log(validcheck[0].checked);
    if (validcheck[0].checked) {
        console.log('true');
        validbool = 1;
    } else {
        console.log('false');
        validbool = 0;
    }
    $.get('checked.php',{
        dbname: '<?=$dbname ?>',
        MatchID: '<?=$id ?>',
        Valid: validbool
    },function(data,state) {
        console.log(data);
    })
}
$('#addModal').modal({
    keyboard: false,
	show: false
});
$('.addinfo').click(function() {
    validbool = 0;
    var id = $(this).attr('id');
    var pid = id.split('~');
    console.log(pid);
    if (pid[0] == 'H')
        var t = '<?=$hometeam?>';
    else if (pid[0] == 'A')
        var t = '<?=$awayteam?>';
    var title = t + ' ' + pid[1] + ' ' + pid[2];
    if (pid[3] == 'chg') {
        $('.chgradio').show();
        $('.goalradio').hide();
        $('.rycradio').hide();
        title += ' 换人';
    } else if (pid[3] == 'goal') {
        $('.chgradio').hide();
        $('.goalradio').show();
        $('.rycradio').hide();
        title += ' 进球';
    } else if (pid[3] == 'ryc') {
        $('.chgradio').hide();
        $('.goalradio').hide();
        $('.rycradio').show();
        title += ' 红黄牌';
    }
    $('.modal-title').text(title);     
    $('.timeinput').val("");
    $('#addModal').modal('show');
})
$('.btnsubmit').click(function() {
    var title = $('.modal-title').text();
    var pt = title.split(/\s+/);
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
function homecheck(id) {
    var Hnum = $("input[name=Homecheck]");
    var n = 0;
    for (k in Hnum) {
        if (Hnum[k].checked)
            n++;
    }
    var Hinst = document.getElementById('H'+id); 
    if (Hinst.checked && n > 11) {
        Hinst.checked = false;
        alert('more than 11');
    }
    else {
        validbool = 0;
        $.get('checked.php', {
            dbname: '<?=$dbname ?>',
            MatchID: '<?=$id ?>',
            Valid: validbool
        }, function(data, state) {
            console.log(data);
            if (Hinst.checked) {
                $.get('additem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: '<?=$hometeam ?>',
                    KitNumber: id,
                    Name: null,
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
                    Team: '<?=$hometeam ?>',
                    KitNumber: id,
                    Name: null,
                    Type: '首发',
                    Time: 0,
                    StoppageTime: 0
                }, function(data, state) {
                    console.log(data);
                    showreport();
                })
            }
        })
    }
}
function awaycheck(id) {
    var Anum = $("input[name=Awaycheck]");
    var n = 0;
    for (k in Anum) {
        if (Anum[k].checked)
            n++;
    }
    var Ainst = document.getElementById('A'+id); 
    if (Ainst.checked && n > 11) {
        Ainst.checked = false;
        alert('more than 11');
    }
    else {
        validbool = 0;
        $.get('checked.php', {
            dbname: '<?=$dbname ?>',
            MatchID: '<?=$id ?>',
            Valid: validbool
        }, function(data, state) {
            console.log(data);
            if (Ainst.checked) {
                $.get('additem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: '<?=$awayteam ?>',
                    KitNumber: id,
                    Name: null,
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
                    Team: '<?=$awayteam ?>',
                    KitNumber: id,
                    Name: null,
                    Type: '首发',
                    Time: 0,
                    StoppageTime: 0
                }, function(data, state) {
                    console.log(data);
                    showreport();
                })
            }
        })
    }

}
//function HSubmit() {
//    validbool = 0;
//    $.get('checked.php',{
//        dbname: '<?=$dbname ?>',
//        MatchID: '<?=$id ?>',
//        Valid: validbool
//    },function(data,state) {
//        console.log(data);
//        showreport();
//        var Hnum = $("input[name=Homecheck]");
//        var h_check = [];
//        for (var k in Hnum) {
//            if (Hnum[k].checked)
//                h_check.push(Hnum[k].value);
//        }
//        //alert(h_check);
//        $.get('delitem.php',{
//                dbname: '<?=$dbname ?>',
//                MatchID: "<?='Match'.$id ?>",
//                Team: '<?=$hometeam ?>',
//                KitNumber: null,
//                Name: null,
//                Type: '首发',
//                Time: null,
//                StoppageTime:null
//        },function (data,state) {
//            //alert(data);
//            for (var item in h_check) {
//                $.get('additem.php',{
//                    dbname: '<?=$dbname ?>',
//                    MatchID: "<?='Match'.$id ?>",
//                    Team: '<?=$hometeam ?>',
//                    KitNumber: h_check[item],
//                    Name: null,
//                    Type: '首发',
//                    Time: 0,
//                    StoppageTime: 0
//                },function (data,state) {
//                    console.log(data);
//                    console.log(state);
//                    showreport();
//                });
//            }
//        });
//
//    })
//        
//}
//function ASubmit() {
//    validbool = 0;
//    $.get('checked.php',{
//        dbname: '<?=$dbname ?>',
//        MatchID: '<?=$id ?>',
//        Valid: validbool
//    },function(data,state) {
//        console.log(data);
//        showreport();
//        var Anum = $("input[name=Awaycheck]");
//        var a_check = [];
//        for (k in Anum) {
//            if (Anum[k].checked)
//                a_check.push(Anum[k].value);
//        }
//        //alert(a_check);
//        $.get('delitem.php',{
//                dbname: '<?=$dbname ?>',
//                MatchID: "<?='Match'.$id ?>",
//                Team: '<?=$awayteam ?>',
//                KitNumber: null,
//                Name: null,
//                Type: '首发',
//                Time: null,
//                StoppageTime:null
//
//        },function(data,state) {
//            //alert(data);
//            for (var item in a_check) {
//                $.get('additem.php',{
//                    dbname: '<?=$dbname ?>',
//                    MatchID: "<?='Match'.$id ?>",
//                    Team: '<?=$awayteam ?>',
//                    KitNumber: a_check[item],
//                    Name: null,
//                    Type: '首发',
//                    Time: 0,
//                    StoppageTime: 0
//                },function (data,state) {
//                    //alert(data);
//                    //alert(state);
//                    showreport();
//                });
//            }
//        });
//
//    })
//        
//}
</script>
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
            <option> 黄牌
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
<script>
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
        var time = 110;
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
        //if (type == "进球") {
        //    type = 'GOAL';
        //} else if (type == "换下") {
        //    type = 'OUT';
        //} else if (type == "换上") {
        //    type = 'IN';
        //} else if (type == "黄牌") {
        //    type = 'YELLOW';
        //} else if (type == "红牌") {
        //    type = 'RED';
        //} else if (type == "点球") {
        //    type = 'PENALTY';
        //} else if (type == "乌龙球") {
        //    type = 'ONWGOAL';
        //} else if (type == "点球罚失") {
        //    type = 'PMISSS';
        //}
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

function showreport() {
    var validcheck = document.getElementById('validcheck'); 
    console.log('valid:',validbool);
    if (validbool == 1) {
        validcheck.checked = true;
    } else {
        validcheck.checked = false;
    }
    $('.tbl').remove();
    $(".eventdisplay").remove();
    $.get('showreport.php',{
        dbname: '<?=$dbname ?>',
        MatchID: "<?='Match'.$id ?>"
    }, function(data,state) {
        info = JSON.parse(data);
        console.log(info);
        var hg = 0;
        var ag = 0;
        var homename = "<?=$hometeam ?>";
        var awayname = "<?=$awayteam ?>";
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
        var hflist = [];
        var aflist = [];
        var hevent = [];
        var aevent = [];
        for (var i = 0;i < info.length;i++) {
            var e = JSON.parse(info[i]);
            if (e.team == homename) {
                if (e.type == "首发") {
                    hflist.push(e);
                } else if (e.type == "弃赛") {
                    var Hhab = document.getElementById('H~Abandon');
                    Hhab.checked = true;
                } else {
                    hevent.push(e);
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
                    var Haab = document.getElementById('A~Abandon');
                    Haab.checked = true;
                } else {
                    aevent.push(e);
                    if (e.type == "进球" || e.type == "点球") {
                        ag++;
                    }
                    if (e.type == "乌龙球") {
                        hg++;
                    }
                }
            }
        }
        if (stage != 'Group' && hg == ag) {
            $(".penalty").show();
        } else {
            $(".penalty").hide();
        }
        //hevent.reverse();
        //aevent.reverse();
        //console.log(hflist,aflist,hevent,aevent);
        for (var i = 0;i < hflist.length;i++) {
            var Hinst = document.getElementById('H'+hflist[i].kitnum); 
            Hinst.checked = true;
            //if blank extrainfo
            var blk = /^\s+/;
            if (!blk.test(hflist[i].extrainfo) && hflist[i].extrainfo != "") {
                var txt = hflist[i].kitnum + "-" + hflist[i].name + "(" + hflist[i].extrainfo + ")";
            }
            else {
                var txt = hflist[i].kitnum + "-" + hflist[i].name;
            }
            hflist[i].namestr = txt;
            tb = " <input type='button' class='delplayer btn btn-sm btn-default' id='"+hflist[i].team+"\."+hflist[i].kitnum.toString()+"\."+hflist[i].name+"' value='delete'>";
            var cont = $("<p></p>").text(txt); 
            cont.append(tb);
            //homefirst.append(cont);
        } 
        for (var i = 0;i < aflist.length;i++) {
            var Ainst = document.getElementById('A'+aflist[i].kitnum); 
            Ainst.checked = true;
            //if blank extrainfo
            var blk = /^\s+/;
            if (!blk.test(aflist[i].extrainfo) && aflist[i].extrainfo != "") {
                var txt = aflist[i].kitnum + "-" + aflist[i].name + "(" + aflist[i].extrainfo + ")";
            }
            else {
                var txt = aflist[i].kitnum + "-" + aflist[i].name;
            }
            aflist[i].namestr = txt;
            tb = " <input type='button' class='delplayer btn btn-sm btn-default' id='"+aflist[i].team+"\."+aflist[i].kitnum.toString()+"\."+aflist[i].name+"' value='delete'>";
            var cont = $("<p></p>").text(txt); 
            cont.append(tb);
            //awayfirst.append(cont);
        }
        $(".delplayer").click(function() {
            var id = $(this).attr('id');
            var id = id.split(".");
            validbool = 0;
            $.get('checked.php',{
                dbname: '<?=$dbname ?>',
                MatchID: '<?=$id ?>',
                Valid: validbool
            },function(data,state) {
                console.log(data);
                //console.log(id);
                $.get('delitem.php', {
                    dbname: '<?=$dbname ?>',
                    MatchID: "<?='Match'.$id ?>",
                    Team: id[0],
                    KitNumber: parseInt(id[1]),
                    Name: id[2],
                    Type: '首发',
                    Time: null,
                    StoppageTime:null

                }, function(data,state) {
                    console.log(data,state);
                    showreport();
                });

            })
                    });
        for (var i = 0;i < hevent.length;i++) {
            var blk = /^\s+/;
            if (!blk.test(hevent[i].extrainfo) && hevent[i].extrainfo != "") {
                var namestr = hevent[i].kitnum + "-" + hevent[i].name + "(" + hevent[i].extrainfo + ")";
            }
            else {
                var namestr = hevent[i].kitnum + "-" + hevent[i].name;
            }
            hevent[i].namestr = namestr;
            if (hevent[i].stptime == 0) 
                var timestr = hevent[i].time.toString();
            else 
                var timestr = hevent[i].time.toString() + "+" + hevent[i].stptime.toString();
            hevent[i].timestr = timestr;
            var tb = " <a class='delevent' id='"+hevent[i].team+"\."+hevent[i].kitnum.toString()+"\."+hevent[i].name+"\."+hevent[i].type+"\."+hevent[i].time.toString()+"\."+hevent[i].stptime.toString()+"'><span class='glyphicon glyphicon-remove'></span></a>" ;
            if (hevent[i].type == '进球' || hevent[i].type == '点球' || hevent[i].type == '乌龙球' || hevent[i].type == '点球罚失' ) {
                var Hcell = $('.hometable .row' + hevent[i].kitnum.toString() + ' .goal');
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
                var Hcell = $('.hometable .row' + hevent[i].kitnum.toString() + ' .chg');
                if (hevent[i].type == '换下') {
                    var lbl = "<span class='tbl label label-danger'><span class='glyphicon glyphicon-arrow-down'></span>"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '换上') {
                    var lbl = "<span class='tbl label label-success'><span class='glyphicon glyphicon-arrow-up'></span>"+hevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (hevent[i].type == '黄牌' || hevent[i].type == '红牌') {
                var Hcell = $('.hometable .row' + hevent[i].kitnum.toString() + ' .ryc');
                if (hevent[i].type == '黄牌') {
                    var lbl = "<span class='tbl label label-warning'>"+hevent[i].timestr+tb+"</span> ";
                }
                if (hevent[i].type == '红牌') {
                    var lbl = "<span class='tbl label label-danger'>"+hevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (hevent[i].type == '点球决胜罚进' || hevent[i].type == '点球决胜罚失') {
                var txt = hevent[i].type + "\t" + timestr + "\'\t" + namestr; 
                tb = " <input type='button' class='delevent btn btn-sm btn-default' id='"+hevent[i].team+"\."+hevent[i].kitnum.toString()+"\."+hevent[i].name+"\."+hevent[i].type+"\."+hevent[i].time.toString()+"\."+hevent[i].stptime.toString()+"' value='delete'>";
                var cont = $("<p class='eventdisplay'></p>").text(txt); 
                cont.append(tb);
                homeevent.append(cont);

            }
        }
        for (var i = 0;i < aevent.length;i++) {
            var blk = /^\s+/;
            if (!blk.test(aevent[i].extrainfo) && aevent[i].extrainfo != "") {
                var namestr = aevent[i].kitnum + "-" + aevent[i].name + "(" + aevent[i].extrainfo + ")";
            }
            else {
                var namestr = aevent[i].kitnum + "-" + aevent[i].name;
            }
            aevent[i].namestr = namestr;
            if (aevent[i].stptime == 0) 
                var timestr = aevent[i].time.toString();
            else 
                var timestr = aevent[i].time.toString() + "+" + aevent[i].stptime.toString();
            aevent[i].timestr = timestr;
            var tb = " <a class='delevent' id='"+aevent[i].team+"\."+aevent[i].kitnum.toString()+"\."+aevent[i].name+"\."+aevent[i].type+"\."+aevent[i].time.toString()+"\."+aevent[i].stptime.toString()+"'><span class='glyphicon glyphicon-remove'></span></a>" ;
            if (aevent[i].type == '进球' || aevent[i].type == '点球' || aevent[i].type == '乌龙球' || aevent[i].type == '点球罚失' ) {
                var Hcell = $('.awaytable .row' + aevent[i].kitnum.toString() + ' .goal');
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
                var Hcell = $('.awaytable .row' + aevent[i].kitnum.toString() + ' .chg');
                if (aevent[i].type == '换下') {
                    var lbl = "<span class='tbl label label-danger'><span class='glyphicon glyphicon-arrow-down'></span>"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '换上') {
                    var lbl = "<span class='tbl label label-success'><span class='glyphicon glyphicon-arrow-up'></span>"+aevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (aevent[i].type == '黄牌' || aevent[i].type == '红牌') {
                var Hcell = $('.awaytable .row' + aevent[i].kitnum.toString() + ' .ryc');
                if (aevent[i].type == '黄牌') {
                    var lbl = "<span class='tbl label label-warning'>"+aevent[i].timestr+tb+"</span> ";
                }
                if (aevent[i].type == '红牌') {
                    var lbl = "<span class='tbl label label-danger'>"+aevent[i].timestr+tb+"</span> ";
                }
                Hcell.append(lbl);
            }
            if (aevent[i].type == '点球决胜罚进' || aevent[i].type == '点球决胜罚失') {
                var txt = aevent[i].type + "\t" + timestr + "\'\t" + namestr; 
                tb = " <input type='button' class='delevent btn btn-sm btn-default' id='"+aevent[i].team+"\."+aevent[i].kitnum.toString()+"\."+aevent[i].name+"\."+aevent[i].type+"\."+aevent[i].time.toString()+"\."+aevent[i].stptime.toString()+"' value='delete'>";
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
                    Team: id[0],
                    KitNumber: parseInt(id[1]),
                    Name: id[2],
                    Type: id[3],
                    Time: parseInt(id[4]),
                    StoppageTime:parseInt(id[5])

                }, function(data,state) {
                    console.log(data,state);
                    showreport();
                });
            })
            
        });
        var che = check(hflist, aflist,hevent,aevent);
        console.log(che);
    });
}
showreport();
function check(hflist, aflist, hevent, aevent) {
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
    if (hflist.length < 7) {
        right = false;
        herrline += '首发不足7人！';
    }
    if (aflist.length < 7) {
        right = false;
        aerrline += '首发不足7人！';
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
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟' + hevent[i].type +'时不是场上球员！';
                        right = false;
                    }
                }
                else if (hevent[i].type == "换下") {
                    if (hplayer[j].onpitch) {
                        hplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟被' + hevent[i].type +'时不是场上球员！';
                    }
                }
                else if (hevent[i].type == "换上") {
                    if (!hplayer[j].onpitch) {
                        hplayer[j].onpitch = true;
                    }
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟被' + hevent[i].type +'时是场上球员！';
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
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！';
                    }
                }
                else if (hevent[i].type == "红牌") {
                    if (hplayer[j].yc < 2 && hplayer[j].rc == 0) {
                        hplayer[j].rc++;
                        hplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！';
                    }
                }
            }
        }
        if (!hfound) {
            hplayer.push(new Player(hevent[i].team, hevent[i].kitnum, hevent[i].name, false));
            if (hevent[i].type == "进球" || hevent[i].type == "点球" || hevent[i].type == "乌龙球" || hevent[i].type == "点球罚失") {
                right = false;
                herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟' + hevent[i].type +'时不是场上球员！';
            }
            else if (hevent[i].type == "换下") {
                right = false;
                herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟被' + hevent[i].type +'时不是场上球员！';
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
                    herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！';
                }
            }
            else if (hevent[i].type == "红牌") {
                if (hplayer[j].yc < 2 && hplayer[j].rc == 0) {
                    hplayer[j].rc = 1;
                    hplayer[j].onpitch = false;
                }
                else {
                    right = false;
                    herrline += hevent[i].namestr + '在第' + hevent[i].timestr + '分钟得到' + hevent[i].type +'时已经被罚下！';
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
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟' + aevent[i].type +'时不是场上球员！';
                    }
                }
                else if (aevent[i].type == "换下") {
                    if (aplayer[j].onpitch) {
                        aplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟被' + aevent[i].type +'时不是场上球员！';
                    }
                }
                else if (aevent[i].type == "换上") {
                    if (!aplayer[j].onpitch) {
                        aplayer[j].onpitch = true;
                    }
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟被' + aevent[i].type +'时是场上球员！';
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
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！';
                    }
                }
                else if (aevent[i].type == "红牌") {
                    if (aplayer[j].yc < 2 && aplayer[j].rc == 0) {
                        aplayer[j].rc++;
                        aplayer[j].onpitch = false;
                    }
                    else {
                        right = false;
                        aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！';
                    }
                }
            }
        }
        if (!afound) {
            aplayer.push(new Player(aevent[i].team, aevent[i].kitnum, aevent[i].name, false));
            if (aevent[i].type == "进球" || aevent[i].type == "点球" || aevent[i].type == "乌龙球" || aevent[i].type == "点球罚失") {
                right = false;
                aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟' + aevent[i].type +'时不是场上球员！';
            }
            else if (aevent[i].type == "换下") {
                right = false;
                aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟被' + aevent[i].type +'时不是场上球员！';
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
                    aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！';
                }
            }
            else if (aevent[i].type == "红牌") {
                if (aplayer[j].yc < 2 && aplayer[j].rc == 0) {
                    aplayer[j].rc = 1;
                    aplayer[j].onpitch = false;
                }
                else {
                    right = false;
                    aerrline += aevent[i].namestr + '在第' + aevent[i].timestr + '分钟得到' + aevent[i].type +'时已经被罚下！';
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
$conn->close();
?>
        </div>
     </div>
  </body>
</html>

