 
 
// Lorsque le DOM est complètement chargé
/*document.addEventListener('DOMContentLoaded', function () {
    const bouton = document.getElementById('accordMetsVinsBtn');
    const listePlat = document.getElementById('accordsMetsVinsList');
    const platSelect = document.getElementById('platSelect');

    // Vérifie que les éléments existent
    if (!bouton || !listePlat || !platSelect) {
        console.error('Un ou plusieurs éléments du DOM sont manquants.');
        return;
    }

    // Cacher la liste au début
    listePlat.classList.add('d-none');

    // Fonction pour charger les plats depuis Symfony
    function chargerPlats() {
        fetch('/api/plats') // La route Symfony qui renvoie les plats
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                 
                // Vider la liste avant d'ajouter les nouveaux plats
                platSelect.innerHTML = '';

                // Ajouter un premier élément option pour "Choisir un plat"
                const optionDefault = document.createElement('option');
                optionDefault.textContent = 'Choisir un plat';
                optionDefault.value = '';
                platSelect.appendChild(optionDefault);

                // Remplir la liste avec les plats récupérés
                data.forEach(plat => {
                    const option = document.createElement('option');
                    option.value = plat.id;
                    option.textContent = plat.name;
                    platSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des plats:', error);
            });
    }

    // Ajouter un événement de clic sur le bouton pour afficher la liste et charger les plats
    bouton.addEventListener('click', function () {
        listePlat.classList.toggle('d-none'); // Basculer la visibilité
        if (!listePlat.classList.contains('d-none')) {
            chargerPlats(); // Charger les plats uniquement si la liste est visible
        }
    });
        // Gérer la sélection d'un plat
    platSelect.addEventListener('change', function () {
        const platId = platSelect.value;
        if (platId) {
            window.location.href = '/produit/accords/' + platId;
        }
    });

});*/

// le code js qui permettra de m'afficher les produits par accord 
 
document.addEventListener('DOMContentLoaded', function () {
    const regionList = document.getElementById('regionList');
    const domaineList = document.getElementById('domaineList');
    const produitList = document.getElementById('produitList');

    // Vérifie que les éléments existent
    if (!regionList || !domaineList || !produitList) {
        console.error('Un ou plusieurs éléments du DOM sont manquants.');
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


 /*document.addEventListener("DOMContentLoaded", function () {
    let navbar = document.getElementById("navbar");
    let lastScrollTop = 0;

    window.addEventListener("scroll", function () {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop) {
            // Scroll vers le bas → cacher la navbar
            navbar.classList.add("hidden");
        } else {
            // Scroll vers le haut → afficher la navbar
            navbar.classList.remove("hidden");
        }
        
        lastScrollTop = scrollTop;
    });
});
/* Open when someone clicks on the span element 
function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

/* Close when someone clicks on the "x" symbol inside the overlay 
function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}

//  la fonction qui permet d'afficher la liste déroulante.
document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("accordsToggle");
    const platsList = document.getElementById("platsList");
    const produitsList = document.getElementById("produitsList");

    // Charger les plats via l'API Symfony
    fetch("/api/plats")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur réseau : " + response.statusText);
            }
            return response.json();
        })
        .then(plats => {
            console.log("Plats reçus :", plats); // Affiche les plats dans la console

            // Vider la liste avant d'ajouter les nouveaux plats
            platsList.innerHTML = "";

            // Ajouter chaque plat à la liste
            plats.forEach(plat => {
                let listItem = document.createElement("li");
                let link = document.createElement("a");
                link.href = "#";
                link.textContent = plat.name;
                link.dataset.platId = plat.id;

                // Ajout de l'événement de clic pour récupérer les produits du plat
                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    let platId = this.dataset.platId;
                    console.log("Plat ID cliqué :", platId); // Affiche l'ID du plat cliqué

                    // Charger les produits du plat
                   /* fetch(`/api/accords/${platId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Erreur réseau : " + response.statusText);
                            }
                            return response.json();
                        })
                        .then(produits => {
                            console.log("Produits reçus :", produits); // Affiche les produits dans la console

                            // Vider la liste des produits avant d'ajouter les nouveaux
                            produitsList.innerHTML = "";

                            if (produits.length === 0) {
                                produitsList.innerHTML = "<p>Aucun produit trouvé.</p>";
                                return;
                            }

                            // Ajouter chaque produit à la liste
                            let ulProduits = document.createElement("ul");
                            produits.forEach(produit => {
                                let produitItem = document.createElement("li");
                                produitItem.textContent = `${produit.name} (${produit.plat})`; // Affiche le nom du produit et du plat
                                ulProduits.appendChild(produitItem);
                            });
                            produitsList.appendChild(ulProduits);
                        })
                        .catch(error => {
                            console.error("Erreur lors de la récupération des accords :", error);
                        });
                         // Redirige vers la page des produits associés au plat
                    window.location.href = `/plats/${platId}/produits`;
                });


                listItem.appendChild(link);
                platsList.appendChild(listItem);
            });
        })
        .catch(error => {
            console.error("Erreur lors du chargement des plats :", error);
        });

    // Toggle pour afficher ou cacher le menu
    toggleButton.addEventListener("click", function (event) {
        event.preventDefault();
        platsList.style.display = platsList.style.display === "none" ? "block" : "none";
    });
});*/
 
console.log(document.getElementById("accordsToggle")); 
console.log(document.getElementById("platsList"));
 