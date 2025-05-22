
# Contents
- Object-Oriented Programming
    - Inheritance
    - Abstraction
    - Polymorphism
    - Encapsulation
- Middleware
    - 3 types of registered routes
    - How does only() works?
    - Logic behind everything (Middleware::resolve)
    - Middleware class
- Validation
    - How to Use
    - Digging Deeper
    - Before and After
- Session
    - Alert
    - Errrors
    - Old Values
- Missing Parts
    - Paginations
    - Packages

# Object Oriented Programming 📦
All of the discussion below will cover how did I use each pillars of OOP in this template, and as usual, if that part has less explanation that the rest it means I didn't implement it as much XD.
# Inheritance (👩‍👦)
Open these files on tabs in order:
```
Model.php > User.php
```
- The concept behind inheritance is stated within the name itself, inherit. Similar to how a child can inherit a trait from their parents.
- In its programming coounterpart, this is how the process goes,
- We create a parent class that has pre-defined methods inside it and create a sub or child class that use `extends` to explicitely define that they are that class child.

### Model.php
- This class is located within `Core\Model.php`, this class serves as the parent class of all the model class that we will create within `App\Models\...` directory.
- This class contains `function procedure`, means that all basic operation such as `CRUD` are defined here as methods to simplify the process (reduced MySQL writing on each Controller).
- But the most important part of this class is the 
```php
// Model.php
protected $table;
```
- This `$table` var / property is always being called on each method in the form of `$this->table`, such as this one:
```php
public function findAll($order = "")
{
    $this->iniDB();

    $table = substr($this->table, 0, -1);

    $records = $this->query("SELECT * FROM $this->table ORDER BY {$table}_id $order")->get();

        return $records;
    }
```
- This is where the main logic happens, if you visit the `App\Models\...` each of the model classes there have `extends Model` means that they are defining themselves as a child of `Model.php` this way they also have access to the methods defined on `Model.php`;
- But the methods within `Model.php` contains ambiguity since the `$this->table` property there doesn't contain any value yet.
- All of this are solved due to this property defined on each child model class:
```php
// User.php
protected $table = "users";
```
- This overwrites the `$table` property within the parent `Model.php` class.

### How to use this:
- So imagine this scenario, say we want to find a user whose email is `pogiako@gmail.com`, knowing all the process above we can do this:
```php
// SomewhereController.php
$userObj = new User;
$user->firstWhere([
    "email" => "pogiako@gmail.com",
]);
```
- Remember that the `firstWhere()` is a method defined within `Model.php`, but since we `extends` that class on `User` class we now have access to its methods and properties.

# Abstraction 🎨
Open this files in order:
```
Controller > UserController.php
```
- Abstraction as defined is a way to hide complexity, however in a deeper sense, abstraction is a combination of all four programming pillars (inheritance, polymprphism, encapsulation).
- **Inheritance:** An abstract class also have a parent and child where the methods of the parent can be inherited by the child class, such as `request()` and `validate()`.
- **Polymorphism:** Abstract methods contain within an abstract class also required the `child` class to create their own implementation of the said method.
- **Encapsulation:** Is not always required, but its a process of protecting the properties / variables.

# Middleware 🧅
Open these files on tabs in order:
```
routes.php > Router.php > Middleware.php
```
- As we all know, we register routes that can be access on our system on `routes.php` file, and this file contains three kinds of registered route;
```php
// the normal one
$router->get('index', 'UserController', 'index');
// w middleare
$router->get('registration', 'RegistrationController', 'create')->only('guest');
// w middleware + role
$router->get('menu', 'MenuController', 'index')->only('auth', 'Customer');

```
- The **normal one** simply indicates that it can be access whenever there's logged in or out user etc.,
- The **w middleware** indicates that the route can only be access when there's no session stored.
- The **w middleare + role** similar to what's above, however only *authenticated* user that has the role of *Customer* can access.

### How does **only()** actually works?
- To properly undertand this logic we have to take a closer look within `Router.php` file first:
```php
/**
* Used for adding middleware to each route
*/
public function only($key, $role = null)
{
    // we are accessing the $this->routes array like this because its a multidiemnsional array
    $this->routes[array_key_last($this->routes)]['middleware'] = $key;
    $this->routes[array_key_last($this->routes)]['middleware_role'] = $role;
}
```
- Similar to the `add()` method within `Router.php`, its `only()` method also does the same but it passes the value it received from `routes.php`,
- Before proceding look at the 3  different kind of registered routes again, focus specifically how we are using `->only()` method there.
- Notice that the `only()` method within `Router.php` is expecting 2 arguments, because it have two paramters, but why on `routes.php` we can choose whether to pass one or two arguments on `->only()` method? 
- This is because of this line
```php
// Router.php
public function only($key, $role = null)
```
- This means that `$key` is always expecting an argument, but `$role` on the otherhand, if it receives value it will use it, but if it didn't it will have a default value of `NULL`, in short passing a value is not required for `$role`.

