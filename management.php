<?php
	const MAIN_PAGE = "../informaticstore";
	// const MAIN_PAGE = "../";	// Go to the proyect root. Load the index file

	$connection;

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

	// Product variables
	$productID       = [];
	$productName     = [];
	$productCategory = [];
	$productDescript = [];
	$productPrice    = [];
	$productAmount   = [];
	$productImg      = [];

	// Order variables
	$orderOrderID    = [];
	$orderCustomerID = [];
	$orderDate       = [];
	$orderAmountProd = [];
	$orderPrice      = [];
	$orderProduct    = [[]];
	$orderAmount     = [[]];
	$productName     = [[]];
	$productPrice    = [[]];

	function databaseConnection(){
		global $connection;

	    // if(isset($_ENV['OPENSHIFT_APP_NAME'])){
	    //     $user     = $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
	    //     $host     = $_ENV['OPENSHIFT_MYSQL_DB_HOST'];
	    //     $password = $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];
	    //     $database = "informaticstore";
	    // }else{
	    //     $host     = "localhost";
	    //     $user     = "informatic";
	    //     $password = "store";
	    //     $database = "informaticstore";
	    // }

    	// $host     = "mysql.hostinger.es";
     //    $user     = "u449232361_inf";
     //    $password = "J9KMLLpWt2JmNmys09";
     //    $database = "u449232361_store";

	    $host     = "localhost";
        $user     = "informatic";
        $password = "store";
        $database = "informaticstore";

	    $connection = new mysqli($host, $user, $password, $database);
	    $connection->set_charset("utf8");

	    if ($connection->connect_errno) {
	        printf("Connection failed: %s\n", $connection->connect_error);
	        exit();
	    }
	    return $connection;
	}


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
				$userData['pass']     = $customer->password;

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
		// If password is encoded with sha1 algorithm
		$pass = (strlen($userData['pass']) >= 40) ? $userData['pass'] : sha1($userData['pass']);

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

				$productData['id']          = $product->idproduct;
				$productData['name']        = $product->name;
				$productData['category']    = $product->category;
				$productData['description'] = $product->description;
				$productData['price']       = $product->price;
				$productData['amount']      = $product->amount;
				$productData['urlImage']    = $product->urlimage;

				return $productData;
	        }else
	            echo "Impossible to get the product data";
	    }else
	        echo "Wrong Query";
    }

	function getProductDataArray($connection, $prodID){
		$productData = [[[]]];

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
						$productData['urlImage'][$i][$j]    = $product->urlimage;
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
					$productData['id'][$i]          = $product->idproduct;
					$productData['name'][$i]        = $product->name;
					$productData['category'][$i]    = $product->category;
					$productData['description'][$i] = $product->description;
					$productData['price'][$i]       = $product->price;
					$productData['amount'][$i]      = $product->amount;
					$productData['urlImage'][$i]    = $product->urlimage;
					$i++;
            	}
            }else
                echo "Impossible to get the products";
        }else
            echo "Wrong Query";

        return $productData;
	}

	function refreshProducts($connection){
		global $productID;
		global $productName;
		global $productCategory;
		global $productDescript;
		global $productPrice;
		global $productAmount;
		global $productImg;

		$productsData = getAllProduct($connection);
		// != 1  -> The array isn't empty. '1' because array is 2 dimensions
		$productID       = (count($productsData) != 1) ? $productsData['id'] : [];
		$productName     = (count($productsData) != 1) ? $productsData['name'] : [];
		$productCategory = (count($productsData) != 1) ? $productsData['category'] : [];
		$productDescript = (count($productsData) != 1) ? $productsData['description'] : [];
		$productPrice    = (count($productsData) != 1) ? $productsData['price'] : [];
		$productAmount   = (count($productsData) != 1) ? $productsData['amount'] : [];
		$productImg      = (count($productsData) != 1) ? $productsData['urlImage'] : [];
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
					$productData['urlImage'][$i] = $product->urlimage;
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

	function deleteProduct($connection, $productID){
        $deleteProduct = "DELETE FROM product WHERE idproduct = $productID;";

	    if ($result = $connection->query($deleteProduct)) {
            if (!$result)
                echo "Impossible to delete the product";
        }else
            echo "Wrong Query";
	}

	function insertProduct($connection, $productData){
		$id       = $productData['id'];
		$name     = $productData['name'];
		$category = $productData['category'];
		$descript = $productData['description'];
		$price    = $productData['price'];
		$amount   = $productData['amount'];
		$urlImage = $productData['urlImage'];

        $insertUser = "INSERT INTO product
                       VALUES($id, '$name', '$category', '$descript', '$price', '$amount', '$urlImage');
                      ";

	    if ($result = $connection->query($insertUser)) {
            if (!$result)
                echo "Impossible to insert the product";
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

	function getCartProductAndAmount($connection, $userID){
        $cartProductAndAmount = [[]];
        $i = 0;
        $getCart = "SELECT *
                    FROM shopping_cart
                    WHERE idcustomer = $userID;
                   ";

        if ($result = $connection->query($getCart)) {
            if ($result->num_rows > 0){
                while($product = $result->fetch_object()){
					$cartProductAndAmount['idProduct'][$i] = $product->idproduct;
					$cartProductAndAmount['amount'][$i]    = $product->amount;
					$i++;
				}
            }else{
                // echo "The shopping cart is empty";
            }
        }else
            echo "Wrong Query";
		return $cartProductAndAmount;
    }

    function getProductIDFromContain($connection, $userID){
        $cartProductID = [];	// Asociative array
        $getusername = "SELECT *
                        FROM contain
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
		$cartProductIDAndAmount = getCartProductAndAmount($connection, $_SESSION['userID']);
		$cartProductID     = (count($cartProductIDAndAmount) != 1) ? $cartProductIDAndAmount['idProduct']: [];
		$cartProductAmount = (count($cartProductIDAndAmount) != 1) ? $cartProductIDAndAmount['amount'] : [];

		$cartProductData  = getProductDataFromCart($connection, $cartProductID);
		$cartProductName  = (count($cartProductData) != 1) ? $cartProductData['name'] : [];
		$cartProductPrice = (count($cartProductData) != 1) ? $cartProductData['price'] : [];

		$cartProductsNumber = count($cartProductID);
		$cartTotalPrice     = 0;

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


    function getOrderData($connection, $orderID){
	    $orderData = [];
	    $getOrder = "SELECT *
	                 FROM order2
	                 WHERE idorder = $orderID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	$order = $result->fetch_object();
				$orderData['orderID']        = $order->idorder;
				$orderData['customerID']     = $order->idcustomer;
				$orderData['date']           = $order->dateorder;
				$orderData['amountProducts'] = $order->amountproducts;
				$orderData['price']          = $order->totalprice;
				return $orderData;
	        }else{
	            // echo "Impossible to get the order data";
	        }
	    }else
	        echo "Wrong Query";
    }

	function getOrderDataOfAClient($connection, $userID){
	    $orderData = [[]];
		$i = 0;
	    $getOrder = "SELECT *
	                 FROM order2
	                 WHERE idcustomer = $userID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	while($order = $result->fetch_object()){
					$orderData['orderID'][$i]    = $order->idorder;
					$orderData['customerID'][$i] = $order->idcustomer;
					$orderData['date'][$i]       = $order->dateorder;
					$orderData['amount'][$i]     = $order->amountproducts;
					$orderData['price'][$i]      = $order->totalprice;
					$i++;
				}
				return $orderData;
	        }else{
	            // echo "Impossible to get the order data";
	        }
	    }else
	        echo "Wrong Query";
    }

    function getAllOrders($connection){
		$orderData = [[]];
		$i = 0;
		$getOrders = "SELECT * FROM order2;";

        if ($result = $connection->query($getOrders)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
					$orderData['orderID'][$i]    = $product->idorder;
					$orderData['customerID'][$i] = $product->idcustomer;
					$orderData['date'][$i]       = $product->dateorder;
					$orderData['amount'][$i]     = $product->amountproducts;
					$orderData['price'][$i]      = $product->totalprice;
					$i++;
            	}
            }else{
                // echo "Impossible to get the orders";
            }
        }else
            echo "Wrong Query";

        return $orderData;
	}

	function getOrderProductAndAmountFromArray($connection, $orderID){
	    $orderProductAndAmount = [[[]]];	// idProduct - order - value

	    for($i=0;$i<count($orderID);$i++){	// Orders (i) - $orderID is a array
	 	    $j = 0;							// Values (j)
		    $getOrder = "SELECT *
		                 FROM contain
		                 WHERE idorder = ".$orderID[$i].";
		                ";

		    if ($result = $connection->query($getOrder)) {
		        if ($result->num_rows > 0){
		        	while($order = $result->fetch_object()){	// A order have a few rows in the database
						$orderProductAndAmount['idProduct'][$i][$j] = $order->idproduct;
						$orderProductAndAmount['amount'][$i][$j]    = $order->amount;
						$j++;
					}
		        }else
		            echo "Impossible to get the relationship between product and its quantity.";
		    }else
		        echo "Wrong Query";
		}
		return $orderProductAndAmount;
	}

	function getOrderProductAndAmount($connection, $orderID){
	    $orderProductAndAmount = [[]];	// idProduct - value
 	    $i = 0;
	    $getOrder = "SELECT *
	                 FROM contain
	                 WHERE idorder = $orderID;
	                ";

	    if ($result = $connection->query($getOrder)) {
	        if ($result->num_rows > 0){
	        	while($order = $result->fetch_object()){
					$orderProductAndAmount['idProduct'][$i] = $order->idproduct;
					$orderProductAndAmount['amount'][$i]    = $order->amount;
					$i++;
				}
	        }else
	            echo "Impossible to get the relationship between product and its quantity.";
	    }else
	        echo "Wrong Query";

		return $orderProductAndAmount;
	}

	function refreshOrders($connection){
		global $orderOrderID;
		global $orderCustomerID;
		global $orderDate;
		global $orderAmount;
		global $orderPrice;

		$ordersData = getAllOrders($connection);

		// != 1  -> The array isn't empty. '1' because array is 2 dimensions
		$orderOrderID    = (count($ordersData) != 1) ? $ordersData['orderID'] : [];
		$orderCustomerID = (count($ordersData) != 1) ? $ordersData['customerID'] : [];
		$orderDate       = (count($ordersData) != 1) ? $ordersData['date'] : [];
		$orderAmount     = (count($ordersData) != 1) ? $ordersData['amount'] : [];
		$orderPrice      = (count($ordersData) != 1) ? $ordersData['price'] : [];
	}

	function refreshOrdersOfAClient($connection, $userID){
		global $orderOrderID;
		global $orderCustomerID;
		global $orderDate;
		global $orderAmountProd;
		global $orderPrice;
		global $orderProduct;
		global $orderAmount;
		global $productName;
		global $productPrice;

		$ordersData = getOrderDataOfAClient($connection, $userID);								// Return 2 dimensions
		$orderOrderID    = $ordersData['orderID'];
		$orderCustomerID = $ordersData['customerID'];
		$orderDate       = $ordersData['date'];
		$orderAmountProd = $ordersData['amount'];
		$orderPrice      = $ordersData['price'];

		// != 1  -> The array isn't empty. '1' because array is 3 dimensions
		$orderProductAndAmount = getOrderProductAndAmountFromArray($connection, $orderOrderID);	// Return 3 dimensions
		$orderProduct = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['idProduct'] : [];
		$orderAmount  = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['amount'] : [];

		$productData = getProductDataArray($connection, $orderProduct);							// Return 3 dimensions
		$productName  = (count($productData) != 1) ? $productData['name'] : [];
		$productPrice = (count($productData) != 1) ? $productData['price'] : [];

		/*
		$productName[0][0]				// Order 0 - Prod 1
		$productName[1][0]				// Order 1 - Prod 1
		$productName[1][1]				// Order 1 - Prod 2
		$productName[1][2]				// Order 1 - Prod 3

		echo count($productName);		// 2 Orders

		echo count($productName[0]);	// Order 1 - 1 Prod
		echo count($productName[1]);	// Order 2 - 3 Prod
		*/
	}

	function deleteOrder($connection, $orderID){
        $deleteOrder = "DELETE FROM order2 WHERE idorder = $orderID;";

	    if ($result = $connection->query($deleteOrder)) {
            if (!$result)
                echo "Impossible to delete the order";
        }else
            echo "Wrong Query";
	}

	function insertOrder($connection, $orderData){
		$orderID        = $orderData['orderID'];
		$customerID     = $orderData['customerID'];
		$date           = (strlen($orderData['date']) == 0) ? date('Y-m-d') : $orderData['date'];
		$amountProducts = $orderData['amountProducts'];
		$price          = $orderData['price'];
		$lastID			= 0;

        $insertOrder = "INSERT INTO order2
                        VALUES($orderID, $customerID, '$date', $amountProducts, $price);
                       ";

	    if ($result = $connection->query($insertOrder)) {

            if ($result)
            	$lastID = $connection->insert_id;
            else
                echo "Impossible insert within of the order.";

        }else
            echo "Wrong Query";

        return $lastID;
	}

	function insertContain($connection, $idorder, $productID, $productAmount){
        for($i=0;$i<count($productID);$i++){
	    	$setPurchase = "INSERT INTO contain
	        			    VALUES($idorder, $productID[$i], $productAmount[$i]);
	                       ";

	        if ($result = $connection->query($setPurchase)){
	            if (!$result)
	                echo "Impossible insert within of the contain table.";
	        }else
	            echo "Wrong Query";
	    }
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

	function loadModalWindow($modalWindow, $buttonClose){
		echo "<script>
				window.onload = function(){
					loadModalWindow('$modalWindow', '$buttonClose');
			 	};
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

	function shortenStrings($string, $maxNumberCharacter){
		if(strlen($string) >= $maxNumberCharacter)
			echo substr($string, 0, $maxNumberCharacter)."...";
		else
			echo $string;
	}

	// function uploadImg(){
	// 	$valid       = true;
	// 	$tmp_file    = $_FILES['addImg']['tmp_name'];
	// 	$target_dir  = "resources/img/product/";
	// 	$target_file = strtolower($target_dir . basename($_FILES['addImg']['name']));

 //        if (file_exists($target_file)) {
 //            echo "Sorry, file already exists.";
 //            $valid = false;
 //        }

 //        if($_FILES['addImg']['size'] > (2048000)) {
 //            $valid = false;
	//         echo 'Oops!  Your file\'s size is to large.';
 //        }

 //        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
 //        if ($file_extension != "jpg" &&
 //            $file_extension != "jpeg" &&
 //            $file_extension != "png" &&
 //            $file_extension != "gif") {
 //            $valid = false;
 //            echo "Only JPG, JPEG, PNG & GIF files are allowed";
 //        }
	// }
?>
