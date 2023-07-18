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
      echo "test";

      // Database connexion
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


      /**** */
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
            echo "Vous n'existez pas dans notre base de données, mais vous pouvez créer un compte en cliquant sur le bouton 'Sign Up !'";
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
          $message = "You Have to be a admin to do that !";
          echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
        }
        else{
          $mail = $_POST['admin-addid'];
          $password = $_POST['admin-addname'];
          
          // Vérifiez si le mail est déjà utilisé
          $query = "SELECT * FROM `users` WHERE `Mail` = '$mail'";
          $result = $mysqli->query($query);
          
          if ($result->num_rows > 0) {
              echo "Mail already used.";
          } else {
            // Insérer les informations de l'utilisateur dans la base de données
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
    
            // Insérer les informations spécifiques au produit dans la table appropriée (t-shirt ou sneakers)
            if ($productCategory == 'T-shirt') {
                $insertTshirtQuery = "INSERT INTO `tshirt` (`Size`, `Color`, `Product_Id`) 
                                      VALUES ('$productSize', '$productColor', '$productId')";
                $mysqli->query($insertTshirtQuery);
            } elseif ($productCategory == 'Sneakers') {
                $insertSneakersQuery = "INSERT INTO `sneakers` (`Size`, `Color`, `Product_Id`) 
                                        VALUES ('$productSize', '$productColor', '$productId')";
                $mysqli->query($insertSneakersQuery);
            }
    

            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';

            if (isset($_FILES['product-image'])) {
                $fileName = $_FILES['product-image']['name'];
                $tempName = $_FILES['product-image']['tmp_name'];
                $uploadDirectory = 'image/'; // Chemin du répertoire d'upload
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDirectory . $newFileName;
    
                // Déplacer le fichier téléchargé vers le répertoire d'upload
                if (move_uploaded_file($tempName, $uploadPath)) {
                    // Enregistrer les informations de l'image dans la table "image" de la base de données
                    $insertImageQuery = "INSERT INTO `image` (`Product_Id`, `File_Name`) VALUES ('$productId', '$newFileName')";
                    $mysqli->query($insertImageQuery);
                    echo "Le fichier a été téléchargé et les informations ont été ajoutées avec succès.";
                } 
                else {
                    echo "Une erreur s'est produite lors du téléchargement du fichier.";
                }
            }
    
            if ($productSellType == 'Auctions') {
                $insertAnchorQuery = "INSERT INTO `auctions` (`Categorie`, `Size`, `Color`, `Starting_Date`, `Finish_Date`, `Price`, `Product_Id`)
                                      VALUES ('$productCategory', '$productSize', '$productColor', NOW(), '$productEndDate', '$productPrice', '$productId')";
                $mysqli->query($insertAnchorQuery);
                echo "Le produit a été ajouté avec succès.";
            } elseif ($productSellType == 'Buy_Now') {
                $insertBuyNowQuery = "INSERT INTO `buy_now` (`Categorie`, `Size`, `Color`, `Price`, `Product_Id`)
                                      VALUES ('$productCategory', '$productSize', '$productColor', '$productPrice', '$productId')";
                $mysqli->query($insertBuyNowQuery);
                echo "Le produit a été ajouté avec succès.";
            } elseif ($productSellType == 'Best_Offer') {
                $insertBestOfferQuery = "INSERT INTO `best_offer` (`Categorie`, `Size`, `Color`, `Proposition_Price`, `Product_Id`)
                                        VALUES ('$productCategory', '$productSize', '$productColor', '$productPrice', '$productId')";
                $mysqli->query($insertBestOfferQuery);
                echo "Le produit a été ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout du produit.";
            }
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
          <button class="nav-link linked rounded-5" id="nav_sign_in" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Sign In</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link linked rounded-5" id="nav_sign_up" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Sign Up</button>
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
        <section class="containers d_none ">
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
                <input type="checkbox" name="type" value="v-neck"> Col V <br>
                <input type="checkbox" name="type" value="v-neck"> Col Rond <br>
                <input type="checkbox" name="type" value="v-neck"> Col Mao <br>
                <input type="checkbox" name="type" value="v-neck"> Col Haut <br>
                <input type="checkbox" name="type" value="v-neck"> Oversize <br>
              </label>
            </div>


            
          </section>
          <section class="main">
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
              <div class="product-card">
                <p class="product-seller-id">ID du vendeur</p>
                <div class="product-image">
                  <img src="image.jpg" alt="Product Image">
                </div>
                <div class="product-details">
                  <h2 class="product-title">Nom du produit</h2>
                  <p class="product-code">Code du produit</p>
                  <div class="quantity-section">
                    <button class="quantity-button minus">-</button>
                    <span class="quantity">1</span>
                    <button class="quantity-button plus">+</button>
                  </div>
                  <h5 class="product-price">
                    <span class="price-label">Prix initial:</span>
                    <span class="initial-price">19.99</span>
                  </h5>
                  <div class="negotiation-section">
                    <label for="negotiation-input">Négocier le prix:</label>
                    <input type="number" id="negotiation-input" class="negotiation-input" step="0.01" min="0" placeholder="Entrez votre prix">
                    <button class="negotiation-button">Négocier</button>
                  </div>
                  <a href="#" class="product-anchor">Voir le produit</a>
                </div>
              </div>
            </Section>



            <section id="buy_buy_now" class="buy_d_none">
                <!--php to generate-->
                <div class="product-card">
                  <p class="product-seller-id">ID du vendeur</p>
                  <div class="product-image">
                    <img src="image.jpg" alt="Product Image">
                  </div>
                  <div class="product-details">
                    <h2 class="product-title">Nom du produit</h2>
                    <p class="product-code">Code du produit</p>
                    <div class="quantity-section">
                      <button class="quantity-button minus">-</button>
                      <span class="quantity">1</span>
                      <button class="quantity-button plus">+</button>
                    </div>
                    <h5 class="product-price">Price </h5>
                    <button class="add-to-cart-button">Ajouter au panier</button>
                  </div>
                </div>
                <!--php to generate-->

            </Section>
            <section id="buy_anchor" class="buy_d_none">
              <div class="product-card">
                <p class="product-seller-id">ID du vendeur</p>
                <div class="product-image">
                  <img src="image.jpg" alt="Product Image">
                </div>
                <div class="product-details">
                  <h2 class="product-title">Nom du produit</h2>
                  <p class="product-code">Code du produit</p>
                  <div class="date-section">
                    <span class="starting-date">Starting date time</span>
                    <span class="finish-date">End date time</span>
                  </div>
                  <h5 class="product-price">
                    <span class="price-label">Prix initial:</span>
                    <span class="initial-price">19.99</span>
                  </h5>
                  <h5 class="product-price">
                    <span class="price-label">Prix actuel:</span>
                    <span class="current-price">24.99</span>
                  </h5>
                  <a class="product-anchor">make an offer</a>
                </div>
              </div>
            </Section>
          
          </section>
        </section>
      </section>

<!-- Sell -->
  <section id="sell" class="d_none two_parts">
    <ul class="nav nav-pills nav-fill gap-2 p-1 small rounded-5" id="pillNav2" role="tablist" style="--bs-nav-link-color: var(--black); --bs-nav-pills-link-active-color: var(--white-cream); --bs-nav-pills-link-active-bg: var(--red--pale);">
      <li class="nav-item" role="presentation">
        <button class="nav-link sell_linked active rounded-5" id="sell_add" data-bs-toggle="tab" type="button" role="tab" aria-selected="true">Add</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link sell_linked rounded-5" id="sell_remove" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">Remove</button>
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
                  <option value="T-shirt">T-shirt</option>
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
    <section id="sell_remove" class="sell_d_true">
      <?php
        if (isset($_POST['delete_submit'])) {
        $product_id = $_POST['product_id'];

        // Supprimer le produit de la base de données
        $deleteQuery = "DELETE FROM product WHERE Product_Id = '$product_id' AND User_Id = '$user_id'";
        if ($mysqli->query($deleteQuery)) {
            echo 'Product deleted successfully.';
        } else {
            echo 'Error deleting product.';
        }
      }

      $user_type = isset($_COOKIE['user_type']) ? $_COOKIE['user_type'] : '';
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

  <section id="cart" class="d_none two_parts">
    <section id="cart_cart" class="cart_d_none two_parts"></section>
    <section id="cart_payment" class="cart_d_none two_parts"></section>
  </section>

  <p id="information-from-js"></p>

</body> 
  
        
<!-- footer -->

    <footer class="text-white text-center">
        <div class="text-center p-4"> © 2022-2023 Copyright: <a class="text-white" href="https://mdbootstrap.com/">SneakDoc.com</a>
        </div>
    </footer>
</html>