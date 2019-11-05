<?php
	session_start();
?>

<html>
	<head>
		<title> admin</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    	<link rel="stylesheet" type="text/css" href="css/admin_user.css">
    	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    	<script src="https://kit.fontawesome.com/a8f317beae.js" crossorigin="anonymous"></script>

    	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>


    	<script>
    		function deleteEvent(eid) {
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			     var elem= document.getElementById("" + eid);
			     elem.parentNode.removeChild(elem);
			    }
			  };
			  xhttp.open("GET", "resources/delete_events.php?eid="+eid, true);
			  xhttp.send();
			}

    	</script>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
        </script>
</head>
<body>
	<section id="Header">
        <nav class="navbar navbar-expand-sm navbar-light  font-weight-normal pt-0" style="background-color: rgba(208, 245, 245, 0.747)">
            <a class="navbar-brand" href="home.php">IIEST<strong>Bulletin</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse mr-0 justify-content-end" id="navbarToggler">
            <ul class="navbar-nav mt-1">
                <?php if(isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a id="profile" class="nav-link" href="events.php"><i class="fa fa-user-circle-o fa-2x text-primary" aria-hidden="true"></i></a>
                    </li>
                    <li class="nav-item">
                        <a id="logout" class="nav-link text-white btn btn-danger mr-2 btn-sm-lg" href="logout.php">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        </nav>
	</section>
	<?php
		if(isset($_SESSION['user']))
    	{
    		$conn = mysqli_connect("localhost","btech2017","btech2017","btech2017");
            if(mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            $uid = $_SESSION['user'];
            $sql="select * from x_pref where uid =".$uid.";";
            $result = mysqli_query($conn,$sql);
            $ebid = array();
            if(mysqli_num_rows($result) > 0)
            {
                $i = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $ebid [$i] = $row["ebid"];
                    $i ++;
                }
            }
        }
        else
        {
        	$conn = mysqli_connect("localhost","btech2017","btech2017","btech2017");
        	$ebid = array();
        	$i = 0;
        	unset($_POST['submit']);
        	foreach($_POST as $x => $x_value)
        	{
        		$ebid[$i] = $x;
        		$i ++;
        	}

        }
            $j = 0;
            $eid = array();
            for($i=0; $i < count($ebid); $i ++)
            {
                $sql="select * from x_eveb where ebid =" . $ebid[$i].";"; 
                $result = mysqli_query($conn,$sql);
                if(mysqli_num_rows($result) > 0)
                {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $eid[$j] = $row["eid"];
                        $j ++;
                    }
                }
            }
          	$eid=array_values(array_unique($eid,SORT_NUMERIC));
    ?>

	<section id="mainBody">
		<div class="container-fluid">
			<div class="row my-4">
				<div class="col-9 card mx-auto">
                    <div class="row mx-auto">
                        <h1>Add Event</h1>
                    </div>
					<form action="resources/add_events.php" method="post">
						
						<div class="row my-3">
							<div class="col-3">
								<label>Event Name:</label>
							</div>
								<div class="col-9">
								<input  type="text" name="ename" required /> 
							</div>
						</div>
						<div class="row my-2">
							<div class="col-3">
								<label>Event desc:</label>
							</div>
							<div class="col-9">
								<textarea name="edesc"></textarea>  
							</div>
						</div>
						<div class="row mx-auto my-auto ">
							<button type="submit" name="add" class="btn btn-primary"> Add Event </button>
						</div> 
					</form>
				</div>
            </div>
            <div class="row">
				<div class="col-9 card mx-auto pb-3">
                    <div class="row mx-auto">
                        <h1>Delete Event</h1>
                    </div>
				<?php 
	                for($i = 0; $i < count($eid); $i ++)
	                {
	                    $sql="select * from x_events where eid =" . $eid[$i].";"; 
	                    $result = mysqli_query($conn,$sql);
	                    $row = mysqli_fetch_assoc($result);
	            ?>
	                <div class="row">
	                    <div class="col-9 text-center mx-auto event-card mt-3 d-flex justify-content-between pt-2" id="<?php echo $row["eid"] ?>">
	                        <h4><?php echo $row["ename"] ?></h4>
                            <span onclick="deleteEvent(<?php echo $row["eid"] ?>)" class="ml-5"><i class="fas fa-trash text-danger"></i></span>
	                    </div>
	                </div>
	            <?php }  ?>

				</div>
			</div>
	    </div>
	</section>
	<section>
        <div id="Footer" class="d-flex justify-content-center fixed-bottom border-top pt-2">
            <p>© 2019 IIEST<strong>Bulletin</strong></p>
            <a href="login.php" class="text-dark pl-2">Login</a>
            <a href="sform.php" class="text-dark pl-2">SignUp</a>
        </div>            
    </section>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
