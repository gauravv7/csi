var mp;
var url = window.location.origin+'/';
console.log(url);
var formElements = [
	//dont ask now seriously
	{
		"membership-period": {
			rule: [
				'required'
			]	
		},
		"paymentMode": {
			rule: [
				'required'
			]	
		},
		"bank": {
			rule: [
				'required',
				'text'
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
				'text'
			]
		},
		"tno": {
			rule: [
				'required',
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
	$('#payable-meta').fadeOut();
});

//for-steps magic
$(document).ready(function(){
	$('#submit').validateIt(formElements);
});

$(document).ready(function(){
	if($('input:radio[name="membership-period"]').is(':checked')){
		mp = $('input:radio[name="membership-period"]').val();
		$('#membershipPeriod').text($(this).data('name'));
		request_amount();
	}
});
$('input:radio[name="membership-period"]').change(function() {
	//indian
	if (this.checked) {
		// note that, as per comments, the 'changed'
		// <input> will *always* be checked, as the change
		// event only fires on checking an <input>, not
		// on un-checking it.
		// append goes here
		mp = parseInt(this.value);
		$('#membershipPeriod').text($(this).data('name'));
		request_amount();
	}
});

function request_amount() {
	
	if( (mp!=0) ) {
		
		// console.log($('#country').val());
		
		var sendInfo = {
			mem_period : mp
		};
		$.ajax({
			url : url+"register/getresource/amount",
			method : "POST",
			async : true,
			dataType: "json",
			data : sendInfo
		}).success(function(data) {;
			$('#fee').text(data.amount);
			$('#tax').text(data.service_tax);
			$('#payable').text(data.total);
			$('#payable-meta').fadeIn();
			$('#amount_paid').val(data.sum);
		}).fail(function(data) {
			alert('some technical error occured. please try again later');
		});
	}
}