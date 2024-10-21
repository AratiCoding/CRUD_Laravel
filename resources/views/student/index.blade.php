@extends('layouts.app')
@section('content')

{{--Add Student modal--}}
<div class="modal fade" id="AddStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="saveform_errList"></ul>
                <div class="form-group mb-3">
                    <label for="">Name</label>
                    <input type="text" class="name form-control">

                </div>
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="text" class="email form-control">

                </div>
                <div class="form-group mb-3">
                    <label for="">Phone</label>
                    <input type="text" class="phone form-control">

                </div>
                <div class="form-group mb-3">
                    <label for="">Student Course</label>
                    <input type="text" class="course form-control">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add_student">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Student Modal--}}

<div class="modal fade" id="EditStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit & Update Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="updateform_errList"></ul>
                <input type="hidden" id="edit_stud_id">
                
                <div class="form-group mb-3">
                    <label for="">Name</label>
                    <input type="text" id="edit_name" class="name form-control">

                </div>
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="text" id="edit_email" class="email form-control">

                </div>
                <div class="form-group mb-3">
                    <label for="">Phone</label>
                    <input type="text" id="edit_phone" class="phone form-control">

                </div>
                <div class="form-group mb-3">
                    <label for="">Student Course</label>
                    <input type="text" id="edit_course" class="course form-control">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update_student">Update</button>
            </div>
        </div>
    </div>
</div>

