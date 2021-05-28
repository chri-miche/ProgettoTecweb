/** When the document is ready we add the event listener.*/
document.addEventListener('DOMContentLoaded', function (event){
    let userName = document.getElementById('username')
    userName.addEventListener('focusout', ()=> userNameCheck(userName))

    let email = document.getElementById('email')
    email.addEventListener('focusout', ()=> emailCheck(email))

    let password = document.getElementById('password')
    password.addEventListener('focusout', ()=> passwordCheck(password))
})

function checkPasswordMatch(){


    let password = document.getElementById('password')
    let reInsert = document.getElementById('password2')

    const formField = document.getElementById('password2_form_field');

    if(password.value !== reInsert.value){

        /*
        reInsert.insertAdjacentElement('beforebegin',
            makeErrorLabel('nonMatchingPasswordsError', 'password2', 'Le password non corrispondono'))

         */
        formField.setAttribute('data-error', 'Le password non corrispondono');
        return false
    } else {
        // removeErrorLabel('nonMatchingPasswordsError')
        formField.setAttribute('data-error', '');
        return true
    }
}


function validateInput(invalid = false){

    const confirmButton = document.getElementById('submit')

    if(invalid || (document.getElementById('usernameError') ||
        document.getElementById('emailError') || document.getElementById('passwordError'))){

        confirmButton.disabled = true
        confirmButton.classList.add('disabled')
    } else {

        confirmButton.disabled = false
        confirmButton.classList.remove('disabled')

    }
}

function makeErrorLabel(id, target, message){

    let errorLabel = document.createElement('label')

    errorLabel.setAttribute('id', id)
    errorLabel.setAttribute('for', target)

    errorLabel.classList.add('inputError')
    errorLabel.appendChild(document.createTextNode(message))

    return errorLabel
}

function removeErrorLabel(id){
    const error = document.getElementById(id)
    if(error) error.remove()
}

function userNameCheck(userName){

    let value = userName.value

    const formField = document.getElementById('username_form_field');
    if(value.length < 5){

        userName.classList.add('invalid')
        formField.setAttribute('data-error', 'Il nome utente deve almeno essere lungo 6 caratteri.');
        /*
        if(!document.getElementById('usernameError')) {
            userName.insertAdjacentElement('beforebegin', makeErrorLabel('usernameError', 'username',
                'Il nome utente deve almeno essere lungo 6 caratteri.'))
        }*/

        validateInput(true)

    } else {

        /* Remove the previous box. */
        userName.classList.remove('invalid')
        // removeErrorLabel('usernameError')

        formField.setAttribute('data-error', '');

        validateInput()
    }
}


function emailCheck(email){

    const input = email.value
    const matcher =  /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/i
    const emailLabel = document.getElementById('email_form_field');

    if(!matcher.test(input)){

        email.classList.add('invalid')

        /* Display error if not already shown.*/
        /*
        if(!document.getElementById('emailError')) {



            email.insertAdjacentElement('beforebegin', makeErrorLabel('emailError', 'email',
                'La mail data non è corretta.'))

        }
        */
        emailLabel.setAttribute('data-error', 'La mail data non è corretta.');

        validateInput(true)

    } else {
        email.classList.remove('invalid')
        // removeErrorLabel('emailError')

        emailLabel.setAttribute('data-error', '');

        validateInput()
    }
}

function passwordCheck(password){

    const input = password.value
    const matcher = /^(?!.* )(?=.*\d)(?=.*[A-Z]).{8,15}$/

    const formField = document.getElementById('password_form_field');

    if(!matcher.test(input)){
        password.classList.add('invalid')

        /*
        if(!document.getElementById('passwordError')){

            password.insertAdjacentElement('beforebegin', makeErrorLabel('passwordError', 'password',
                'La password deve contenere almeno un numero, una maiuscola e essere [8-15] caratteri di lunghezza.'))
        }
         */

        formField.setAttribute('data-error', 'La password deve contenere almeno una maiuscola e essere [8-15] caratteri di lunghezza.')


        validateInput(true)

    } else {
        password.classList.remove('invalid')
        // removeErrorLabel('passwordError')
        formField.setAttribute('data-error', '');
        validateInput()

    }
}

function validateNumericInput(id, min, max) {
    const element = document.getElementById(id);
    const formField = document.getElementById(id + "_form_field");
    if (element.value < min || element.value > max) {
        formField.setAttribute('data-error', 'Il campo deve essere minore di ' + max + ' e maggiore di ' + min);
        return false;
    } else {
        formField.setAttribute('data-error', '');
        return true;
    }

}

