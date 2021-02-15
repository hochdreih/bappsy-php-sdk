<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Bappsy\Bappsy;


$bappsy = new Bappsy(''); // <-- Insert your Key

$lead = new stdClass();
$lead->first_name = 'My First Lead';
$lead->email = 'lead@customer.com';
$lead->t = 'lead';

$newLead = $bappsy->create('consumers', $lead);
print_r($newLead);

$leads = $bappsy->get('consumers', null, null, 'by/type/leads' );
print_r($leads);





