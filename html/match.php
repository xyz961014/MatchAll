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
                    <input type="text" class="form-control timeinput" id="timeinput" placeholder="请输入时间，伤停补时用+号表示。多个事件用空格隔开。">
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
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/jcanvas/21.0.0/min/jcanvas.min.js"></script>
    <script src="https://cdn.bootcss.com/underscore.js/1.8.3/underscore-min.js"></script>
    <div class="container">
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
echo "<div class='row' id='match'>";
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
}
if ($stage != 'Group') {
    //print_r($eliinfo->{$id});
    $hometeam = $eliinfo->{$id}->hometeam;
    $awayteam = $eliinfo->{$id}->awayteam;
    $subtitle = $stage;
} else {
    $subtitle = "小组赛".$group."组第".$round."轮";
}
if (preg_match('/^MA.+/', $dbname)) {
    $hometeam = $dict[$hometeam];
    $awayteam = $dict[$awayteam];
    if (preg_match('/^MANAN.+/', $dbname)) {
        $subtitle = "马杯男足".$subtitle;
    } 
    if (preg_match('/^MANYU.+/', $dbname)) {
        $subtitle = "马杯女足".$subtitle;
    } 
}
if (preg_match('/^FRESHMANCUP.+/', $dbname)) {
    $subtitle = "新生杯".$subtitle;
} 
if (preg_match('/^NANQI.+/', $dbname)) {
    $subtitle = '"小世界杯"'.$subtitle;
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
        $cname = preg_replace('/\s+/', '', $homeplayers[$i]['Name']);
        $name = preg_replace('/^\s+|\s+$/', '', $homeplayers[$i]['Name']);
        echo "<tr class='row".$num.$cname."'><td>".$num."</td><td>".$name."</td><td>";
        echo "<input class='firstcheck' type='checkbox' name='Homecheck' id='H~$num~".$name."' value='$num'>";
        //echo $num."-".$homeplayers[$i]['Name'];
        echo "</td><td class='chg'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$name."~chg'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='goal'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$name."~goal'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='ryc'><button class='addinfo btn btn-info btn-xs' id='H~".$num."~".$name."~ryc'><span class='glyphicon glyphicon-plus'></span></button> </td></tr>";
    }
