import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';

document.addEventListener('DOMContentLoaded', () => {
    const phoneInputField = document.querySelector("#phone");
    const phoneInput = phoneInputField ? intlTelInput(phoneInputField, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        initialCountry: "fr",
        preferredCountries: ["fr", "be", "ch", "ca"],
        formatOnDisplay: true,
        separateDialCode: true,
        autoPlaceholder: "aggressive",
    }) : null;

    // Ajouter le champ caché pour stocker le numéro complet au format international
    if (phoneInput) {
        const form = phoneInputField.closest('form');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                // Format le numéro de téléphone avec le code pays
                if (phoneInput.isValidNumber()) {
                    const phoneValue = phoneInput.getNumber();
                    
                    // Mettre à jour le champ téléphone avec le format international
                    phoneInputField.value = phoneValue;
                } else {
                    e.preventDefault();
                    
                    // Afficher une erreur si le numéro n'est pas valide
                    const errorDiv = document.getElementById('phone-error');
                    if (errorDiv) {
                        errorDiv.textContent = "Veuillez entrer un numéro de téléphone valide";
                        errorDiv.classList.remove('hidden');
                    }
                }
            });
        }
    }
}); 