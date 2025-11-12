<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
  } else{

if(isset($_GET['del']))
		  {
		  	$uid=$_GET['id'];
		          mysqli_query($con,"delete from appointment where id ='$uid'");
                  $_SESSION['msg']="data deleted !!";
		  }
?>
<?php
$sql=mysqli_query($con,"select * from appointment");
while($row=mysqli_fetch_array($sql))
{
?>							
	<a href="appointment-history.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
<?php 

 }?>

<?php } ?>