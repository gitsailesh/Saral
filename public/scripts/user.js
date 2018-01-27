getUsers();

function getUsers(){
	$('#AllUsers').prop('checked', false);
	users_table = $('#users-datatable').DataTable({
		"processing": true,
        "serverSide": true,
        "bDestroy": true,
	    "ajax" : {
	        "url" : "test/user-data",
	        "type" : "post",
	    },
	    "dom" : 'tip',
	    "columnDefs" : [ {
	        "orderable" : false,
	        "targets" : [ 0 ]
	    } ],
	    "order" : [ [1, 'asc' ] ],
	});
}

//validation rule to accept only letter
jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z\s]+$/i.test(value);
}, "Letters only please"); 


// add/edit user form trigger, validation & save trigger here
$(document).on('click', '#AddUser, #EditUser', function(e){
	e.preventDefault();
    var user_id = 0;
    if($(this).attr('id') == 'EditUser'){
    	user_id = $('.UserID:checked').val();
    	if($('.UserID:checked').size() == 0){
    		$.toaster('Please select user to edit.', 'Edit User', 'warning');
            return;
        }else if($('.UserID:checked').size() != 1){
    		$.toaster('Please select only one user to edit.', 'Edit User', 'warning');
            return;
        }
    }
	$('#modal').modal('show');
	$.post(SITE_URL+"test/user-form", {'UserID': user_id}, function(data){
		$('#modal-content').html(data);
		$('#UserForm').validate({
			rules:{
				Username: {required: true, minlength: 2, lettersonly: true},
				EmailID: {required: true, email: true, remote: {
    		        url: SITE_URL+"test/check-email",
    		        type: "post",
    		        data: {
    		        	UserID: $('#UserID').val(),
    		        	EmailID: function() {
    		        		return $( "#EmailID" ).val();
    		          },
    		        }
    		      }
				},
				Password:{required: true},
				ConfirmPassword:{required: true, equalTo: '#Password'}
			},
			messages:{
				Username: {required: 'Please enter your Name.', minlength: 'Name must be minimum of 2 and maximum of 50 characters.'},
				EmailID: {required: 'Please enter User Email.', email: 'Please enter valid Email.', remote: 'User Email already exists.'},
				Password : {required: 'Please enter Password.'},
				ConfirmPassword : {required: 'Please re-enter Password.', equalTo: 'Password mismatch.'},
			},
			submitHandler: function(form) {
				$('#UserSubmit').prop('disabled', true);
				$('#UserForm').ajaxSubmit({url: SITE_URL + "test/user-save", success: function(a, b, c, d){
   				data = $.parseJSON(a);
   				if(data.status == 'success'){
   					$.toaster(data.message, data.title, 'success');
   					$('#modal').modal('hide');
   					getUsers();
   				}else{
   					$('#UserSubmit').prop('disabled', false);
   					$.toaster(data.message, data.title, 'danger');
   				}
   			}});
   			return false;
   		}
		});
	});
});

$(document).on('click', '.UserID', function(e){
	if($('.UserID:enabled').size() == $('.UserID:checked:enabled').size()){
		$('#AllUsers').prop('checked', true);
	}else{
		$('#AllUsers').prop('checked', false);
	}
});

$(document).on('click', '#AllUsers', function(){
	$('.UserID:enabled').prop('checked', $(this).prop('checked'));
});


$(document).on('click', '#DeleteUser', function(e){
	e.preventDefault();
	if($('.UserID:checked').size() == 0){
		$.toaster('Please select at least one user to delete.', 'Delete User', 'warning');
        return;
    }
	$.confirm({
	    title: 'Delete User',
	    content: 'Do you really want to delete?',
	    buttons: {
	        confirm: function () {
				$('#AllUsersForm').ajaxSubmit({'url': SITE_URL +'test/user-delete', success: function(a, b, c, d){
					data = $.parseJSON(a);
					if(data.status == 'success'){
	   					$.toaster(data.message, data.title, 'success');
	   					getUsers();
	   				}else{
	   					$.toaster(data.message, data.title, 'danger');
	   				}
	   			}});
	        },
	        cancel: function () {
	        }
	    }
	});
	
	
});