<!doctype html>
<html lang="ch">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

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
    <script src="https://cdn.bootcss.com/popper.js/1.13.0/umd/popper.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<?php
$dbname = $_GET['Match'];
exec("PYTHONIOENCODING=utf-8 python3 /var/www/TUFA/Evolve.py ".$dbname." 2>&1",$arr,$ret);
//print_r($arr);
?>
<script>
var dbname = '<?=$dbname?>';
var d = new Date();
Refresh(dbname);
function Refresh(dbname) {
    $.getJSON(dbname + ".json",{
        time: d.getTime()
    },function(data,state) {
        var grouprank = data[0];
        var eliinfo = data[1];
        for (var gn in grouprank) {
            var tableml = "<div class='col-lg-12'><table class='table table-hover table-bordered table-condensed'><caption>"+grouprank[gn].name+"组</caption><thead><tr><th>#</th><th>球队</th><th>胜</th><th>平</th><th>负</th><th>进/失</th><th>积分</th></tr></thead><tbody>";
            for (var i = 0;i < grouprank[gn].teams.length;i++) {
                var t = grouprank[gn].teams[i];
                var tml = "<tr><td>"+(i+1).toString()+"</td><td>"+t.name+"</td><td>"+t.win+"</td><td>"+t.draw+"</td><td>"+t.lose+"</td><td>"+t.goals+"/"+t.concede+"</td><td>"+t.point+"</td></tr>";
                tableml += tml;
            }
            tableml += "</tbody></table></div>";
            $("#grouprank").append(tableml);
            console.log(grouprank[gn]);
        }
        var stage = '';
        var mlist = [];
        eliinfo.end = 'end';
        for (var id in eliinfo) {
            if (eliinfo[id].stage != stage) {
                if (mlist.length != 0) {
                    var eliml ="<table class='table table-bordered table-hover table-condensed'><caption>"+stage+"</caption><thead><tr><th>比赛时间</th><th>主队</th><th>比分</th><th>客队</th>";           
                    for (var i = 0;i < mlist.length;i++) {
                        if (mlist[i].valid == 1) {
                            var eml = "</tr></thead><tbody><tr><td>"+mlist[i].time+"</td><td>"+mlist[i].hometeam+"</td><td>"+mlist[i].result+"</td><td>"+mlist[i].awayteam+"</td></tr>";
                        } else {
                            var eml = "</tr></thead><tbody><tr><td>"+mlist[i].time+"</td><td>"+mlist[i].hometeam+"</td><td>VS</td><td>"+mlist[i].awayteam+"</td></tr>";

                        }
                        eliml += eml;
                    }
                    eliml += "</tbody></table>"; 
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
    })
}

</script>
  </body>
</html>

