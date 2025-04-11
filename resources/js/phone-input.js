import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';

document.addEventListener('DOMContentLoaded', () => {
    const phoneInputField = document.querySelector("#phone");
    
    if (!phoneInputField) return;
    
    // Importer le script utils.js directement dans le code
    const script = document.createElement('script');
    script.src = "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/js/utils.js";
    script.async = true;
    document.head.appendChild(script);
    
    // Créer un champ caché pour stocker la valeur du téléphone au format international
    const hiddenPhoneInput = document.createElement('input');
    hiddenPhoneInput.type = 'hidden';
    hiddenPhoneInput.name = 'phone';
    hiddenPhoneInput.id = 'hidden_phone';
    phoneInputField.after(hiddenPhoneInput);
    
    // Initialiser le champ téléphone après un court délai pour s'assurer que utils.js est chargé
    setTimeout(() => {
        const phoneInput = intlTelInput(phoneInputField, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/25.3.1/js/utils.js",
            initialCountry: "fr",
            preferredCountries: ["fr", "be", "ch", "ca"],
            formatOnDisplay: true,
            separateDialCode: true,
            autoPlaceholder: "aggressive",
            allowDropdown: true,
        });

        // Supprimer l'attribut name du champ visible pour éviter les conflits
        phoneInputField.removeAttribute('name');
        
        // Ajouter le champ caché pour stocker le numéro complet au format international
        const form = phoneInputField.closest('form');
        
        if (form) {
            // Mettre à jour le champ caché à chaque changement de valeur
            phoneInputField.addEventListener('input', function() {
                const errorDiv = document.getElementById('phone-error');
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                }
                
                // Mettre à jour le champ caché avec la valeur formatée
                if (phoneInputField.value.trim()) {
                    hiddenPhoneInput.value = phoneInput.getNumber();
                } else {
                    hiddenPhoneInput.value = '';
                }
            });
            
            // S'assurer que le champ caché est mis à jour avant la soumission
            form.addEventListener('submit', function(e) {
                if (phoneInputField.value.trim()) {
                    hiddenPhoneInput.value = phoneInput.getNumber();
                } else {
                    hiddenPhoneInput.value = '';
                }
            });
        }
    }, 500); // Attendre 500ms pour s'assurer que le script est chargé
}); 