echo "</tbody></table></div>";
echo "<div class='awaytable col-lg-6 col-md-6'><table class='table table-bordered table-hover table-condensed'><caption>".$awayteam."\t<input type='checkbox' class='abandon' name='AwayAbandon' id='A~Abandon'>弃赛</caption><thead><tr><th>#</th><th>姓名</th><th>首发</th><th>换人</th><th>进球</th><th>红黄牌</th></tr></thead><tbody>";
//echo "<br>".$awayteam."首发:<input type='checkbox' class='abandon' name='AwayAbandon' id='A~Abandon'>弃赛<br>";
    for($i = 0;$i<count($awayplayers);$i++) {
        $num = $awayplayers[$i]['KitNumber'];
        $cname = preg_replace('/\s+/', '', $awayplayers[$i]['Name']);
        $name = preg_replace('/^\s+|\s+$/', '', $awayplayers[$i]['Name']);
        echo "<tr class='row".$num.$cname."'><td>".$num."</td><td>".$name."</td><td>";
        echo "<input class='firstcheck' type='checkbox' name='Awaycheck' id='A~$num~".$name."' value='$num'>";
        //echo $num."-".$awayplayers[$i]['Name'];
        echo "</td><td class='chg'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$name."~chg'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='goal'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$name."~goal'><span class='glyphicon glyphicon-plus'></span></button> </td><td class='ryc'><button class='addinfo btn btn-info btn-xs' id='A~".$num."~".$name."~ryc'><span class='glyphicon glyphicon-plus'></span></button> </td></tr>";
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
    var Hinst = document.getElementById(id); 
    if (Hinst.checked && (an > 11 || hn > 11)) {
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
            console.log(team,num,name);
            if (Hinst.checked) {
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
        })
    }
})
//function homecheck(id) {
//    var Hnum = $("input[name=Homecheck]");
//    var n = 0;
//    for (k in Hnum) {
//        if (Hnum[k].checked)
//            n++;
//    }
//    var Hinst = document.getElementById('H'+id); 
//    if (Hinst.checked && n > 11) {
//        Hinst.checked = false;
//        alert('more than 11');
//    }
//    else {
//        validbool = 0;
//        $.get('checked.php', {
//            dbname: '<?=$dbname ?>',
//            MatchID: '<?=$id ?>',
//            Valid: validbool
//        }, function(data, state) {
//            console.log(data);
//            if (Hinst.checked) {
//                $.get('additem.php', {
//                    dbname: '<?=$dbname ?>',
//                    MatchID: "<?='Match'.$id ?>",
//                    Team: '<?=$hometeam ?>',
//                    KitNumber: id,
//                    Name: null,
//                    Type: '首发',
//                    Time: 0,
//                    StoppageTime: 0
//            }, function(data, state) {
//                console.log(data);
//                showreport();
//            })
//            }
//            else {
//                 $.get('delitem.php', {
//                    dbname: '<?=$dbname ?>',
//                    MatchID: "<?='Match'.$id ?>",
//                    Team: '<?=$hometeam ?>',
//                    KitNumber: id,
//                    Name: null,
//                    Type: '首发',
//                    Time: 0,
//                    StoppageTime: 0
//                }, function(data, state) {
//                    console.log(data);
//                    showreport();
//                })
//            }
//        })
//    }
//}
//function awaycheck(id) {
//    var Anum = $("input[name=Awaycheck]");
//    var n = 0;
//    for (k in Anum) {
//        if (Anum[k].checked)
//            n++;
//    }
//    var Ainst = document.getElementById('A'+id); 
//    if (Ainst.checked && n > 11) {
//        Ainst.checked = false;
//        alert('more than 11');
//    }
//    else {
//        validbool = 0;
//        $.get('checked.php', {
//            dbname: '<?=$dbname ?>',
//            MatchID: '<?=$id ?>',
//            Valid: validbool
//        }, function(data, state) {
//            console.log(data);
//            if (Ainst.checked) {
//                $.get('additem.php', {
//                    dbname: '<?=$dbname ?>',
//                    MatchID: "<?='Match'.$id ?>",
//                    Team: '<?=$awayteam ?>',
//                    KitNumber: id,
//                    Name: null,
//                    Type: '首发',
//                    Time: 0,
//                    StoppageTime: 0
//            }, function(data, state) {
//                console.log(data);
//                showreport();
//            })
//            }
//            else {
//                 $.get('delitem.php', {
//                    dbname: '<?=$dbname ?>',
//                    MatchID: "<?='Match'.$id ?>",
//                    Team: '<?=$awayteam ?>',
//                    KitNumber: id,
//                    Name: null,
//                    Type: '首发',
//                    Time: 0,
//                    StoppageTime: 0
//                }, function(data, state) {
//                    console.log(data);
//                    showreport();
//                })
//            }
//        })
//    }
//
//}
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
<div class="col-xs-12">
<h5>注：对于同一时间发生的多个事件，双击将其排在最末。</h5>
<a class="btn btn-default" id="pngdown">下载图片</a>
</div>
<canvas id="canvas"></canvas>
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
        var dbstr = '<?=$dbname ?>';
        if (dbstr.match("MANAN"))
            var time = 110;
        else if (dbstr.match("MANYU"))
            var time = 60;
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
        var dbname = '<?=$dbname ?>';
        if (dbname.match(/^MA.+/)) {
            var HomeName = "<?=$dict2[$hometeam] ?>";
            var AwayName = "<?=$dict2[$awayteam] ?>";
        } else {
            var HomeName = homename;
            var AwayName = awayname;
        }
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
        var events = [];
        for (var i = 0;i < info.length;i++) {
            var e = JSON.parse(info[i]);
            if (e.type != "弃赛") {
                var ptn = /(校友|教工|足特)/;
                var name = e.name.replace(/^\s+|\s+$/, '');
                e.name = name;
                if (ptn.test(e.extrainfo)) {
                    var txt = e.kitnum + "-" + e.name + "(" + e.extrainfo + ")";
                }
                else {
                    var txt = e.kitnum + "-" + e.name;
                }
                e.namestr = txt;
            }
            if (e.team == homename) {
                if (e.type == "首发") {
                    hflist.push(e);
                } else if (e.type == "弃赛") {
                    var Hhab = document.getElementById('H~Abandon');
                    Hhab.checked = true;
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
                    var Haab = document.getElementById('A~Abandon');
                    Haab.checked = true;
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
        console.log("events",events);
        if ((stage != 'Group' || '<?=$dbname?>'.match("MANYU")) && hg == ag) {
            $(".penalty").show();
        } else {
            $(".penalty").hide();
        }
        for (var i = 0;i < hflist.length;i++) {
            //if blank extrainfo
            //var ptn = /(校友|教工|足特)/;
            //var name = hflist[i].name.replace(/^\s+|\s+$/, '');
            //hflist[i].name = name;
            //if (ptn.test(hflist[i].extrainfo)) {
            //    var txt = hflist[i].kitnum + "-" + hflist[i].name + "(" + hflist[i].extrainfo + ")";
            //}
            //else {
            //    var txt = hflist[i].kitnum + "-" + hflist[i].name;
            //}
            //hflist[i].namestr = txt;
            var Hinst = document.getElementById('H~'+hflist[i].kitnum+'~'+hflist[i].name); 
            Hinst.checked = true;
            tb = " <input type='button' class='delplayer btn btn-sm btn-default' id='"+hflist[i].team+"\."+hflist[i].kitnum.toString()+"\."+hflist[i].name+"' value='delete'>";
            var cont = $("<p></p>").text(txt); 
            cont.append(tb);
            //homefirst.append(cont);
        } 
        for (var i = 0;i < aflist.length;i++) {
            //if blank extrainfo
            //var ptn = /(校友|教工|足特)/;
            //var name = aflist[i].name.replace(/^\s+|\s+$/, '');
            //aflist[i].name = name;
            //if (ptn.test(aflist[i].extrainfo)) {
            //    var txt = aflist[i].kitnum + "-" + aflist[i].name + "(" + aflist[i].extrainfo + ")";
            //}
            //else {
            //    var txt = aflist[i].kitnum + "-" + aflist[i].name;
            //}
            //aflist[i].namestr = txt;
            var Ainst = document.getElementById('A~'+aflist[i].kitnum+'~'+aflist[i].name); 
            Ainst.checked = true;
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
            //var ptn = /(校友|教工|足特)/;
            //var name = hevent[i].name.replace(/^\s+|\s+$/, '');
            //hevent[i].name = name;
            //if (ptn.test(hevent[i].extrainfo)) {
            //    var namestr = hevent[i].kitnum + "-" + hevent[i].name + "(" + hevent[i].extrainfo + ")";
            //}
            //else {
            //    var namestr = hevent[i].kitnum + "-" + hevent[i].name;
            //}
            //hevent[i].namestr = namestr;
            if (hevent[i].stptime == 0) 
                var timestr = hevent[i].time.toString();
            else 
                var timestr = hevent[i].time.toString() + "+" + hevent[i].stptime.toString();
            hevent[i].timestr = timestr;
            var cname = hevent[i].name.replace(/\s+/, '');
            var tb = " <a class='delevent' id='"+hevent[i].team+"\."+hevent[i].kitnum.toString()+"\."+hevent[i].name+"\."+hevent[i].type+"\."+hevent[i].time.toString()+"\."+hevent[i].stptime.toString()+"'><span class='glyphicon glyphicon-remove'></span></a>" ;
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
                tb = " <input type='button' class='delevent btn btn-sm btn-default' id='"+hevent[i].team+"\."+hevent[i].kitnum.toString()+"\."+hevent[i].name+"\."+hevent[i].type+"\."+hevent[i].time.toString()+"\."+hevent[i].stptime.toString()+"' value='delete'>";
                var cont = $("<p class='eventdisplay'></p>").text(txt); 
                cont.append(tb);
                homeevent.append(cont);

            }
        }
        for (var i = 0;i < aevent.length;i++) {
            //var ptn = /(校友|教工|足特)/;
            //var name = aevent[i].name.replace(/^\s+|\s+$/, '');
            //aevent[i].name = name;
            //if (ptn.test(aevent[i].extrainfo)) {
            //    var namestr = aevent[i].kitnum + "-" + aevent[i].name + "(" + aevent[i].extrainfo + ")";
            //}
            //else {
            //    var namestr = aevent[i].kitnum + "-" + aevent[i].name;
            //}
            //aevent[i].namestr = namestr;
            if (aevent[i].stptime == 0) 
                var timestr = aevent[i].time.toString();
            else 
                var timestr = aevent[i].time.toString() + "+" + aevent[i].stptime.toString();
            aevent[i].timestr = timestr;
            var cname = aevent[i].name.replace(/\s+/, '');
            var tb = " <a class='delevent' id='"+aevent[i].team+"\."+aevent[i].kitnum.toString()+"\."+aevent[i].name+"\."+aevent[i].type+"\."+aevent[i].time.toString()+"\."+aevent[i].stptime.toString()+"'><span class='glyphicon glyphicon-remove'></span></a>" ;
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
        //console.log(hflist,aflist,hevent,aevent);
        //REPORT PNG
        var revents = [];
        var simul = [];
        if (events.length > 0)
            simul = [events[0]];
        for (var i = 1;i < events.length;i++) {
            if (simul.length == 0 || events[i].timestr == simul[0].timestr) {
                simul.push(events[i]);
            } else {
                revents.push(simul);
                simul = [events[i]];
            }
        } 
        revents.push(simul);
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
            var halfh = Math.max(hside.length, aside.length) * 45 / 2 + 10;
            if (hside.length == 0 && aside.length == 0)
                halfh = -20;
            hy += 2 * halfh + 40;
        }
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
        $('canvas').drawText({
            layer: true,
            fillStyle: 'rgba(0, 0, 0, 0)',
            fontFamily: 'WenQuanYi Micro Hei',
            fontSize: 36,
            name: 'measurehfirst',
            text: hfstr,
            fromCenter: false,
            x: 0, y: curY,
            align: 'left',
            maxWidth: 600
        });
        $('canvas').drawText({
            layer: true,
            fillStyle: 'rgba(0, 0, 0, 0)',
            fontFamily: 'WenQuanYi Micro Hei',
            fontSize: 36,
            name: 'measureafirst',
            text: afstr,
            fromCenter: false,
            x: 0, y: curY,
            align: 'left',
            maxWidth: 600
        });
        var hf = Math.max($('canvas').measureText('measurehfirst').height, $('canvas').measureText('measureafirst').height) / 2;
        hy += Math.max(hf, 70) + hf;
        hy += 70;
        if ((stage != 'Group' || '<?=$dbname?>'.match("MANYU")) && hg == ag)  //点球决胜
            hy += 80;
        $('canvas').attr("height", hy);

        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontStyle: 'bold',
            name: 'homename',
            x: 400, y: 50,
            fontSize: 50,
            fontFamily: 'simHei',
            text: HomeName
        });
        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontStyle: 'bold',
            name: 'awayname',
            x: 1200, y: 50,
            fontSize: 50,
            fontFamily: 'simHei',
            text: AwayName
        });
        curY += $('canvas').measureText('homename').height + 10; 
        if ((stage != 'Group' || '<?=$dbname?>'.match("MANYU")) && hg == ag)  //点球决胜
            curY += 80;
        var subtitle = "<?=$subtitle ?>";
        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            name: 'subtitle',
            x: 800, y: curY,
            fontSize: 25,
            fontFamily: 'simHei',
            text: subtitle
        })
        curY += $('canvas').measureText('subtitle').height + 20; 
        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontFamily: 'WenQuanYi Micro Hei',
            fontSize: 36,
            name: 'homefirst',
            text: hfstr,
            fromCenter: false,
            x: 0, y: curY,
            align: 'left',
            maxWidth: 600
        });
        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontFamily: 'WenQuanYi Micro Hei',
            fontSize: 36,
            text: afstr,
            name: 'awayfirst',
            fromCenter: false,
            x: 1000, y: curY,
            align: 'left',
            maxWidth: 600
        });
        var firstheight = Math.max($('canvas').measureText('homefirst').height, $('canvas').measureText('awayfirst').height);
        curY += firstheight / 2 - 10; 
        $('canvas').drawText({
            layer: true,
            name: 'First',
            fillStyle: '#000',
            fontFamily: 'simHei',
            fontSize: 38,
            text: "首发阵容",
            x: 800, y: curY,
        });
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
                    hside.push(revents[i][j]);
                }
                else if (revents[i][j].team == awayname && !revents[i][j].type.match(/点球决胜/)) {
                    aside.push(revents[i][j]);
                }
            }
            var halfh = Math.max(hside.length, aside.length) * 45 / 2 + 10;
            if (hside.length == 0 && aside.length == 0)
                halfh = -20;
            console.log(halfh);
            curY += halfh;
            if (hside.length != 0) {
                $('canvas').drawLine({
                    layer: true,
                    name: 'htimeline' + i.toString(),
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    rounded: true,
                    x1: 700, y1: curY,
                    x2: 800, y2: curY,
                });
                $('canvas').drawText({
                    layer: true,
                    name: 'htime' + i.toString(),
                    fillStyle: '#000',
                    fontFamily: 'Trebuchet MS',
                    fontSize: 36,
                    text: hside[0].timestr,
                    x: 750, y: curY - 20,
                })
                var rectwidth = 0;
                var rectheight = hside.length * 50 + 10;
                for (var k = 0;k < hside.length;k++) {
                    //console.log('KKKK',hside[k].namestr);
                    $('canvas').drawText({
                        layer: true,
                        name: 'measure' + hside[k].namestr,
                        fillStyle: 'rgba(0, 0, 0, 0)',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: hside[k].namestr,
                        x: 800, y: 0,
                    });
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + hside[k].namestr).width);
                }
                rectwidth += 100;
                $('canvas').drawRect({
                    layer: true,
                    name: 'hrect' + i.toString(),
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    x: 700 - rectwidth / 2, y: curY,
                    width: rectwidth,
                    height: rectheight,
                    cornerRadius: 10
                });
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < hside.length;k++) {
                    if (hside[k].type == "进球") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hG' + i.toString() + k.toString(),
                            source: 'ReportElements/G.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });
                    } else if (hside[k].type == "点球") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hPG' + i.toString() + k.toString(),
                            source: 'ReportElements/PG.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });
                    
                    } else if (hside[k].type == "点球罚失") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hPM' + i.toString() + k.toString(),
                            source: 'ReportElements/PM.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (hside[k].type == "乌龙球") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hOG' + i.toString() + k.toString(),
                            source: 'ReportElements/OG.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (hside[k].type == "换下") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hSO' + i.toString() + k.toString(),
                            source: 'ReportElements/SO.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (hside[k].type == "换上") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hSI' + i.toString() + k.toString(),
                            source: 'ReportElements/SI.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });

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
                            $('canvas').drawImage({
                                layer: true,
                                name: 'hY2C' + i.toString() + k.toString(),
                                source: 'ReportElements/Y2C.png',
                                x: 700 - rectwidth + 20, y: ey,
                                fromCenter: false
                            });

                        } else {
                            $('canvas').drawImage({
                                layer: true,
                                name: 'hYC' + i.toString() + k.toString(),
                                source: 'ReportElements/YC.png',
                                x: 700 - rectwidth + 20, y: ey,
                                fromCenter: false
                            });

                        }

                    } else if (hside[k].type == "红牌") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'hRC' + i.toString() + k.toString(),
                            source: 'ReportElements/RC.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });

                    }
                    $('canvas').drawText({
                        layer: true,
                        name: hside[k].team + "~" + hside[k].kitnum + "~" + hside[k].name + "~" + hside[k].type + "~" + hside[k].time + "~" + hside[k].stptime + "~" + i.toString() + "~" + k.toString(),
                        fillStyle: '#000',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: hside[k].namestr,
                        fromCenter: false,
                        x: 700 - rectwidth + 70, y: ey,
                        dblclick: function(layer) {
                            var item = layer.name.split("~");
                            console.log(item);
                            $.get('delitem.php', {
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
                    });
                    ey += 50;
                }

            } 
            if (aside.length != 0) {
                $('canvas').drawLine({
                    layer: true,
                    name: 'atimeline' + i.toString(),
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    rounded: true,
                    x1: 800, y1: curY,
                    x2: 900, y2: curY,
                });
                $('canvas').drawText({
                    layer: true,
                    name: 'atime' + i.toString(),
                    fillStyle: '#000',
                    fontFamily: 'Trebuchet MS',
                    fontSize: 36,
                    text: aside[0].timestr,
                    x: 850, y: curY - 20,
                })
                var rectwidth = 0;
                var rectheight = aside.length * 50 + 10;
                for (var k = 0;k < aside.length;k++) {
                    $('canvas').drawText({
                        layer: true,
                        name: 'measure' + aside[k].namestr,
                        fillStyle: 'rgba(0, 0, 0, 0)',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: aside[k].namestr,
                        x: 800, y: 0,
                    });
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + aside[k].namestr).width);
                }
                rectwidth += 100;
                $('canvas').drawRect({
                    layer: true,
                    name: 'arect' + i.toString(),
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    x: 900 + rectwidth / 2, y: curY,
                    width: rectwidth,
                    height: rectheight,
                    cornerRadius: 10
                });
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < aside.length;k++) {
                     if (aside[k].type == "进球") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aG' + i.toString() + k.toString(),
                            source: 'ReportElements/G.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });
                    } else if (aside[k].type == "点球") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aPG' + i.toString() + k.toString(),
                            source: 'ReportElements/PG.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (aside[k].type == "点球罚失") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aPM' + i.toString() + k.toString(),
                            source: 'ReportElements/PM.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (aside[k].type == "乌龙球") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aOG' + i.toString() + k.toString(),
                            source: 'ReportElements/OG.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (aside[k].type == "换下") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aSO' + i.toString() + k.toString(),
                            source: 'ReportElements/SO.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (aside[k].type == "换上") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aSI' + i.toString() + k.toString(),
                            source: 'ReportElements/SI.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

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
                            $('canvas').drawImage({
                                layer: true,
                                name: 'aY2C' + i.toString() + k.toString(),
                                source: 'ReportElements/Y2C.png',
                                x: 900 + 20, y: ey,
                                fromCenter: false
                            });

                        } else {
                            $('canvas').drawImage({
                                layer: true,
                                name: 'aYC' + i.toString() + k.toString(),
                                source: 'ReportElements/YC.png',
                                x: 900 + 20, y: ey,
                                fromCenter: false
                            });

                        }

                    } else if (aside[k].type == "红牌") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'aRC' + i.toString() + k.toString(),
                            source: 'ReportElements/RC.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    }
                    $('canvas').drawText({
                        layer: true,
                        name: aside[k].team + "~" + aside[k].kitnum + "~" + aside[k].name + "~" + aside[k].type + "~" + aside[k].time + "~" + aside[k].stptime + "~" + i.toString() + "~" + k.toString(),
                        fillStyle: '#000',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: aside[k].namestr,
                        fromCenter: false,
                        x: 970, y: ey,
                        dblclick: function(layer) {
                            var item = layer.name.split("~");
                            console.log(item);
                            $.get('delitem.php', {
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
                    });
                    ey += 50;
                }
            }
            curY += halfh + 40;
        }
        if ((stage != 'Group' || '<?=$dbname?>'.match("MANYU")) && hg == ag) { //点球决胜
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
                $('canvas').drawLine({
                    layer: true,
                    name: 'phtimeline',
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    rounded: true,
                    x1: 700, y1: curY,
                    x2: 800, y2: curY,
                });
                $('canvas').drawText({
                    layer: true,
                    name: 'phtime',
                    fillStyle: '#000',
                    fontFamily: 'Trebuchet MS',
                    fontSize: 36,
                    text: hside[0].timestr,
                    x: 750, y: curY - 20,
                })
                var rectwidth = 0;
                var rectheight = hside.length * 50 + 10;
                for (var k = 0;k < hside.length;k++) {
                    $('canvas').drawText({
                        layer: true,
                        name: 'measure' + hside[k].namestr,
                        fillStyle: 'rgba(0, 0, 0, 0)',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: hside[k].namestr,
                        x: 800, y: 0,
                    });
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + hside[k].namestr).width);
                }
                rectwidth += 100;
                $('canvas').drawRect({
                    layer: true,
                    name: 'phrect',
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    x: 700 - rectwidth / 2, y: curY,
                    width: rectwidth,
                    height: rectheight,
                    cornerRadius: 10
                });
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < hside.length;k++) {
                    if (hside[k].type == "点球决胜罚进") {
                        phg++;
                        $('canvas').drawImage({
                            layer: true,
                            name: 'phPG' + i.toString() + k.toString(),
                            source: 'ReportElements/PG.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });
                    } else if (hside[k].type == "点球决胜罚失") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'phPM' + i.toString() + k.toString(),
                            source: 'ReportElements/PM.png',
                            x: 700 - rectwidth + 20, y: ey,
                            fromCenter: false
                        });

                    }                    
                    $('canvas').drawText({
                        layer: true,
                        name: hside[k].team + "~" + hside[k].kitnum + "~" + hside[k].name + "~" + hside[k].type + "~" + hside[k].time + "~" + hside[k].stptime + "~" + i.toString() + "~" + k.toString(),
                        fillStyle: '#000',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: hside[k].namestr,
                        fromCenter: false,
                        x: 700 - rectwidth + 70, y: ey,
                        dblclick: function(layer) {
                            var item = layer.name.split("~");
                            console.log(item);
                            $.get('delitem.php', {
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
                    });
                    ey += 50;
                }

            } 
            if (aside.length != 0) {
                $('canvas').drawLine({
                    layer: true,
                    name: 'patimeline',
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    rounded: true,
                    x1: 800, y1: curY,
                    x2: 900, y2: curY,
                });
                $('canvas').drawText({
                    layer: true,
                    name: 'patime',
                    fillStyle: '#000',
                    fontFamily: 'Trebuchet MS',
                    fontSize: 36,
                    text: aside[0].timestr,
                    x: 850, y: curY - 20,
                })
                var rectwidth = 0;
                var rectheight = aside.length * 50 + 10;
                for (var k = 0;k < aside.length;k++) {
                    $('canvas').drawText({
                        layer: true,
                        name: 'measure' + aside[k].namestr,
                        fillStyle: 'rgba(0, 0, 0, 0)',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: aside[k].namestr,
                        x: 800, y: 0
                    });
                    rectwidth = Math.max(rectwidth, $('canvas').measureText('measure' + aside[k].namestr).width);
                }
                rectwidth += 100;
                $('canvas').drawRect({
                    layer: true,
                    name: 'parect',
                    strokeStyle: '#000',
                    strokeWidth: 3,
                    x: 900 + rectwidth / 2, y: curY,
                    width: rectwidth,
                    height: rectheight,
                    cornerRadius: 10
                });
                var ey = curY - rectheight / 2 + 10;
                for (var k = 0;k < aside.length;k++) {
                    if (aside[k].type == "点球决胜罚进") {
                        pag++;
                        $('canvas').drawImage({
                            layer: true,
                            name: 'paPG' + i.toString() + k.toString(),
                            source: 'ReportElements/PG.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    } else if (aside[k].type == "点球决胜罚失") {
                        $('canvas').drawImage({
                            layer: true,
                            name: 'paPM' + i.toString() + k.toString(),
                            source: 'ReportElements/PM.png',
                            x: 900 + 20, y: ey,
                            fromCenter: false
                        });

                    }                     
                    $('canvas').drawText({
                        layer: true,
                        name: aside[k].team + "~" + aside[k].kitnum + "~" + aside[k].name + "~" + aside[k].type + "~" + aside[k].time + "~" + aside[k].stptime + "~" + i.toString() + "~" + k.toString(),
                        fillStyle: '#000',
                        fontFamily: 'WenQuanYi Micro Hei',
                        fontSize: 40,
                        text: aside[k].namestr,
                        fromCenter: false,
                        x: 970, y: ey,
                        dblclick: function(layer) {
                            var item = layer.name.split("~");
                            console.log(item);
                            $.get('delitem.php', {
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

                    });
                    ey += 50;
                }
            }
            curY += halfh + 40;
        }
            var pscore = "(" + phg.toString() + ":" + pag.toString() + ")";
            $('canvas').drawText({
                layer: true,
                fillStyle: '#000',
                fontStyle: 'bold',
                name: 'pscore',
                x: 800, y: 120,
                fontSize: 60,
                fontFamily: 'Trebuchet MS',
                text: pscore
            });
        }
        
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
        var score = hg.toString() + ":" + ag.toString();
        $('canvas').drawText({
            layer: true,
            fillStyle: '#000',
            fontStyle: 'bold',
            name: 'score',
            x: 800, y: 50,
            fontSize: 60,
            fontFamily: 'Trebuchet MS',
            text: score
        });
        //$('canvas').attr("height", curY + 50);


        //
        window.setTimeout(function() {
            var pngdown = $('canvas').getCanvasImage('png');
            var pngbtn = document.getElementById("pngdown");
            pngbtn.href = pngdown;
            pngbtn.download = subtitle + HomeName + score + AwayName +".png";
        },500);
            //callback(subtitle, HomeName, score, AwayName);
        });
}
//function getpng(subtitle, HomeName, score, AwayName) {
//    window.setTimeout(function() {
//        var pngdown = $('canvas').getCanvasImage('png');
//        var pngbtn = document.getElementById("pngdown");
//        pngbtn.href = pngdown;
//        pngbtn.download = subtitle + HomeName + score + AwayName +".png";
//    },200);
//}
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
<?php
$conn->close();
?>
        </div>
     </div>
  </body>
</html>

