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
$validation->run();
```


