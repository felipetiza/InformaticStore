<?php

	// Cart variables
	$cartProductIDAndAmount = [[]];
	$cartProductsNumber     = 0;
	$cartTotalPrice         = 0;
	$cartProductID          = [];
	$cartProductAmount      = [];
	$cartProductData        = [[]];
	$cartProductName        = [];
	$cartProductPrice       = [];

	function getUserData($connection, $userID){
		$userData = [];
		$getusername = "SELECT *
	                    FROM customer
	                    WHERE idcustomer = $userID;
	                   ";

	    if ($result = $connection->query($getusername)) {
	        if ($result->num_rows > 0){
	            $product = $result->fetch_object();

				$userData['name']     = $product->name;
				$userData['surname']  = $product->surname;
				$userData['email']    = $product->email;
				$userData['address']  = $product->address;
				$userData['phone']    = $product->phone;
				$userData['type']     = $product->type;
				$userData['username'] = $product->username;
				$userData['password'] = $product->password;

	        	return $userData;
	        }else
	            echo "Impossible to get the user data";
	    }else
	        echo "Wrong Query";
	}

	function getProductData($connection, $id){
	    $productData = [];
	    $getproduct = "SELECT *
	                   FROM product
	                   WHERE idproduct = $id;
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

	function getProductCategory($connection){
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

    function deleteProductFromCart($connection, $id){
        $getProducts = "DELETE FROM shopping_cart WHERE idproduct = $id;";

        if ($result = $connection->query($getProducts)) {
            if ($result)
            	$_SESSION["modalWindow"] = "true";
            else
                echo "Impossible to delete the product";
        }else
            echo "Wrong Query";
    }

    function clearCart($connection){
        $getProducts = "DELETE FROM shopping_cart;";

        if ($result = $connection->query($getProducts)) {
            if ($result)
            	$_SESSION["modalWindow"] = "true";
            else
                echo "Impossible to clear the cart";
        }else
            echo "Wrong Query";
    }

    function makePurchase($connection, $userID, $prodNumber, $prodTotalPrice){
    	$date = date('Y-m-d');
    	$getproduct = "INSERT INTO order2
        			   VALUES(NULL, $userID, '$date', $prodNumber, $prodTotalPrice);
                      ";

        if ($result = $connection->query($getproduct)){
            if (!$result)
                echo "Can't make the purchase. Impossible insert within of the order.";
        }else
            echo "Wrong Query";
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

	function getProductDataFromCart($connection, $productNumber, $prodID){
		$cartProductData = [];		// Array 2 dimensions

		for($i=0;$i<$productNumber;$i++){
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
		$cartProductIDAndAmount = getProductIDFromCart($connection, $_SESSION['iduser']);
		$cartProductsNumber     = count($cartProductIDAndAmount);
		$cartTotalPrice         = 0;
        // Get products data of client
		$cartProductID     = [];
		$cartProductAmount = [];

		foreach($cartProductIDAndAmount as $id=>$amount){
			array_push($cartProductID, $id);
			array_push($cartProductAmount, $amount);
		}

		$cartProductData  = getProductDataFromCart($connection, $cartProductsNumber, $cartProductID);
		$cartProductName  = (count($cartProductData) != 0) ? $cartProductData['name'] : [];
		$cartProductPrice = (count($cartProductData) != 0) ? $cartProductData['price'] : [];

		// Calculates the purchase price
		for($i=0;$i<$cartProductsNumber;$i++)
			$cartTotalPrice += $cartProductPrice[$i] * $cartProductAmount[$i];
	}























?>