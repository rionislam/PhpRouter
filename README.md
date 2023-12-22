# PhpRouter
[![Version](https://img.shields.io/badge/version-1.0.1-blue.svg)](https://github.com/rionislam/PhpRouter/releases/tag/v1.0.1)

A Php Router with a custom error handler

## Features

- Supports all request methods like **_POST_**, **_GET_**, **_PUT_**, **_DELETE_**
- Can show your custom error pages
- Supports url parameters for dynamic urls
- Works by calling specific functions from specific class. So no need to create multiple files for multiple requests.

# How to use?

Download this codes extract them to your desire folder. Custimize the namespaces as your environment. If you have autoloader you can remove the require_once statement in line 3 and 59 in the **Router.php** file. Also specify your **Controllers** folder path and namespace in line 56 and 60 in the **Router.php** file.

## Edit your _.htaccess_

Configure your **.htaccess** for the PhpRouter. Copy paste the codes below to your **.htaccess** file.

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

RewriteEngine on
RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA,L]

</IfModule>
```

## Configure the Router in your _index.php_

### Set the ErrorHandler to handle all errors (optional)

- If you don't have a autoloader *require* the **ErrorHandler.php** file

    ```php
    require_once(__DIR__.'/Services/ErrorHandler.php');
    ```

- Set the error handler
    ```php
    set_error_handler(['Services\ErrorHandler', 'handleErrors']);

    set_exception_handler(['Services\ErrorHandler', 'handleExceptions']);

    register_shutdown_function(['Services\ErrorHandler', 'handleShutdown']);
    ```

### Initiate the router

- Add use statemate for the Router class
    ```php
    use Services\Router;
    ```
- Add require statement if there is no autoloader
    ```php
    require_once(__DIR__."/services/Router.php"); //Customize the path as your file structure
    ```
- Create **Router** instance
    ```php
    new Router;
    ```
    


### Define the routes

- For **GET** requests
    ```php
    Router::get("/example", "PageController@loadExample");//PageController is a class and the loadExample is a function inside that
    ```

    ```php
    //Here is the example class
    class PageController{
        public function loadExample(){
            //do anything you want here
            include('example.php');//For example
        }
    }
    ```
- For **GET** requests with dynamic urls
    ```php
    Router::get("/product/{id}", "ProductController@loadProduct");//You can directly access the id in the loadProduct function as the code below
    ```

    ```php
    //Here is the example class
    class ProductController{
        public function loadProduct($id){
            echo 'The product id is: '. $id;
        }
    }
    ```

- For **POST** requests
    ```php
    Router::post("/submit-form", "FormController@submit");//You can access the POST data inside the submit function normally
    ```

- For **PUT** requests
    ```php
     Router::put("/update-product", "ProcuctController@updateProduct");//You can access the PUT data inside the submit function from the php input
    ```
- For **DELETE** requests
    ```php
     Router::del("/delete-product", "ProcuctController@deleteProduct");//You can access the DELETE data inside the submit function from the php input
    ```

### Dispatch the requests

```php
Router::dispatch();
```

# More
You can also use the custome error handler to show error from anywhere from your code. Just *require*
**ErrorHandler.php** file if you don't have a autoloader. If you have a autoloader just add a *use* statement to your file and show a error page like below.

```php
ErrorHandler::showErrorPage(404);
```

You can also create your own error code and a error page for that. Check near line 29 in the **ErrorHandler.php** file. If you wanna use a custom error code, use something after **600** to avoid collision with default error codes.

**NOTE:** Please leave a star if you find it valuable. And please let me know where to improve
