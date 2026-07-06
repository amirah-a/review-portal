<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';

    protected $primaryKey = 'APL_ID';

    public $incrementing = true;

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Participant Information
        |--------------------------------------------------------------------------
        */

        'APL_FName',
        'APL_MName',
        'APL_LName',

        'APL_Address_1',
        'APL_Address_2',
        'APL_Area',

        'APL_DOB',
        'APL_Age',
        'APL_Sex',
        'APL_Is_Citizen',

        'APL_ID_Type',
        'APL_ID_Number',

        'APL_Enrolled',
        'APL_School_Enrolled',
        'APL_Education_Level',
        'APL_Last_School_Name',


        'APL_Programme_Center',
        'APL_Participant_Jersey_Size',
        'APL_Meal_Preference',

        'APL_Allergy_Signs',

        'APL_Has_Medical_Condition',
        'APL_Medical_Details',

        /*
        |--------------------------------------------------------------------------
        | Learning Profile
        |--------------------------------------------------------------------------
        */

        'APL_Confident_Abilities',
        'APL_Listening_Skills',
        'APL_Social_Skills',
        'APL_Perseverance',
        'APL_Mood',
        'APL_Communication',
        'APL_Attitude_Learning',
        'APL_New_Skills',
        'APL_Education_Goals',
        'APL_Career_Goals',


        /*
        |--------------------------------------------------------------------------
        | Contact Information
        |--------------------------------------------------------------------------
        */

        'APL_Parent_Name',
        'APL_Parent_ID_Type',
        'APL_Parent_ID_Number',
        'APL_Parent_Cellphone',
        'APL_Parent_Work_Home_Phone',
        'APL_Parent_Email',

        'APL_Emergency_Contact_Name',
        'APL_Emergency_Contact_Phone',
        'APL_Emergency_Contact_Alt_Phone',
        'APL_Emergency_Contact_ID_Type',

        'APL_Authorized_Pickup_Name',
        'APL_Authorized_Pickup_Phone',
        'APL_Authorized_Pickup_Alt_Phone',
        'APL_Authorized_Pickup_ID_Type',

        'APL_How_Heard',
        'APL_How_Heard_Other',

        /*
        |--------------------------------------------------------------------------
        | Consent
        |--------------------------------------------------------------------------
        */

        'APL_Consent_Participation',
        'APL_Consent_Medical',
        'APL_Consent_Photo',
        'APL_Consent_Liability',
        'APL_Consent_Declaration',

        /*
        |--------------------------------------------------------------------------
        | Uploads
        |--------------------------------------------------------------------------
        */

        'APL_ID_File',
        'APL_Birth_Certificate_File',
        'APL_Passport_Photo_File',

        'APL_Parent_ID_File',

        'APL_Emergency_Contact_ID_File',

        'APL_Authorized_Pickup_ID_File',

        'APL_Consent_Form_File',
    ];

    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        'APL_DOB' => 'date',

        /*
        |--------------------------------------------------------------------------
        | JSON Fields
        |--------------------------------------------------------------------------
        */

        'APL_Allergy_Signs' => 'array',


        /*
        |--------------------------------------------------------------------------
        | Uploads
        |--------------------------------------------------------------------------
        */

        'APL_ID_File' => 'array',
        'APL_Birth_Certificate_File' => 'array',
        'APL_Passport_Photo_File' => 'array',

        'APL_Parent_ID_File' => 'array',

        'APL_Emergency_Contact_ID_File' => 'array',

        'APL_Authorized_Pickup_ID_File' => 'array',

        'APL_Consent_Form_File' => 'array',
    ];
    
}
