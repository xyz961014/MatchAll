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
    <div class="container">
        <div class="row" id="grouprank">
        </div>
        <div class="row" id="eliinfo">
        </div>
     </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
$dbname = $_GET['Match'];
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
//print_r($arr);
?>
<script>

var dbname = '<?=$dbname?>';
var d = new Date();
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
                }
                stage = eliinfo[id].stage;
                mlist.push(eliinfo[id]);
            }
            else {
                mlist.push(eliinfo[id]);
            }
            console.log(eliinfo[id]);
            $("#eliinfo").append(eliml);
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

