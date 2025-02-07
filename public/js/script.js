 

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

});
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
