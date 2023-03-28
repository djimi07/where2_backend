<?php

return [

//    ............ USER ..............
    'req_username'=> 'User Name is required.',
    'unique_username'=> 'User Name is already registered.',
    'req_email' => 'Email is required.',
    'wrong_email_format' => 'Invalid Email format.',
    'unique_email_format' => 'Email already registered.',
    'req_password' => 'Password is required.',
    'wrong_credential' => 'Invalid Credential.',
    'succ_login' => 'Login Successfully.',
    'succ_signup' => 'Registration Successfully.',
    'req_user_image' => 'Image is required.',
    'req_user_image_mimes' => 'Allow jpeg,png,jpg,gif,svg Image type.',
    'req_user_image_max' => 'Allow maximum size is 3 MB.',
    'succ_update_user_image' => 'Image Updated Successfully.',
    'req_first_name' => 'First Name is required.',
    'invalid_first_name_format' => 'Invalid First Name format.',
    'max_first_name' => 'First Name may not be greater than 20 characters.',
    'req_last_name' => 'Last Name is required.',
    'invalid_last_name_format' => 'Invalid Last Name format.',
    'max_last_name' => 'Last Name may not be greater than 20 characters.',
    'invalid_middle_name_format' => 'Invalid Middle Name format.',
    'max_middle_name' => 'Middle Name may not be greater than 20 characters.',

    'req_phone' => 'Phone Number is required.',
    'req_phone_numeric' => 'Phone Number must be a number.',
    'req_invalid'=>'Invalid Phone Number.',
    'req_phone_range' => 'Phone Number must be between 8 and 12 digits.',
    'invalid_phone' => 'Invalid Phone Number format.',
    'unique_phone' => 'Phone Number is already registered.',
    'unique_email' => 'Email is already registered.',
    'wrong_email'=> 'Please enter a valid email ID.',
    'max_phone'=>'Phone Number length must be 10 digit',
    'succ_update_profile' => 'Profile Updated Successfully.',
    'success' => 'Success',
    'succ_logout' => 'Logout Successfully.',
    'succ_add_customer' => 'Customer Details Added Successfully.',
    'succ_update_customer' => 'Customer Details Updated Successfully.',
    'succ_update_user_status' => 'Status Updated Successfully.',
    'succ_otp_send_mail' => 'OTP sent Successfully',
    'failed_otp_send_mail' => 'OTP sent Failed. Please Resend.',
    'req_otp' => 'OTP is required.',
    'invalid_otp' => 'Invalid OTP.',
    'user_email_already_verified' => 'Email already verified.',
    'succ_verify_otp' => 'OTP Verified Successfully.',
    'succ_update_user_details' => 'User Details Updated Successfully.',
    'succ_add_provider' => 'Service Provider Details Added Successfully.',
    'succ_update_provider' => 'Service Provider Details Updated Successfully.',
    'req_zip_code' => 'Zip Code is required',
    'succ_update_user_setting' => 'Setting Details Updated Successfully.',

    /////token
    'req_fcm_token'=>'fcmToken is required.',
    'req_deviceToken'=>'deviceToken is required.',

    ///Restaurant........
    'succ_insert' => 'Added successfully.',
    'succ_update'=>'Updated successfully.',
    'succ_insert_bar_restaurant'=>'Bar/Restaurant Added Successfully.',
    //Owner.........
    'del_owner'=>'Owner Removed Successfully.',
    'succ_update_owner'=>'Owner Updated Successfully.',
    'succ_insert_owner'=>'Owner Added Successfully.',
    //user...
     'del_user'=>'User Deleted Successfully.',
     'succ_update_user'=>'User Updated Successfully.',
     'succ_insert_user'=>'User Added Successfully.',


    'succ_active' => 'Owner Unblocked successfully.',
    'de_active' => 'Owner Blocked successfully.',

    'req_delete'=>'Record Deleted Successfully.',

    'publish'=>'Published successfully.',
    'unpublish'=>'Unpublished successfully.',

    ///Bar/Restauant image
    'error_delete'=>"Last Image can't be deleted,Please select New image and then delete. ",

    /////invite_friend
    'remove_friend' =>"Removed Friend successfully.",
    'accept'=>'Accepted successfully.',
    'decline'=>'Decline successfully. ',
    'record_not_found'=>'Not Record Found.',
    'send_invitation_req'=> 'Invitation Sent Successfully.',
    'send_friend_req'=> 'Friend Request Sent Successfully.',
     /*.......... CMS ...........*/
     'req_term_condition' => 'Terms and Condition is Required.',
     'succ_update_term_condition' => 'Terms and Condition Details Updated Successfully.',
     'req_policy' => 'Policy is required.',
     'succ_update_policy' => 'Policy Details Updated Successfully.',
 
////////////checked in
    'checkedIn'=>'Checked In Successfully.',
/////Comment
    'add_comment'=>'Comment Added Successfully.',

    //Admin Comment section 
    'succ_insert_comment'=>'Comment Added Successfully.',
    'succ_update_comment'=>'Comment Updated Successfully.',
    'pre_comment_delete'=>'Comment Deleted Successfully.',
    'req_pre_comment'=>'Comment is required.',

    // ADMIN PASSWORD RESET
    'new_password' => 'New Password is required.',
    'req_confirmPassword'=>'Confirm Password is required.',
    'req_new_password_min' => 'The New Password must be at least 6 characters.',
    'new_password_confirmed_not_match' => 'The New Password Confirmation does not match.',

    'password_confirmed_not_match' => 'New Password & Confirm Password must be same.',

    'req_password_min' => 'The Password must be at least 6 characters.',
    'invalid_password_format' => 'Password must contain at least one capital letter, one small letter, one number and one special character.',
    'invalid_new_password_format' => 'New Password must contain at least one capital letter, one small letter,one number and one special character.',
    'user_not_register' => ' Mobile Number is not registered with us, please enter correct Mobile Number.',
    'succ_password_reset_send_mail' => 'A reset link has been sent to your email address.',
    'failed_password_reset_send_mail' => 'A Network Error occurred. Please try again.',
    'req_token' => 'Token is required.',
    'invalid_token' => 'Invalid Token.',
    'succ_update_password' => 'Password Updated Successfully.',
    'req_old_password' => 'Old Password is required.',
    'invalid_old_password' => 'Invalid Old Password.',

    //Assign Ownwer
    'succ_assign_owner' =>"Owner Added Successfully.",
    'req_ownerId'=>'Please select Owner.',

   ///Add bar/restaruant
   'req_location'=>'Location is required.',
   'req_search_text'=>'Search is required.',
   'num_latitude'=>'Latitude must be a number.',
   'num_longitude'=>'Longitude must be a number.',
   'num_radius'=>'Radius must be a number.',
   'd_b_radius'=>'Radius must be between 1 and 25 digits.',

   'unique_bar_res'=>'This Bar/Restaurant is already exist.',
     'req_bar_name'=>'Bar Name is required.',
     'req_city'=>'City is required.',
     'req_state'=>'State is required.',
     'req_address'=>'Address is required.',
     'req_zipCode'=>'Zip Code is required.',
     'between_zipCode'=>'Zip Code must be between 4 and 10 digits.',
     'req_latitude'=>'Latitude is required.',
     'req_longitude'=>'Logitude is required.',
     'req_country'=>'Country is required.',
     'req_rating'=>'Rating is required.',
     'req_discription'=> 'Description is required.',

/// Add Event
'req_eventType'=>'Event Type is required.',
'req_firstdate'=>'Start Date is required.',
'req_secdate'=>'End Date is required.',
'req_category'=>'Category is required.',
'req_offer'=>'Offer is required.',
'req_eventname'=>'Event Name is required.',
'req_description'=>'Description is required.',
'req_image'=> 'Image is required.',
'digits_offer'=>'Offer length must be 2 digit.',
'num_offer'=> 'Offer must be a number.',
'req_title'=>'Title is required.',
'max_description'=>'Description may not be greater than 30 characters.',
];