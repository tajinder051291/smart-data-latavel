<script>

    CKEDITOR.replace( 'discription' );
    CKEDITOR.replace( 'step_to_earn' );
    CKEDITOR.replace( 'note' );
    setTimeout(() => {
        $('.alert-success').hide();
    }, 2000);
    var _URL = window.URL;
    function readURL(input) {
        var file, img;
        if ((file = input.files[0])) {
            img = new Image();
            img.onload = function () {
                if((this.width >= 350 && this.width <= 450) && (this.height >= 350 && this.height <= 450) ){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
                }else{
                    $("#imageUpload").val('');
                    swal("Image size is not valid, please try to upload the image of size 400 X 400");
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });
    function readURLOne(input) {
        var file, img;
        if ((file = input.files[0])) {
            img = new Image();
            img.onload = function () {
                //if((this.width >= 1000 && this.width <= 1200) && (this.height >= 300 && this.height <= 550) ){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview1').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview1').hide();
                        $('#imagePreview1').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
               /* }else{
                    $("#imageUpload1").val('');
                    swal("Image size is not valid, please try to upload the image of size (1000-1200) X (300-550)");
                    return false;
                }
                alert("Width:" + this.width + "   Height: " + this.height);//this will give you image width and height and you can easily validate here.... */
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    $("#imageUpload1").change(function() {
        readURLOne(this);
    });
    function readURLTwo(input) {
        var file, img;
        if ((file = input.files[0])) {
            img = new Image();
            img.onload = function () {
                
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview2').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview2').hide();
                        $('#imagePreview2').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
                
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    $("#imageUpload2").change(function() {
        readURLTwo(this);
    });
    function readURLThree(input) {
        var file, img;
        if ((file = input.files[0])) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview3').css('background-image', 'url('+e.target.result +')');
                $('#imagePreview3').hide();
                $('#imagePreview3').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload3").change(function() {
        readURLThree(this);
    });
    
    function readURLFour(input) {
        var file, img;
        if ((file = input.files[0])) {
            img = new Image();
            img.onload = function () {
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview4').css('background-image', 'url('+e.target.result +')');
                    $('#imagePreview4').hide();
                    $('#imagePreview4').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
                return true;
                
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    $("#imageUpload4").change(function() {
        readURLFour(this);
    });

    function readURLFive(input) {
        var file, img;
        if ((file = input.files[0])) {
            img = new Image();
            img.onload = function () {
                //if((this.width == 600) && (this.height == 900 && this.height == 900) ){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview5').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview5').hide();
                        $('#imagePreview5').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
                /*}else{
                    $("#imageUpload5").val('');
                    swal("Image size is not valid, please try to upload the image of size 600 X 900");
                    return false;
                }
                alert("Width:" + this.width + "   Height: " + this.height);//this will give you image width and height and you can easily validate here.... */
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    $("#imageUpload5").change(function() {
        readURLFive(this);
    });
    function readURLSix(input) {
        var file, img;
        if ((file = input.files[0])) {
            img = new Image();
            img.onload = function () {
                if((this.width == 600) && (this.height == 600) ){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview6').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview6').hide();
                        $('#imagePreview6').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
                }else{
                    $("#imageUpload6").val('');
                    swal("Image size is not valid, please try to upload the image of size 600 X 600");
                    return false;
                }
                alert("Width:" + this.width + "   Height: " + this.height);//this will give you image width and height and you can easily validate here....
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    $("#imageUpload6").change(function() {
        readURLSix(this);
    });
// Users
    //Delete User section start 
    $('.delete_user').on('click',function(e){
        var delete_row = $(this);
        var id = $(this).attr("data-user_id");

        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/deleteUser')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/deleteUser')}}"
        }
        swal("Are you sure you want to delete this user?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
        
    });
    $('.select2').select2();
    $('.select2').select2({
        placeholder: {
            id: '-1', // the value of the option
            text: 'Categories'
        }
    });
    $('.select3').select2({
        placeholder: {
            id: '-1', // the value of the option
            text: 'States'
        }
    });
    $('.select4').select2({
        placeholder: {
            id: '-1', // the value of the option
            text: 'Cities'
        }
    });

    $('.select4').select2({
        placeholder: {
            id: '-1', // the value of the option
            text: 'Select the Permissions'
        }
    });
    
    // Delete Category section End 
    
    $('#category_select').on('change',function(){
        var category_id = $(this).val();
        
        if(category_id == 3){
            $(".add_offer_display_image").removeAttr('required');
            $(".video_categories").removeClass('d-none');
            $(".video_categories select").attr('required','required');
        }else{
            $(".add_offer_display_image").attr('required','required');
            $(".video_categories").addClass('d-none');
            $(".video_categories select").removeAttr('required');
        }
        if(category_id == 10){
            $('.cashback_category').show();
        }else{
            $('.cashback_category input').val('');
            $('.cashback_category').hide();
        }
    });
    $('#category_filter').on('change',function(){
        $('#category_form').submit();
    });
    
    // Delete User 
    $('.delete_admin').on('click',function(e){
        var delete_row = $(this);
        var id = $(this).attr("data-user_id");

        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/deleteUser')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/deleteUser')}}"
        }
        swal("Are you sure you want to delete this user?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    
    });

    $('.delete_delivery_partner').on('click',function(e){
        var delete_row = $(this);
        var id = $(this).attr("data-id");

        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/deleteDeliveryPartner')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/deleteDeliveryPartner')}}"
        }
        swal("Are you sure you want to delete this Delivery partner?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    
    });

    // Delete App Permissions 

    $('.delete_app_permission').on('click',function(e){
        var delete_row = $(this);
        var id = $(this).attr("data-id");

        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/deleteAppPermission')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/deleteAppPermission')}}"
        }
        swal("Are you sure you want to delete this App Permission?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    
    });

    // Block/Unblock Section 
    $(document).on('click','.approved_btn',function(){
        var user_id = $(this).attr('data-user_id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/blockUnblockUsers')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/blockUnblockUsers')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to block this user?';
        }else{
            var mesages = 'Are you sure you want to unblock this user?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':user_id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.removeClass('btn-danger');
                                current_row.attr('data-original-title','Active');
                                current_row.attr('data-status',0);
                                current_row.addClass('btn-success');
                                current_row.find('i').removeClass('ni ni-fat-remove');
                                current_row.find('i').addClass('ni ni-check-bold');
                                toastr.success('Approved', data.message , {displayDuration:3000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.removeClass('btn-success');
                                current_row.addClass('btn-danger');
                                current_row.attr('data-original-title','Block');
                                current_row.attr('data-status',1);
                                current_row.find('i').removeClass('ni ni-check-bold');
                                current_row.find('i').addClass('ni ni-fat-remove');
                                toastr.success('Not approved', data.message , {displayDuration:3000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Not approved', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });
    // Banner Active In-active Section End
    // Promotional Active In-active Section 
    $(document).on('click','.activityUserOrNot',function(){
        var user_id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveUser')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveUser')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to Activate this user ?';
        }else{
            var mesages = 'Are you sure you want to Deactivate this user ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':user_id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactiveSeller',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveSeller')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveSeller')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want Activate this Seller ?';
        }else{
            var mesages = 'Are you sure you want to Deactivate this Seller ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactivePermission',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActivePermissions')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActivePermissions')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to publish this Permission ?';
        }else{
            var mesages = 'Are you sure you want to unpublish this Permission ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactiveRole',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveRoles')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveRoles')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to active this Role ?';
        }else{
            var mesages = 'Are you sure you want to in-active this Role ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactiveFaq',function(){
        //alert();
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveFaq')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveFaq')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to publish this FAQ ?';
        }else{
            var mesages = 'Are you sure you want to unpublish this FAQ ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.verifiedSeller',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        if(status=='0'){
            swal({
              text: "Seller already Verified",
            });
            return false;
        }
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/verifySeller')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/verifySeller')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to Verify this Seller ?';
        }else{
            var mesages = 'Are you sure you want to change the status of the Seller ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.removeClass('btn-danger');
                                current_row.addClass('btn-success');
                                current_row.attr('data-original-title','Active');
                                current_row.attr('data-status',0);
                                current_row.find('i').removeClass('ni ni-fat-remove');
                                current_row.find('i').addClass('ni ni-check-bold');
                                toastr.success('Status', data.message , {displayDuration:3000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.removeClass('btn-success');
                                current_row.addClass('btn-danger');
                                current_row.attr('data-original-title','In-active');
                                current_row.attr('data-status',1);
                                current_row.find('i').removeClass('ni ni-check-bold');
                                current_row.find('i').addClass('ni ni-fat-remove');
                                toastr.success('Status', data.message , {displayDuration:3000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactiveDeliveryPartner',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveDeliveryPartner')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveDeliveryPartner')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to active this Delivery Partner ?';
        }else{
            var mesages = 'Are you sure you want to in-active this Delivery Partner ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactiveModels',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveModel')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveModel')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to Activate this Model ?';
        }else{
            var mesages = 'Are you sure you want to Deactivate this Model ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');
                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');
                                toastr.success('Status', data.message , {displayDuration:1000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });

    $(document).on('click','.activeInactiveBrand',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/activeInActiveBrand')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/activeInActiveBrand')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to Activate this Brand ?';
        }else{
            var mesages = 'Are you sure you want to Deactivate this Brand ?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                
                                current_row.closest('tr').find('.not_ap_ms').addClass('d-none');
                                current_row.closest('tr').find('.approved_ms').removeClass('d-none');

                                current_row.closest('tr').find('.active_cls').addClass('d-none');
                                current_row.closest('tr').find('.inactive_cls').removeClass('d-none');

                                toastr.success('Status', data.message , {displayDuration:3000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').find('.approved_ms').addClass('d-none');
                                current_row.closest('tr').find('.not_ap_ms').removeClass('d-none');

                                current_row.closest('tr').find('.inactive_cls').addClass('d-none');
                                current_row.closest('tr').find('.active_cls').removeClass('d-none');

                                toastr.success('Status', data.message , {displayDuration:3000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Status', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });
    // Promotional Active In-active Section End

    // Banner Section 
    $('#mediaType').on('change',function(){
        var type = $(this).val();
        if(type == 2){
            $('#videoSection').removeClass('d-none');
        }else{
            $('#videoSection').addClass('d-none');
        }
    });

    $('#top_bottom_div').on('change',function(){
        var type_top_bottom = $(this).val();
        if(type_top_bottom == 2){
            $('#is_referal').addClass('d-none');
            $('#forTop input[type=file]').removeAttr('name');
            $('#ltrb input[type=file]').attr('name','image');
            $('#forTop').addClass('d-none');
            $('#imgvid select option[value=1]').attr('selected','selected');
            $('#imgvid').addClass('d-none');
            $('#image_position_div').removeClass('d-none');
            $('#ltrb').removeClass('d-none');
            $('#videoSection').addClass('d-none');
        }else{
            $('#is_referal').removeClass('d-none');
            $('#imgvid select option[value=1]').attr('selected','selected');
            $('.fileup').removeAttr('name');
            $('#forTop input[type=file]').attr('name','image');
            $('#forTop').removeClass('d-none');
            $('#imgvid').removeClass('d-none');
            $('#image_position_div').addClass('d-none');
            $('#ltrb').addClass('d-none');
            $('#rtlb').addClass('d-none');
        }
    });

    $('#image_position_div select').on('change',function(){
        var image_position = $(this).val();
        if(image_position == 2 || image_position == 3){
            $('#rtlb input[type=file]').attr('name','image');
            $('#ltrb input[type=file]').removeAttr('name');
            $('#ltrb').addClass('d-none');
            $('#rtlb').removeClass('d-none');
        }else{
            $('#ltrb input[type=file]').attr('name','image');
            $('#rtlb input[type=file]').removeAttr('name');
            $('#rtlb').addClass('d-none');
            $('#ltrb').removeClass('d-none');
        }
    });
    // End Banner Section 
//End User 
// User transection Section Start
    $(".sub_chk").on('change',function(){
        var selectedVal = [];
        $(".sub_chk:checked").each(function() {
            selectedVal.push($(this).attr('data-id'));
        });
        if(selectedVal.length > 0){
            $('.payAmount').show();
        }else{
            $('.payAmount').hide();
        }
    });

    $('#customCheck01').on('change', function(event){
        if($(this).is(':checked',true))  
        {
            $('.sub_chk').prop('checked', true);  
            $('.payAmount').show();
        } else { 
            $('.sub_chk').prop('checked', false);
            $('.payAmount').hide();
        }  
    });

    $('.payAmount').on('click',function(){
        $('#payAmountModal').modal('show');
    });
    
    $(document).on('click','.approved_btn_points',function(){
        var cashback_id = $(this).attr('data-cashback_id');
        var status = $(this).attr('data-status');
        status = $.trim(status);
        var current_row = $(this);
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/approvedOrRejectCashback')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/approvedOrRejectCashback')}}"
        }
        if(status == 1){
            var mesages = 'Are you sure you want to Approved cashback points?';
        }else{
            var mesages = 'Are you sure you want to reject cashback points?';
        }
        swal(mesages, {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':cashback_id,
                        'status':status
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 1){
                                current_row.closest('tr').remove();
                                toastr.success('Approved', data.message , {displayDuration:3000,position: 'top-right'});
                            }else{
                                current_row.closest('tr').remove();
                                toastr.success('Not approved', data.message , {displayDuration:3000,position: 'top-right'});
                            }
                        }else{
                            toastr.error('Not approved', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }
        });
    });
// User Cash back Section End
// Offer Section
    $('.button_select').on('change',function(){
        var selectedValue = $(this).find('option:selected').val();
        if(selectedValue == "other"){
            $('.button_name_div').removeClass('d-none');
            $('.button_name_text').attr('required','required');
        }else{
            $('.button_name_div').addClass('d-none');
            $('.button_name_text').removeAttr('required');
        }
    });
    // Offer Coupen Script
    $(document).on('click','.add_cuppon_more',function(){
        var html = '<div class="row"><div class="col-lg-3"><div class="form-group"> <input required="" type="text" class="form-control" name="coupon_code[]" placeholder="Coupon Code"></div></div><div class="col-lg-6"><div class="form-group"> <input required="" type="text" class="form-control" name="coupon_description[]" placeholder="Coupon Description"></div></div><div class="col-lg-2"><div class="form-group"><input class="form-control datepicker" placeholder="Expiry date" type="text" name="expiry_date[]" autocomplete="off" required=""></div></div><div class="col-lg-1"><div class="form-group text-right"> <button type="button" class="btn btn-danger remove_coupon"><i class="fas fa-trash"></i></button></div></div></div>';
        $('#coupens_div').append(html);
        $('.datepicker').datepicker();
    });
    // $(document).on('click','.add_cuppon_more',function(){
    //     var html = '<div class="row"><div class="col-lg-4"><div class="form-group"> <input required="" type="text" class="form-control" name="coupon_code[]" placeholder="Coupon Code"></div></div><div class="col-lg-7"><div class="form-group"> <input required="" type="text" class="form-control" name="coupon_description[]" placeholder="Coupon Description"></div></div><div class="col-lg-1"><div class="form-group text-right"> <button type="button" class="btn btn-danger remove_coupon"><i class="fas fa-trash"></i></button></div></div></div>';
    //     $('#coupens_div').append(html);
    // });
    $(document).on('click','.remove_coupon',function(){
        $(this).closest('.row').remove();
    });
    // End Offer Coupen Script

    
    $('.delete_role').on('click',function(e){
        e.stopPropagation(); e.preventDefault();
        var delete_row = $(this);
        var id = $(this).attr("data-role-id");

        if(window.location.protocol == "http:"){
            requesturl = "{{url('/admin/deleteRole')}}"
        }else if(window.location.protocol == "https:"){
            requesturl = "{{secure_url('/admin/deleteRole')}}"
        }
        swal("Are you sure you want to delete this Role?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: requesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }       
                });
            }
        });
        
    });

    $('.delete_seller').on('click',function(e){
        e.stopPropagation(); e.preventDefault();
        var delete_row = $(this);
        var id = $(this).attr("data-id");

        if(window.location.protocol == "http:"){
            requesturl = "{{url('/admin/deleteSeller')}}"
        }else if(window.location.protocol == "https:"){
            requesturl = "{{secure_url('/admin/deleteSeller')}}"
        }
        swal("Are you sure you want to delete this Seller?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: requesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }       
                });
            }
        });
        
    });

    $('.delete_mobile_brands').on('click',function(e){
        e.stopPropagation(); e.preventDefault();
        var delete_row = $(this);
        var id = $(this).attr("data-id");

        if(window.location.protocol == "http:"){
            requesturl = "{{url('/admin/deleteMobileBrand')}}"
        }else if(window.location.protocol == "https:"){
            requesturl = "{{secure_url('/admin/deleteMobileBrand')}}"
        }
        swal("Are you sure you want to delete this Brand?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: requesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }       
                });
            }
        });
        
    });

    $('.delete_mobile_model').on('click',function(e){
        e.stopPropagation(); e.preventDefault();
        var delete_row = $(this);
        var id = $(this).attr("data-id");

        if(window.location.protocol == "http:"){
            requesturl = "{{url('/admin/deleteMobileModel')}}"
        }else if(window.location.protocol == "https:"){
            requesturl = "{{secure_url('/admin/deleteMobileModel')}}"
        }
        swal("Are you sure you want to delete this Model?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: requesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        $('.loader-div').hide();
                        if(data.success){
                            delete_row.closest('tr').remove();
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }       
                });
            }
        });
        
    });

    $(".sub_chk_with").on('change',function(){
        var selectedVal = [];
        $(".sub_chk_with:checked").each(function() {
            selectedVal.push($(this).attr('data-id'));
        });
        if(selectedVal.length > 0){
            $('.deletePermission').show();
            $('#adPermission').hide();
        }else{
            $('.deletePermission').hide();
            $('#adPermission').show();
        }
    });
    $('#customCheck01').on('change', function(event){
        if($(this).is(':checked',true))  
        {
            $('.sub_chk_with').prop('checked', true);  
            $('.deletePermission').show();
            $('#adPermission').hide();
        } else { 
            $('.sub_chk_with').prop('checked', false);
            $('.deletePermission').hide();
            $('#adPermission').show();
        }  
    });
    $('.deletePermission').on('click',function(){
        $('.loader-div').show();
        var allVals = [];
        $(".sub_chk_with:checked").each(function() {
            allVals.push($(this).attr('data-id'));
        });
        var join_selected_values = allVals.join(","); 
        var URL = $(this).attr("data-url");
        $.ajax({
            url: URL,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:'json',
            data: {
                ids: join_selected_values
            },
            success: function (data) {
                $('.loader-div').hide();
                location.reload();
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    });

// Withdrawl Section End
$(document).ready(function(){

    $('.txtOnly').bind('keydown', function(event) {
      var key = event.which;
      if (key >=48 && key <= 57) {
        event.preventDefault();
      }
    });

    $('input.excel_file').change(function(e){
        var fileName = e.target.files[0].name;
        $('#filename').text(fileName);
    });
    $('.datepicker').datepicker();

    $('.phone_search').select2({
        placeholder: 'Select Phone Number',
        ajax: {
            url: window.location.origin+$('#phone_search').attr('data-url'),
            type:"POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:'json',
            data: function (params) {
            var query = {
                search: params.term,
            }
            return query;
            }
        }
    });
    var lastSelected = $('.status option:selected');
    
    $(document).on('click','.status',function(event){
        var lastSelected = $(this).find('option:selected');
    });

    $(document).on('change','.status',function(){
        var selectbox = $(this);
        var id = $(this).attr('data-id');
        var status = $(this).find('option:selected').val();
        
        if(window.location.protocol == "http:"){
            resuesturl = "{{url('/admin/categoryActiveInactive')}}"
        }else if(window.location.protocol == "https:"){
            resuesturl = "{{secure_url('/admin/categoryActiveInactive')}}"
        }
        swal("Are you sure you want to change status of this category?", {
            buttons: ["No", "Yes"],
        })
        .then(name => {
            if(name){
                $('.loader-div').show();
                $.ajax({
                    type: "post",
                    url: resuesturl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:'json',
                    data: {
                        'id':id,
                        'status':status
                    },
                    success: function (data) {
                        
                        $('.loader-div').hide();
                        if(data.success){
                            if(status == 0){
                                selectbox.addClass('red');
                                selectbox.removeClass('green');
                            }else{
                                selectbox.addClass('green');
                                selectbox.removeClass('red');
                            }
                            toastr.success('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }else{
                            lastSelected.prop("selected", true);
                            toastr.error('Delete', data.message , {displayDuration:3000,position: 'top-right'});
                        }
                    }         
                });
            }else{
                lastSelected.prop("selected", true);
            }
        });
    });
});


</script>