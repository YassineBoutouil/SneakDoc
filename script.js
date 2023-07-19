$(document).ready(function() {
  var usertype= 0;
  usertype = parseInt(getCookie("user_type"));
  console.log(usertype+" here")
  lock_for_user_type(usertype)

  function getCookie(cookieName) {
    const cookies = document.cookie.split("; ");
    for (let i = 0; i < cookies.length; i++) {
      const [name, value] = cookies[i].split("=");
      if (name === cookieName) {
        return decodeURIComponent(value);
      }
    }
    return "";
  }
  
  function lock_for_user_type(usertype){
    switch(usertype) {
      case 1:
        console.log("has buyer")
        $("#nav_sell").closest('.nav-item').addClass("d_none")
        $("#nav_admin").closest('.nav-item').addClass("d_none")
        $("#nav_sign_in").closest('.nav-item').addClass("d_none")
        $("#nav_sign_up").closest('.nav-item').addClass("d_none")
        
      case 2:
        console.log("has seller")
        $("#nav_admin").closest('.nav-item').addClass("d_none")
        $("#nav_sell").closest('.nav-item').removeClass("d_none").addClass("d_true")
        $("#nav_sign_in").closest('.nav-item').addClass("d_none")
        $("#nav_sign_up").closest('.nav-item').addClass("d_none")
        break;
      case 3:
        console.log("has admin")
        $("#nav_sign_in").closest('.nav-item').addClass("d_none")
        $("#nav_sign_up").closest('.nav-item').addClass("d_none")
        $("#nav_sell").closest('.nav-item').removeClass("d_none").addClass("d_true")
        $("#nav_admin").closest('.nav-item').removeClass("d_none").addClass("d_true")
        break;
      default:
        console.log("has foreigner")
        $("#nav_sign_in").closest('.nav-item').removeClass("d_none").addClass("d_true")
        $("#nav_sign_up").closest('.nav-item').removeClass("d_none").addClass("d_true")
        $("#nav_sell").closest('.nav-item').addClass("d_none")
        $("#nav_admin").closest('.nav-item').addClass("d_none")
        $("#nav_disconnect").closest('.nav-item').addClass("d_none")
        }
    }

  $('.linked').click(function() {
    // Get the section ID to show
    if(usertype == 0 && $(this).attr('id') != 'nav_buy'){
      $('section.d_true, section.d_none').each(function() {
        $(this).removeClass('d_true').addClass('d_none');
        console.log($(this).attr('id'))
        if($(this).attr('id') == 'nav_sign_up'){
          $("#sign_up").removeClass('d_none').addClass("d_true")
        }
        else{
          $("#sign_in").removeClass('d_none').addClass("d_true")
        }
      })
    }
    else{
      const sectionId = $(this).attr('id').replace('nav_', '');
    // Show the selected section and hide others
    interact_with_nav("section.d_true, section.d_none","d_none","d_true",sectionId)
    }
  })

  $('.logo').click(function() {
    // Définir l'ID du bouton "Buy"
    const buttonId = 'nav_buy';
  
    // Ajouter la classe "active" au bouton "Buy" et la supprimer des autres boutons
    $('.nav-link').each(function() {
      const id = $(this).attr('id');
      if (id === buttonId) {
        $(this).addClass('active');
      } else {
        $(this).removeClass('active');
      }
    });
  
    // Définir l'ID de la section "Buy"
    const sectionId = 'buy';
  
    // Afficher la section "Buy" et masquer les autres sections
    interact_with_nav("section.d_true, section.d_none","d_none","d_true",sectionId);
  });

  $('.sell_linked').click(function() {
    // Get the section ID to show
    const sectionId = $(this).attr('id').replace('nav_', '');
    // Show the selected section and hide others
    interact_with_nav("section.sell_d_true, section.sell_d_none","sell_d_none","sell_d_true",sectionId)
  });



  $('.buy_linked').click(function() {
    // Get the section ID to show
    const sectionId = $(this).attr('id').replace('nav_', '');
    interact_with_nav("section.buy_d_true, section.buy_d_none","buy_d_none","buy_d_true",sectionId)
    // Show the selected section and hide others
  });


  function interact_with_nav(selection,undisplayed,displayed,sectionId){
    $(selection).each(function() {
      if ($(this).attr('id') === sectionId) {
        $(this).removeClass(undisplayed).addClass(displayed);
      } else {
        $(this).removeClass(displayed).addClass(undisplayed);
      }
    });
  }

  $(".role-grid").click(function() {
    console.log("hey there")
    var usertype = 0;
    if($(this).hasClass('role-buyer')){
      var usertype = 1;
    }
    if($(this).hasClass('role-seller')){
      var usertype = 2;
    }
    if($(this).hasClass('role-admin')){
      var usertype = 3;
    }
    $("#sign_in_role-choice").addClass('d_none');
    $('#sign_in_login').removeClass('d_none')
    console.log(usertype);

    document.cookie = "user_type=" + usertype;
  });

  const categorieSelect = $('#categorie');
  const sneakersSizeFilters = $('#sneakers-size-filters');
  const tshirtSizeFilters = $('#tshirt-size-filters');
  const sneakersFilters = $('#sneakers-filters');
  const tshirtFilters = $('#tshirt-filters');
  tshirtFilters.hide();
  tshirtSizeFilters.hide();
  $("#cart_payment").hide()
  categorieSelect.on('change', function() {
  const selectedCategory = $(this).val();

    
    if (selectedCategory === 'sneakers') {
      sneakersSizeFilters.show();
      tshirtSizeFilters.hide();
      sneakersFilters.show();
      tshirtFilters.hide();
    } else if (selectedCategory === 'tshirt') {
      sneakersSizeFilters.hide();
      tshirtSizeFilters.show();
      sneakersFilters.hide();
      tshirtFilters.show();
    } else {
      sneakersSizeFilters.hide();
      tshirtSizeFilters.hide();
      sneakersFilters.hide();
      tshirtFilters.hide();
    }
  });


  $("#cart_order_confirmation").on('click', function(){
    $("#cart_payment").show()
    $("#cart_cart").hide()
  })

  $(".return-order-button").on('click', function(){
    $("#cart_payment").hide()
    $("#cart_cart").show()
  })

  $("#nav_disconnect").on('click',function(){
    usertype = 0
    lock_for_user_type(usertype)
    window.addEventListener("beforeunload", resetAllCookies);
    console.log(usertype)

  })


  function resetAllCookies() {
    var cookies = document.cookie.split(";");
  
    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i];
      var eqPos = cookie.indexOf("=");
      var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
      document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
  }
  

  $("#login-submit").on("click",function(){
      resetAllCookies()
      usertype = parseInt(getCookie("user_type"));
      lock_for_user_type(usertype);
      console.log(usertype)
  })

  function generateQuery() {
    // Tableau pour stocker les conditions de filtre
    var conditions = [];

    // Récupérer la valeur sélectionnée dans le menu déroulant "Catégories"
    var categorie = $('#categorie').val();
    if (categorie) {
      conditions.push("`Categorie` = '" + categorie + "'");
    }

    // Tableaux pour stocker les valeurs sélectionnées des types et des tailles
    var types = [];
    var sizes = [];

    // Parcourir les cases à cocher des types
    $('input[name="type"]').each(function() {
      if ($(this).is(':checked')) {
        types.push($(this).val());
      }
    });

    // Parcourir les cases à cocher des tailles
    $('input[name="size"]').each(function() {
      if ($(this).is(':checked')) {
        sizes.push($(this).val());
      }
    });

    // Générer la requête SQL en fonction des conditions de filtre
    var query = "SELECT * FROM `product`";

    if (categorie === "sneakers") {
      query += " INNER JOIN `sneakers` ON `product`.`Product_Id` = `sneakers`.`Product_Id`";
    } else if (categorie === "tshirt") {
      query += " INNER JOIN `tshirt` ON `product`.`Product_Id` = `tshirt`.`Product_Id`";
    }

    if (types.length > 0 || sizes.length > 0) {
      query += " WHERE (";
      if (types.length > 0) {
        query += "`Type` IN ('" + types.join("', '") + "')";
      }
      if (types.length > 0 && sizes.length > 0) {
        query += " OR ";
      }
      if (sizes.length > 0) {
        query += "`Size` IN ('" + sizes.join("', '") + "')";
      }
      query += ")";
    }

    // Jointures supplémentaires avec les tables best_offer, buy_now et auctions
    query += " LEFT JOIN `best_offer` ON `product`.`Product_Id` = `best_offer`.`Product_Id`";
    query += " LEFT JOIN `buy_now` ON `product`.`Product_Id` = `buy_now`.`Product_Id`";
    query += " LEFT JOIN `auctions` ON `product`.`Product_Id` = `auctions`.`Product_Id`";

    // Afficher la requête SQL dans la console
    console.log(query);

    // Afficher la requête SQL sur la page
    $('#sql-query').text(query);
  }

  // Écouteur d'événement pour les changements dans le menu déroulant "Catégories"
  $('#categorie').change(function() {
    generateQuery();
  });

  // Écouteur d'événement pour les changements dans les cases à cocher des types
  $('input[name="type"]').change(function() {
    generateQuery();
  });

  // Écouteur d'événement pour les changements dans les cases à cocher des tailles
  $('input[name="size"]').change(function() {
    generateQuery();
  });


  // Decrease quantity
  $(document).on('click', '.quantity-button.minus', function() {
    var productId = $(this).attr('id').split('_')[1];
    var quantityElement = $('#quantity_' + productId);
    var currentQuantity = parseInt(quantityElement.text());

    if (currentQuantity > 1) {
      quantityElement.text(currentQuantity - 1);
      updatePrice(productId, currentQuantity - 1);
    }
  });

  // Increase quantity
  $(document).on('click', '.quantity-button.plus', function() {
    var productId = $(this).attr('id').split('_')[1];
    var quantityElement = $('#quantity_' + productId);
    var currentQuantity = parseInt(quantityElement.text());

    quantityElement.text(currentQuantity + 1);
    updatePrice(productId, currentQuantity + 1);
  });

  // Function to update the price
  function updatePrice(productId, quantity) {
    var basePriceElement = $('#base_price_' + productId);
    var basePrice = parseFloat(basePriceElement.text());
    var currentPriceElement = $('#auction_' + productId).find('.current-price');

    var totalPrice = basePrice * quantity;
    currentPriceElement.text(totalPrice.toFixed(2));
  }

  
  // Define an array to store the cart items
  var cartItems = [];

  // Add to cart button click
  $(document).on('click', '.add-to-cart-button', function() {
    var productId = $(this).closest('.product-card').attr('id').split('_')[2];
    var productTitle = $(this).closest('.product-card').find('.product-title').text();
    var productPrice = parseFloat($(this).closest('.product-card').find('.initial_price_' + productId).text());

    // Check if the product is already in the cart
    var existingItem = cartItems.find(function(item) {
      return item.id === productId;
    });

    if (existingItem) {
      existingItem.quantity++;
    } else {
      cartItems.push({
        id: productId,
        title: productTitle,
        price: productPrice,
        quantity: 1
      });
    }

    updateCartUI();
  });

  // Function to update the cart UI
  function updateCartUI() {
    var cartHTML = '';
    var totalPrice = 0;

    for (var i = 0; i < cartItems.length; i++) {
      var cartItem = cartItems[i];
      var itemPrice = cartItem.price * cartItem.quantity;
      totalPrice += itemPrice;

      cartHTML += '<div class="cart-product-row">';
      cartHTML += '<div class="cart-product-col">';
      cartHTML += '<img class="cart-product-image" src="image/' + cartItem.id + '.png" alt="Product Image">';
      cartHTML += '</div>';
      cartHTML += '<div class="cart-product-col">';
      cartHTML += '<div class="cart-product-id">Product ID: ' + cartItem.id + '</div>';
      cartHTML += '<div class="cart-product-title">' + cartItem.title + '</div>';
      cartHTML += '</div>';
      cartHTML += '<div class="cart-product-col">';
      cartHTML += '<div class="quantity-buttons">';
      cartHTML += '<button class="quantity-button minus" data-id="' + cartItem.id + '">-</button>';
      cartHTML += '<input type="number" class="cart-product-quantity" value="' + cartItem.quantity + '" readonly>';
      cartHTML += '<button class="quantity-button plus" data-id="' + cartItem.id + '">+</button>';
      cartHTML += '</div>';
      cartHTML += '<div class="cart-product-price">Price: $' + itemPrice.toFixed(2) + '</div>';
      cartHTML += '</div>';
      cartHTML += '<div class="cart-product-col">';
      cartHTML += '<button class="delete-button" data-id="' + cartItem.id + '">Delete</button>';
      cartHTML += '</div>';
      cartHTML += '</div>';
    }

    // Update the cart section with the cart items
    $('#cart_cart .cart-product').html(cartHTML);

    // Update the final price
    $('#final-price').text('Final Price: $' + totalPrice.toFixed(2));
  }

  // Update quantity button click
  $(document).on('click', '.quantity-button', function() {
    var productId = $(this).data('id');
    var cartItem = cartItems.find(function(item) {
      return item.id === productId;
    });

    if (cartItem) {
      if ($(this).hasClass('plus')) {
        cartItem.quantity++;
      } else if (cartItem.quantity > 1) {
        cartItem.quantity--;
      }
    }

    updateCartUI();
  });

  // Delete product button click
  $(document).on('click', '.delete-button', function() {
    var productId = $(this).data('id');
    cartItems = cartItems.filter(function(item) {
      return item.id !== productId;
    });

    $('select[name="selling-category"]').on('change', function() {
      // Récupérer la valeur sélectionnée dans la catégorie
      const selectedCategory = $(this).val();
  
      // Filtrer les choix de type en fonction de la catégorie sélectionnée
      if (selectedCategory === 'tshirt') {
        // Filtrer les choix de type pour les t-shirts
        $('select[name="selling-type"]').html(`
          <option value="V Cut">V Cut</option>
          <option value="Round Neck">Round Neck</option>
          <option value="Stand-up Collar">Stand-up Collar</option>
          <option value="Oversize">Oversize</option>
        `);
      } else if (selectedCategory === 'sneakers') {
        // Filtrer les choix de type pour les sneakers
        $('select[name="selling-type"]').html(`
          <option value="Low">Low</option>
          <option value="Mid">Mid</option>
          <option value="High">High</option>
        `);
      }
  
      // Filtrer les choix de taille en fonction de la catégorie sélectionnée
      if (selectedCategory === 'tshirt') {
        // Filtrer les choix de taille pour les t-shirts
        $('select[name="selling-size"]').html(`
          <option value="XS">XS</option>
          <option value="S">S</option>
          <option value="M">M</option>
          <option value="L">L</option>
          <option value="XL">XL</option>
          <option value="2XL">2XL</option>
        `);
      } else if (selectedCategory === 'sneakers') {
        // Filtrer les choix de taille pour les sneakers
        $('select[name="selling-size"]').html(`
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        `);
      }
    });
  })
  });
