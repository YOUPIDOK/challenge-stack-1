import { Controller } from '@hotwired/stimulus';
import Choices from "choices.js";

export default class extends Controller {
    static targets = [ "select" ];

    default_options  = {
        loadingText: 'Chargement...',
        noResultsText: 'Aucun résultat',
        noChoicesText: 'Aucun choix',
        itemSelectText: 'Sélectionner',
    }

    connect() {
        // Create choises
        this.choices = new Choices(this.selectTarget, this.default_options);
    }
}
