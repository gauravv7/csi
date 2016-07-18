var mp;
var KEY_COUNTRY = "country_choice";
var KEY_STATE = "state_choice";
var KEY_CHAPTER = "chapter_choice";
var KEY_STUDENT_BRANCH = "sbranch_choice";
var KEY_ASSOCIATING_INST = "associating_institution";

var TTL = 15;
var url = window.location.origin+'/';

function setCookie(name, value, TTL) {
   var date = new Date();
   date.setTime(date.getTime()+(TTL*(60*1000)));
   var expires = "; expires="+date.toGMTString();
   
   document.cookie = name+"="+value+expires+"; path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
function handleCookie(name, value) {
    var cookieData = getCookie(name);
    if (cookieData != "") {
        eraseCookie(name);
    } else{
    	cookieData = false;
    }
    setCookie(name, value, TTL);
    return cookieData;
}
function eraseCookie(name) {
    setCookie(name,"",-1*TTL);
}
function setSelect(name, value){
	$('select[name=' + name + ']').find('option[value="' + value + '"]').attr("selected", "selected");
}

$(document).ready(function() {
	mp = $('input:radio[name="membership-period"]:checked').val();
	var is_nominee_form = ($('select[name="associating_institution"] option:selected').length >0 )?true:false;
	var valueSelected, valueStateSelected, valueSBranchSelected, valueChapterSelected, valueAssociatingInstitutionSelected;
	if( (valueSelected = getCookie(KEY_COUNTRY))!="" ){
		if(valueSelected!="invalid" && valueSelected == 'IND'){
			setSelect("country", valueSelected);
			request_states(valueSelected);
			request_country_dial_code(valueSelected);
		} else{
			$('#ForeignNationalNotAllowed').modal({
				backdrop: 'static'
			}).modal('show');
			$('#ForeignNationalNotAllowed').on('hidden.bs.modal', function (e) {
				eraseCookie(KEY_COUNTRY);
				window.location.reload();
			});
		}
	} else{
		valueSelected = "IND";
		cdata = handleCookie(KEY_COUNTRY, valueSelected);	//setting cookie to get value later
		setSelect("country", valueSelected);
		request_states(valueSelected);
		request_country_dial_code(valueSelected);
	}

	if(is_nominee_form) {
		if ((valueAssociatingInstitutionSelected = getCookie(KEY_ASSOCIATING_INST)) != "") {
			if (valueAssociatingInstitutionSelected != "invalid") {
				setSelect("associating_institution", valueAssociatingInstitutionSelected);
				var text = $('select[name="associating_institution"] option:selected').text();
				$('input[name="organisation"]').val(text);
			}
		}
		$('select[name="associating_institution"]').keydown(function (e) {
			var code = e.keyCode || e.which;
			if (code == '9') {
				var optionSelected = $("option:selected", this);
				var valueAssociatingInstitutionSelected = this.value;
				if (valueAssociatingInstitutionSelected != "invalid") {
					cdata = handleCookie(KEY_ASSOCIATING_INST, valueAssociatingInstitutionSelected);	//setting cookie to get value later
					var text = $('select[name="associating_institution"] option:selected').text();
					$('input[name="organisation"]').val(text);
				}
			}
		});

		$('select[name="associating_institution"]').on('click', function (e) {
			var optionSelected = $("option:selected", this);
			var valueAssociatingInstitutionSelected = this.value;
			if (valueAssociatingInstitutionSelected != "invalid") {
				cdata = handleCookie(KEY_ASSOCIATING_INST, this.value);	//setting cookie to get value later
				var text = $('select[name="associating_institution"] option:selected').text();
				$('input[name="organisation"]').val(text);
			}
		});
	}
	
	$('input:radio[name="membership-period"]').change(function() {
		//indian
		if (this.checked) {
			// note that, as per comments, the 'changed'
			// <input> will *always* be checked, as the change
			// event only fires on checking an <input>, not
			// on un-checking it.
			// append goes here
			mp = parseInt(this.value);
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
		}
	});
	$('select[name="country"]').keydown(function(e) {
	   var code = e.keyCode || e.which;
	   if (code == '9') {
	    var optionSelected = $("option:selected", this);
		var valueSelected = this.value;
		if(valueSelected!="invalid" && valueSelected == 'IND'){
			cdata = handleCookie(KEY_COUNTRY, valueSelected);	//setting cookie to get value later
			request_states(valueSelected);
			request_country_dial_code(valueSelected);
		} else{
			$('#ForeignNationalNotAllowed').modal({
				backdrop: 'static'
			}).modal('show');
			$('#ForeignNationalNotAllowed').on('hidden.bs.modal', function (e) {
				eraseCookie(KEY_COUNTRY);
				window.location.reload();
			});
		}
	   }
	});
	$('#state').keydown(function(e) {
	   var code = e.keyCode || e.which;
	   	if (code == '9') {
			if( $('#state').has('option').length != 0 ){
				var optionSelected = $("option:selected", this);
				var valueSelected = this.value;
				cdata = handleCookie(KEY_STATE, valueSelected);	//setting cookie to get value later
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
	if($('#chapter').length){
		$('#chapter').keydown(function(e) {
		   var code = e.keyCode || e.which;
		   if (code == '9') {
			if(this.value!="invalid"){
				cdata = handleCookie(KEY_CHAPTER, this.value);	//setting cookie to get value later
			}
		   }
		});
	}
	if($('#stud_branch').length){
		$('#stud_branch').keydown(function(e) {
		   var code = e.keyCode || e.which;
		   if (code == '9') {
			if(this.value!="invalid"){
				cdata = handleCookie(KEY_STUDENT_BRANCH, this.value);	//setting cookie to get value later
			}
		   }
		});
	}
	$('select[name="country"]').on('click', function(e) {
		var optionSelected = $("option:selected", this);
		var valueSelected = this.value;
		if(valueSelected!="invalid" && valueSelected == 'IND'){
			cdata = handleCookie(KEY_COUNTRY, this.value);	//setting cookie to get value later
			request_states(valueSelected);
			request_country_dial_code(valueSelected);
		} else{
			$('#ForeignNationalNotAllowed').modal({
				backdrop: 'static'
			}).modal('show');
			$('#ForeignNationalNotAllowed').on('hidden.bs.modal', function (e) {
				eraseCookie(KEY_COUNTRY);
				window.location.reload();
			});
		}
	});
	
	$('#state').on('click', function(e){
		if( $('#state').has('option').length == 0 ){
			var valueSelected = $('select[name="country"] option:selected').val();
			cdata = handleCookie(KEY_STATE, valueSelected);	//setting cookie to get value later
			request_states(valueSelected);
		}else{
			var optionSelected = $("option:selected", this);
			var valueSelected = this.value;
			if(valueSelected!="invalid"){
				cdata = handleCookie(KEY_STATE, valueSelected);	//setting cookie to get value later
				if( $(this).data('state') == 0 ){
					request_chapters(valueSelected);
				} else if( $(this).data('state') ==1 ){
					request_student_branches(valueSelected);
				}
			}
		}
	});

	if($('#chapter').length){
		$('#chapter').on('click', function(e) {
			console.log(this.value);
			if(this.value!="invalid"){
				cdata = handleCookie(KEY_CHAPTER, this.value);	//setting cookie to get value later
			}
		});
	}
	if($('#stud_branch').length){
		$('#stud_branch').on('click', function(e) {
			if(this.value!="invalid"){
				cdata = handleCookie(KEY_STUDENT_BRANCH, this.value);	//setting cookie to get value later
			}
		});
	}
	
});

function request_amount() {
	
	if( (mp!=0) && ( 'invalid'!==$('#country').val().toLowerCase() ) ) {
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
			
			try{			
				var amount = parseFloat(data.amount);
				var tax = parseFloat(data.service_tax);
				var total = (amount + ((amount*tax)/100));
			} catch(e){
				console.log(e);
			}
			var span = $('<span />', {
				html: 'Membership Fee: '+data.amount+'<br/> Service Tax: '+data.service_tax+'0#37<br/> Total Payable Amount: '+total
			});
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
		//set previously made choice from cookie
		if( (valueStateSelected = getCookie(KEY_STATE))!="" ){
			setSelect("state", valueStateSelected);
		
			if($('#state').data('state') == 1){
				//set student branch
				request_student_branches(valueStateSelected);
			} else{
				//set chapter
				request_chapters(valueStateSelected);
			}
		}

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
		$('#stud_branch').empty();
		$('#stud_branch').append($('<option>', {
			value: 'invalid',
			text: 'Please select a student branch'
		}));
		//re-define this service to safely type cast receiving data as of null type
		if((data) != "null"){
			$.each(data, function(idx, obj) {
				$('#stud_branch').prepend($('<option>', { 
			        value: obj.member_id,
			        text : obj.name
			    }));
			});
			//select previously made choice from cookie
			if( (valueSBranchSelected = getCookie(KEY_STUDENT_BRANCH))!="" ){
				setSelect("stud_branch", valueSBranchSelected);
			}
		} else{
			$('#stud_branch').prepend($('<option>', { 
		        value: '',
		        text : 'No student branches are available for selected state'
		    }));
		}
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
		$('#chapter').empty();
		$('#chapter').append($('<option>', {
			value: 'invalid',
			text: 'Please select a chapter'
		}));
		if(data.length){
			$.each(data, function(idx, obj) {
				$('#chapter').append($('<option>', { 
			        value: obj.id,
			        text : obj.name
			    }));
			});
			//select previously made choice from cookie
			if( (valueChapterSelected = getCookie(KEY_CHAPTER))!="" ){
				setSelect("chapter", valueChapterSelected);
			} 
		} else{
			$('#chapter').prepend($('<option>', { 
			        value: '',
			        text : 'No Chapter available for selected state'
			 }));
		}
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
	}).fail(function(data) {
		alert('some technical error occured. please try again later');
	});
}

function request_country_dial_code(country_code) {

	var sendInfo = {
		code : country_code
	};

	$.ajax({
		url : url+"register/getresource/country_dial_code",
		method : "POST",
		async : true,
		dataType: "json",
		data : sendInfo
	}).success(function(data) {
		$.each(data, function(idx, obj) {
			$('#country-code-for-std').text("+" + obj.dial_code);
			$('#country-code').val(obj.dial_code);
		});
	}).fail(function(data) {
		alert('some technical error occured. please try again later');
	});
}