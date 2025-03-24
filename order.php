<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';


	/*
	 * TO-DO: Define a function that retrives ALL customer and order info from the database based on values entered into form.
	 		  - Write SQL query to retrieve ALL customer and order info based on form values
	 		  - Execute the SQL query using the pdo function and fetch the result
	 		  - Return the order info
	 */
	function get_order_info(PDO $pdo, array $form_data) {
		// Extract form data (ensure proper sanitization before usage)
		$email = isset($form_data['email']) ? $form_data['email'] : '';  // Assuming 'email' is passed in the form data
    	$ordernum = isset($form_data['ordernum']) ? $form_data['ordernum'] : '';
		$custnum = isset($form_data['custnum']) ? $form_data['custnum'] : '';
		$date_ordered = isset($form_data['date_ordered']) ? $form_data['date_ordered'] : '';
	
		// SQL query to retrieve customer and order details based on form values
		$sql = "SELECT customer.*, orders.*
				FROM customer
				JOIN orders ON customer.custnum = orders.custnum
				WHERE customer.email = :email AND orders.ordernum = :ordernum";
	
		// Add conditions based on form input
		$params = [
			'email' => $email,
			'ordernum' => $ordernum
		];
		if ($custnum) {
			$sql .= " AND customer.custnum = :custnum";
			$params['custnum'] = $custnum;
		}
		if ($date_ordered) {
			$sql .= " AND orders.date_ordered = :date_ordered";
			$params['date_ordered'] = $date_ordered;
		}
	
		// Execute query and fetch results
		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Using fetchAll() to return all matching records
	}

	
	// Check if the request method is POST (i.e, form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Retrieve the value of the 'email' field from the POST data
		$email = $_POST['email'];

		// Retrieve the value of the 'orderNum' field from the POST data
		$ordernum = $_POST['ordernum'];


		/*
		 * TO-DO: Retrieve info about order from the db using provided PDO connection
		 */
		$order_info = get_order_info($pdo, ['email' => $email, 'ordernum' => $ordernum]);
		
	}
// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Toys R URI</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
					<img src="imgs/logo.png" alt="Toy R URI Logo">
      			</div>

	      		<nav>
	      			<ul>
	      				<li><a href="index.php">Toy Catalog</a></li>
	      				<li><a href="about.php">About</a></li>
			        </ul>
			    </nav>
		   	</div>

		    <div class="header-right">
		    	<ul>
		    		<li><a href="order.php">Check Order</a></li>
		    	</ul>
		    </div>
		</header>

		<main>

			<div class="order-lookup-container">
				<div class="order-lookup-container">
					<h1>Order Lookup</h1>
					<form action="order.php" method="POST">
						<div class="form-group">
							<label for="email">Email:</label>
							<input type="email" id="email" name="email" required>
						</div>

						<div class="form-group">
							<label for="ordernum">Order Number:</label>
							<input type="text" id="ordernum" name="ordernum" required>
						</div>

						<button type="submit">Lookup Order</button>
					</form>
				</div>
				
				<!-- 
				  -- TO-DO: Check if variable holding order is not empty. Make sure to replace null with your variable!
				  -->
				
				  <?php if (!empty($order_info)): ?>
    	<div class="order-details">
        <h1>Order Details</h1>
        <p><strong>Name: </strong> <?= htmlspecialchars($order_info[0]['cname']) ?></p>
        <p><strong>Username: </strong> <?= htmlspecialchars($order_info[0]['username']) ?></p>
        <p><strong>Order Number: </strong> <?= htmlspecialchars($order_info[0]['ordernum']) ?></p>
        <p><strong>Quantity: </strong> <?= htmlspecialchars($order_info[0]['quantity']) ?></p>
        <p><strong>Date Ordered: </strong> <?= htmlspecialchars($order_info[0]['date_ordered']) ?></p>
        <p><strong>Delivery Date: </strong> <?= htmlspecialchars($order_info[0]['date_deliv']) ?></p>
    </div>
<?php endif; ?>
				

			</div>

		</main>

	</body>

</html>
