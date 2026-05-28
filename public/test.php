<?php require 'public/index.php';
$m = new \App\Models\MovieModel();
$movies = $m->search('')->sortBy('newest')->paginate(20, 'default', 1);
echo "Count paginate(20): " . count($movies) . "\n";
echo "Total count: " . $m->countAllResults() . "\n";
