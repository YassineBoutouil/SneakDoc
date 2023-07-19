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

  var quantity = 1;
  var quantityElement = $(".quantity");
  var minusButton = $(".quantity-button.minus");
  var plusButton = $(".quantity-button.plus");

  minusButton.on("click", function() {
    if (quantity > 1) {
      quantity--;
      quantityElement.text(quantity);
    }
  });

  plusButton.on("click", function() {
    quantity++;
    quantityElement.text(quantity);
  });

  function updateQuantityPrice(quantity) {
    var initialPrice = parseFloat($('.initial-price').text().trim());
    var totalPrice = initialPrice * quantity;

    // Mettre à jour la quantité affichée
    $('.quantity').text(quantity);

    // Mettre à jour le prix total affiché
    $('.total-price').text(totalPrice.toFixed(2));
  }
    // Initialiser la quantité et le prix total
    updateQuantityPrice(1);

    // Augmenter la quantité lors du clic sur le bouton "+"
    $('.quantity-button.plus').on('click', function() {
      var quantity = parseInt($('.quantity').text().trim()) + 1;
      updateQuantityPrice(quantity);
    });

    // Diminuer la quantité lors du clic sur le bouton "-"
    $('.quantity-button.minus').on('click', function() {
      var quantity = parseInt($('.quantity').text().trim()) - 1;
      if (quantity >= 1) {
        updateQuantityPrice(quantity);
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
  
      if (conditions.length > 0) {
        query += " WHERE " + conditions.join(" AND ");
      }
  
      if (types.length > 0 || sizes.length > 0) {
        query += " AND (";
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

  });