/**
 * Détecteur d'AdBlock pour WebTools
 */
class AdBlockDetector {
    constructor() {
        this.detected = false;
        this.settings = window.adBlockSettings || {};
    }

    /**
     * Initialise la détection
     */
    init() {
        // Si les paramètres ne sont pas définis ou que la détection est désactivée, ne rien faire
        if (!this.settings || !this.settings.enabled) return;
        
        // Créer un élément appât que les AdBlockers vont cibler
        this.createBait();
        
        // Vérifier après un court délai si l'élément appât est toujours visible
        setTimeout(() => {
            this.checkDetection();
        }, 100);
    }

    /**
     * Crée un élément appât qui sera bloqué par AdBlock
     */
    createBait() {
        const bait = document.createElement('div');
        bait.className = 'ad-container pub_300x250 pub_300x250m pub_728x90 text-ad textAd text_ad text_ads text-ads banner-ad';
        bait.textContent = '&nbsp;';
        bait.id = 'adblock-bait';
        bait.style.width = '1px';
        bait.style.height = '1px';
        bait.style.position = 'absolute';
        bait.style.top = '-999px';
        bait.style.left = '-999px';
        document.body.appendChild(bait);
    }

    /**
     * Vérifie si AdBlock est détecté
     */
    checkDetection() {
        const bait = document.getElementById('adblock-bait');
        
        if (!bait || 
            window.getComputedStyle(bait).display === 'none' || 
            window.getComputedStyle(bait).visibility === 'hidden' || 
            bait.offsetHeight === 0) {
            
            this.detected = true;
            this.handleDetection();
        }
    }

    /**
     * Gère la détection d'AdBlock
     */
    handleDetection() {
        if (this.settings.show_message) {
            this.showMessage();
        }
        
        if (this.settings.block_content) {
            this.blockContent();
        }
        
        // Envoyer l'événement au serveur pour les statistiques
        this.sendEvent();
    }

    /**
     * Affiche un message à l'utilisateur
     */
    showMessage() {
        // Créer l'overlay
        const overlay = document.createElement('div');
        overlay.id = 'adblock-overlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        overlay.style.zIndex = '9999';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        
        // Créer le conteneur du message
        const messageBox = document.createElement('div');
        messageBox.style.backgroundColor = '#fff';
        messageBox.style.borderRadius = '8px';
        messageBox.style.padding = '30px';
        messageBox.style.maxWidth = '500px';
        messageBox.style.width = '90%';
        messageBox.style.textAlign = 'center';
        messageBox.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.3)';
        
        // Titre
        const title = document.createElement('h2');
        title.style.color = '#660bab';
        title.style.marginBottom = '15px';
        title.style.fontSize = '24px';
        title.textContent = this.settings.message_title;
        
        // Texte
        const text = document.createElement('p');
        text.style.marginBottom = '25px';
        text.style.fontSize = '16px';
        text.style.lineHeight = '1.5';
        text.style.color = '#333';
        text.textContent = this.settings.message_text;
        
        // Conteneur du bouton et compteur
        const btnContainer = document.createElement('div');
        btnContainer.style.display = 'flex';
        btnContainer.style.flexDirection = 'column';
        btnContainer.style.alignItems = 'center';
        
        // Compteur
        const counter = document.createElement('div');
        counter.style.marginBottom = '15px';
        counter.style.fontSize = '14px';
        counter.style.color = '#666';
        
        // Bouton
        const button = document.createElement('button');
        button.style.backgroundColor = '#660bab';
        button.style.color = '#fff';
        button.style.border = 'none';
        button.style.borderRadius = '4px';
        button.style.padding = '10px 20px';
        button.style.fontSize = '16px';
        button.style.cursor = 'pointer';
        button.style.transition = 'background-color 0.3s';
        button.textContent = this.settings.message_button;
        button.disabled = true;
        button.style.opacity = '0.7';
        
        button.addEventListener('mouseover', () => {
            if (!button.disabled) {
                button.style.backgroundColor = '#4e0883';
            }
        });
        
        button.addEventListener('mouseout', () => {
            if (!button.disabled) {
                button.style.backgroundColor = '#660bab';
            }
        });
        
        button.addEventListener('click', () => {
            document.body.removeChild(overlay);
        });
        
        // Ajouter le compteur si nécessaire
        if (this.settings.countdown > 0) {
            let count = this.settings.countdown;
            counter.textContent = `Veuillez patienter ${count} secondes...`;
            
            const interval = setInterval(() => {
                count--;
                counter.textContent = `Veuillez patienter ${count} secondes...`;
                
                if (count <= 0) {
                    clearInterval(interval);
                    button.disabled = false;
                    button.style.opacity = '1';
                    counter.textContent = 'Vous pouvez maintenant continuer';
                }
            }, 1000);
            
            btnContainer.appendChild(counter);
        } else {
            button.disabled = false;
            button.style.opacity = '1';
        }
        
        // Assembler l'interface
        btnContainer.appendChild(button);
        messageBox.appendChild(title);
        messageBox.appendChild(text);
        messageBox.appendChild(btnContainer);
        overlay.appendChild(messageBox);
        document.body.appendChild(overlay);
    }

    /**
     * Bloque le contenu pour les utilisateurs avec AdBlock
     */
    blockContent() {
        // Sélectionner le contenu principal
        const mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.style.filter = 'blur(5px)';
            mainContent.style.pointerEvents = 'none';
            mainContent.style.userSelect = 'none';
        }
    }

    /**
     * Envoie un événement de détection au serveur
     */
    sendEvent() {
        // Vérifier que les en-têtes CSRF sont disponibles
        if (!document.querySelector('meta[name="csrf-token"]')) {
            console.error('CSRF token not found');
            return;
        }
        
        // Envoyer une requête au serveur pour les statistiques
        fetch('/api/adblock-detected', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                url: window.location.href,
                userAgent: navigator.userAgent
            })
        }).catch(err => console.error('Erreur lors de l\'envoi des données d\'AdBlock:', err));
    }
}

// Initialiser la détection
document.addEventListener('DOMContentLoaded', () => {
    const detector = new AdBlockDetector();
    detector.init();
}); 