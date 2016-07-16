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
		"stud_branch": {
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
		"college": {
			rule: [
				'required',
				'text'
			]
		},
		"course": {
			rule: [
				'required',
				'alphaDash'
			]	
		},
		"cbranch": {
			rule: [
				'required',
				'alphaDash'
			]
		},
		"cduration": {
			rule: [
				'required',
				'number'
			]
		},
		"student_id":{
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
	var studentLastDate = new Date(today.getFullYear() -15, 1, 1);
	
	$("#dob_student").datepicker({
		dateFormat : 'dd/mm/yy',
		changeMonth: true,
	    changeYear: true, 
	    maxDate: today,
	    hideIfNoPrevNext: true,
	    yearRange:  "-30:+0"
	}).val();
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
