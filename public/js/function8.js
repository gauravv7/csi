var formElements = [
	//first object of form elements to be checked for 1st next button click
	{
		//name attribute value of the form input element
		"nameOfInstitution": {
			rule: [
				'required',
				'text'	
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
				'required',
				'number'
			],
			// aah, new thing; FYI it has to an ID attribute value of the element 
			errorBlock: 'errorSTD'
		},
		"phone": {
			rule: [
				'required',
				'number'	
			],
			errorBlock: 'errorPhone'
		}
		
	},
	//dont ask now seriously
	{
		"salutation": {
			rule: [
				'required'
			]	
		},
		"headEmail": {
			rule: [
				'required',
				'email'
			]
		},
		"headName": {
			rule: [
				'required',
				'name'
			]	
		},
		"headDesignation": {
			rule: [
				'required',
				'designation'
			]
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
	
];
$(document).ready(function() {
  $.validateIt({
        debug: false
    });
});
//datepicker ui settings
$(document).ready(function(){
	$("#drawn_on").datepicker({
        changeMonth: true,
        changeYear: true,
		dateFormat : 'dd/mm/yy'
	}).val();
});

//for-steps magic
$(document).ready(function(){
	$('.next').validateIt(formElements);
});