{{--Delete Record--}}
<div class="modal fade" id="DeleteStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <input type="hidden" id="delete_stud_id">
                <h4>Are sure? want to delete this data?</h4>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary delete_student_btn">Yes</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-md-12">
            <div id="success_message"></div>
            <div class="card responsive-header">
                <div class="card-header">
                    <h4>Students Listing<a href="#" class="btn btn-dark float-end btn-sm" data-bs-toggle="modal"
                            data-bs-target="#AddStudentModal">Add Student</a></h4>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        fetchstudent();
        function fetchstudent() {
            $.ajax({
                type: 'GET',
                url: '/fetch-students',
                dataType: 'json',
                success: function (response) {
                    $('tbody').html("");
                    $.each(response.students, function (key, item) {
                       
                        $('tbody').append(`
                        <tr>
                         <td>${item.id}</td>
                         <td>${item.name}</td>
                         <td>${item.email}</td>
                         <td>${item.phone}</td>
                         <td>${item.course}</td>
                         <td><button type="button" value="${item.id}" class="edit_student btn btn-warning btn-sm">Edit</button></td>
                      <td><button type="button" value="${item.id}" class="delete_student btn btn-danger btn-sm">Delete</button></td>
                    </tr>
                    `);
                    });
                   },
                   error: function (xhr, status, error) {
                    console.error("Error fetching students:", error);
                    alert("Failed to fetch students. Please try again later.");
                }
            })
        }
       $(document).on('click','.delete_student',function(e){
            e.preventDefault();
            var stud_id=$(this).val();
            // alert(stud_id);
            $('#delete_stud_id').val(stud_id);
            $('#DeleteStudentModal').modal('show');
       });
       $(document).on('click','.delete_student_btn',function(e){
             e.preventDefault();
             var $button = $(this);
             $button.prop('disabled', true).text('Deleting...');
             var stud_id=$('#delete_stud_id').val();
            
            $.ajaxSetup({
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
             });

             $.ajax({
                 type:"DELETE",
                 url:"/delete_student/"+stud_id,
                 success:function(response){
                    console.log(response);
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text(response.message);
                    $('#DeleteStudentModal').modal('hide');
                    // $('.delete_student_btn').text("Deleting");
                    setTimeout(function() {
                      $('#success_message').removeClass('alert alert-success').html(""); // Optionally clear the message
                       }, 5000);
                    fetchstudent();
                 },
                 error: function () {
                 alert("An error occurred. Please try again.");
                },
                complete: function () {
                $button.prop('disabled', false).text('Yes');
                  }
             });
       });
        $(document).on('click','.edit_student', function(e){
               e.preventDefault();
               var stud_id=$(this).val();
               $('#EditStudentModal').modal('show');
               $.ajax({
                 type: "GET",
                 url: "/edit-student/"+stud_id,
                 success: function (response) {
                    console.log(response);
                    if(response.status==404){
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-danger');
                        $('#success_message').text(response.message);
                        }else{
                        $('#edit_name').val(response.message.name);
                        $('#edit_email').val(response.message.email);
                        $('#edit_phone').val(response.message.phone);
                        $('#edit_course').val(response.message.course);
                        $('#edit_stud_id').val(stud_id);
                    }
                    
                }
              });
        });

        $(document).on('click','.update_student',function(e){
        e.preventDefault();
          var $button = $(this);
          $button.prop('disabled', true).text('Updating...');
          $(this).text("Updating");
          var stud_id=$('#edit_stud_id').val();
          var data={
            'name': $('#edit_name').val(),
            'email': $('#edit_email').val(),
            'phone': $('#edit_phone').val(),
            'course': $('#edit_course').val(),
          }

          $.ajaxSetup({
           headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
           });
            $.ajax({
            type: "PUT",
            url: "/update_student/"+stud_id,
            data: data,
            dataType: "json",
            success: function (response) {
                if(response.status==400){
                    $('#updateform_errList').html("");
                    $('#updateform_errList').addClass("alert alert-danger");
                    $.each(response.errors, function (key, err_values) {
                    $('#updateform_errList').append('<li>' + err_values + '</li>');
                     
                     });
                     $('.update_student').text("Update");
                }else if(response.status==404){
                     $('#updateform_errList').html("");
                     $('#success_message').addClass("alert alert-success");
                     $('#success_message').text(response.message);
                     $('.update_student').text("Update");
                }else{
                    $('#updateform_errList').html("");
                    $('#success_message').html("");
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text(response.message);
                    $('#EditStudentModal').modal('hide');
                     

                     var studentRow = $('#student-table').find('tr[data-id="' + stud_id + '"]');
                     if (studentRow.length) {
                     studentRow.find('.student-name').text(response.student.name);
                     studentRow.find('.student-email').text(response.student.email);
                     studentRow.find('.student-phone').text(response.student.phone);
                     studentRow.find('.student-course').text(response.student.course);
                      }
                      setTimeout(function() {
                      $('#success_message').removeClass('alert alert-success').html(""); // Optionally clear the message
                       }, 5000);

                      $('.update_student').text("Update");
                      fetchstudent();
                }
              },
                error: function () {
               alert("An error occurred. Please try again.");
              },
               complete: function () {
               $button.prop('disabled', false).text('Update');
              }
          });
        });
        $(document).on('click', '.add_student', function (e) {
            e.preventDefault();
            var $button = $(this);
           $button.prop('disabled', true).text('Saving...');


            var data = {
                'name': $('.name').val(),
                'email': $('.email').val(),
                'phone': $('.phone').val(),
                'course': $('.course').val(),
            }
            //   console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                'url': "/students",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#saveform_errList').html("");
                        $('#saveform_errList').addClass("alert alert-danger");
                        $.each(response.errors, function (key, err_values) {
                        $('#saveform_errList').append('<li>' + err_values + '</li>');

                        });
                        } else {
                        $('#saveform_errList').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#AddStudentModal').modal('hide');
                        $('#AddStudentModal').find('input').val("");

                        setTimeout(function() {
                        $('#success_message').removeClass('alert alert-success').html("");
                        $('#saveform_errList').removeClass("alert alert-danger").html("");
                         }, 3000);
                        fetchstudent();
                    }
                },
                error: function () {
                alert("An error occurred. Please try again.");
                },
                complete: function () {
                 $button.prop('disabled', false).text('Save');
               }

            });
        });

    });

</script>

@endsection