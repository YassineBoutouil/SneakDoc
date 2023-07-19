<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakDoc</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="style.css" rel="stylesheet">
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body>

  <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);

      // Database connection
      $user = 'root';
      $password = '';
      $database = 'web_project';
      $port = 3306;
      $mysqli = new mysqli('127.0.0.1', $user, $password, $database, $port);

      $user_type = '';
      $user_id = '';
      $user_name = '';


      if (isset($_POST['sign_up_submit'])) {
          $mail = $_POST['sign_up_user_id'];
          $password = $_POST['sign_up_password'];
          $name = $_POST['sign_up_name'];
          $address = $_POST['sign_up_address'];
          $city = $_POST['sign_up_city'];
          $postalCode = $_POST['sign_up_postal_code'];
          $country = $_POST['sign_up_country'];
          $telephone = $_POST['sign_up_telephone'];

          // Try to find if the mail is already used
          $query = "SELECT * FROM `users` WHERE `Mail` = '$mail'";
          $result = $mysqli->query($query);
          if ($result->num_rows > 0) {
              echo "Mail already used.";
          } else {
              // insert user information in the database
              $insertQuery = "INSERT INTO `users` (`Mail`, `Pwd`, `Name`, `Address`, `City`, `Postal_Code`, `Country`, `Telephone_Number`, `User_Type`) 
                              VALUES ('$mail', '$password', '$name', '$address', '$city', '$postalCode', '$country', '$telephone', 1)";
              if ($mysqli->query($insertQuery)) {
                  echo "The user has been added.";
              } else {
                  echo "Error detected.";
              }
          }
      }

      if (isset($_POST['login-submit'])) {
        $email = $_POST['login-mail-input'];
        $password = $_POST['login-pwd-input'];
    
        $user_type_from_js = $_COOKIE['user_type'];
    
        // Check if the email and password match a user in the database
        $query = "SELECT * FROM `users` WHERE `Mail` = '$email' AND `Pwd` = '$password'";
        $result = $mysqli->query($query);
    
        if ($result->num_rows > 0) {
            // User exists
    
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['User_Id'];
            $_SESSION['user_mail'] = $row['Mail'];
            $_SESSION['user_type'] = $row['User_Type'];
            $_SESSION['user_name'] = $row['Name'];
            $_SESSION['user_address'] = $row['Address'];
            $_SESSION['user_city'] = $row['City'];
            $_SESSION['user_postal_code'] = $row['Postal_Code'];
            $_SESSION['user_country'] = $row['Country'];
            $_SESSION['user_telephone'] = $row['Telephone_Number'];
    
            $user_type = $row['User_Type'];
            $user_name = $row['Name'];
            $user_id = $row['User_Id'];

            
    
            echo $user_id;

            if ($user_type == $user_type_from_js) {
                echo "Access Granted";
                setcookie('user_type', $user_type, time() + (86400 * 30), '/');
                setcookie('user_name', $user_name, time() + (86400 * 30), '/');
                setcookie('user_id', $user_id, time() + (86400 * 30), '/');
            } else {
                $message = "Your account doesn't correspond with your role";
                echo '<script type="text/javascript">window.alert("' . $message . '");</script>';
            }
        } else {
            // User does not exist in the database
            echo "You don't exist in our database, but you can create an account by clicking on the 'Sign Up!' button.";
            setcookie('user_type', 0, time() + (86400 * 30), '/');

        }
      }
    


      if (isset($_POST['admin-removeusersubmit'])) {

        $user_id_remove = $_POST['admin-id-remove'];

        $query = "SELECT * FROM `users` WHERE `User_Id` = '$user_id_remove'";

        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
          // User exists
        
          $row = $result->fetch_assoc();

          echo "here ".$row['User_Id'];

          $query = "DELETE FROM `users` WHERE `User_Id` = '$user_id_remove'";
          $result = $mysqli->query($query);

          if ($result) {
            echo "User remove";
          } else {
            echo "Error : " . $mysqli->error;
          }

        }
      }

      if (isset($_POST['account-submit'])) {
        echo "im here !";
        $newName = $_POST['new_name'];
        $newAddress = $_POST['new_address'];
        $newCity = $_POST['new_city'];
        $newPostalCode = $_POST['new_postal_code'];
        $newCountry = $_POST['new_country'];
        $newTelephoneNumber = $_POST['new_telephone_number'];
      
        
        $userId = $_SESSION['user_id'];
        $updateQuery = "UPDATE `users` SET `Name`='$newName', `Address`='$newAddress', `City`='$newCity', `Postal_Code`='$newPostalCode', `Country`='$newCountry', `Telephone_Number`='$newTelephoneNumber' WHERE `User_Id`='$userId'";
      
        if ($mysqli->query($updateQuery)) {
          echo "User information updated successfully.";
          $_SESSION['user_name'] = $newName;
          $_SESSION['user_address'] = $newAddress;
          $_SESSION['user_city'] = $newCity;
          $_SESSION['user_postal_code'] = $newPostalCode;
          $_SESSION['user_country'] = $newCountry;
          $_SESSION['user_telephone'] = $newTelephoneNumber;
        } else {
          echo "Error updating user information.";
        }
      }

      if (isset($_POST['admin-addusersubmit'])) {
        $user_type = isset($_COOKIE['user_type']) ? $_COOKIE['user_type'] : '';
        if($user_type != 3){
          $message = "You Have to be an admin to do that!";
          echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
        }
        else{
          $mail = $_POST['admin-addid'];
          $password = $_POST['admin-addname'];
          
          // Check if the email is already used
          $query = "SELECT * FROM `users` WHERE `Mail` = '$mail'";
          $result = $mysqli->query($query);
          
          if ($result->num_rows > 0) {
              echo "Mail already used.";
          } else {
            // Insert user information into the database
            $insertQuery = "INSERT INTO `users` (`Mail`, `Pwd`, `Telephone_Number`, `Address`, `City`, `Postal_Code`, `Country`, `User_Type`) 
                            VALUES ('$mail', '$password', 0, 'To fill', 'To fill', 'To fill', 'To fill', 1)";
            
            if ($mysqli->query($insertQuery)) {
                echo "User added successfully.";
            } else {
                echo "Error detected.";
            }
          }
        }
        
      }
        
      if (isset($_POST['selling-submit'])) {
        $user_type = isset($_COOKIE['user_type']) ? $_COOKIE['user_type'] : '';
        $user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : '';
        $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
    
        $productTitle = $_POST['selling-usertitle'];
        $productPrice = $_POST['selling-price'];
        $productType = $_POST['selling-type'];
        $productCategory = $_POST['selling-category'];
        $productEndDate = $_POST['selling-enddate'];
        $productSize = $_POST['selling-size'];
        $productSellType = $_POST['selling-selltype'];
        $productColor = $_POST['selling-color'];
        $productDescription = $_POST['selling-productdescription'];
    
        $insertQuery = "INSERT INTO `product` (`Categorie`, `User_Id`, `Price`, `Product_Description`, `Product_Title`)
                        VALUES ('$productCategory', '$user_id', '$productPrice', '$productDescription', '$productTitle')";
    
        if ($mysqli->query($insertQuery)) {
            $productId = $mysqli->insert_id;
    
            // Insert specific product information into the appropriate table (t-shirt or sneakers)
            if ($productCategory == 'tshirt') {
              $insertTshirtQuery = "INSERT INTO `tshirt` (`Size`, `Color`, `Product_Id`, `Type`) 
                                    VALUES ('$productSize', '$productColor', '$productId' , '$productType')";
              $mysqli->query($insertTshirtQuery);
            } elseif ($productCategory == 'Sneakers') {
              $insertSneakersQuery = "INSERT INTO `sneakers` (`Size`, `Color`, `Product_Id`, `Type`) 
                                      VALUES ('$productSize', '$productColor', '$productId' , '$productType')";
              $mysqli->query($insertSneakersQuery);
              echo "je mets à jour sneakers avec cette requete : " . $insertSneakersQuery;
            }
    

            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';

            if (isset($_FILES['product-image'])) {
                $fileName = $_FILES['product-image']['name'];
                $tempName = $_FILES['product-image']['tmp_name'];
                $uploadDirectory = 'image/'; // Path to the upload directory
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDirectory . $newFileName;
    
                // Move the uploaded file to the upload directory
                if (move_uploaded_file($tempName, $uploadPath)) {
                    // Save the image information in the "image" table of the database
                    $insertImageQuery = "INSERT INTO `image` (`Product_Id`, `File_Name`) VALUES ('$productId', '$newFileName')";
                    $mysqli->query($insertImageQuery);
                    echo "The file has been uploaded and the information has been added successfully.";
                } 
                else {
                    echo "An error occurred while uploading the file.";
                }
            }
    
            if ($productSellType == 'Auctions') {
                $insertAnchorQuery = "INSERT INTO `auctions` (`Categorie`, `Size`, `Color`, `Starting_Date`, `Finish_Date`, `Price`, `Product_Id`)
                                      VALUES ('$productCategory', '$productSize', '$productColor', NOW(), '$productEndDate', '$productPrice', '$productId')";
                $mysqli->query($insertAnchorQuery);
                echo "Product added successfully.";
            } elseif ($productSellType == 'Buy_Now') {
                $insertBuyNowQuery = "INSERT INTO `buy_now` (`Categorie`, `Size`, `Color`, `Price`, `Product_Id`)
                                      VALUES ('$productCategory', '$productSize', '$productColor', '$productPrice', '$productId')";
                $mysqli->query($insertBuyNowQuery);
                echo "Product added successfully.";
            } elseif ($productSellType == 'Best_Offer') {
                $insertBestOfferQuery = "INSERT INTO `best_offer` (`Categorie`, `Size`, `Color`, `Proposition_Price`, `Product_Id`)
                                        VALUES ('$productCategory', '$productSize', '$productColor', '$productPrice', '$productId')";
                $mysqli->query($insertBestOfferQuery);
                echo "Product added successfully.";
            } else {
                echo "Error!";
            }
        }
    }

    if (isset($_POST['payment-submit'])) {
      $paymentType = $_POST['payment-type'];
      $cardNumber = $_POST['payment-card-number'];
      $cardName = $_POST['payment-card-name'];
      $cardExpiration = $_POST['payment-card-expiration'];
      $securityCode = $_POST['payment-security-code'];

      $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

      echo "USER ID FROM PAYMENT: " . $user_id;

      $sql = "SELECT * FROM users WHERE User_Id = $user_id";
      echo $sql;
      $result = $mysqli->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['Payment_Type'] === $paymentType && $row['Card_Number'] === $cardNumber && $row['Card_Name'] === $cardName && $row['Card_Expiration_Date'] === $cardExpiration && $row['Security_Code'] === $securityCode) {
            echo "Payment successful!";
        } else {
            echo "Incorrect payment information. Please check again.";
        }
    } else {
        echo "User not authorized to make payment.";
    }
  
      
    }

    ?>

