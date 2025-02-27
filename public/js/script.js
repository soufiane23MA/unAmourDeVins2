 

// Lorsque le DOM est complètement chargé
document.addEventListener('DOMContentLoaded', function () {
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

});/*
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
*/
/*document.addEventListener('DOMContentLoaded', function() {
    const regionsLink = document.getElementById('regionsLink');
    const domainesLink = document.getElementById('domainesLink');
    const produitsLink = document.getElementById('produitsLink');

    regionsLink.addEventListener('click', function(event) {
        event.preventDefault();
        domainesLink.style.display = 'block';
        produitsLink.style.display = 'block';
    });

    domainesLink.addEventListener('click', function(event) {
        event.preventDefault();
        alert('Filtre Domaines cliqué'); // Remplacez par votre logique
    });

    produitsLink.addEventListener('click', function(event) {
        event.preventDefault();
        alert('Filtre Produits cliqué'); // Remplacez par votre logique
    });
});*/
 
//console.log(typeof mdb);
//console.log(document.querySelector('.sidebar-log button'));
//console.log(document.querySelector('[data-mdb-toggle="sidenav"]'));
// ce code permet de résoudre le probléme de disfonctionnement de l'extention MDB; 
/*document.addEventListener("DOMContentLoaded", function () {
    if (typeof mdb !== "undefined") {
        console.log("MDB chargé !");
        console.log("Sidenav :", mdb.Sidenav);
    } else {
        console.error("Erreur : MDB ne se charge pas.");
    }
});*/
 

// rajouter le fonctioment des prix max et Min pour le curseur.
/*document.addEventListener("DOMContentLoaded", function () {
    var slider = document.getElementById("prix-slider");

    noUiSlider.create(slider, {
        start: [0, 100], // Valeurs de départ (min, max)
        connect: true,
        range: {
            'min': 0,
            'max': 100
        },
        step: 1, // Incrémentation
        tooltips: true, // Afficher les valeurs au-dessus du curseur
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return Number(value);
            }
        }
    });

    // Mettre à jour les valeurs affichées
   var prixMin = document.getElementById("prix-min");
    var prixMax = document.getElementById("prix-max");

    slider.noUiSlider.on("update", function (values) {
        prixMin.innerText = values[0];
        prixMax.innerText = values[1];
        if (!slider) {
            console.error("Erreur : Le div #prix-slider n'existe pas !");
        } else {
            console.log("Div #prix-slider trouvé !");
        }
    });
});
console.log(typeof noUiSlider);*/
// juste pour le test
/*document.addEventListener("DOMContentLoaded", function () {
    var slider = document.getElementById("prix-slider");
    var prixMin = document.getElementById("prix-min");
    var prixMax = document.getElementById("prix-max");

    if (!slider || !prixMin || !prixMax) {
        console.error("Erreur : Un des éléments nécessaires n'existe pas !");
        return;
    }

    noUiSlider.create(slider, {
        start: [0, 100],
        connect: true,
        range: {
            'min': 0,
            'max': 100
        },
        step: 1,
        tooltips: true,
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return Number(value);
            }
        }
    });

    slider.noUiSlider.on("update", function (values) {
        prixMin.innerText = values[0];
        prixMax.innerText = values[1];
    });
});*/
 /*// Met à jour la valeur du slider en temps réel
 const slider = document.getElementById('prix-slider'); // Sélectionne le slider
 const sliderValue = document.getElementById('slider-value'); // Sélectionne l'élément pour afficher la valeur

 // Affiche la valeur initiale du slider
 sliderValue.textContent = slider.value;

 // Met à jour la valeur affichée lorsque l'utilisateur déplace le slider
 slider.addEventListener('input', function() {
     sliderValue.textContent = this.value;
 });*/
