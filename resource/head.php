<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="<?PHP echo($_SESSION['config']->getAuthor()); ?>">
    <meta name="description" content="<?PHP echo($_SESSION['config']->getDescription()); ?>">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <title><?PHP echo($_SESSION['config']->getTitle()); ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styles.css">
</head>