<!-- Navbar -->
    <nav class="navbar navbar-light">
      <a class="navbar-brand" href="#">
        <img class="logo" src="image/sneakdoc_logo.png" width="160" height="35" alt="">
      </a>
      <ul class="nav nav-pills nav-fill gap-2 p-1 small  rounded-5" id="pillNav2" role="tablist" style="--bs-nav-link-color: var(--black); --bs-nav-pills-link-active-color: var(--white-cream); --bs-nav-pills-link-active-bg: var(--red--pale);">
        <li class="nav-item" role="presentation">
          <button class="nav-link linked active rounded-5" id="nav_buy" data-bs-toggle="tab" type="button" role="tab" aria-selected="true">Buy</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked rounded-5" id="nav_sell" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Sell</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked rounded-5" id="nav_admin" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Admin</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked rounded-5" id="nav_cart" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Cart</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked rounded-5" id="nav_account" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Account</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked active rounded-2" id="nav_sign_in" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Sign In</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked active rounded-2" id="nav_sign_up" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Sign Up</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked active rounded-2" id="nav_disconnect" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Disconnect</button>
        </li>
      </ul>
    </nav>

<!-- Account -->
    <!-- Account -->
<section id="account" class="d_none two_parts">
  <section id="account">
    <div>
      <h2 id="account-title">Your Account</h2>
    </div>
    <form method="POST">
      <div id="account-containerchoice">
        <div class="account-grid">
          <div>
            <h2>Name and Surname</h2>
          </div>
          <div>
            <input type="text" class="account-caracteristic" name="new_name" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
          </div>
        </div>
        <div class="account-grid">
          <div>
            <h2>Address</h2>
          </div>
          <div>
            <input type="text" class="account-caracteristic" name="new_address" value="<?php echo isset($_SESSION['user_address']) ? $_SESSION['user_address'] : ''; ?>">
          </div>
        </div>
        <div class="account-grid">
          <div>
            <h2>City</h2>
          </div>
          <div>
            <input type="text" class="account-caracteristic" name="new_city" value="<?php echo isset($_SESSION['user_city']) ? $_SESSION['user_city'] : ''; ?>">
          </div>
        </div>
        <div class="account-grid">
          <div>
            <h2>Postal Code</h2>
          </div>
          <div>
            <input type="text" class="account-caracteristic" name="new_postal_code" value="<?php echo isset($_SESSION['user_postal_code']) ? $_SESSION['user_postal_code'] : ''; ?>">
          </div>
        </div>
        <div class="account-grid">
          <div>
            <h2>Country</h2>
          </div>
          <div>
            <input type="text" class="account-caracteristic" name="new_country" value="<?php echo isset($_SESSION['user_country']) ? $_SESSION['user_country'] : ''; ?>">
          </div>
        </div>
        <div class="account-grid">
          <div>
            <h2>Telephone Number</h2>
          </div>
          <div>
            <input type="text" class="account-caracteristic" name="new_telephone_number" value="<?php echo isset($_SESSION['user_telephone']) ? $_SESSION['user_telephone'] : ''; ?>">
          </div>
        </div>
      </div>
      <center>
        <input type="submit" value="Save" id="account-submit" name="account-submit">
      </center>
    </form>
  </section>
