    <script>
    function ajouterAuPanier(idProduit) {
        const xhr = new XMLHttpRequest();
        // Route vers le contrôleur panier
        xhr.open('POST', 'index.php?controller=cart&action=add', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Produit ajouté au panier avec succès!');
                        // Mettre à jour le badge du panier
                        const badge = document.querySelector('.badge-panier');
                        if (badge) {
                            let count = parseInt(badge.textContent) || 0;
                            badge.textContent = count + 1;
                        } else {
                            // Créer le badge s'il n'existe pas
                            const panierDiv = document.querySelector('.panier');
                            if (panierDiv) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'badge-panier';
                                newBadge.textContent = '1';
                                panierDiv.appendChild(newBadge);
                            }
                        }
                    } else {
                        // Si l'utilisateur n'est pas connecté, redirection possible ou message
                        if(response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            alert('Erreur: ' + response.message);
                        }
                    }
                } catch(e) {
                    console.error('Erreur parsing JSON:', e);
                    // alert('Réponse invalide du serveur'); // Commenté pour éviter le spam si erreur PHP visible
                }
            } else {
                alert('Erreur serveur: ' + xhr.status);
            }
        };
        
        xhr.onerror = function() {
            alert('Erreur réseau');
        };
        
        xhr.send('id=' + idProduit);
    }
    </script>
</body>
</html>
