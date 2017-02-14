<?php

	function getUsername($connection){
		$getusername = "SELECT username
	                    FROM customer
	                    WHERE idcustomer = {$_SESSION['iduser']};
	                   ";

	    if ($result = $connection->query($getusername)) {
	        if ($result->num_rows > 0)
	            return $result->fetch_object()->username;
	        else
	            echo "Impossible to get the username";
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
	            echo "Impossible to get the product";
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
        $getProducts = "DELETE FROM shopping_cart WHERE idproduct = $id";

        if ($result = $connection->query($getProducts)) {
            if ($result){
            	// $_SESSION["modalWindow"] = true;
            	header("Refresh:0");
            }else
                echo "Impossible to delete the product";
        }else
            echo "Wrong Query";
    }

    function getProductIDFromCart($connection, $idUser){
        $cartProductID = [];	// Asociative array
        $getusername = "SELECT *
                        FROM shopping_cart
                        WHERE idcustomer = $idUser;
                       ";

        if ($result = $connection->query($getusername)) {
            if ($result->num_rows > 0){
                while($product = $result->fetch_object())
					$cartProductID[$product->idproduct] = $product->amount;
				return $cartProductID;
            }else
                echo "Impossible to get client's products from shopping cart";
        }else
            echo "Wrong Query";
    }

	function getProductDataFromCart($connection, $productNumber, $prodID){
		$cartProductData = [[]];

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































?>