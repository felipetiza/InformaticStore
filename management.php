<?php
	const MAIN_PAGE = "../informaticstore";


	// Cart variables
	$cartProductIDAndAmount = [[]];
	$cartProductsNumber     = 0;
	$cartTotalPrice         = 0;
	$cartProductID          = [];
	$cartProductAmount      = [];
	$cartProductData        = [[]];
	$cartProductName        = [];
	$cartProductPrice       = [];

	// User variables
	$userID       = [];
	$userName     = [];
	$userSurname  = [];
	$userEmail    = [];
	$userAddress  = [];
	$userPhone    = [];
	$userType     = [];
	$userUsername = [];
	$userPassword = [];



// ██╗   ██╗███████╗███████╗██████╗
// ██║   ██║██╔════╝██╔════╝██╔══██╗
// ██║   ██║███████╗█████╗  ██████╔╝
// ██║   ██║╚════██║██╔══╝  ██╔══██╗
// ╚██████╔╝███████║███████╗██║  ██║
//  ╚═════╝ ╚══════╝╚══════╝╚═╝  ╚═╝

	function getUserData($connection, $userID){
		$userData = [];
		$getCustomer = "SELECT *
	                    FROM customer
	                    WHERE idcustomer = $userID;
	                   ";

	    if ($result = $connection->query($getCustomer)) {
	        if ($result->num_rows > 0){
	            $customer = $result->fetch_object();
				$userData['id']       = $customer->idcustomer;
				$userData['name']     = $customer->name;
				$userData['surname']  = $customer->surname;
				$userData['email']    = $customer->email;
				$userData['address']  = $customer->address;
				$userData['phone']    = $customer->phone;
				$userData['type']     = $customer->type;
				$userData['username'] = $customer->username;
				$userData['pass'] = $customer->password;

	        	return $userData;
	        }else
	            echo "Impossible to get the user data <br><br>";
	    }else
	        echo "Wrong Query";
	}

	function getAllUser($connection){
		$userData = [[]];
		$i = 0;
		$getCustomer = "SELECT * FROM customer;";

        if ($result = $connection->query($getCustomer)){
            if ($result->num_rows > 0){
            	while($customer = $result->fetch_object()){
					$userData['id'][$i]       = $customer->idcustomer;
					$userData['name'][$i]     = $customer->name;
					$userData['surname'][$i]  = $customer->surname;
					$userData['email'][$i]    = $customer->email;
					$userData['address'][$i]  = $customer->address;
					$userData['phone'][$i]    = $customer->phone;
					$userData['type'][$i]     = $customer->type;
					$userData['username'][$i] = $customer->username;
					$userData['password'][$i] = $customer->password;
					$i++;
            	}
            	return $userData;
            }else
                echo "Impossible to get all the users";
        }else
            echo "Wrong Query";
	}

	function refreshUsers($connection){
		global $userID;
		global $userName;
		global $userSurname;
		global $userEmail;
		global $userAddress;
		global $userPhone;
		global $userType;
		global $userUsername;
		global $userPassword;

		$usersData = getAllUser($connection);
		$userID       = $usersData['id'];
		$userName     = $usersData['name'];
		$userSurname  = $usersData['surname'];
		$userEmail    = $usersData['email'];
		$userAddress  = $usersData['address'];
		$userPhone    = $usersData['phone'];
		$userType     = $usersData['type'];
		$userUsername = $usersData['username'];
		$userPassword = $usersData['password'];
	}

	function deleteUser($connection, $userID){
        $deleteUser = "DELETE FROM customer WHERE idcustomer = $userID;";

	    if ($result = $connection->query($deleteUser)) {
            if (!$result)
                echo "Impossible to delete the user";
        }else
            echo "Wrong Query";
	}

	function insertUser($connection, $userData){
		$id      = $userData['id'];
		$name    = $userData['name'];
		$surname = $userData['surname'];
		$email   = $userData['email'];
		$address = $userData['address'];
		$phone   = $userData['phone'];
		$type    = $userData['type'];
		$user    = $userData['user'];
		$pass    = $userData['pass'];

        $insertUser = "INSERT INTO customer
                       VALUES($id, '$name', '$surname', '$email', '$address', '$phone', '$type', '$user', '$pass');
                      ";

	    if ($result = $connection->query($insertUser)) {
            if (!$result)
                echo "Impossible to insert the user";
        }else
            echo "Wrong Query";
	}


