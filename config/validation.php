<?php
return [
    'register_user_rules'    =>     [
                                                'first_name'                => 'required',
                                                'last_name'                 => 'required',
                                                'phone_number'              => 'required',
                                                'email_address'             => 'required|unique:users|email',
                                                'gender'                    => 'required',
                                                'account_type'              => 'required',
                                                'password'                  => 'required',
                                    ],
    'register_user_messages' =>     [
                                                'first_name.required'       => 'First Name Required',
                                                'last_name.required'        => 'Last Name required',
                                                'phone_number.required'     => 'Phone number required',
                                                'email_address.required'    => 'Email Address required',
                                                'email_address.unique'      => 'Email Address Taken',
                                                'account_type.required'     => 'Account type required',
                                                'password.required'         => 'Password',
                                                'gender.required'           => 'Gender required'
                                    ],
    'add_staff_response_complaint_rules'       =>     [
                                                'complaint_id'              => 'required',
                                                'user_id'                   => 'required',
                                                'response'                  => 'required'
                                    ],
    'add_staff_response_complaint_messages' =>     [
                                                'complaint_id.required'     => 'Complaint required',
                                                'user_id.required'     	    => 'User required',
                                                'response.required'         => 'Response required'
                                    ],
                                
    'add_staff_response_crime_rules'       =>     [
                                                'crime_id'                  => 'required',
                                                'user_id'                   => 'required',
                                                'response'                  => 'required'
                                                                ],
    'add_staff_response_crime_messages'    =>       [
                                                'crime_id.required'         => 'Crime required',
                                                'user_id.required'          =>  'User required',
                                                'response.required'         => 'Response required'
                    ],
    'add_complaint_rules'       =>     [
                                                'village'                   => 'required',
                                                'user_id'                   => 'required',
                                                'details'                   => 'required',
                                                'subject'                   => 'required'
                                    ],
    'add_complaint_messages' =>     [
                                                'village.required'          => 'Complaint required',
                                                'user_id.required'     	    => 'User required',
                                                'details.required'          => 'Response required',
                                                'subject'                   => 'Subject required'
                                    ],
            
    'add_crime_rules'       =>     [
                                                'crime_detail'              => 'required',
                                                'user_id'                   => 'required',
                                                'village'                   => 'required'
                                                                ],
    'add_crime_messages'    =>       [
                                                'crime_detail.required'     => 'Crime required',
                                                'user_id.required'          => 'User required',
                                                'village.required'          => 'Village required'
                        ],
    'get_civilian_complaints_rules'       =>     [
                                                'user_id'                   => 'required'
                                                
                                            ],
    'get_civilian_complaints_messages'    =>       [
    
                                                'user_id.required'          => 'User required'
                            
                                            ],
    'get_village_complaints_rules'       =>     [
                                                'village_id'                => 'required'
                                                
                                            ],
    'get_village_complaints_messages'    =>       [

                                                'village_id.required'      => 'Village required'

                                        ],
    'get_civilian_crimes_rules'       =>     [
                                                'user_id'                   => 'required'
                                            
                                        ],
    'get_civilian_crimes_messages'    =>       [

                                                'user_id.required'          => 'User required'
                            
                                            ],
    'get_village_crimes_rules'       =>     [
                                                'village_id'                => 'required'
                                                
                                            ],
    'get_village_crimes_messages'    =>       [

                                                'village_id.required'       => 'Village required'

                                        ],
    'get_response_complaint_rules'       =>     [
                                                'complaint_id'              => 'required'
                                                
                                            ],
    'get_response_complaint_messages'    =>       [

                                                'complaint_id.required'     => 'Complaint required'
                            
                                            ],
    'get_staff_response_complaint_rules'       =>     [
                                                'user_id'                   => 'required'
                                                
                                            ],
    'get_staff_response_complaint_messages'    =>       [

                                                'user_id.required'          => 'User required'

                                        ],
    'get_crime_response_rules'       =>     [
                                                'crime_id'                   => 'required'
                                            
                                        ],
    'get_crime_response_messages'    =>       [

                                                'crime_id.required'          => 'Crime required'
                            
                                            ],
    'get_staff_response_crimes_rules'       =>     [
                                                'user_id'                => 'required'
                                                
                                            ],
    'get_staff_response_crimes_messages'    =>       [

                                                'user_id.required'       => 'User required'

                                        ]
   
];