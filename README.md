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

```
```





```