// ██████╗ ██████╗  ██████╗ ██████╗ ██╗   ██╗ ██████╗████████╗
// ██╔══██╗██╔══██╗██╔═══██╗██╔══██╗██║   ██║██╔════╝╚══██╔══╝
// ██████╔╝██████╔╝██║   ██║██║  ██║██║   ██║██║        ██║
// ██╔═══╝ ██╔══██╗██║   ██║██║  ██║██║   ██║██║        ██║
// ██║     ██║  ██║╚██████╔╝██████╔╝╚██████╔╝╚██████╗   ██║
// ╚═╝     ╚═╝  ╚═╝ ╚═════╝ ╚═════╝  ╚═════╝  ╚═════╝   ╚═╝

	function getProductData($connection, $prodID){
	    $productData = [];
	    $getproduct = "SELECT *
	                   FROM product
	                   WHERE idproduct = $prodID;
	                  ";

	    if ($result = $connection->query($getproduct)) {
	        if ($result->num_rows > 0){
	        	$product = $result->fetch_object();

				$productData['name']        = $product->name;
				$productData['category']    = $product->category;
				$productData['description'] = $product->description;
				$productData['price']       = $product->price;
				$productData['amount']      = $product->amount;
				$productData['urlimage']    = $product->urlimage;

				return $productData;
	        }else
	            echo "Impossible to get the product data";
	    }else
	        echo "Wrong Query";
    }

	function getProductDataArray($connection, $prodID){
		$productData = [[]];

		for($i=0;$i<count($prodID);$i++){				// Orders
			for($j=0;$j<count($prodID[$i]);$j++){		// Values
		        $getProducts = "SELECT *
		                        FROM product
		                        WHERE idproduct = ".$prodID[$i][$j].";
		                       ";

		        if ($result = $connection->query($getProducts)) {
		            if ($result->num_rows > 0){
		            	$product = $result->fetch_object();

						$productData['name'][$i][$j]        = $product->name;
						$productData['category'][$i][$j]    = $product->category;
						$productData['description'][$i][$j] = $product->description;
						$productData['price'][$i][$j]       = $product->price;
						$productData['amount'][$i][$j]      = $product->amount;
						$productData['urlimage'][$i][$j]    = $product->urlimage;
		            }else
		                echo "Impossible to get the products";
		        }else
		            echo "Wrong Query";
		    }
		}
	    return $productData;
	}

    function getAllProduct($connection){
		$productData = [[]];
		$i = 0;
		$getProducts = "SELECT * FROM product;";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
					$productData['id'][$i]       = $product->idproduct;
					$productData['name'][$i]     = $product->name;
					$productData['price'][$i]    = $product->price;
					$productData['urlimage'][$i] = $product->urlimage;
					$i++;
            	}
            }else
                echo "Impossible to get the products";
        }else
            echo "Wrong Query";

        return $productData;
	}

	function getProductCategory($connection, $categ){
		$productData = [[]];
		$i = 0;
		$getProducts = "SELECT * FROM product WHERE category = '$categ';";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
					$productData['id'][$i]       = $product->idproduct;
					$productData['name'][$i]     = $product->name;
					$productData['price'][$i]    = $product->price;
					$productData['urlimage'][$i] = $product->urlimage;
					$i++;
            	}
            }else
                echo "Impossible to get the products by category";
        }else
            echo "Wrong Query";
        return $productData;
	}

	function getAllProductCategory($connection){
		$productCategory = [];
        $getProducts = "SELECT DISTINCT category FROM product ORDER BY category ASC;";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object())
            		array_push($productCategory, $product->category);
            	return $productCategory;
            }else
                echo "Impossible to get the products category";
        }else
            echo "Wrong Query";
	}


