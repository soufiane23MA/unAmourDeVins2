 
     
        document.addEventListener('DOMContentLoaded', function() {
            const platItems = document.querySelectorAll('.plat-item');
            const produitsContainer = document.getElementById('produits-container');

            platItems.forEach(platItem => {
                platItem.addEventListener('click', function() {
                    const platId = this.getAttribute('data-plat-id');

                    // Appel API pour récupérer les produits associés
                    fetch(`/api/accords/${platId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Vider le conteneur des produits
                            produitsContainer.innerHTML = '';

                // Afficher les produits
                data.forEach(produit => {
                    const produitElement = document.createElement('div');
                    produitElement.className = 'accord-produit-card';
                    produitElement.innerHTML = `
                        <img src="/imgs/${produit.image}" alt="${produit.name}" class="accord-produit-img">
                        <h5>${produit.name}</h5>
                        <p>${produit.prix} €</p>
                        <p>${produit.detail}</p>
                        <button class="btn-ajouter-panier" data-produit-id="${produit.id}">Ajouter au panier</button>
                    `;
                    produitsContainer.appendChild(produitElement);
                });
	   // Ajouter un écouteur d'événement pour les boutons "Ajouter au panier"
            const boutonsPanier = document.querySelectorAll('.btn-ajouter-panier');
            boutonsPanier.forEach(bouton => {
                bouton.addEventListener('click', function() {
                    const produitId = this.getAttribute('data-produit-id');
                    ajouterAuPanier(produitId); // Appeler la fonction ajouterAuPanier
            });
            });
})
               .catch(error => console.error('Erreur lors de la récupération des produits:', error));
                });
            });
        });
				// Fonction pour ajouter un produit au panier
	function ajouterAuPanier(produitId) {
		fetch(`/panier/add/${produitId}`, {
				method: 'GET', // ou 'POST' si tu préfères
		})
		.then(response => {
				if (response.ok) {
						alert('Produit ajouté au panier !');
						window.location.href = '/panier'; // Rediriger vers le panier
				} else {
						alert('Erreur lors de l\'ajout au panier.');
				}
		})
		.catch(error => console.error('Erreur:', error));
	}