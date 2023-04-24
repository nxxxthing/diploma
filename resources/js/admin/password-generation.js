$(document).ready(function () {
    $('#password-generation-button').click(function () {
        let parentSection = $(this).parent();
        let password_el = $(parentSection).find('#password');
        let password_confirm_el = $(parentSection).find('#password-confirmation')
        console.log(password_confirm_el);
        let password = randString();

        $(password_el).val(password).change();
        $(password_confirm_el).val(password).change();
    })

    function randString(){
        let possible = '';

        possible += 'abcdefghijklmnopqrstuvwxyz';
        possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        possible += '0123456789';
        possible += '![]{}()%&*$#^<>~@|';

        let text = '';
        let size = randomIntFromInterval(8,10);
        for(let i=0; i < size; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    }

    function randomIntFromInterval(min, max) { // min and max included
        return Math.floor(Math.random() * (max - min + 1) + min)
    }
})