// ███████╗██╗  ██╗ ██████╗ ██████╗ ██████╗ ██╗███╗   ██╗ ██████╗      ██████╗ █████╗ ██████╗ ████████╗
// ██╔════╝██║  ██║██╔═══██╗██╔══██╗██╔══██╗██║████╗  ██║██╔════╝     ██╔════╝██╔══██╗██╔══██╗╚══██╔══╝
// ███████╗███████║██║   ██║██████╔╝██████╔╝██║██╔██╗ ██║██║  ███╗    ██║     ███████║██████╔╝   ██║
// ╚════██║██╔══██║██║   ██║██╔═══╝ ██╔═══╝ ██║██║╚██╗██║██║   ██║    ██║     ██╔══██║██╔══██╗   ██║
// ███████║██║  ██║╚██████╔╝██║     ██║     ██║██║ ╚████║╚██████╔╝    ╚██████╗██║  ██║██║  ██║   ██║
// ╚══════╝╚═╝  ╚═╝ ╚═════╝ ╚═╝     ╚═╝     ╚═╝╚═╝  ╚═══╝ ╚═════╝      ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝

    function deleteProductFromCart($connection, $id){
        $getProducts = "DELETE FROM shopping_cart WHERE idproduct = $id;";

        if ($result = $connection->query($getProducts)) {
            if (!$result)
                echo "Impossible to delete the product";
        }else
            echo "Wrong Query";
    }

    function clearCart($connection){
        $getProducts = "DELETE FROM shopping_cart;";

        if ($result = $connection->query($getProducts)) {
            if (!$result)
                echo "Impossible to clear the cart";
        }else
            echo "Wrong Query";
    }

    function makePurchase($connection, $userID, $prodNumber, $prodTotalPrice){
    	// Insert order
    	$date = date('Y-m-d');
    	$setPurchase = "INSERT INTO order2
        			    VALUES(NULL, $userID, '$date', $prodNumber, $prodTotalPrice);
                       ";

        if ($result = $connection->query($setPurchase)){
            if (!$result)
                echo "Can't make the purchase. Impossible insert within of the order.";
        }else
            echo "Wrong Query";

        // Get the idorder of previous query
        // Returns the next idorder to insert, so we subtract 1
        $idorder;
        $getOrderID = "SELECT AUTO_INCREMENT
					   FROM information_schema.tables
					   WHERE table_name = 'order2' AND
					   		 table_schema = 'informaticstore';
                      ";

        if ($result = $connection->query($getOrderID)){
            if ($result)
            	$idorder = $result->fetch_object()->AUTO_INCREMENT - 1;
            else
                echo "Can't make the purchase. Impossible to get the idorder of the previous insertion.";
        }else
            echo "Wrong Query";

        // Insert in 'contain' table
        // ==================================
        // - Set the idorder previous gotten
        //
        // Remove 		Relationship saved in table 'shopping_cart' (temporary)
        // Create 		Relationship in table 'contain'

        // Get relationship of product/amount from cart
		$cartProductIDAndAmount = getProductIDFromCart($connection, $userID);
		$cartProductID     = [];
		$cartProductAmount = [];

		foreach($cartProductIDAndAmount as $id=>$amount){
			array_push($cartProductID, $id);
			array_push($cartProductAmount, $amount);
		}

        for($i=0;$i<$prodNumber;$i++){
	    	$setPurchase = "INSERT INTO contain
	        			    VALUES($idorder, $cartProductID[$i], $cartProductAmount[$i]);
	                       ";

	        if ($result = $connection->query($setPurchase)){
	            if (!$result)
	                echo "Can't make the purchase. Impossible insert within of the contain table.";
	        }else
	            echo "Wrong Query";
	    }
    }

    function getProductIDFromCart($connection, $userID){
        $cartProductID = [];	// Asociative array
        $getusername = "SELECT *
                        FROM shopping_cart
                        WHERE idcustomer = $userID;
                       ";

        if ($result = $connection->query($getusername)) {
            if ($result->num_rows > 0){
                while($product = $result->fetch_object())
					$cartProductID[$product->idproduct] = $product->amount;
            }else{
                // echo "The shopping cart is empty";
            }
        }else
            echo "Wrong Query";
		return $cartProductID;
    }

	function getProductDataFromCart($connection, $prodID){
		$cartProductData = [[]];

		for($i=0;$i<count($prodID);$i++){
	        $getProducts = "SELECT *
	                        FROM product
	                        WHERE idproduct = ".$prodID[$i].";
	                       ";

	        if ($result = $connection->query($getProducts)) {
	            if ($result->num_rows > 0){
	            	$product = $result->fetch_object();
					$cartProductData['name'][$i]  = $product->name;
					$cartProductData['price'][$i] = $product->price;
	            }else
	                echo "Impossible to get the products";
	        }else
	            echo "Wrong Query";
		}
	    return $cartProductData;
	}

    function checkProductIsInserted($connection, $prodID){
	    $productInserted = false;

        $getproduct = "SELECT idproduct
        			   FROM shopping_cart
        			   WHERE idproduct = $prodID;
                      ";

        if ($result = $connection->query($getproduct)){
			$productInserted = ($result->num_rows > 0) ? true : false;
        	return $productInserted;
        }else
            echo "Wrong Query";
    }

    function addToCart($connection, $userID, $prodID, $prodAmount){
	    $getproduct = "INSERT INTO shopping_cart
        			   VALUES($userID, $prodID, $prodAmount);
                      ";

        if ($result = $connection->query($getproduct)){
            if (!$result)
                echo "Impossible insert the product within shopping cart";
        }else
            echo "Wrong Query";
    }

	function getAmountProductOfCart($connection, $prodID){
        $getproduct = "SELECT amount
        			   FROM shopping_cart
        			   WHERE idproduct = $prodID;
                      ";

        if ($result = $connection->query($getproduct)){
            if ($result->num_rows > 0)
            	return $result->fetch_object()->amount;
        }else
            echo "Wrong Query";
	}

    function riseAmountProductOfCart($connection, $currentAmount, $newAmount, $userID, $prodID){
        $quantity = $currentAmount;
        $quantity += $newAmount;

        $getproduct = "UPDATE shopping_cart
        			   SET amount = $quantity
                       WHERE idproduct = $prodID;
                      ";

        if ($result = $connection->query($getproduct)){
            if (!$result)
                echo "Impossible update the new quantity of a product";
        }else
            echo "Wrong Query";
    }

	function refreshCart($connection){
		global $cartProductIDAndAmount;
		global $cartProductsNumber;
		global $cartTotalPrice;
		global $cartProductID;
		global $cartProductAmount;
		global $cartProductData;
		global $cartProductName;
		global $cartProductPrice;

        // Get client's products from shopping cart
		$cartProductIDAndAmount = getProductIDFromCart($connection, $_SESSION['userID']);
		$cartProductsNumber     = count($cartProductIDAndAmount);
		$cartTotalPrice         = 0;
        // Get products data of client
		$cartProductID     = [];
		$cartProductAmount = [];

		foreach($cartProductIDAndAmount as $id=>$amount){
			array_push($cartProductID, $id);
			array_push($cartProductAmount, $amount);
		}

		// != 1 -> The cart isn't empty. '1' because is array 2 dimensions
		$cartProductData  = getProductDataFromCart($connection, $cartProductID);
		$cartProductName  = (count($cartProductData) != 1) ? $cartProductData['name'] : [];
		$cartProductPrice = (count($cartProductData) != 1) ? $cartProductData['price'] : [];

		// Calculates the purchase price
		for($i=0;$i<$cartProductsNumber;$i++)
			$cartTotalPrice += $cartProductPrice[$i] * $cartProductAmount[$i];
	}


