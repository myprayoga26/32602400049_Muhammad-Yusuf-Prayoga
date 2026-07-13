<?php
$db = new PDO('mysql:host=127.0.0.1;dbname=literia_db', 'root', '');
$q = $db->query("DESCRIBE buku");
print_r($q->fetchAll(PDO::FETCH_ASSOC));
