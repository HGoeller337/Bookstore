// Registration form validator by James Hyun
"use strict";

// =====================================================================================================================

function $(id) {
    return document.getElementById(id);
}

function isStringBlank(str) {
    return /^\s*$/.test(str); // Matches empty strings and strings with only whitespace
}

// =====================================================================================================================

window.onload = function() {
    
    let ccBox = $("creditCard");
    let ccvBox = $("ccv");
    let dateBox = $("date");
    let nameBox  = $("name");
    
    let add1Box = $("addressOne");
    let cityBox = $("city");
    let stateBox = $("state");
    let zipBox  = $("zip");
    
    let add1ShipBox = $("addressOne");
    let cityShipBox = $("city");
    let stateShipBox = $("state");
    let zipShipBox  = $("zip");
    
    let emailNote = $("emailNote");
    let fNameNote = $("fNameNote");
    let lNameNote = $("lNameNote");
    let passNote  = $("passNote");
    
    function isFormValid() {
        let emailValid = isEmailValid(emailBox.value);
        let passValid  = passBox.value == passBoxR.value;
        return emailValid && passValid;
    }
    
    emailBox.onchange = function() { // I love the ternary operator
        emailNote.innerHTML = isEmailValid(emailBox.value) ? "*" : "Please enter a valid email address.";
    }
    
    fNameBox.onchange = function() {
        fNameNote.innerHTML = !isStringBlank(fNameBox.value) ? "*" : "Please enter your first name.";
    }
    lNameBox.onchange = function() {
        lNameNote.innerHTML = !isStringBlank(lNameBox.value) ? "*" : "Please enter your last name.";
    }
    
    function passBoxChanged() {
        let msg = "*";
        if (isStringBlank(passBox.value)) {
            msg = "Your password cannot be blank or only contain whitespace.";
        } else if (passBox.value != passBoxR.value) {
            msg = "The passwords you entered do not match.";
        }
        passNote.innerHTML = msg;
    }
    passBox.onchange  = passBoxChanged;
    passBoxR.onchange = passBoxChanged;

    console.log("Registration form validation initialized.");

}