var formElements = [
    //first object of form elements to be checked for 1st next button click
     //dont ask now seriously
    {
        "associating_institution": {
            rule: [
                'required',
            ]
        },
    },
];
$(document).ready(function() {
    $.validateIt({
        debug: false
    });
});
//for-steps magic
$(document).ready(function(){
    $('#submit').validateIt(formElements);
});