/**
 * Utilitaires pour gérer les ressources statiques
 */

// Importer toutes les images pour qu'elles soient incluses dans la compilation Vite
const images = import.meta.glob('../images/**/*', { eager: true });

/**
 * Renvoie le chemin d'une image depuis le dossier resources/images
 * @param {string} path - Le chemin relatif à resources/images
 * @returns {string} - L'URL de l'image
 */
export function imagePath(path) {
    const key = `../images/${path}`;
    if (key in images) {
        return images[key].default;
    }
    console.warn(`Image not found: ${path}`);
    return '';
}

// Exporter les chemins des logos pour un accès facile
export const logos = {
    primary: imagePath('webtools_logo.png'),
    dark: imagePath('webtools_logo_gris.png'),
};

export default {
    imagePath,
    logos,
}; 