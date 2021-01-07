<?php

require 'petoneer.class.php';

$petoneer = new Petoneer();

// GET SINGLE DEVICE DETAILS
$fountain = $petoneer->getDevice("SERIAL_NUMBER");
var_dump($fountain);

// LIST ALL DEVICES
$devices = $petoneer->getDevices();
