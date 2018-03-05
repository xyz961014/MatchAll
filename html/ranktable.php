<!doctype html>
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
        <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <a href="index.php">返回</a>
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#pt" data-toggle="tab">积分表</a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   球员数据<b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                   <li><a href="#pgoal" data-toggle="tab">进球</a></li>
                   <li><a href="#pyc" data-toggle="tab">黄牌</a></li>
                   <li><a href="#prc" data-toggle="tab">红牌</a></li>
                   <li><a href="#ppen" data-toggle="tab">点球</a></li>
                   <li><a href="#papp" data-toggle="tab">出场次数</a></li>
                   <li><a href="#pmin" data-toggle="tab">出场时间</a></li>
                   <li><a href="#ppenmiss" data-toggle="tab">点球罚失</a></li>
                   <li><a href="#pog" data-toggle="tab">乌龙球</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   球队数据<b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                   <li><a href="#tgoal" data-toggle="tab">进球</a></li>
                   <li><a href="#tconcede" data-toggle="tab">失球</a></li>
                   <li><a href="#tpen" data-toggle="tab">点球</a></li>
                   <li><a href="#tyc" data-toggle="tab">黄牌</a></li>
                   <li><a href="#trc" data-toggle="tab">红牌</a></li>
                   <li><a href="#tpenmiss" data-toggle="tab">点球罚失</a></li>
                   <li><a href="#tog" data-toggle="tab">乌龙球</a></li>
                </ul>
            </li> 
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="pt">
                <div class="row" id="grouprank">
                </div>
                <div class="row" id="eliinfo">
                </div>
            </div>
            <div class="tab-pane fade" id="papp">
            </div>
            <div class="tab-pane fade" id="pmin">
            </div>
            <div class="tab-pane fade" id="pgoal">
            </div>
            <div class="tab-pane fade" id="pyc">
            </div>
            <div class="tab-pane fade" id="prc">
            </div>
            <div class="tab-pane fade" id="ppen">
            </div>
            <div class="tab-pane fade" id="ppenmiss">
            </div>
            <div class="tab-pane fade" id="pog">
            </div>
            <div class="tab-pane fade" id="tgoal">
            </div>
            <div class="tab-pane fade" id="tconcede">
            </div>
            <div class="tab-pane fade" id="tpen">
            </div>
            <div class="tab-pane fade" id="tyc">
            </div>
            <div class="tab-pane fade" id="trc">
            </div>
            <div class="tab-pane fade" id="tpenmiss">
            </div>
            <div class="tab-pane fade" id="tog">
            </div>
        </div>
     </div>

<?php
$dbname = $_GET['Match'];
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
//print_r($arr);
?>
<script>

