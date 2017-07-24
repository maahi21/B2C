<?php
include 'db_config.php';
session_start();
if(isset($_POST["add_to_cart"])){
	if(isset($_SESSION["shoping_cart"]))
	{
		$item_array_id=array_column($_SESSION["shoping_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count=count($_SESSION["shoping_cart"]);
			$item_array = array(
			'item_id' => $_GET["id"] ,
			'item_name' => $_POST["hidden_name"] ,
			'item_price' => $_POST["hidden_price"] ,
			'item_quantity' => $_POST["quantity"]
			);
		$_SESSION["shoping_cart"][$count]=$item_array;
		}
		else
		{
			echo '<script>alert("Item already added")</script>';
			echo '<script>window.location="index.php"</script>';
		}
	}
	else
	{
		$item_array = array(
			'item_id' => $_GET["id"] ,
			'item_name' => $_POST["hidden_name"] ,
			'item_price' => $_POST["hidden_price"] ,
			'item_quantity' => $_POST["quantity"]
			);
		$_SESSION["shoping_cart"][0]=$item_array;
	}
}
if(isset($_GET["action"]))
{
	if($_GET["action"] =="delete")
	{
		foreach ($_SESSION["shoping_cart"] as $keys => $values) {
			if($values["item_id"]==$_GET["id"])
			{
				unset($_SESSION["shoping_cart"][$keys]);
				echo '<script>alert("Item Removed")</script>';
				echo '<script>window.location="index.php"</script>';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Online Shoping Cart</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"></script>
	</head>
	<body>
		<br />
		<div class="container" style="width: 700px;">
			<h3 align="center">Shoping Cart</h3><br />
			<?php
			$query="SELECT * FROM products ORDER BY id ASC";
			$result = mysqli_query($connect, $query);
			if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
						{
							?>
							<div class="col-md-4">
								<form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
									<div style="border: 1px solid #333; background-color: #f3f3f1; border-radius: 5px; padding:16px;" align="center">
										<img src="<?php echo $row["image"]; ?>" class="img-responsive" /><br />
										<h4 class="text-info"><?php echo $row["p_name"]; ?></h4>
										<h4 class="text-danger">Tk. <?php echo $row["price"]; ?></h4>
										<input type="text" name="quantity" class="form-control" value="1" />
										<input type="hidden" name="hidden_name" value="<?php echo $row["p_name"]; ?>" />
										<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
										<input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />
									</div>
								</form>
							</div>
							<?php

						}
				}
			?>
			<div style="clear: both"></div>
			<br />
			<h3>Order Details</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="40%">Item Name</th>
						<th width="10%">Quantity</th>
						<th width="20%">Price</th>
						<th width="15%">Total</th>
						<th width="5%">Action</th>
					</tr> 
					<?php 
					if(!empty($_SESSION["shoping_cart"]))
					{
						$total=0;
						foreach ($_SESSION["shoping_cart"] as $keys => $values) {
							?>
							<tr>
								<td><?php echo $values["item_name"]; ?></td>
								<td><?php echo $values["item_quantity"]; ?></td>
								<td>$ <?php echo $values["item_price"]; ?></td>
								<td><?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
								<td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
							</tr>
							<?php
								$total=$total+($values["item_quantity"]*$values["item_price"]);
						}
						?>
						<tr>
							<td colspan="3" align="right">Total</td>
							<td align="right">$ <?php echo number_format($total, 2); ?></td>
							<td></td>
						</tr>
						<?php
					}
					 ?>
					
				</table>
				
			</div>

		</div>
	</body>
</html>