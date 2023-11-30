<!DOCTYPE html>


<body>

<?php
use Illuminate\Support\Facades\DB;

if(DB::connection()->getPdo()) {
    echo "Connected";
} else {
    echo "Not Connected";
}
?>

</body>
