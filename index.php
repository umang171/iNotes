<?php
// Database connection
$servername="localhost";
$username="root";
$password="";
$database="inotes";
$inserted=false;
$updated=false;
$deleted=false;

$conn=mysqli_connect($servername,$username,$password,$database);
if(!$conn){
    die("Not connected ".mysqli_connect_error());
}
// insert the data
if($_SERVER['REQUEST_METHOD']=="POST"){
    // update
    if(isset($_POST['editsno']))
    {
        $sno=$_POST['editsno'];
        $title=$_POST['edittitle'];
        $description=$_POST['editdescription'];
        $sql="UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno;";
        $result=mysqli_query($conn,$sql);
        if($result){
            $updated=true;
        }
    }
    else{

        $title=$_POST['title'];
        $description=$_POST['description'];
        $sql="insert into `notes` (`title`,`description`)values('$title','$description')";
        $result=mysqli_query($conn,$sql);
        if($result){
            $inserted=true;
        }
    }
}
   
if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);
    $deleted = true;
}


?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <title>iNotes-Make your useful notes here.</title>
</head>

<body>
    <?php
    if($inserted){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your data has been submitted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if($updated){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your data has been updated successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if($deleted){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your data has been deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    ?>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <input type="hidden" name="editsno" id="editsno">
                        <div class="mb-3">
                            <label for="edittitle" class="form-label">Enter title</label>
                            <input type="text" class="form-control" id="edittitle" name="edittitle">
                        </div>
                        <div class="mb-3">
                            <label for="editdescription" class="form-label">Enter description</label>
                            <textarea class="form-control" id="editdescription" rows="3"
                                name="editdescription"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Contact us</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-4">

        <form method="post" action="index.php">
            <div class="mb-3">
                <label for="title" class="form-label">Enter title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Enter description</label>
                <textarea class="form-control" id="description" rows="3" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>



    <!-- For showing records -->
    <div class="container">
        <h1>Your notes</h1>
        <table class="tabl" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sno</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php
            $sql="select * from notes";
            $result=mysqli_query($conn,$sql);
            $no=1;
            while($row=mysqli_fetch_assoc($result)){
                // echo $row['sno'].' '.$row['title'];
                $sno=$row['sno'];
                echo ' <tr>
                <th scope="row">'.$no.'</th>
                <td>'.$row['title'].'</td>
                <td>'.$row['description'].'</td>
                <td>
                    <button class="edit btn btn-small btn-primary" id="'.$sno.'">Edit</button>
                    <button class="delete btn btn-small btn-primary" id=d"'.$sno.'">Delete</button>
                </td>
            </tr>';
            $no++;
            }
            ?>

            </tbody>
        </table>
    </div>
    <div class="my-3"></div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>

    <!-- Edit button -->
    <script>
        edit=document.getElementsByClassName('edit');
        // console.log(Array.from(edit));
        Array.from(edit).forEach((ele)=>{
            ele.addEventListener("click",(e)=>{
                tr=e.target.parentNode.parentNode;
                title=tr.getElementsByTagName("td")[0].innerText;
                description=tr.getElementsByTagName("td")[1].innerText;
                // console.log(title);
                // console.log(description);
                edittitle.value=title;
                editdescription.value=description;
                editsno.value = e.target.id;
                // console.log(e.target)
                $('#editModal').modal('toggle');
            });
        });

        deletes=document.getElementsByClassName('delete');
        Array.from(deletes).forEach(element => {
            element.addEventListener("click",(e)=>{
            sno = e.target.id.substr(1);
            console.log(sno);
                
            if(confirm("Are you sure you want to delete?"))
            {
                console.log("yes");
                window.location="index.php?delete="+sno;
            }
            else{
                console.log("no");
            }
            });
        });

    </script>


    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();

    });
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    -->
</body>

</html>