//  ██████╗ ██████╗ ██████╗ ███████╗██████╗
// ██╔═══██╗██╔══██╗██╔══██╗██╔════╝██╔══██╗
// ██║   ██║██████╔╝██║  ██║█████╗  ██████╔╝
// ██║   ██║██╔══██╗██║  ██║██╔══╝  ██╔══██╗
// ╚██████╔╝██║  ██║██████╔╝███████╗██║  ██║
//  ╚═════╝ ╚═╝  ╚═╝╚═════╝ ╚══════╝╚═╝  ╚═╝


	function getOrderData($connection, $userID){
	    $orderData = [[]];
		$i = 0;
	    $getOrder = "SELECT *
	                 FROM order2
	                 WHERE idcustomer = $userID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	while($order = $result->fetch_object()){
					$orderData['idOrder'][$i]        = $order->idorder;
					$orderData['date'][$i]           = $order->dateorder;
					$orderData['amountproducts'][$i] = $order->amountproducts;
					$orderData['totalprice'][$i]     = $order->totalprice;
					$i++;
				}
				return $orderData;
	        }else{
	            // echo "Impossible to get the order data";
	        }
	    }else
	        echo "Wrong Query";
    }

	function getOrderProductAndAmount($connection, $orderID){
	    $orderProductAndAmount = [[[]]];	// idProduct - order - value ; $i = order |	$j = value

	    for($i=0;$i<count($orderID);$i++){
	 	    $j = 0;
		    $getOrder = "SELECT *
		                 FROM contain
		                 WHERE idorder = ".$orderID[$i].";
		                ";

		    if ($result = $connection->query($getOrder)) {
		        if ($result->num_rows > 0){
		        	while($order = $result->fetch_object()){
						$orderProductAndAmount['idProduct'][$i][$j] = $order->idproduct;
						$orderProductAndAmount['amount'][$i][$j]    = $order->amount;
						$j++;
					}
		        }else
		            echo "Impossible to get the order data";
		    }else
		        echo "Wrong Query";
		}
		return $orderProductAndAmount;
	}



