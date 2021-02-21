<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Bappsy\Bappsy;


$bappsy = new Bappsy('<apiKey>'); // <-- Insert your Key


/**
 * Step one: Create a product (if not yet existent)
 */

$product = new stdClass();

$product->title = 'My new product';
$product->short_description = '';
$product->kind = 'Simples Produkt';
$product->type = 'Elektronisch erbrachte Leistung';
$product->prices = new stdClass();
$product->prices->group = '5ee06fc6860473001cfb2917'; //<-Replace with your default group from  https://app.bappsy.com/#/config/pricegroups
$product->prices->price = 1.99;
$product->prices->from = '1';
$product->prices->to = '99';

$newProduct = $bappsy->create('products', $product);

/**
 * Step two: Create a new customer (if not yet existent)
 */
$customer = new stdClass();

$customer->first_name = 'My First';
$customer->last_name = 'Customer';
$customer->email = 'customer@customer.com';
$customer->t = 'customer';

$newCustomer = $bappsy->create('consumers', $customer);

/**
 * Step three: Create a new invoice (if not yet existent)
 */

$invoice = new stdClass();

$invoice->_consumerID=  $newCustomer->id;
$invoice->_receiver=  $newCustomer->id;
$invoice->paid=  true;
$invoice->referenceNumber= 1;
$invoice->status=  'Bezahlt';

$invoice->positions=  new stdClass();

$invoice->positions->pos=  1;
$invoice->positions->customer_pos=  1;
$invoice->positions->desc=  'My product description';
$invoice->positions->start=  '2021-01-01T00:00:00.000Z';
$invoice->positions->end=  '2021-12-31T00:00:00.000Z';
$invoice->positions->duration=  365;
$invoice->positions->durationUnit=  'days';
$invoice->positions->external=  true;
$invoice->positions->productId=  $newProduct->_id;
$invoice->positions->productTitle= 'My new product';
$invoice->positions->qty=  1;
$invoice->positions->tax=  19;
$invoice->positions->sku=  'my-sku';
$invoice->positions->typ=  'Zeitraum';
$invoice->positions->unit=  'Tage';
$invoice->positions->price=  1.99;
$invoice->positions->timespan=  true;
$invoice->positions->status=  'Erledigt';

$newInvoice = $bappsy->create('invoices', $invoice);

/**
 * Step four: Prepare the invoice with a email to send.
 */

$template = $bappsy->get('sendprepare', null, 'invoice/' . $newInvoice->_id);

/**
 * Step five: Build the body to send.
 */
$bodyToSend = new stdClass();
$bodyToSend->ap =  $template->ap; // Account Person
$bodyToSend->defaults =  $template->defaults; // Set defaults from mail template
$bodyToSend->from =  "email@company.com"; //<- Needs to be a verified sending domain
$bodyToSend->rhtml =  $template->rhtml; // Body returned from sendprepare
$bodyToSend->rsubject =  $template->rsubject; // Subject returned from sendprepare
$bodyToSend->template =  "5ee06fc6860473001cfb291b"; //<- Default Layout from https://app.bappsy.com/#/config/emailtemplates
$bodyToSend->to =  $template->ap->_id; // The receiver of the mail

/**
 * Step six: Send the Mail.
 * This command will create a pdf and attaches it to the Mail build before.
 */

$sendResult = $bappsy->create('sendmail', $bodyToSend, null, 'invoice/' . $newInvoice->_id);