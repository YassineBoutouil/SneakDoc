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
        $("#nav_sign_in").closest('.nav-item').addClass("d_none")
        $("#nav_sign_up").closest('.nav-item').addClass("d_none")
        break;
      case 3:
        console.log("has admin")
        $("#nav_sign_in").closest('.nav-item').addClass("d_none")
        $("#nav_sign_up").closest('.nav-item').addClass("d_none")
        break;
      default:
        console.log("has foreigner")
        $("#nav_sell").closest('.nav-item').addClass("d_none")
        $("#nav_admin").closest('.nav-item').addClass("d_none")
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





  /* by now*/
  var cart = []; // Tableau pour stocker les produits ajoutés au panier

      $('.product-card').each(function() {
        var product = {}; // Objet pour stocker les détails du produit
        var $productCard = $(this);

        // Récupérer les informations du produit
        product.sellerId = $productCard.find('.product-seller-id').text().trim();
        product.productId = $productCard.find('.product-code').text().trim();
        product.productTitle = $productCard.find('.product-title').text().trim();
        product.initialPrice = parseFloat($productCard.find('.initial-price').text().trim());

        // Ajouter l'événement de clic pour ajouter le produit au panier
        $productCard.find('.product-anchor').on('click', function(e) {
          e.preventDefault();

          // Récupérer la quantité du produit
          var quantity = parseInt($productCard.find('.quantity').text().trim());

          // Vérifier si une quantité valide est sélectionnée
          if (quantity > 0) {
            product.quantity = quantity;
            product.totalPrice = product.initialPrice * quantity;

            // Vérifier si le produit est déjà dans le panier
            var existingProduct = cart.find(function(item) {
              return item.productId === product.productId;
            });

            if (existingProduct) {
              // Mettre à jour la quantité et le prix total du produit existant
              existingProduct.quantity += product.quantity;
              existingProduct.totalPrice += product.totalPrice;
            } else {
              // Ajouter le produit au panier
              cart.push(product);
            }

            // Afficher une confirmation
            alert('Le produit "' + product.productTitle + '" a été ajouté au panier.');

            // Réinitialiser la quantité à 1
            $productCard.find('.quantity').text('1');
          } else {
            alert('Veuillez sélectionner une quantité valide.');
          }
        });

        // Gestion des boutons "+" et "-" pour augmenter/diminuer la quantité
        $productCard.find('.quantity-button.plus').on('click', function() {
          var quantity = parseInt($productCard.find('.quantity').text().trim());
          $productCard.find('.quantity').text(quantity + 1);
        });

        $productCard.find('.quantity-button.minus').on('click', function() {
          var quantity = parseInt($productCard.find('.quantity').text().trim());
          if (quantity > 1) {
            $productCard.find('.quantity').text(quantity - 1);
          }
        });
      });

});