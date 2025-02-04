<?php
    use app\models\TempModel;

    $model = new TempModel(Flight::db());
try {
    $model->NourrirAnimauxAuto(100);
    echo "OOO";
}
catch (\Exception $e) {
    echo $e->getMessage();
}

?>