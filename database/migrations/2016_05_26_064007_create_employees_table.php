<?php
/***
 * Migration generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\LAModule;

class CreateEmployeesTable extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        LAModule::generate("Employees", 'employees', 'name', 'fa-group', [
            [
                "colname" => "name",
                "label" => "Nombre",
                "field_type" => "Name",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 5,
                "maxlength" => 250,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "city",
                "label" => "Ciudad",
                "field_type" => "String",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 50,
                "required" => false,
                "listing_col" => true
            ], [
                "colname" => "email",
                "label" => "Email",
                "field_type" => "Email",
                "unique" => true,
                "defaultvalue" => "",
                "minlength" => 5,
                "maxlength" => 250,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "mobile",
                "label" => "Telefono",
                "field_type" => "Mobile",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 9,
                "maxlength" => 20,
                "required" => true,
                "listing_col" => true
            ], [
                "colname" => "address",
                "label" => "Direccion",
                "field_type" => "Address",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 1000,
                "required" => false,
                "listing_col" => true
            ], [
                "colname" => "limitcode",
                "label" => "Límite Códigos",
                "field_type" => "Integer",
                "unique" => false,
                "defaultvalue" => "0",
                "minlength" => 0,
                "maxlength" => 11,
                "required" => true,
                "listing_col" => false
            ], [
                "colname" => "type",
                "label" => "Duracion Codigos",
                "field_type" => "Integer",
                "unique" => false,
                "defaultvalue" => "0",
                "minlength" => 0,
                "maxlength" => 11,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "renove",
                "label" => "AutoRenovacion",
                "field_type" => "Integer",
                "unique" => false,
                "defaultvalue" => "0",
                "minlength" => 0,
                "maxlength" => 11,
                "required" => true,
                "listing_col" => false
            ], [
                "colname" => "about",
                "label" => "Notas",
                "field_type" => "Textarea",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => true
            ], [
                "colname" => "token",
                "label" => "Token",
                "field_type" => "String",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "salary_cur",
                "label" => "Current Salary",
                "field_type" => "Decimal",
                "unique" => false,
                "defaultvalue" => "0.0",
                "minlength" => 0,
                "maxlength" => 2,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "date_left",
                "label" => "Resignation Date",
                "field_type" => "Date",
                "unique" => false,
                "defaultvalue" => "1990-01-01",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "date_hire",
                "label" => "Hiring Date",
                "field_type" => "Date",
                "unique" => false,
                "defaultvalue" => "date('Y-m-d')",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "date_birth",
                "label" => "Fecha de nacimiento",
                "field_type" => "Date",
                "unique" => false,
                "defaultvalue" => "1990-01-01",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "dept",
                "label" => "Department",
                "field_type" => "Dropdown",
                "unique" => false,
                "defaultvalue" => "0",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => "@departments",
            ], [
                "colname" => "mobile2",
                "label" => "Alternative Mobile",
                "field_type" => "Mobile",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 10,
                "maxlength" => 20,
                "required" => false,
                "listing_col" => false
            ], [
                "colname" => "gender",
                "label" => "Sexo",
                "field_type" => "Radio",
                "unique" => false,
                "defaultvalue" => "Male",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => true,
                "listing_col" => false,
                "popup_vals" => ["Male","Female"],
            ], [
                "colname" => "designation",
                "label" => "Designation",
                "field_type" => "String",
                "unique" => false,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 50,
                "required" => true,
                "listing_col" => false
            ]
        ]);

        /*
        LAModule::generate("Module_Name", "Table_Name", "view_column_name" "Fields_Array");

        Field Format:
        [
            "colname" => "name",
            "label" => "Name",
            "field_type" => "Name",
            "unique" => false,
            "defaultvalue" => "John Doe",
            "minlength" => 5,
            "maxlength" => 100,
            "required" => true,
            "listing_col" => true,
            "popup_vals" => ["Employee", "Client"],
            "comment" => ""
        ]
        # Format Details: Check https://laraadmin.com/docs/migrations_cruds#schema-ui-types

        colname: Database column name. lowercase, words concatenated by underscore (_)
        label: Label of Column e.g. Name, Cost, Is Public
        field_type: It defines type of Column in more General way.
        unique: Whether the column has unique values. Value in true / false
        defaultvalue: Default value for column.
        minlength: Minimum Length of value in integer.
        maxlength: Maximum Length of value in integer.
        required: Is this mandatory field in Add / Edit forms. Value in true / false
        listing_col: Is allowed to show in index page datatable.
        popup_vals: These are values for MultiSelect, TagInput and Radio Columns. Either connecting @tables or to list []
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('employees')) {
            Schema::drop('employees');
        }
    }
}