</section>

    
<!-- Sign Up -->
    <section id="sign_up" class="d_none two_parts">
      <section id="sign_up_add_user" class="content">
        <h2 id="sign-up-title">Add Your Details !</h2>
      
        <form action="" method="POST">
            <div id="sign-up-containerchoice">


            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                <div><h2 id="sign-up-mail-text" class="sign-up-detail-title">Mail</h2></div>
                <div><input type="text" name="sign_up_user_id" id="sign-up-mail-input" class="sign-up-input"></div>
                </div>
            </div>
            

            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                    <div><h2 id="sign-up-pwd-text" class="sign-up-detail-title">Password</h2></div>
                    <div><input type="password" name="sign_up_password" id="sign-up-pwd-input" class="sign-up-input"></div>
                </div>
            </div>

            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                    <div><h2 class="sign-up-detail-title">Name and Surname</h2></div>
                    <div><input type="text" name="sign_up_name" class="sign-up-input"></div>
                </div>
            </div>

            <div class="sign-up-containerelement">
                
                <div class="sign-up-grid">
                    <div><h2 class="sign-up-detail-title">Address</h2></div>
                    <div><input type="text" name="sign_up_address" class="sign-up-input"></div>
                </div>
            </div>

            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                    <div><h2 class="sign-up-detail-title">City</h2></div>
                    <div><input type="text" name="sign_up_city" class="sign-up-input"></div>
                </div>
            </div>

            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                    <div><h2 class="sign-up-detail-title">Postal Code</h2></div>
                    <div><input type="text" name="sign_up_postal_code" class="sign-up-input"></div>
                </div>
            </div>

            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                    <div><h2 class="sign-up-detail-title">Country</h2></div>
                    <div><input type="text" name="sign_up_country" class="sign-up-input"></div>
                </div>
            </div>

            <div class="sign-up-containerelement">
                <div class="sign-up-grid">
                    <div><h2 class="sign-up-detail-title">Telephone Number</h2></div>
                    <div><input type="text" name="sign_up_telephone" class="sign-up-input" id="sign-up-lastitem"></div>
                </div>
            </div>
          </div>
          <center>
            <input type="submit" name="sign_up_submit" value="Sign-Up" id="sign-up-submit">
          </center>
        </form>
      </section>
    </section>

