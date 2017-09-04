# Read all todo marks

# Create your own .env

# About Democracy-project
## How to launch
- php artisan migrate (Put databases on (out of production))
- php artisan passport:install (Setting up all OAuth2 informations)
- composer install and update (for all init)


# How to use api on laravel
- Install axios and read doc to use it from js in views (needed to call api)
- For example : 
``` html
<script>
    axios.post('/api/user/sayHello')
    .then(function (response) {
        console.log(response.response.data) // will display {response : "Hello"}
    }
<script>
```
