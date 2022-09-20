const tagsChoices = new Choices(document.querySelector('#tags'), {
    placeholder: true,
    placeholderValue: 'Veuillez sélectionner un ou plusieurs tags...',
    noChoicesText: 'Plus de choix disponible.',
    itemSelectText: 'Cliquez pour sélectionner',
    removeItems: true,
    removeItemButton: true,
    duplicateItemsAllowed: false,
    allowHTML: true
});
const newTagsChoices = new Choices(document.querySelector('#newtags'), {
    addItems: true,
    placeholder: true,
    placeholderValue: 'Veuillez saisir un ou plusieurs tags...',
    itemSelectText: 'Cliquez pour sélectionner',
    removeItems: true,
    removeItemButton: true,
    editItems: true,
    duplicateItemsAllowed: false,
    allowHTML: true,
    addItemText: (value) => {
        return `Pressez la touche 'Entrée' pour ajouter <b>"${value}"</b>`;
    },
});