<!-- Sign In -->
    <section id="sign_in" class="d_none two_parts">
    <center>
    <section id="sign_in_role-choice">
      <div><h2 id="role-title">CHOOSE YOUR ROLE</h2></div>

        <div id="role-containerchoice">
            <div class="role-containerelement role-grid role-buyer">
                <div><img src="image/buyer.png" class="role-left-image"></div>
                <div><h2 class="role-Roletype">Buyer</h2></div>
                <div><img src="image/fleche.png" class="role-right-image"></div>
            </div>

            <div class="role-containerelement role-grid role-seller">
                <div><img src="image/seller.png" class="role-left-image"></div>
                <div><h2 class="role-Roletype">Seller</h2></div>
                <div><img src="image/fleche.png" class="role-right-image"></div>
            </div>


            <div class="role-containerelement role-grid role-admin">
                <div><img src="image/admin.png" class="role-left-image"></div>
                <div><h2 class="role-Roletype">Admin</h2></div>
                <div><img src="image/fleche.png" class="role-right-image"></div>
            </div>
        </div>
        <center>
      </section>
        <section id="sign_in_login" class="content d_none">
            <center>
                <form action="" method="POST">
                    <div id="login-containerchoice">
                        <div class="login-containerelement">
                            <div class="login-grid">
                                <div>
                                    <h2 id="login-mail-text">Mail</h2>
                                </div>
                                <div>
                                    <input type="text" id="login-mail-input" name="login-mail-input">
                                </div>
                            </div>
                        </div>
                        <div class="login-containerelement">
                            <div class="login-grid">
                                <div>
                                    <h2 id="login-pwd-text">Password</h2>
                                </div>
                                <div>
                                    <input type="password" id="login-pwd-input" name="login-pwd-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Sign-In" id="login-submit" name="login-submit" >
                </form>
            </center>
        </section>
    </section>

<!-- Admin -->
    <section id="admin" class="d_none two_parts">
      <section id="admin_add_user" class="content">
        <form action="" method="POST">
          <div id="admin-firstgrid">
            <div id="admin-imgcontainer">
              <img src="image/admin.png" id="admin-img">
            </div>
            <div id="admin-id">
              <div id="admin-information" class="admin-detailcontainer">
                <div>
                  <h2>Admin-Id : </h2>
                </div>
                <div id="admin-idtext">
                  <input type="text" value="  
                  <?php echo (isset($user_id))?$user_id:'';
                  ?>"name="admin-idtextvalue" readonly>
                </div>
              </div>
            </div>
          </div>
          <div id="admin-secondgrid">
            <div id="admin-removeuser">
              <div class="admin-detailcontainer">
                <div>
                  <h2>Id : </h2>
                </div>
                <div id="admin-removeid">
                  <input type="text" name="admin-id-remove">
                </div>
              </div>
              <center>
                <input type="Submit" value="Remove User" id="admin-removeusersubmit" name="admin-removeusersubmit" class="admin-userbutton">
              </center>
            </div>
            <div id="admin-adduser">
              <div class="admin-detailcontainer">
                <div>
                  <h2>Mail : </h2>
                </div>
                <div id="admin-addid">
                  <input type="text" name="admin-addid">
                </div>
              </div>
              <div class="admin-detailcontainer">
                <div>
                  <h2>Password : </h2>
                </div>
                <div id="admin-addname">
                  <input type="text" name="admin-addname">
                </div>
              </div>
              <center>
                <input type="Submit" value="Add User" id="admin-addusersubmit" class="admin-userbutton" name="admin-addusersubmit">
              </center>
            </div>
          </div>
        </form>
        <div id="admin-user-table">
          <div id="admin-user-table-UserId">

          </div>
          <div id="admin-user-table-Mail">

          </div>
          <div id="admin-user-table-Cross">

          </div>
        </div>
      </section>
    </section>
