var mp;
var url = window.location.origin+'/';
var formElements = [
	
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
				'alphaDash'	
			]	
		},
		
	},
	
];
$(document).ready(function() {
  $.validateIt({
        debug: false
    });
});
/*
$(document).ready(function(){
	
	var originalPadding = $('body').css('padding-top');
	var isExpanded = false;
	var isNavbarFixedToggled = false;
	var newPadding = parseInt(originalPadding);
	var windowWidth = 0;
	console.log(originalPadding);
	
	//when toggle button for navbar is clicked, set body padding to match fixed-nav styles
	$('#navbar_fixed_top').click(function(){
		
		if(!isNavbarFixedToggled){
			// getting the last div of the div containing this clicked button
			var div = $(this).parent().siblings(':last');
			var offsetHeight = div.outerHeight();
			console.log(offsetHeight);
			console.log(div.prop('id'));
			newPadding += offsetHeight + 10;
			console.log(newPadding);
			isNavbarFixedToggled = true;
		}
		
		//menu is not expanded then set the body padding to new one
		if(!isExpanded){
			$('body').css('padding-top', newPadding +'px');
			isExpanded = true;
		} else if(isExpanded){
			//menu is collapsed then set the body padding to old one
			$('body').css('padding-top', originalPadding);
			isExpanded = false;
		}
	});
	
	//when resizing the window, set body padding to match fixed-nav styles
	$( window ).resize(function() {
	  	windowWidth = $(window).width();
	  	console.log( windowWidth );
		
		if(windowWidth < 768){
			if(isExpanded){
				if(isNavbarFixedToggled){
					$('body').css('padding-top', newPadding +'px');
				}
			}
		} else if(isExpanded){
				if(isNavbarFixedToggled){
					$('body').css('padding-top', originalPadding);
				}
		}
	});
	
	
	var pos = $('#main-nav-bar').position();
	$('#brand_logo').hide();
	      	  
	  mainBarScrollHandler(window);
	  console.log($(this).scrollTop());
	  $(window).scroll(function(){
			mainBarScrollHandler(this);
	  });
});
*/
// handle scroll event styles for main bar
function mainBarScrollHandler(element){

	if ( $(window).scrollTop() > 157 ){
		$('#main-nav-bar').addClass('fixed').addClass('slideDown');
      	  
	      	$('body').removeClass('p-157');
	      	 $('body').addClass('p-210');
  		  
      	  $('#csi-logo').css({'visibility': 'hidden'});
      	  $('#brand_logo').css('width', '200px').fadeIn();

      	  return true;
	}
	 if ($(element).scrollTop() > 135) {
      	  $('#main-nav-bar').addClass('fixed').addClass('slideDown');
      	  if($(this).scrollTop()>157){
	      	 if($('body').hasClass('p-157')){
	      	  	$('body').removeClass('p-157');
	      	 }
	      	 $('body').addClass('p-210');
  		  }else {
      	  	if($('body').hasClass('p-210')){
      	  		$('body').removeClass('p-210');
      	  	}
      	  	$('body').addClass('p-157');
      	  }
      	  $('#csi-logo').css({'visibility': 'hidden'});
      	  $('#brand_logo').css('width', '200px').fadeIn();
      } else {
	      $('#brand_logo').css('width', 0).fadeOut(50);
      	  if($('body').hasClass('p-157')){
      	  	$('body').removeClass('p-157');
      	  }
      	  if($('body').hasClass('p-210')){
      	  	$('body').removeClass('p-210');
      	  }
      	  $('#csi-logo').css({'visibility': 'visible'});
          $('#main-nav-bar').removeClass('fixed').removeClass('slideDown');
      }
}


//datepicker ui settings
$(document).ready(function(){
	var today = new Date();
	var studentLastDate = new Date(today.getFullYear() -15, 1, 1);
	var professionalLastDate = new Date(today.getFullYear() -18, 1, 1);
	
	$("#dob_student").datepicker({
		dateFormat : 'dd/mm/yy',
		changeMonth: true,
	    changeYear: true, 
	    maxDate: new Date(today-100, 1,1),
	    hideIfNoPrevNext: true,
	    yearRange:  "-100:+0"
	}).val();
	$("#dob_professional").datepicker({
		dateFormat : 'dd/mm/yy',
		changeMonth: true,
	    changeYear: true, 
	    maxDate: professionalLastDate,
	    maxDate: new Date(today-100, 1,1),
	    hideIfNoPrevNext: true,
	    yearRange: '-100:+0'
	}).val();
	$("#drawn_on").datepicker({
        changeMonth: true,
        changeYear: true,
		dateFormat : 'dd/mm/yy'
	}).val();
	
});

