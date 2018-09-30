<!doctype html>
<html lang="ch">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="static/bootstrap.min.css" rel="stylesheet">
    <link href="static/bootstrap-theme.min.css" rel="stylesheet">


    <title>TUFA</title>
  </head>
  <body>
    <?php echo "<a href='index.php'>返回</a>"; ?>
    <?php require "session.php"; ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="static/jquery.min.js"></script>
    <script src="static/bootstrap.min.js"></script>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×            
			    </button>
                <h4 class="modal-title" id="deleteModalLabel">
               删除            
			    </h4>
                <h6 class="modal-hidden" hidden></h6>
            </div>
            <div class="modal-body">
                <p> 确定要删除吗？</p>
		    </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消           
			    </button>
                <button type="button" class="btn btn-primary delbtnsubmit">
                确定            
			    </button>
            </div>
        </div>
    </div>
</div>

    <div class="container">

        <p class='list'></p>

<?php
if ($right > 1) { //权限
?>
<script>
$('#deleteModal').modal({
    keyboard: false,
	show: false
});
var d = new Date();
$.get("showmatch.php", {
    time: d.getTime()
}, function(data, state) {
    var matchlist = JSON.parse(data);
    console.log(matchlist);
    if (matchlist.length > 0) {
        var tableml = "<div>  <table class='table table-bordered table-hover table-condensed'> <caption>比赛列表<a class='btn btn-default btn-sm pull-right' href='newmatch.php'>增加新比赛</a></caption><thead><tr><th>比赛名称</th><th>比赛简称</th><th>最多上场人数</th><th>最少上场人数</th><th>球衣号码</th><th>比赛系列代码</th><th>点球大战</th><th>常规时间</th><th>加时赛时间</th><th>点球轮数</th><th>年份</th><th>编辑</th>";
<?php
    if ($right > 3) {
?>
        tableml += "<th>删除</th>";
<?php
    }
?>
        tableml += "</tr></thead><tbody>";
        for (var i = 0;i < matchlist.length;i++) {
            var tablerow = "<tr class='"+ matchlist[i]['dbname'] + "'><td class='name'>" + matchlist[i]['name'] + "</td><td class='subname'>" + matchlist[i]['subname'] + "</td><td class='maxonfield'>" + matchlist[i]['maxonfield'] + "</td><td class='minonfield'>" + matchlist[i]['minonfield'] + "</td><td class='enablekitnum'>";
            if (matchlist[i]['enablekitnum'] == 1) {
                tablerow += "有</td>";
            }
            else {
                tablerow += "无</td>";
            }
            tablerow += "<td class='class'>" + matchlist[i]['class'] + "</td><td class='penalty'>";
            tablerow += matchlist[i]['penalty'] + "</td><td class='ordinarytime'>"; 
            tablerow += matchlist[i]['ordinarytime'] + "</td><td class='extratime'>" + matchlist[i]['extratime'] + "</td><td class='penaltyround'>" + matchlist[i]['penaltyround'] + "</td><td class='year'>" + matchlist[i]['year'] + "</td>"; 
            tablerow += "<td class='edit'><button type='button' class='matchedit btn btn-default btn-sm' id='E~" + matchlist[i]["dbname"] + "'><span class='glyphicon glyphicon-edit'></span></button></td>";
<?php
    if ($right > 3) {
?>
            tablerow += "<td class='delete'><button type='button' class='matchdelete btn btn-default btn-sm' id='D~" + matchlist[i]["dbname"] + "'><span class='glyphicon glyphicon-remove'></span></button></td>"
<?php
    }
?>
            tableml += "</tr>" + tablerow;
        }
        tableml += "</tbody></table></div>";
        $(".list").append(tableml);
    }
    $(".matchok").hide();
<?php
        if ($right > 3) {
?>
    $(".matchdelete").click(function () {
        var btndelete = $(this);
        var id = $(this).attr("id");
        var pid = id.split("~");
        $(".modal-hidden").text(pid[1]);
        $("#deleteModal").modal("show");
       
         
    });
    $(".delbtnsubmit").click(function() {
        var id = $(".modal-hidden").text();
        $.get("removeitem.php", {
            db: "MATCHES",
            table: "matches",
            idkey: "dbname",
            idvalue: id
        });
        location.reload();
    })
<?php
        }
?>
    $(".matchedit").click(function() {
        var btnedit = $(this);
        var id = $(this).attr('id');
        var pid = id.split('~');
        var edit = $("." + pid[1] + " .edit");
        edit.append("<button type='button' class='matchok" + pid[1] + " btn btn-default btn-sm' id='O~" + pid[1] + "'><span class='glyphicon glyphicon-ok'></span></button>");
        function editfield(dbname, fieldname) {
            var field = $("." + dbname + " ." + fieldname);
            var fieldtxt = field.text();
            field.empty();
            field.append("<input type='text' class='" + fieldname + dbname + " form-control input-sm' value='" + fieldtxt + "'>");
        }
        editfield(pid[1], "name");
        editfield(pid[1], "subname");
        editfield(pid[1], "maxonfield");
        editfield(pid[1], "minonfield");
        var kit = $("." + pid[1] + " .enablekitnum");
        var kittxt = kit.text();
        kit.empty();
        kit.append("<select class='enablekitnum" + pid[1] + "'><option>有<option>无</select>");
        $(".enablekitnum" + pid[1]).val(kittxt);
        //editfield(pid[1], "class");
        var penalty = $("." + pid[1] + " .penalty");
        var penaltytxt = penalty.text();
        penalty.empty();
        penalty.append("<select class='penalty" + pid[1] + "'><option>淘汰赛<option>总是</select>");
        $(".penalty" + pid[1]).val(penaltytxt);
        editfield(pid[1], "ordinarytime");
        editfield(pid[1], "extratime");
        editfield(pid[1], "penaltyround");
        //editfield(pid[1], "year");
        $(this).hide();

        $(".matchok" + pid[1]).click(function() {
            var id = $(this).attr("id");
            var pid = id.split("~");
            console.log(pid);
            var ok = $("." + pid[1] + " .edit");
            btnedit.show();
            $(this).remove();
            function fieldok(dbname, fieldname) {
                var fieldinput = $("." + fieldname + dbname);
                var field = $("." + dbname + " ." + fieldname);
                var fieldtxt = fieldinput.val();
                fieldinput.remove();
                field.append(fieldtxt);
                return fieldtxt;
            }
            var name = fieldok(pid[1], "name");
            var subname = fieldok(pid[1], "subname");
            var maxonfield = fieldok(pid[1], "maxonfield");
            var minonfield = fieldok(pid[1], "minonfield");
            var enablekitnum = fieldok(pid[1], "enablekitnum");
            //var class1 = fieldok(pid[1], "class");
            var class1 = $("." + pid[1] + " .class").text();
            var penalty = fieldok(pid[1], "penalty");
            var ordinarytime = fieldok(pid[1], "ordinarytime");
            var extratime = fieldok(pid[1], "extratime");
            var penaltyround = fieldok(pid[1], "penaltyround");
            //var year = fieldok(pid[1], "year");
            var year = $("." + pid[1] + " .year").text();
            var parseyear = year.split("-");
            if (parseyear.length == 1)
                var dbname = class1 + "_" + year.substring(2);
            if (parseyear.length == 2)
                var dbname = class1 + "_" + parseyear[0].substring(2) + parseyear[1].substring(2);
            if (enablekitnum == "有")
                enablekitnum = 1;
            else
                enablekitnum = 0;
            console.log(dbname,name,subname,maxonfield,minonfield,enablekitnum,class1,penalty,ordinarytime,extratime,penaltyround,year);
            $.post("editmatch.php", {
                dbname: dbname,
                name: name,
                subname: subname,
                maxonfield: maxonfield,
                minonfield: minonfield,
                enablekitnum: enablekitnum,
                'class': class1,
                penalty: penalty,
                ordinarytime: ordinarytime,
                extratime: extratime,
                penaltyround: penaltyround,
                year: year
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

