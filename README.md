# RickAndMorty
RickAndMorty Microservice Symfony Api

Project built on symfony 5 + postgresql 13 + php 7

## Running and Deployment:
Update enviorment variables .env.{$APP_ENV}:

Example in .env:
```
DATABASE_URL="postgresql://symfony:ChangeMe@database:5432/app?serverVersion=13&charset=utf8"
```

Runinng Docker:
```
docker-compuse build
docker-compose up
```

## Api End Points

### Create Characters

Method: POST

>Url: http://127.0.0.1:8000/api/v1/characters

Body Params:

@name: String

@lastName: String

@age: number

@isProtagonist: bool

@occupation: String

@gender: String

```
Curl Example:

curl -XPOST http://127.0.0.1:8000/api/v1/characters -H "Content-Type: application/json" -d '
{"name": "Rick", "lastName": "Sánchez", "age": 70, "isProtagonist": true, "occupation": "Científico", "gender": "male"}'

curl -XPOST http://127.0.0.1:8000/api/v1/characters -H "Content-Type: application/json" -d '
{"name": "Summer", "lastName": "Smith", "age": 17, "isProtagonist": false, occupation: "Student", "gender": "female"}'

curl -XPOST http://127.0.0.1:8000/api/v1/characters -H "Content-Type: application/json" -d '
{"name": "Summer", "lastName": "Smith", "age": 17, "isProtagonist": false, occupation: "Student", "gender": "female"}'
```

### Get All Characters

Method: GET

>URL: http://127.0.0.1:8000/api/v1/characters?gender=male&name=OneName&page=0&limit=6
Params:

@gender: String

@name: String

@page: number

@limit: number

```
Curl Example:
curl -XGET http://127.0.0.1:8000/api/v1/characters -H "Content-Type: application/json" -d '{"page": 0, "limit": 6}'
```

### Edit Characters

Method: PUT

>Url: http://127.0.0.1:8000/api/v1/characters/{ID}

Body Params:

@name: String

@lastName: String

@age: number

@isProtagonist: bool

@occupation: String

@gender: String

```
Curl Example:
curl -X PUT http://127.0.0.1:8000/api/v1/characters/1 -H "Content-Type: application/json" -d '
{"name": "Ricardo", "age": 170}'
```

### Delete Characters
 
Method: Delete
> Url: http://127.0.0.1:8000/api/v1/characters/{ID}

```
Curl Example:
curl -X DELETE http://127.0.0.1:8000/api/v1/characters/1 -H "Content-Type: application/json"
```