// ██████╗ ██╗   ██╗███╗   ██╗         ██╗███████╗
// ██╔══██╗██║   ██║████╗  ██║         ██║██╔════╝
// ██████╔╝██║   ██║██╔██╗ ██║         ██║███████╗
// ██╔══██╗██║   ██║██║╚██╗██║    ██   ██║╚════██║
// ██║  ██║╚██████╔╝██║ ╚████║    ╚█████╔╝███████║
// ╚═╝  ╚═╝ ╚═════╝ ╚═╝  ╚═══╝     ╚════╝ ╚══════╝


	// Check if user is logged and access only allowed pages
	function checkAccesOption($pageBelongs){
		if(isset($_SESSION["userID"])){
            if($_SESSION["userType"] != $pageBelongs){
            	session_destroy();
				header('Location: '.MAIN_PAGE);
            }
		}else
			header('Location: '.MAIN_PAGE);
	}

	// Return you to the first page
	function checkAccesOptionOnLogin(){
		if(isset($_SESSION["userID"])){
            if($_SESSION["userType"] == "Admin")
                header('Location: admin.php');
            else if($_SESSION["userType"] == "User")
                header('Location: menu.php');
		}
	}

	function loadModalWindow($modalWindow){
		echo "
		      <script>
				  document.addEventListener('load', function(){
				  	  loadModalWindow('$modalWindow');
				  }, true);
			  </script>
			 ";
	}

	function showToast($message){
		echo "<div id='toast'>$message</div>";
        echo "<script>loadToast();</script>";
	}

	function toggleDesignCart(){
		global $cartProductsNumber;

		if($cartProductsNumber == 0){
			echo "<script>";
			echo "document.addEventListener('load', function(){";
			echo "document.getElementById('inner').style.display = 'none';";
			echo "document.getElementById('cartEmpty').style.display = 'flex';";
			echo "}, true);";
			echo "</script>";
		}else{
			echo "<script>";
			echo "document.addEventListener('load', function(){";
			echo "document.getElementById('inner').style.display = 'inline';";
			echo "document.getElementById('cartEmpty').style.display = 'none';";
			echo "}, true);";
			echo "</script>";
		}
	}
?>