<!-- Buy -->
<section id="buy" class="d_true two_parts">
        <section class="containers d_none">
          <section class="aside">
            <?php
            error_reporting(E_ERROR | E_PARSE);
            if ($_SESSION['user_type'] == 2) {
                echo '<div>Welcome ' . $user_name . '</div>';
            }
            ?>
            <!-- Filtre par catégorie -->
            <h2 class="filter-title">Catégories</h2>
            <div>
                <select id="categorie">
                  <option value="sneakers">Sneakers</option>
                  <option value="tshirt">T-shirt</option>
                </select>
            </div>
            <hr>

            <!-- Filtre par taille des t-shirts -->
            <div id="tshirt-size-filters" class=" ">
              <h3 class="filter-title">Size</h3>
              <label>
                <input type="checkbox" name="size" value="xs"> XS<br>
              </label>
              <label>
                <input type="checkbox" name="size" value="s"> S<br>
              </label>
              <label>
                <input type="checkbox" name="size" value="m"> M<br>
              </label>
              <label>
                <input type="checkbox" name="size" value="l"> L<br>
              </label>
              <label>
                <input type="checkbox" name="size" value="xl"> XL<br>
              </label>
              <label>
                <input type="checkbox" name="size" value="xl"> 2XL<br>
              </label>
            </div>

            <!-- Filtre par taille des chaussures -->
            <div id="sneakers-size-filters" class="">
              <h3 class="filter-title">Size</h3>
              <label>
                <input type="checkbox" name="size" value="3"> 3us
              </label>
              <label>
                <input type="checkbox" name="size" value="4"> 4us
              </label>
              <label>
                <input type="checkbox" name="size" value="5"> 5us
              </label>
              <label>
                <input type="checkbox" name="size" value="6"> 6us
              </label>
              <label>
                <input type="checkbox" name="size" value="7"> 7us
              </label>
              <label>
                <input type="checkbox" name="size" value="8"> 8us
              <label>
                <input type="checkbox" name="size" value="9"> 9us
              <label>
                <input type="checkbox" name="size" value="10"> 10us
              </label>
              <label>
                <input type="checkbox" name="size" value="11"> 11us
              </label>
              <label>
                <input type="checkbox" name="size" value="12"> 12us
              </label>
            </div>
            <div id="sneakers-filters" class="">
              <h3 class="filter-title">Type</h3>
              <label>
                <input type="checkbox" name="type" value="low"> Low
              </label>
              <label>
                <input type="checkbox" name="type" value="mid"> Mid
              </label>
              <label>
                <input type="checkbox" name="type" value="high"> High
              </label>
            </div>

            <!-- Filtre par type de t-shirt -->
            <div id="tshirt-filters" class="">
              <h3 class="filter-title">Type</h3>
              <label>
                <input type="checkbox" name="type" value="v-neck"> V Cut <br>
                <input type="checkbox" name="type" value="round-neck"> Round Neck <br>
                <input type="checkbox" name="type" value="stand-up-collar"> Stand-up Collar <br>
                <input type="checkbox" name="type" value="oversize"> Oversize <br>
              </label>
            </div>


            
          </section>
          <section class="main overflow">
          <div>
            <ul class="nav nav-pills nav-fill gap-5 p-1 small  rounded-2" id="pillNav2" role="tablist" style="--bs-nav-link-color: var(--black); --bs-nav-pills-link-active-color: var(--white-cream); --bs-nav-pills-link-active-bg: var(--red--pale);">
                <li class="nav-item" role="presentation">
                <button class="nav-link buy_linked active rounded-2" id="nav_buy_best_offer" data-bs-toggle="tab" type="button" role="tab" aria-selected="true">Best_offer</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link buy_linked rounded-2" id="nav_buy_buy_now" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Buy_now</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link buy_linked rounded-2" id="nav_buy_anchor" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Buy_anchor</button>
                </li>
            </ul>
          </div>
            <section id="buy_best_offer" class="buy_d_true">
              <?php
              $query = "SELECT *, product.Product_Id FROM best_offer LEFT JOIN product ON best_offer.Product_Id = product.Product_Id LEFT JOIN tshirt ON product.Product_Id = tshirt.Product_Id LEFT JOIN sneakers ON product.Product_Id = sneakers.Product_Id LEFT JOIN `image` ON product.Product_Id = `image`.Product_Id";

              $result = $mysqli->query($query);
        
              // Vérifier si la requête a renvoyé des résultats
              if ($result->num_rows > 0) {
                  // Parcourir les résultats de la requête
                while ($row = $result->fetch_assoc()) {
                    // Accéder aux valeurs des colonnes
                    $seller_id = $row["User_Id"];
                    $best_offer_id = $row["Best_Offer_Id"];
                    $categorie = $row["Categorie"];
                    $size = $row["Size"];
                    $color = $row["Color"];
                    $price = $row["Price"];
                    $number_of_negociation = $row["Number_Of_Negociation"];
                    $product_id = $row["Product_Id"];
                    $product_description = $row["Product_Description"];
                    $product_title = $row["Product_Title"];
                    $type = $row["Type"];
                    $file_name = $row["File_Name"];
        
                    // Faire quelque chose avec les valeurs récupérées
                    // Par exemple, les afficher à l'écran
                    echo '<div class="product-card">';
                    echo '<p class="product-seller-id">ID du vendeur: ' . $seller_id . '</p>';
                    echo '<div class="product-image">';
                    echo '<img src="image/' . $file_name . '" alt="'.$file_name.'">';
                    echo '</div>';
                    echo '<div class="product-details">';
                    echo '<h2 class="product-title">' . $product_title . '</h2>';
                    echo '<p class="product-code">Code du produit: ' . $product_id . '</p>';
                    echo '<div class="quantity-section">';
                    echo '<button class="quantity-button minus">-</button>';
                    echo '<span class="quantity">1</span>';
                    echo '<button class="quantity-button plus">+</button>';
                    echo '</div>';
                    echo '<h5 class="product-price">';
                    echo '<span class="price-label">Prix initial : </span>';
                    echo '<span class="initial-price">' . $price . '</span>';
                    echo '</h5>';
                    echo '<div class="negotiation-section">';
                    echo '<label for="negotiation-input">Négocier le prix:</label>';
                    echo '<input type="number" id="negotiation-input" class="negotiation-input" step="0.01" min="0" placeholder="Entrez votre prix">';
                    echo '<button class="negotiation-button">Négocier</button>';
                    echo '<a href="#" class="product-anchor">Voir le produit</a></br>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div></br>';
                  };
              }
              else {
                  echo "Aucun résultat trouvé.";
              }
              ?>
            </Section>



            <section id="buy_buy_now" class="buy_d_none">
                <!--php to generate-->
              <?php
                $query = "SELECT *, product.Product_Id FROM buy_now LEFT JOIN product ON buy_now.Product_Id = product.Product_Id LEFT JOIN tshirt ON product.Product_Id = tshirt.Product_Id LEFT JOIN sneakers ON product.Product_Id = sneakers.Product_Id LEFT JOIN `image` ON product.Product_Id = `image`.Product_Id";

                $result = $mysqli->query($query);
                // Vérifier si la requête a renvoyé des résultats
                if ($result->num_rows > 0) {
                    // Parcourir les résultats de la requête
                  while ($row = $result->fetch_assoc()) {
                      // Accéder aux valeurs des colonnes
                      $buy_now_seller_id = $row["User_Id"];
                      $buy_now = $row["Best_Offer_Id"];
                      $buy_now_categorie = $row["Categorie"];
                      $buy_now_size = $row["Size"];
                      $buy_now_color = $row["Color"];
                      $buy_now_price = $row["Price"];
                      $buy_now_number_of_negociation = $row["Number_Of_Negociation"];
                      $buy_now_product_id = $row["Product_Id"];
                      $buy_now_product_description = $row["Product_Description"];
                      $buy_now_product_title = $row["Product_Title"];
                      $buy_now_type = $row["Type"];
                      $buy_now_file_name = $row["File_Name"];          
                      // Faire quelque chose avec les valeurs récupérées
                      // Par exemple, les afficher à l'écran
                      echo '<div class="product-card">';
                          echo '<p class="product-seller-id">ID du vendeur: ' . $buy_now_seller_id . '</p>';
                          echo '<div class="product-image">';
                          echo    '<img src="image/' . $buy_now_file_name . '" alt="'.$buy_now_file_name.'">';
                          echo '</div>';
                          echo '<div class="product-details">';
                              echo '<h2 class="product-title">' . $buy_now_product_title . '</h2>';
                              echo '<p class="product-code">Code du produit: ' . $buy_now_product_id . '</p>';
                              echo '<div class="quantity-section">';
                                  echo '<button class="quantity-button minus">-</button>';
                                  echo '<span class="quantity">1</span>';
                                  echo '<button class="quantity-button plus">+</button>';
                              echo '</div>';
                              echo '<h5 class="product-price">';
                              echo '<span class="price-label">Prix : </span>';
                              echo '<span class="initial-price">' . $buy_now_price . '</span>';
                              echo '</h5>';
                              echo '<button class="add-to-cart-button">Add to cart</button></br>';
                              echo '<a href="#" class="product-anchor">Voir le produit</a>';

                          echo '</div>';
                      echo '</div></br>';
                  };
                }
                else {
                    echo "Aucun résultat trouvé.";
                }
              ?>
              <!--php to generate-->

            </Section>
            <section id="buy_anchor" class="buy_d_none">
            <?php
              $query = "SELECT *, product.Product_Id, auctions.Price FROM auctions LEFT JOIN product ON auctions.Product_Id = product.Product_Id LEFT JOIN tshirt ON product.Product_Id = tshirt.Product_Id LEFT JOIN sneakers ON product.Product_Id = sneakers.Product_Id LEFT JOIN `image` ON product.Product_Id = `image`.Product_Id";

              $result = $mysqli->query($query);
        
              // Vérifier si la requête a renvoyé des résultats
              if ($result->num_rows > 0) {
                  // Parcourir les résultats de la requête
                while ($row = $result->fetch_assoc()) {
                    // Accéder aux valeurs des colonnes
                    $auction_seller_id = $row["User_Id"];
                    $auction = $row["Best_Offer_Id"];
                    $auction_categorie = $row["Categorie"];
                    $auction_size = $row["Size"];
                    $auction_color = $row["Color"];
                    $auction_starting_date = $row["Starting_Date"];
                    $auction_finish_date = $row["Finish_Date"];
                    $auction_current_price = $row["Actual_Bid"];
                    $auction_price = $row["Price"];
                    $auction_number_of_negociation = $row["Number_Of_Negociation"];
                    $auction_product_id = $row["Product_Id"];
                    $auction_product_description = $row["Product_Description"];
                    $auction_product_title = $row["Product_Title"];
                    $auction = $row["Type"];
                    $auction = $row["File_Name"];
        
                    // Faire quelque chose avec les valeurs récupérées
                    // Par exemple, les afficher à l'écran
                    echo '<div class="product-card">';
                        echo '<p class="product-seller-id">ID du vendeur: ' . $buy_now_seller_id . '</p>';
                        echo '<div class="product-image">';
                        echo    '<img src=image/"' . $buy_now_file_name . '" alt="'.$buy_now_file_name.'">';
                        echo '</div>';
                        echo '<div class="product-details">';
                            echo '<h2 class="product-title">' . $auction_product_title . '</h2>';
                            echo '<p class="product-code">Code du produit: ' . $auction_product_id . '</p>';
                            echo '<div class="quantity-section">';
                            echo '<div class="date-section">';
                            echo '<span class="starting-date">'.$auction_starting_date.'</span><br>';  
                            echo '<span class="to-date">To</span><br>';  
                            echo '<span class="finish-date">'.$auction_finish_date.'</span>';
                            echo '</div>';
                            echo '</div>';
                            echo '<h5 class="product-price">';
                            echo '<span class="price-label">Initial-price : </span>';
                            echo '<span class="initial-price">' . $auction_price . '</span>';
                            echo '</h5>';
                            echo '<h5 class="product-price">';
                            echo '<span class="price-label">Current price : </span>';
                            echo '<span class="current-price">' . $auction_current_price . '</span>';
                            echo '</h5>';
                            echo '<button class="make-anchor">Make an offer</button>';

                        echo '</div>';
                    echo '</div></br>';
                };
              }
              else {
                  echo "Aucun résultat trouvé.";
              }
              ?>
            </Section>
          </section>
        </section>
      </section>

