<div class='row'>
	<div class='col-md-8 col-md-offset-2'>&nbsp;</div>
</div>
<div class='row'>
	<div class='col-md-8 col-md-offset-2'>
		<div class='well well-success'>No problem exists without a solution; The solution is hidden at the back side of your brain, you just need to pull it over. Take a break and come back to see it with a different side.
<i class='pull-right'>- Sailesh Jaiswal</i></div>
	</div>
</div>
<div class='row'>
	<div class='col-md-4 col-md-offset-2'><h4>Users</h4></div>
	<div class='col-md-4 text-right'><button id='AddUser' class='btn btn-primary'>Add User</button>
		<button id='EditUser' class='btn btn-primary'>Edit User</button>
		<button id='DeleteUser' class='btn btn-danger'>Delete User</button>
		</div>
	<div class='col-md-8 col-md-offset-2'>
		<form name='AllUsersForm' id='AllUsersForm' method='post'>
		<table class='table table-bordered table-stripped' id='users-datatable'>
			<thead>
				<tr>
					<th width='20'><input type='checkbox' name='AllUsers' id='AllUsers' /></th>
					<th>Name</th>
					<th>Email</th>
					<th>Created On</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		</form>
	</div>
</div>

<div aria-hidden="true" role="dialog" tabindex="-1" id="modal" class="modal fade modal-info" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content" id='modal-content'></div>
	</div>
</div>