var dbname = '<?=$dbname?>';
var d = new Date();
Getrank(dbname,d.getTime(),'Players', 'Appearances', 'papp', '出场次数');
Getrank(dbname,d.getTime(),'Players', 'Minutes', 'pmin', '出场时间');
Getrank(dbname,d.getTime(),'Players', 'Goals', 'pgoal', '进球',asort='Penalties');
Getrank(dbname,d.getTime(),'Players', 'YellowCards', 'pyc', '黄牌');
Getrank(dbname,d.getTime(),'Players', 'RedCards', 'prc', '红牌');
Getrank(dbname,d.getTime(),'Players', 'Penalties', 'ppen', '点球');
Getrank(dbname,d.getTime(),'Players', 'Penaltymiss', 'ppenmiss', '点球罚失');
Getrank(dbname,d.getTime(),'Players', 'OwnGoals', 'pog', '乌龙球');
Getrank(dbname,d.getTime(),'Teams', 'Goal', 'tgoal', '进球',asort='Penalty',isteam=true);
Getrank(dbname,d.getTime(),'Teams', 'Concede', 'tconcede', '失球',null,isteam=true);
Getrank(dbname,d.getTime(),'Teams', 'Penalty', 'tpen', '点球',null,isteam=true);
Getrank(dbname,d.getTime(),'Teams', 'YellowCard', 'tyc', '黄牌',null,isteam=true);
Getrank(dbname,d.getTime(),'Teams', 'RedCard', 'trc', '红牌',null,isteam=true);
Getrank(dbname,d.getTime(),'Teams', 'Penaltymiss', 'tpenmiss', '点球',null,isteam=true);
Getrank(dbname,d.getTime(),'Teams', 'OwnGoal', 'tog', '乌龙球',null,isteam=true);
function Getrank(dbname,time,table,sort,divid,divname,asort=null,isteam=false) {
    $.get("getrank.php", {
        table: table,
        sort: sort,
        asort: asort,
        time: time,
        dbname: dbname
}, function(data,state) {
    var tableinfo = JSON.parse(data);
    console.log(tableinfo);
    if (isteam) {
        var tableml = "<div class='col-md-6 col-lg-6'><table class='table table-hover table-bordered table-condensed'><caption>"+divname+"</caption><thead><tr><th>#</th><th>球队</th><th>"+divname+"</th></tr></thead><tbody>";
        for (var i = 0;i < tableinfo.length;i++) {
            var t = tableinfo[i];
            var tml = "<tr><td>"+(i+1).toString()+"</td><td>"+t.TeamName+"</td><td>"+t[sort]+"</td></tr>";
            tableml += tml;
        }
    } else {
        var tableml = "<div class='col-md-6 col-lg-6'><table class='table table-hover table-bordered table-condensed'><caption>"+divname+"</caption><thead><tr><th>#</th><th>球员</th><th>球队</th><th>"+divname+"</th></tr></thead><tbody>";
        for (var i = 0;i < tableinfo.length;i++) {
            var t = tableinfo[i];
            var tml = "<tr><td>"+(i+1).toString()+"</td><td>"+t.Name+"</td><td>"+t.Team+"</td><td>"+t[sort]+"</td></tr>";
            tableml += tml;
        }
    }
    tableml += "</tbody></table></div>";
    $('#'+divid).append(tableml);
})
}
Refresh(dbname,d.getTime());
function Refresh(dbname,time) {
    $('.ranktable').remove();
    $('.eliinfo').remove();
    $.getJSON(dbname + ".json",{
        time: time
    },function(data,state) {
        var grouprank = data[0];
        var eliinfo = data[1];
        for (var gn in grouprank) {
            var tableml = "<div class='ranktable col-md-6 col-lg-6'><table class='table table-hover table-bordered table-condensed'><caption>"+grouprank[gn].name+"组</caption><thead><tr><th>#</th><th>球队</th><th>胜</th><th>平</th><th>负</th><th>进/失</th><th>积分</th></tr></thead><tbody>";
            for (var i = 0;i < grouprank[gn].teams.length;i++) {
                var t = grouprank[gn].teams[i];
                var btndn = "  <button type='button' class='btnmove btn btn-xs' id='"+gn+"\.DN\."+i.toString()+"'><span class='glyphicon glyphicon-chevron-down'></span></button>"
                var btnup = "  <button type='button' class='btnmove btn btn-xs' id='"+gn+"\.UP\."+i.toString()+"'><span class='glyphicon glyphicon-chevron-up'></span></button>"
                if (i == 0) {
                btnup = "  <button type='button' class='btnmove btn btn-xs hidden'><span class='glyphicon glyphicon-arrow-up'></span></button>"
                }
                if (i == grouprank[gn].teams.length - 1) {
                btndn = "  <button type='button' class='btnmove btn btn-xs hidden'><span class='glyphicon glyphicon-arrow-down'></span></button>"
                }
                var tml = "<tr><td>"+(i+1).toString()+"</td><td>"+t.name+btndn+btnup+"</td><td>"+t.win+"</td><td>"+t.draw+"</td><td>"+t.lose+"</td><td>"+t.goals+"/"+t.concede+"</td><td>"+t.point+"</td></tr>";
                tableml += tml;
            }
            tableml += "</tbody></table></div>";
            $("#grouprank").append(tableml);
            console.log(grouprank[gn]);
        }
        $(".btnmove").click(function() {
            var id = $(this).attr('id');
            var id = id.split(".");
            console.log(id);
            var temp = grouprank[id[0]].teams[id[2]];
            console.log(temp);
            if (id[1] == 'UP')
                var t = parseInt(id[2]) - 1;
            else 
                var t = parseInt(id[2]) + 1;
            console.log(grouprank[id[0]].teams[t]);
            grouprank[id[0]].teams[id[2]] = grouprank[id[0]].teams[t];
            grouprank[id[0]].teams[t] = temp;
            var jsonstr = JSON.stringify([grouprank, eliinfo]);
            //console.log(jsonstr);
            $.post('writejson.php', {
                name: dbname,
                str: jsonstr
            }, function(data,state) {
                console.log(data,state);
                window.setTimeout(function() {
                    Refresh(dbname,d.getTime());
                },0);
            })
        })
        var stage = '';
        var mlist = [];
        eliinfo.end = 'end';
        for (var id in eliinfo) {
            if (eliinfo[id].stage != stage) {
                if (mlist.length != 0) {
                    var eliml ="<div class='eliinfo col-lg-12'><table class='table table-bordered table-hover table-condensed'><caption>"+stage+"</caption><thead><tr><th>比赛时间</th><th>主队</th><th>比分</th><th>客队</th>";           
                    for (var i = 0;i < mlist.length;i++) {
                        if (mlist[i].valid == 1) {
                            if (mlist[i].todecide)
                                var eml = "</tr></thead><tbody><tr><td>"+mlist[i].time+"</td><td>"+"<div class='col-lg-12'><div class='input-group'><input type='text' disabled='true' class='eliinput form-control input-sm' id='"+"H~"+mlist[i].matchid+"~INPUT'"+" value='"+mlist[i].hometeam+"'><span class='input-group-btn'><button class='eliok btn btn-default btn-sm' type='button' disabled='true' id='H~"+mlist[i].matchid+"~OK'"+"><span class='glyphicon glyphicon-ok'></span></button>"+"<button type='button' class='eliedit btn btn-default btn-sm' id='"+"H~"+mlist[i].matchid+"'><span class='glyphicon glyphicon-edit'></span></button>"+"</span></div></div>"+"</td><td>"+mlist[i].result+"</td><td>"+"<div class='col-lg-12'><div class='input-group'><input type='text' disabled='true' class='eliinput form-control input-sm' id='"+"A~"+mlist[i].matchid+"~INPUT'"+" value='"+mlist[i].awayteam+"'><span class='input-group-btn'><button class='eliok btn btn-default btn-sm' type='button' disabled='true' id='A~"+mlist[i].matchid+"~OK'"+"><span class='glyphicon glyphicon-ok'></span></button>"+"<button type='button' class='eliedit btn btn-default btn-sm' id='"+"A~"+mlist[i].matchid+"'><span class='glyphicon glyphicon-edit'></span></button>"+"</span></div></div>"+"</td></tr>";
                            else
                                var eml = "</tr></thead><tbody><tr><td>"+mlist[i].time+"</td><td>"+mlist[i].hometeam+"</td><td>"+mlist[i].result+"</td><td>"+mlist[i].awayteam+"</td></tr>";
                        } else {
                            if (mlist[i].todecide)
                                var eml = "</tr></thead><tbody><tr><td>"+mlist[i].time+"</td><td>"+"<div class='col-lg-12'><div class='input-group'><input type='text' disabled='true' class='eliinput form-control input-sm' id='"+"H~"+mlist[i].matchid+"~INPUT'"+" value='"+mlist[i].hometeam+"'><span class='input-group-btn'><button class='eliok btn btn-default btn-sm' type='button' disabled='true' id='H~"+mlist[i].matchid+"~OK'"+"><span class='glyphicon glyphicon-ok'></span></button>"+"<button type='button' class='eliedit btn btn-default btn-sm' id='"+"H~"+mlist[i].matchid+"'><span class='glyphicon glyphicon-edit'></span></button>"+"</span></div></div>"+"</td><td>VS</td><td>"+"<div class='col-lg-12'><div class='input-group'><input type='text' disabled='true' class='eliinput form-control input-sm' id='"+"A~"+mlist[i].matchid+"~INPUT'"+" value='"+mlist[i].awayteam+"'><span class='input-group-btn'><button class='eliok btn btn-default btn-sm' type='button' disabled='true' id='A~"+mlist[i].matchid+"~OK'"+"><span class='glyphicon glyphicon-ok'></span></button>"+"<button type='button' class='eliedit btn btn-default btn-sm' id='"+"A~"+mlist[i].matchid+"'><span class='glyphicon glyphicon-edit'></span></button>"+"</span></div></div>"+"</td></tr>";
                            else
                                var eml = "</tr></thead><tbody><tr><td>"+mlist[i].time+"</td><td>"+mlist[i].hometeam+"</td><td>VS</td><td>"+mlist[i].awayteam+"</td></tr>";

                        }
                        eliml += eml;
                    }
                    eliml += "</tbody></table></div>"; 
                    mlist = [];
                    $("#eliinfo").append(eliml);
                }
                stage = eliinfo[id].stage;
                mlist.push(eliinfo[id]);
            }
            else {
                mlist.push(eliinfo[id]);
            }
            console.log(eliinfo[id]);
        }
        $('.eliedit').click(function() {
            var id = $(this).attr('id');
            var parseid = id.split("~");
            console.log(parseid);
            $(this).attr("disabled", true);
            var Htxt = document.getElementById(id+'~INPUT');
            var Hok = document.getElementById(id+'~OK');
            Htxt.disabled = false;
            Hok.disabled = false;
        })
        $('.eliok').click(function() {
                var id = $(this).attr('id');
                var parseid = id.split("~");
                console.log(parseid);
                var text = parseid[0] + "~" + parseid[1] + "~INPUT";
                var Htxt = document.getElementById(text);
                console.log(Htxt.value);
                if (parseid[0] == 'H')
                    eliinfo[parseid[1]].hometeam = Htxt.value;
                else
                    eliinfo[parseid[1]].awayteam = Htxt.value;
                var jsonstr = JSON.stringify([grouprank, eliinfo]);
                $.post('writejson.php', {
                    name: dbname,
                    str: jsonstr
                }, function(data,state) {
                    console.log(data,state);
                    //Refresh(dbname,d.getTime());
                })
                Htxt.disabled = true;
                $(this).attr('disabled',true);
                var Hedit = document.getElementById(parseid[0] + '~' + parseid[1]);
                Hedit.disabled = false;
            })
    })
}

</script>
  </body>
</html>