<!-- Sell -->
  <section id="sell" class="d_none two_parts overflow">
    <ul class="nav nav-pills nav-fill gap-5 p-1 small rounded-3" id="pillNav2" role="tablist" style="--bs-nav-link-color: var(--black); --bs-nav-pills-link-active-color: var(--white-cream); --bs-nav-pills-link-active-bg: var(--red--pale);">
      <li class="nav-item" role="presentation">
        <button class="nav-link sell_linked active rounded-3" id="sell_add" data-bs-toggle="tab" type="button" role="tab" aria-selected="true">Add</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link sell_linked rounded-3" id="sell_remove" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Remove</button>
      </li>
    </ul>
    <section id="sell_add" class="sell_d_true">
      <div>
        <h2 id="selling-title"> Product </h2>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div id="selling-containerchoice">
          <div id="selling-productimg" class="selling-upload-container">
            <input type="file" id="file-upload-input" name="product-image">
            <label for="file-upload-input" id="file-upload-label">Click to upload file</label>
          </div>
          <div id="selling-productcaracteristics">
            <p class="selling-producttitle">Title</p>
            <input type="textarea" id="selling-usertitle" class="selling-productsform" name="selling-usertitle">
            <div id="selling-productdetails">
              <div>
                <p class="selling-producttitle">Price</p>
                <input type="textarea" class="selling-productsform" name="selling-price">
              </div>
              <div>
                <p class="selling-producttitle">Type</p>
                <input type="textarea" class="selling-productsform" name="selling-type">
              </div>
              <div>
                <p class="selling-producttitle">Category</p>
                <select class="selling-productsform selling-selection-choice" name="selling-category">
                  <option value="Sneakers">Sneakers</option>
                  <option value="tshirt">T-shirt</option>
                </select>
              </div>
              <div>
                <p class="selling-producttitle">End Date</p>
                <input type="textarea" class="selling-productsform" name="selling-enddate">
              </div>
              <div>
                <p class="selling-producttitle">Size</p>
                <input type="textarea" class="selling-productsform" name="selling-size">
              </div>
              <div>
                <p class="selling-producttitle">Type of sell</p>
                <select class="selling-productsform selling-selection-choice" name="selling-selltype">
                  <option value="Auctions">Auctions</option>
                  <option value="Buy_Now">Buy_Now</option>
                  <option value="Best_Offer">Best_Offer</option>
                </select>
              </div>
              <div>
                <p class="selling-producttitle">Color</p>
                <input type="textarea" class="selling-productsform" name="selling-color">
              </div>
            </div>
          </div>
        </div>
        <input type="textarea" id="selling-productdescription" class="selling-productsform" name="selling-productdescription" value="Description :">
        <center>
          <input type="submit" value="Add" id="selling-submit" name="selling-submit">
        </center>
      </form>
    </section>
    <section id="sell_remove" class="sell_d_none">
      <?php
        if (isset($_POST['delete_submit'])) {
          $product_id = $_POST['product_id'];

          // Supprimer le produit de la base de données
          $deleteQuery = "DELETE FROM product WHERE Product_Id = '$product_id'";
          if ($mysqli->query($deleteQuery)) {
              echo 'Product deleted successfully.';
          } else {
              echo 'Error deleting product.';
          }
        }

        $user_type = isset($_COOKIE['user_type']) ? $_COOKIE['user_type'] : '';
        $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
        echo "User Id : " . $user_id . " User Type : " . $user_type;
        if($user_type == 3){
          $query = "SELECT p.Product_Id, p.Categorie, p.Price FROM product p";
        }
        else{
          $query = "SELECT p.Product_Id, p.Categorie, p.Price FROM product p WHERE p.User_Id = '$user_id'";
        }

        // Récupérer les produits de l'utilisateur
        
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            echo '<div><table id="selling-removetable">';
            echo '<tr><th>Product Id</th><th>Categorie</th><th>Price</th><th>Action</th></tr>';

            while ($row = $result->fetch_assoc()) {
                $product_id = $row['Product_Id'];
                $categorie = $row['Categorie'];
                $price = $row['Price'];

                echo '<tr>';
                echo '<td>' . $product_id . '</td>';
                echo '<td>' . $categorie . '</td>';
                echo '<td>' . $price . '</td>';
                echo '<td>';
                echo '<form method="post">';
                echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                echo '<input type="submit" name="delete_submit" value="Delete">';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</table></div>';
        } else {
            echo 'No products found.';
        }
      ?>
    </section>
  </section>

  <!-- Cart -->

  <section id="cart" class="d_none two_parts overflow">
    <section id="cart_cart" class="cart_d_none two_parts overflow">
      <div id="cart-title-container">
        <h2 id="cart-title"> Your Cart </h2>
      </div>
      <center>
        <div class="cart-product">
          <div class="cart-product-row">
            <div class="cart-product-col">
              <img class="cart-product-image" src="image/64b6ae7956fe8.png" alt="Product Image">
            </div>
            <div class="cart-product-col">
              <div class="cart-product-id">Product ID: ABC123</div>
              <div class="cart-product-title">Product Title</div>
            </div>
            <div class="cart-product-col">
              <div class="quantity-buttons">
                <button class="quantity-button minus">-</button>
                <input type="number" class="cart-product-quantity" value="1">
                <button class="quantity-button plus">+</button>
              </div>
              <div class="cart-product-size">Size: M</div>
              <div class="cart-product-price">Price: $19.99</div>
            </div>
            <div class="cart-product-col">
              <button class="delete-button">Delete</button>
            </div>
          </div>
        </div>

      
        <div id="final-price">Final Price: $19.99</div>
      
        <input id="cart_order_confirmation" type="submit" value="Pay">      
      </center>
    </section>
      <section id="cart_payment" class="cart_d_none two_parts overflow">
      <div class="payment-container">
        <h1 class="payment-title">Page de paiement</h1>
        <form method="POST">
          <label class="payment-label" for="payment-type">Type de carte de paiement:</label>
          <select class="payment-select" id="payment-type" name="payment-type">
            <option value="Visa">Visa</option>
            <option value="MasterCard">MasterCard</option>
            <option value="American Express">American Express</option>
            <option value="PayPal">PayPal</option>
          </select>

          <label class="payment-label" for="payment-card-number">Numéro de carte:</label>
          <input class="payment-input" type="text" id="payment-card-number" name="payment-card-number">

          <label class="payment-label" for="payment-card-name">Nom affiché sur la carte:</label>
          <input class="payment-input" type="text" id="payment-card-name" name="payment-card-name">

          <label class="payment-label" for="payment-card-expiration">Date d'expiration de la carte:</label>
          <input class="payment-input" type="text" id="payment-card-expiration" name="payment-card-expiration">

          <label class="payment-label" for="payment-security-code">Code de sécurité:</label>
          <input class="payment-input" type="text" id="payment-security-code" name="payment-security-code">

          <button  class="return-order-button" type="button">Go back</button>
          <button  class="payment-button" type="submit" name="payment-submit">Payer</button>
        </form>


      </div>
    </section>
  </section>

</body> 
  
        
<!-- footer -->

    <footer class="text-white text-center">
        <div class="text-center p-4"> © 2022-2023 Copyright: <a class="text-white" href="https://mdbootstrap.com/">SneakDoc.com</a>
        </div>
    </footer>
</html>