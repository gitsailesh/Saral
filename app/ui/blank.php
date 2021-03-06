<?php
// you can pass css files that can be used for particular page
if (isset($data['css'])) {
    foreach ($data['css'] as $css) {
        echo "<link href='" . $this->getPublicURL() . "css/$css' rel='stylesheet' /> \n";
    }
}

$this->loadView($data['view'], $data, true);

// you can pass javascript files that can be used for particular page
if (isset($data['js'])) {
    foreach ($data['js'] as $js) {
        echo "<script src='" . $this->getPublicURL() . "scripts/$js'></script> \n";
    }
}