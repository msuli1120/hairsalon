<?php
  date_default_timezone_set('America/Los_Angeles');
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Stylist.php";
  require_once __DIR__."/../src/Client.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $server = 'mysql:host=localhost;dbname=hair_salon';
  $user = 'root';
  $pass = 'root';

  $db = new PDO($server, $user, $pass);

  $app['debug'] = true;

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function () use ($app) {
    return $app['twig']->render('index.html.twig', array('results'=>Stylist::getAll(), 'clients'=>Client::newest()));
  });

  $app->post("/stylist", function () use ($app) {
    if(!empty($_POST['stylist'])){
      $new_stylist = new Stylist($_POST['stylist']);
      $new_stylist->save();
      return $app['twig']->render('index.html.twig', array('results'=>Stylist::getAll()));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/stylist/{id}", function ($id) use ($app) {
    $result = Stylist::find($id);
    return $app['twig']->render('stylist.html.twig', array('stylist'=>$result, 'clients'=>Client::getAll($id)));
  });

  $app->post("/addclient", function () use ($app) {
    if(!empty($_POST)){
      $new_client = new Client($_POST['name'], $_POST['gender'], $_POST['age'], $_POST['address'], $_POST['styid']);
      $new_client->save();
      return $app['twig']->render('stylist.html.twig', array('stylist'=>Stylist::find($new_client->getStylistId()), 'clients'=>Client::getAll($new_client->getStylistId())));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/client/{id}", function ($id) use ($app) {
    $result = Client::find($id);
    return $app['twig']->render('client.html.twig', array('client'=>$result, 'stylist'=>Stylist::find($result->getStylistId())));
  });

  $app->get("/client/{id}/edit", function ($id) use ($app) {
    $result = Client::find($id);
    return $app['twig']->render('client_edit.html.twig', array('client'=>$result));
  });

  $app->patch("/client/{id}", function($id) use ($app) {
    if(!empty($_POST)){
      $new_client = Client::find($id);
      $new_client->update($_POST['name'],$_POST['gender'],$_POST['age'],$_POST['address']);
      return $app['twig']->render('stylist.html.twig', array('stylist'=>Stylist::find($new_client->getStylistId()), 'clients'=>Client::getAll($new_client->getStylistId())));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->delete("/client/{id}", function($id) use ($app) {
    $client = Client::find($id);
    $stylist_id = $client->getStylistId();
    $client->delete();
    return $app['twig']->render('stylist.html.twig', array('stylist'=>Stylist::find($stylist_id), 'clients'=>Client::getAll($stylist_id)));
  });

  return $app;
?>
