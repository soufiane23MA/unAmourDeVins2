 
 
 /* ------- le code js pour ecouter le click de la modal/ age 18ans*/
 // Solution technique sans changer votre style
 /*if (!localStorage.getItem('adult')) {
    document.getElementById('ageCheck').style.display = 'flex';
}

document.getElementById('yes').addEventListener('click', function() {
    localStorage.setItem('adult', 'true');
    document.getElementById('ageCheck').style.display = 'none';
});

document.getElementById('no').addEventListener('click', function() {
    window.location.href = 'https://www.google.com';
});*/
     
/*-----------------------fin de la modal----------------*/
// le code js qui permettra de m'afficher les produits par accord 

 
document.addEventListener('DOMContentLoaded', function () {
    const regionList = document.getElementById('regionList');
    const domaineList = document.getElementById('domaineList');
    const produitList = document.getElementById('produitList');

    // Vérifie que les éléments existent
    if (!regionList || !domaineList || !produitList) {
         
        return;
    }

    // Charger les régions
    function chargerRegions() {
        fetch('/produit/regions') // La route Symfony pour récupérer les régions
            .then(response => response.json())
            .then(data => {
                // Vider la liste avant d'ajouter les nouvelles régions
                regionList.innerHTML = '';

                data.forEach(region => {
                    const item = document.createElement('li');
                    item.textContent = region.nom;
                    item.setAttribute('data-region-id', region.id);
                    item.addEventListener('click', function () {
                        chargerDomaines(region.id);
                    });
                    regionList.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des régions:', error);
            });
    }

    // Charger les domaines pour une région donnée
    function chargerDomaines(regionId) {
        fetch(`/produit/regions/${regionId}/domaines`) // La route pour récupérer les domaines
            .then(response => response.json())
            .then(data => {
                // Vider la liste avant d'ajouter les nouveaux domaines
                domaineList.innerHTML = '';
                produitList.innerHTML = ''; // Réinitialiser la liste des produits

                data.forEach(domaine => {
                    const item = document.createElement('li');
                    item.textContent = domaine.nom;
                    item.setAttribute('data-domaine-id', domaine.id);
                    item.addEventListener('click', function () {
                        chargerProduits(domaine.id);
                    });
                    domaineList.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des domaines:', error);
            });
    }

    // Charger les produits pour un domaine donné
    function chargerProduits(domaineId) {
        fetch(`/produit/domaines/${domaineId}/produits`) // La route pour récupérer les produits
            .then(response => response.json())
            .then(data => {
                // Vider la liste avant d'ajouter les nouveaux produits
                produitList.innerHTML = '';

                data.forEach(produit => {
                    const item = document.createElement('li');
                    item.textContent = `${produit.nom} - ${produit.prix} €`;
                    produitList.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des produits:', error);
            });
    }

    // Charger les régions au démarrage
    chargerRegions();
});

// le code javascript qui permet en cas de click , de rajouter le produit cliqué au panier 
/*document.addEventListener('DOMContentLoaded', function() {
    // Écouteur pour les boutons "Ajouter au panier"
    const boutonsPanier = document.querySelectorAll('.btn-ajouter-panier');

    boutonsPanier.forEach(bouton => {
        bouton.addEventListener('click', function() {
            const produitId = this.getAttribute('data-produit-id');
            ajouterAuPanier(produitId);
        });
    });*/

    // Fonction pour ajouter un produit au panier
    function ajouterAuPanier(produitId) {
        fetch(`/panier/add/${produitId}`, {
            method: 'GET',
        })
        .then(response => {
            if (response.ok) {
                alert('Produit ajouté au panier !');
                window.location.href = '/panier';
            } else {
                alert('Erreur lors de l\'ajout au panier.');
            }
        })
        .catch(error => console.error('Erreur:', error));
    }


 
 /* code pour le slider page d'acueil ----------*/
  
 const swiper = new Swiper('.swiper', {
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    effect: 'fade',
    fadeEffect: {
      crossFade: true
    }
  });

  /*------- le scripte pour activer la pagination du slider / page d'acueil--*/
  document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.swiper', {
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      }
    });
  });
  

    /*------------------fin du code pour le slider / page d'acueill*/
 

 
 