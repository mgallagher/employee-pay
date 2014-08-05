<!-- 
Michael Gallagher
CS2610
HW7 - LOTOJA 
-->

<!DOCTYPE html>
<!--
URL: http://cs2610.cs.usu.edu/~mgallagher/HW9/index.php
-->
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Computing Tax</title>
	<!-- Custom CSS -->
	<link href="./styles.css" rel="stylesheet" type="text/css" />
</head>

	<body>
		<h1>Employee Pay Calculator</h1>
		<?php
		include 'Employee.php';

		$pay = array();

		$employees[] = new Employee("Harold Wilson",18.5,6);

		$employees[] = new Employee("Carl Walters",14.32);

		$employees[] = new Employee("Walter Scott");        

		$employees[] = new Employee("Carol Knighton",42.95);

		$employees[] = new Employee("Andrew Sawyer",28.75,8);

		$employees[] = new Employee("Caroline Johnson");

		$employees[] = new Employee("Anthony Meyer",2.75,8);

		$employees[] = new Employee("Drew Carlson",23,12);        

		$employees[] = new Employee("Mary Johnson",31.5,-2);

		for ($i = 0; $i < count($employees); $i++)
		{
			$pay[$i] = $employees[$i]->computePay(40);
		}

		$error = false;

		if(!isset($_POST['submit']))
		{
			$message = "Please enter hours worked for the week";
			$hours = array_fill(0,count($employees),"");
			$payHeader = "";
			$payMsg = array_fill(0,count($employees),"");;
		}

		else
		{
			$hours = $_POST['hours'];

			for ($i = 0; $i < count($employees); $i++)
			{
				if ($hours[$i] < 0)
				{	
					$payMsg[$i] = "Must be greater than 0!";
					$error = true;
				}

				// Blank input allowed, doesn't set off error
				else if (empty($hours[$i]))
				{
					$payMsg[$i] = "";
				}

				else if (!is_numeric($hours[$i]))
				{
					$payMsg[$i] = "Value must be a number!";
					$error = true;
				}

				else
				{
					$payMsg[$i] = "";
				}
			}

			if (!$error)
			{
				$payHeader = "Pay";

				for ($i = 0; $i < count($employees); $i++)
				{
					if (!empty($hours[$i]))
					{
						$pay[$i] = $employees[$i]->computePay($hours[$i]);
						$payMsg[$i] = '$' . $pay[$i];
					}

					else
					{
						$payMsg[$i] = "";
					}
				}
			}

			else if ($error)
			{
				$payHeader = "";
			}
			
		}


	echo '<form action="index.php" method="post" class="fields">';
	echo "
	<table>
		<tr>
			<th>Name</th>
			<th>Hours</th>
			<th> $payHeader </th>
		</tr>
		";

		// Loops through employees array
		for ($i = 0; $i < count($employees); $i++) 
		{
			echo '
			<tr>
				<td> '. $employees[$i] .' </td>
				<td><input type="text" name="hours[]" value="'. $hours[$i] .'"/></td>
				<td>'. $payMsg[$i] .'</td>
			</tr>';
		}
		echo "</table>";
		?>
		<span class="button"><input type="submit" value="Submit" name="submit" /></span>
		</form>
	
<!-- 	<pre id="testing">
		<?php 
		// $_GET;
		// print_r($_POST);
		// print_r($payMsg);
		?>
	</pre> -->

	</body>
</html>
