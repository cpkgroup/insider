# League Coding Challenge

## Installation

To start this application, you need to do following steps:

```
git clone git@github.com:cpkgroup/insider.git
```

- Run from the project root:

```
docker-compose build
docker-compose run php composer install
```

- Run from the project root:

```
docker-compose up -d
```

- Wait until the docker and VueJs up (VueJs takes a few minutes to up, using `docker-compose logs` to show if VueJs compile is finished), after run these commands:

```
docker-compose run php bin/console doctrine:schema:update --force  # generate mysql schema
```

- Open [http://localhost](http://localhost)


## Technologies
- Docker
- MySQL
- Symfony 4.3 for PHP Framework
- VueJS

## TODOs
- Write functional and unit tests
- Write validations
- Write edit match in VueJs (API is exist for it)
- Follow PSR and use php-cs-fixer
- Add docs for APIs
- Add doc blocks for all functions
- Optimization playGame

## Author
- [Mohamad Habibi](https://www.linkedin.com/in/habibimh) 
