<?php

require_once 'setup.php';

$projection = $projectionManager->createProjection('workshop_projection');
$projection
    ->fromAll()
    ->whenAny(function($state, $event) {
       dump($event);
    })
    ->run(false);