//for-steps magic
$(document).ready(function(){
	$('#submit').validateIt(formElements);
});


/*
$(document).ready(function() {


	mp = $('input:radio[name="membership-period"]:checked').val();
	
	var valueSelected = $('select[name="country"] option:selected').val();
	
	request_amount();
	request_states(valueSelected);
	request_states(valueSelected);

	$('input:radio[name="membership-period"]').change(function() {
		//indian
		if (this.checked) {
			// note that, as per comments, the 'changed'
			// <input> will *always* be checked, as the change
			// event only fires on checking an <input>, not
			// on un-checking it.
			// append goes here
			mp = parseInt(this.value);
			request_amount();
		}
	});
	$('input:radio[name="membership-type"]').change(function() {
		//indian
		if (this.checked && parseInt(this.value) != 0) {
			// note that, as per comments, the 'changed'
			// <input> will *always* be checked, as the change
			// event only fires on checking an <input>, not
			// on un-checking it.
			// append goes here

			mt = parseInt(this.value);
			if (mp != 0 && mt != 0) {
				request_amount();
			}
		}
	});
	
	$('select[name="country"]').keydown(function(e) {
	   console.log('keyup called');
	   var code = e.keyCode || e.which;
	   if (code == '9') {
	     console.log('Tab pressed');
	     var optionSelected = $("option:selected", this);
		var valueSelected = this.value;
		console.log(valueSelected);
		
		request_amount();
		request_states(valueSelected);
	
	   }
	
	});
	$('#state').keydown(function(e) {
	   console.log('keyup called');
	   var code = e.keyCode || e.which;
	   if (code == '9') {
	     console.log('Tab pressed');
	     console.log($('#state').has('option').length ==0);
		if( $('#state').has('option').length == 0 ){
			console.log('Please select country first');
		}else{
			var optionSelected = $("option:selected", this);
			var valueSelected = this.value;
			console.log(valueSelected);
			
			console.log(typeof($(this).data('state')));
			if( $(this).data('state') ==0 ){
				request_chapters(valueSelected);
				if($('#asoc_inst').length > 0){
					request_asoc_inst(valueSelected);
				}
			} else if( $(this).attr('data-state') ==1 ){
				request_student_branches(valueSelected);
			}
			
		}
	
	   }
	
	});
	
	$('#chapter').on('click', function(e) {
		console.log($('#state').has('option').length ==0);
		if( $('#state').has('option').length == 0 ){
			console.log('Please select country first');
		}else{
			
		}
	});

	$('select[name="country"]').on('click', function(e) {
		var optionSelected = $("option:selected", this);
		var valueSelected = this.value;
		console.log(valueSelected);
		
		request_amount();
		request_states(valueSelected);
	});
	
	$('#state').on('click', function(e){
		console.log($('#state').has('option').length == 0);
		
		
		
		if( $('#state').has('option').length == 0 ){
			console.log('Please select country first');
			//repeating code here to test with one country only
			
			var valueSelected = $('select[name="country"] option:selected').val();
			console.log(valueSelected);
			request_amount();
			request_states(valueSelected);
			
		}else{
			var optionSelected = $("option:selected", this);
			var valueSelected = this.value;
			console.log(valueSelected);
			
			console.log(typeof($(this).data('state')));
			if( $(this).data('state') ==0 ){
				request_chapters(valueSelected);
			} else if( $(this).attr('data-state') ==1 ){
				request_student_branches(valueSelected);
			}
			
		}
	});
	
	$('#chapter').on('click', function(e){
		console.log($('#state').has('option').length ==0);
		if( $('#state').has('option').length == 0 ){
			console.log('Please select country first');
		}else{
			
		}
	});
	
});

function request_amount() {
	
	if( (mp!=0) && ( 'invalid'!==$('#country').val().toLowerCase() ) ) {
		
		console.log($('#country').val());
		
		var sendInfo = {
		   	country_code : $('#country').val(),
			mem_period : mp
		};
		$.ajax({
			url : url+"register/getresource/amount",
			method : "POST",
			async : true,
			dataType: "json",
			data : sendInfo
		}).success(function(data) {
			if (console && console.log) {
				console.log("Sample of data:"+ data.amount);
			}
			try{			
						var amount = parseFloat(data.amount);
						console.log(amount);
						var tax = parseFloat(data.service_tax);
						var total = (amount + ((amount*tax)/100));
						console.log(total);
			} catch(e){
				console.log(e);
			}

			var span = $('<span />', {
				html: 'Membership Fee: '+data.amount+'<br/> Service Tax: '+data.service_tax+'0#37<br/> Total Payable Amount: '+total
			});
			//console.log(span);
			$('#fee').text(data.amount);
			$('#tax').text(data.service_tax);
			$('#payable').text(total);
		}).fail(function(data) {
			alert('some technical error occured. please try again later');
		});
	}
}

function request_states(country_code) {

	var sendInfo = {
		code : country_code
	};

	$.ajax({
		url : url+"register/getresource/states",
		method : "POST",
		async : true,
		dataType: "json",
		data : sendInfo
	}).success(function(data) {
		if (console && console.log) {
			console.log("Sample of data:", (data!="[]")? data: "false");
			$('#state').empty();
			$('#state').append($('<option>', {
				value: 'invalid',
				text: 'Please select a state'
			}));
			$.each(data, function(idx, obj) {
				$('#state').append($('<option>', { 
			        value: obj.state_code,
			        text : obj.name 
			    }));
			});
		}
		//$('#amount').text(data);
	}).fail(function(data) {
		alert('some technical error occured. please try again later');
	});
}
function request_student_branches(state_code) {


	var sendInfo = {
		code : state_code
	};
	$.ajax({
		url : url+"register/getresource/branches",
		method : "POST",
		async : true,
		dataType: "json",
		data : sendInfo
	}).success(function(data) {
		if (console && console.log) {
			console.log("Sample of chapter data:", (data!="[]")? data: "false");
			console.log("Student branch data: "+(data != "null"));
			
			
			$('#stud_branch').empty();
			$('#stud_branch').append($('<option>', {
				value: 'invalid',
				text: 'Please select a student branch'
			}));
			//re-define this service to safely type cast receiving data as of null type
			if((data) != "null"){
				$.each(data, function(idx, obj) {
					console.log(obj.chapter_name);
					$('#stud_branch').prepend($('<option>', { 
				        value: obj.member_id,
				        text : obj.name
				        
				    }));
				    
				});
			} else{
				$('#stud_branch').prepend($('<option>', { 
				        value: '',
				        text : 'No student branches are available for selected state'
				        
				    }));
			}
		}
		//$('#amount').text(data);
	}).fail(function(data) {
		//alert('some technical error occured. please try again later');
	});
}
function request_chapters(state_code) {

	var sendInfo = {
		code : state_code
	};

	$.ajax({
		url : url+"register/getresource/chapters",
		method : "POST",
		async : true,
		dataType: "json",
		data : sendInfo
	}).success(function(data) {
		if (console && console.log) {
			console.log("Sample of chapter data:", (data!="[]")? data: "false");
			
			$('#chapter').empty();
			$('#chapter').append($('<option>', {
				value: 'invalid',
				text: 'Please select a chapter'
			}));
			if(data!="[]"){
				$.each(data, function(idx, obj) {
					console.log(obj.chapter_name);
					$('#chapter').prepend($('<option>', { 
				        value: obj.id,
				        text : obj.name
				        
				    }));
				    
				});
			} else{
				$('#chapter').prepend($('<option>', { 
				        value: '',
				        text : 'No Chapter available for selected state'
				        
				 }));
			}
		}
		
		//$('#amount').text(data);
	}).fail(function(data) {
		alert('some technical error occured. please try again later');
	});
}

//getting all the asoc inst for a prof member .. revise this later acc to buz. logic
function request_asoc_inst(state_code) {

	var sendInfo = {
		code : state_code
	};

	$.ajax({
		url : url+"register/getresource/institutions",
		method : "POST",
		async : true,
		dataType: "json",
		data : sendInfo
	}).success(function(data) {
		if (console && console.log) {
			console.log("Sample of chapter data:", (data!="[]")? data: "false");
			
			$('#asoc_inst').empty();
			$('#asoc_inst').append($('<option>', {
				value: 'invalid',
				text: 'Please select an associated institution'
			}));
			$.each(data, function(idx, obj) {
				console.log(obj.chapter_name);
				$('#asoc_inst').prepend($('<option>', { 
			        value: obj.member_id,
			        text : obj.name
			        
			    }));
			    
			});
		}
		//$('#amount').text(data);
	}).fail(function(data) {
		alert('some technical error occured. please try again later');
	});
}*/