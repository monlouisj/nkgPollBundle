# nkgPollBundle

Simply publish and administrate polls within your Symfony2 application.

<br/>
<h3>How to install :</h3>

1 - install bundle via packagist

2 - install dependencies : run *composer update*

3 - install database : run *php app/console doctrine:schema:update --force*

4 - import routes (they are defined in Annotation) in your routing.yml
```
app:
    resource: "@NkgPollBundle/Controller/"
    type:     annotation
```
<br/>
<h3>Default routes:</h3>

Administration:
```
  /polladmin/poll/list/
```

Voting:
```
  /front/
```
