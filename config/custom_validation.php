<?php
return [

    'password' =>  ['required', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
    'profile_image' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:3000',
    'image' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:3000',
    'firstName' =>  'required|regex:/^[a-zA-Z]+$/u|max:20',
    'lastName' =>  'required|regex:/^[a-zA-Z]+$/u|max:20',
    'middleName' =>  'required|regex:/^[a-zA-Z]+$/u|max:20',
    'phone' =>  'required|unique:users|numeric|not_in:0|digits_between:8,12',
    'string_validation' => 'required|regex:/^[a-zA-Z0-9&? ]+$/u',
    'string_validation_2' => 'required|regex:/^[a-zA-Z0-9&?()\-\/\' ]+$/u',
];
