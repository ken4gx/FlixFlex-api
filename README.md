# FLIXFLEX-API
### Introduction
Project is an API that enable users to navigate through movies and TV Shows causes they're passionate about.
### Project Support Features
* Users can signup and login to their accounts
* Make favorite lists of movies and TV shows.
* Get details and watching a trailer about a movie or show.
* Make search of movies or shows.
* Getting access to the top 5 of all time movies and shows

### Installation Guide
* Clone this repository.
* The master branch is the most stable branch at any given time, ensure you're working from it.
* Run composer install to install all dependencies
* Create an .env file in your project root folder and add your variables. See .env.sample for assistance.
* Run the command php artisan key:generate.
* Run the command php artisan migrate.
* Run the command php artisan jwt:secret.

### Usage
* Run the command php artisan serve to lunch the server.
* Connect to the API using Postman.
### API Endpoints
| HTTP Verbs | Endpoints | Action |
| --- | --- | --- |
| POST | /api/subscribe | To sign up a new user account |
| POST | /api/auth/login | To login an existing user account |
| POST | /api/auth/logout | To logout from an user account |
| GET | /api/movies/list | To retrieve all movies on the platform |
| GET | /api/movies/search | To search for movies on the platform |
| GET | /api/movies/detail/{id} | To retrieve a single movie on the platform |
| GET | /api/movies/detail/{id}/trailer | To retrieve a single movie trailer on the platform |
| GET | /api/movies/addToFavorite/{id} | To add a single movie on the user favorite's list |
| GET | /api/shows/list | To retrieve all shows on the platform |
| GET | /api/shows/search | To search for shows on the platform |
| GET | /api/shows/detail/{id} | To retrieve a single movie on the platform |
| GET | /api/shows/detail/{id}/trailer | To retrieve a single movie trailer on the platform |
| GET | /api/shows/addToFavorite/{id} | To add a single movie on the user favorite's list |
| GET | /api/favorites/movies | To retrieve the movies user favorite's list on the platform |
| GET | /api/favorites/shows | TTo retrieve the shows user favorite's list on the platform |
| DELETE | /api/favorites/deleteMovie/{id} | To retrieve all movies on the platform |
| DELETE | /api/favorites/deleteShow/{id} | To retrieve all movies on the platform |
### Technologies Used
* [NodeJS](https://nodejs.org/) This is a cross-platform runtime environment built on Chrome's V8 JavaScript engine used in running JavaScript codes on the server. It allows for installation and managing of dependencies and communication with databases.* [ExpressJS](https://www.expresjs.org/) This is a NodeJS web application framework.* [MongoDB](https://www.mongodb.com/) This is a free open source NOSQL document database with scalability and flexibility. Data are stored in flexible JSON-like documents.* [Mongoose ODM](https://mongoosejs.com/) This makes it easy to write MongoDB validation by providing a straight-forward, schema-based solution to model to application data.### Authors
* [Black Developa](https://github.com/blackdevelopa)
* ![alt text](https://avatars0.githubusercontent.com/u/29962968?s=400&u=7753a408ed02e51f88a13a5d11014484bc4d80ee&v=4)### License
This project is available for use under the MIT License.


# REST API

The REST API to the example app is described below.

## Create an account / SignUp

### Request

`POST /api/subscribe/`

### Parametres

* email (required)
* username (required|min:3)
* password (required|min:5)
* password_confirmation (required|min:5)

### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        {
            "status": "200",
            "success": "true",
            "message": "The user was created successfly"
        }
    ]
## Connect an account / Login

### Request

`POST /api/login/`

### Parametres

* username (required|min:3)
* password (required|min:5)


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        {
            "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.",
            "token_type": "bearer",
            "expires_in": 3600
        }
    ]

## Logout an account / Logout
### Request

`POST /api/logout/`

### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        {
            "message": "Successfully logged out"
        }
    ]
## Movies List
### Request

`GET /api/movies/list`

### Query

* page (optional|default:1)

### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status": 200,
        "success": true,
        "results": [{},{}],
        "page":1,
        "total_pages":70,
        "total_results":15897
    ]
## Movies Search
### Request

`GET /api/movies/search`

### Query

* page (optional|default:1)
* language (optional|default:EN|max:2)
* region (optional|default:US|max:2)
* query (required|strig|min:1)

### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status": 200,
        "success": true,
        "results": [{},{}],
        "page":1,
        "total_pages":70,
        "total_results":15897
    ]
## Movies details
### Request

`GET /api/movies/details/{id}`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "results":{}
        "url_trailer":""
    ]
## Movie trailer
### Request

`GET /api/movies/details/{id}/trailer`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "url":""
    ]
## Add a movie to favorite list
### Request

`GET /api/movies/addToFavorite/{id}`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "message":"movie added successfly to favorite list"
    ]
## shows List
### Request

`GET /api/shows/list`

### Query

* page (optional|default:1)

### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status": 200,
        "success": true,
        "results": [{},{}],
        "page":1,
        "total_pages":70,
        "total_results":15897
    ]
## shows Search
### Request

`GET /api/shows/search`

### Query

* page (optional|default:1)
* language (optional|default:EN|max:2)
* region (optional|default:US|max:2)
* query (required|strig|min:1)

### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status": 200,
        "success": true,
        "results": [{},{}],
        "page":1,
        "total_pages":70,
        "total_results":15897
    ]
## shows details
### Request

`GET /api/shows/details/{id}`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "results":{}
        "url_trailer":""
    ]
## Movie trailer
### Request

`GET /api/shows/details/{id}/trailer`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "url":""
    ]
## Add a movie to favorite list
### Request

`GET /api/shows/addToFavorite/{id}`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "message":"show added successfly to favorite list"
    ]
## favorite movies list
### Request

`GET /api/favorites/movies`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "result":[]
    ]
## favorite shows list
### Request

`GET /api/favorites/movies`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "result":[]
    ]
## Delete favorite movie from the list
### Request

`DELETE /api/favorites/deleteMovie/{id}`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "message":"the movie has been deleted from your favorite's"
    ]
## delete favorite movie from the list
### Request

`DELETE /api/favorites/deleteShow/{id}`

### Query


### Response
    Status: 200 OK
    Content-Type: application/json
    data:[
        "status":200,
        "success":true,
        "message":"the show has been deleted from your favorite's"
    ]



