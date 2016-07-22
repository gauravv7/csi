var formElements = [
    //first object of form elements to be checked for 1st next button click
    {
        "salutation": {
            rule: [
                'required'
            ]
        },
        "fname": {
            rule: [
                'required',
                'name'
            ]
        },
        "mname": {
            rule: [
                'name'
            ]
        },
        "lname": {
            rule: [
                'name'
            ]
        },
        "card_name": {
            rule: [
                'required',
                'name'
            ]
        },
        "dob": {
            rule: [
                'required',
                'date'
            ]
        },
        "gender": {
            rule: [
                'required'
            ]
        },
    },
    //similarly second
    {
        "country": {
            rule: [
                'required'
            ]
        },
        "state": {
            rule: [
                'required'
            ]
        },
        "chapter": {
            rule: [
                'required'
            ]
        },
        "address": {
            rule: [
                'required',
                'address'
            ]
        },
        "city": {
            rule: [
                'required',
                'alphaDash'
            ]
        },
        "pincode": {
            rule: [
                'required',
                'alphaNumeric',
                'max:15'
            ]
        },

    },
    //dont ask now seriously
    {
        "email1": {
            rule: [
                'required',
                'email'
            ]
        },
        "email2": {
            rule: [
                'email'
            ]
        },
        "std": {
            rule: [
                'number'
            ],
            // aah, new thing; FYI it has to an ID attribute value of the element
            errorBlock: 'errorSTD'
        },
        "phone": {
            rule: [
                'number'
            ],
            errorBlock: 'errorPhone'
        },
        "country-code": {
            rule: [
                'required',
                'number',
                'max:3'
            ],
            // aah, new thing; FYI it has to an ID attribute value of the element
            errorBlock: 'errorCountry'
        },
        "mobile": {
            rule: [
                'required',
                'number',
                'min:10',
                'max:10'
            ],
            errorBlock: 'errorMobile'
        }

    },
    //dont ask now seriously
    {
        "associating_institution": {
            rule: [
                'required',

            ]
        },
        "organisation": {
            rule: [
                'required',
                'text'
            ]
        },
        "designation": {
            rule: [
                'required',
                'designation'
            ]
        },
        "employee_id":{
            rule: [
                'required',
            ]
        }

    },
];
$(document).ready(function() {
    $.validateIt({
        debug: false
    });
});
//datepicker ui settings
$(document).ready(function(){
    var today = new Date();
    var professionalLastDate = new Date(today.getFullYear() -21, 31, 12);

    $("#dob_professional").datepicker({
        dateFormat : 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: professionalLastDate,
        hideIfNoPrevNext: true,
        yearRange: '-45:-21'
    }).val();
});
//for-steps magic
$(document).ready(function(){
    $('.next').validateIt(formElements);
});