var formElements = [
	//dont ask now seriously
	{
		"paymentMode": {
			rule: [
				'required'
			]	
		},
		"bank": {
			rule: [
				'required',
				'alphaNumeric'
			]
		},
		"drawn": {
			rule: [
				'required',
				'date'
			]	
		},
		"branch": {
			rule: [
				'required',
				'alphaNumeric'
			]
		},
		"tno": {
			rule: [
				'required',
				'alphaNumeric'
			],
		},
		"paymentReciept": {
			rule: [
				'required'
			]
		},
		"amountPaid": {
			rule: [
				'required',
				'number'	
			],
		}
		
	}
	
];
$(document).ready(function() {
  $.validateIt({
        debug: false
    });
});
//datepicker ui settings
$(document).ready(function(){
	var today = new Date();
	$("#drawn_on").datepicker({
		dateFormat : 'dd/mm/yy',
		changeMonth: true,
	    changeYear: true,
	    minDate: new Date(today.getFullYear(), today.getMonth()-3,today.getDate()),
	    maxDate: today,
	    hideIfNoPrevNext: true
	}).val();
});

//for-steps magic
$(document).ready(function(){
	$('#submit').validateIt(formElements);
});