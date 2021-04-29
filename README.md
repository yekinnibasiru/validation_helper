# validation_helper
## This is a simple validation helper library

1. Instantiate the class by calling
```php
$validation=Validation($conn); //By passing required database $conn string 
```
2. Validate your form data by calling
```php
$validation->validate('formfieldname','label','rules','custom errors');
```
3. Check if there is errors by calling
```php
if($validation->run()){
  echo "Form Submitted";
}
else{
  //Perform actions if there is no errors
}
```
4. List of rules you can call
```php
$validation->validate('name','Name','required');
$validation->validate('email','Email','required|valid_email|is_unique:users.email');
$validation->validate('url','Url','required|valid_url');

$validation->validate('username','Username','required|min_len[4]|max_len[8]');
$validation->validate('firstname','Firstname','required|is_alpha');
$validation->validate('username','Username','required|is_alphanum');
```
5. Get array of errors from validation
```php
$validation->validationErrors();
```

