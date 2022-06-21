// Profile edit form validator by James Hyun
"use strict";

// =====================================================================================================================

function $(id) {
    return document.getElementById(id);
}

function isEmailValid(str) {
    // Regex expression is from https://stackoverflow.com/questions/3968500/regex-to-validate-a-message-id-as-per-rfc2822
    // jfc this is wild, thanks IETF >:(
    let regex = /((([a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*)|("(([\x01-\x08\x0B\x0C\x0E-\x1F\x7F]|[\x21\x23-\x5B\x5D-\x7E])|(\\[\x01-\x09\x0B\x0C\x0E-\x7F]))*"))@(([a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*)|(\[(([\x01-\x08\x0B\x0C\x0E-\x1F\x7F]|[\x21-\x5A\x5E-\x7E])|(\\[\x01-\x09\x0B\x0C\x0E-\x7F]))*\])))/;
    return regex.test(str);
}

function isStringBlank(str) {
    return /^\s*$/.test(str); // Matches empty strings and strings with only whitespace
}

// =====================================================================================================================

window.onload = function() {
    
    let emailBox = $("email");
    let fNameBox = $("fName");
    let lNameBox = $("lName");
    
    let emailNote = $("emailNote");
    let fNameNote = $("fNameNote");
    let lNameNote = $("lNameNote");
    
    emailBox.onchange = function() { // I love the ternary operator
        emailNote.innerHTML = isEmailValid(emailBox.value) ? "*" : "Please enter a valid email address.";
    }
    
    fNameBox.onchange = function() {
        fNameNote.innerHTML = !isStringBlank(fNameBox.value) ? "*" : "Please enter your first name.";
    }
    lNameBox.onchange = function() {
        lNameNote.innerHTML = !isStringBlank(lNameBox.value) ? "*" : "Please enter your last name.";
    }

    console.log("Profile edit form validation initialized.");

}