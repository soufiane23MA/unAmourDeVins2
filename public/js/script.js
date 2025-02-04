 

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
                console.log(data);
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
});