### Logic behind everything (Middleware::resolve)
- **Notice:** To further understand this, you have to ensure that you understand the logic behinnd `routes()` method within `Router.php` first, how it iterates through every registered routes and stuff.
- See this line on `routes()` method of `Router.php`:
```php
Middleware::resolve($route['middleware'], $route['middleware_role']);
```
- Basicaly, what this does is it calls a method named `resolve()` within `Core\Middleware\Middleware.php` another class that we will introduce.

### Middleware Class
- The class has a property:
```php
public const MAP = [
    'guest' => Guest::class,
    'auth' => Authenticated::class
];
```
- This is an associative array that will call the respective class within `Core\Middleware\...`
- But most importantly understand the code within `public static function resolve($key, $role="")`. Basically what this does is it recives the argument passed earlier within `routes()` on `Router.php`:
```php
// Router.php
// route() method within Router.php
Middleware::resolve($route['middleware'], $route['middleware_role']);
```
- Now inside the method, if the key passed is not one of those who are indiciated withhin the `MAP` property above, it will just return without doing anything:
```php
if (! $key) {
    return;
}
```
- However, if it is, it means this code can access the propety `MAP` using the key passed and it will return the value, the respective class class
```php
// Will contain the Middleware Class
$middleware = static::MAP[$key] ?? false;
```
- Now we can just create an instance of the class, depending whether the key passed within `only` on `routes.php` is either `auth` or `guest`:
```php
// Middleware.php
$instance = new $middleware;
$instance->handle($role);
``` 
- Now its up to you guys to understand `Core\Middleware\Guest.php` and `Core\Middleware\Middleware.php`

# Validation ✅
Open this files in order:
```
Validator.php > Controller.php > UserController.php
```
**How to use:**
- Inside your `UserController.php` you have to first get all the data within `GET` & `POST` array,
- To do this, inside `Controller.php` there's a method named `request()` this method has two return types, (1) if you pass an argument on its `$key` parameter it will only return a single value, the value to be passed on the `$key` should be to the input element, (2) another one is the `return $this`, this is reponsible for returning the class itself or an instance of the class;
- If you use the (1) first return type, you can do this:
```php
// UserController.php
$data =  $this->request('emai'); // jonasemperor@gmail.com
```
- But if you choose the (2) other return type,  method chaining is now possible:
```php
// UserController.php
$data = $this->request()->validate([
    'email' => 'required|email'
]);
```

**Digging Deeper:**
- The method chaining is possible because of the `return $this` inside the `request()` method, which return the whole class.
- And inside the `validate()`, instead of manually passing all the `$data` on the method as you can see if you scroll down below, I'm automatically assigning all of the  `value` and `name` of inputs fetched from `$GET` & `$POST` to a local variable named `$data` inside the method.
- Automatic redirection is also added if the local variable `$errors` inside of `validate` method is set:
```php
if (isset($errors)) {
    $this->redirect(Session::get('__url', 'last_url'));
}
```

**Before and After:**
- Unlike the approach I used in *Norte Cafe*:
```php
// UserController.php
$data = [
    'email' => $this->getInput('email'),
];

$errors = $this->validate($data, [
    'emai' => 'required|email'
];

if($errors) {
    return $this->redirect('registration');
}
```
- I can use this now:
```php
// UserController.php
$data = $this->request()->validate([
    'email' => 'required|email'
]);

// .. automatic redirection is happening inside validate() if there's errors

// .. you can just continue with the rest of the codes
```

# Session 👮‍♂️
Here are the main usages of session within this template codebase.

**Alert:**
- Although not pre-defined here, you can make use of `$_SESSION['__flash']['data']['errors']['input_name]` to trigger an alert to one of your view files, such as this:
```php
// Norte Cafe > resources > views > Admin > transaction > show.view.php
<?php if (isset($_SESSION['__flash']['rider_assigned'])) : ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Transaction assigned to <?= $transactions[0]['rider_name'] ?>!",
            allowOutsideClick: false,
        });
    </script>
<?php endif; ?>
```
**Errrors**
- Automatic error messages after validation are also using `$_SESSION`:
```php
// functions.php
function error($input)
{

    $errors = Core\Session::get('__flash', 'data')['errors'][$input] ?? null;

    if (isset($_SESSION['__flash']['data']['errors'][$input])) {
        echo "<ul class='m-0 p-0' style='list-style: none;'>";
        foreach ($errors as $error) {
            echo "<li class='text-danger'>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
}
```
- This can be use link this
```php
// .. some view with input
<div>
    <label for="password">Password</label>
    <input 
    type="password"
    name="password"
    id="password">
    <?php error('password') ?> 
</div>
```
**Old Values:**
- After a failed validation input fields can retain their previous values using this function:
```php
// functions.php
function old($input)
{
    return Core\Session::get('__flash', 'data')['old'][$input] ?? null;
}
```
- And we can use them like this:
```html
<input class="form-control" name="username" type="text" id="username" placeholder="Username" value="<?= old('username') ?>" required autofocus>
```
- The `__flash` array inside of `$_SESSION` is empty everytime we within the entry point `public/index.php`:
```php
// public/index.php
Session::unflash();
```

# Missing Parts
- Pagination essential methods within the `Database.php`.
- Various packages such as `FPDF`, `PHP Mailer` are not included here.