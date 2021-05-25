$('#add-image').click(function(){
    // Je récupère le numéo des futurs champs que je vais créer
    const index = +$('#widgets-counter').val();

    console.log(index);

    // Je récupère le prototype des entrées (remplace /__name__/(g veut dire qu'on peut le faire plusieurs fois) par index)
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index+1);

    // Je gère le bouton supprimer
    handleDeleteButtons();
})

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;

        $(target).remove();
    })
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButtons();