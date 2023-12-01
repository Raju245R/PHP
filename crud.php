<script type="text/javascript" src="script.js"></script>
<?php
$mydb=new mysqli("localhost","Raju","b9s36430","sample");
if($mydb->connect_error){
    die("Error Occur : Please Check Your DataBase Connection");
};

function form($id,$name,$age,$gender,$income){
    if($gender=="male"){$male="checked";$female="";}
    else if($gender=="female"){$female="checked";$male="";}
    else{$male="";$female="";}
    echo "<form id='inputs' action='crud.php' method='post' class='row g-3'>";
    echo "<input type='hidden' id='id' value=$id>";
    echo "<div class='col-auto'><input type='text' id='name' class='form-control' value=$name></div>";
    echo "<div class='col-auto'><input type='text' id='age' class='form-control' value=$age></div>";
    echo "<div class='col-auto'><input type='radio' name='gender' id='male' value='male' $male>Male</input>";
    echo "<input type='radio' name='gender' id='female' value='female' $female>Female</input></div>";
    echo "<div class='col-auto'><input type='text' id='income' class='form-control' value=$income></div>";
    echo "<div class='col-auto'><input type='button' onclick='save()' id='but' value='Save' class='btn btn-success'></div>";
    echo  "</form>";
}
function table($mydb){
     $results=mysqli_query($mydb,"select * from examplephp");
     echo "<table class='table table-striped table-hover container' style='text-align:center;'>";
     echo "<tr>";
     echo "<th>ID</th>";
     echo "<th>Name</th>";
     echo "<th>Age</th>";
     echo "<th>Gender</th>";
     echo "<th>Income</th>";
     echo "<th>Actions</th>";
     echo "</tr>";
     while($row=$results->fetch_assoc()){
        echo "<tr>";
        echo "<td>".$row["id"]."</td>";
        echo "<td>".$row["name"]."</td>";
        echo "<td>".$row["age"]."</td>";
        echo "<td>".$row["gender"]."</td>";
        echo "<td>".$row["income"]."</td>";
        echo "<td><input type='button' id='delete' class='btn btn-danger' data-value='".$row["id"]."' value='Delete'>
                    <input type='button' id='update' class='btn btn-warning' data-value='".$row["id"]."' value='Update'></td>";
        echo "</tr>";
     }
}
if(isset($_POST["op"])){
    if($_POST["op"]==="save"){
        $name=$_POST["name"];
        $age=$_POST["age"];
        if($_POST["gender"]){$gender=$_POST["gender"];}else{$gender="";}
        $income=$_POST["income"];
        if($_POST["id"]==""){
            mysqli_query($mydb,"insert into examplephp(name,age,gender,income) values ('$name',$age,'$gender',$income)");
            echo table($mydb);
        }
        else{
            mysqli_query($mydb,"update examplephp set name='".$name."',age=$age,gender='".$gender."',income=$income where id=".$_POST["id"]);
            echo table($mydb);
        }
    }
    if($_POST["op"]==="delete"){
        mysqli_query($mydb,"delete from examplephp where id=".$_POST["id"]);
        echo table($mydb);
    }
    if($_POST["op"]==="update"){
        if(isset($_POST["id"])){
            $result=mysqli_query($mydb,"select * from examplephp where id=".$_POST["id"]);
            $list=mysqli_fetch_array($result);
            echo form($list["id"],$list["name"],$list["age"],$list["gender"],$list["income"]);
        }
    }
    exit;
}
?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
    <body>
        <div class="container" style="padding-top:30px;">
        <div id="form"><?php echo form("","","","","")?></div>
        <div id="table"><?php echo table($mydb)?></div>
        </div>
    </body>
</html>
<script>
    function save(){
            let id=$("#id").val();
            let name=$("#name").val();
            let age=$("#age").val();
            let gender=$("input[name='gender']:checked").val();
            let income=$("#income").val();
            $.ajax({
                type:"post",
                data:{op:"save",name:name,id:id,gender:gender,income:income,age:age},
                success:function(res){
                    $("#table").html(res);
                    //$("#id").val(""),$("#name").val(""),$("#income").val(""),$("#age").val("");
                    //$("#male").prop("checked",false),$("#female").prop("checked",false);
                    //$("inputs")[0].reset();
                    $("#but").attr("class","btn btn-success");
                    $(":input","#inputs")
                    .not(":button")
                    .val("")
                    .prop("checked",false);
                }
            })
        }
    $(document).ready(function(){
        $(document).on("click","#delete",function(){
            let id=$(this).data("value");
            $.ajax({
                type:"post",
                data:{op:"delete",id:id},
                success:function(res){
                    $("#table").html(res);
                    $("#id").val(""),$("#name").val(""),$("#income").val(""),$("#age").val("");
                    $("#male").prop("checked",false),$("#female").prop("checked",false);
                }
            })
        })
        $(document).on("click","#update",function(){
            let id=$(this).data("value");   
            $.ajax({
                type:"post",
                data:{op:"update",id:id},
                success:function(res){
                    $("#form").html(res);
                    $("#but").attr("class","btn btn-warning");                   
                }
            })         
        })
    })
</script>