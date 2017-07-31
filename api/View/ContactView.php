<?php

namespace View;


class ContactView
{
    public function displayJson($array) {
        header('Content-type: application/json; charset=uft-8');
        echo json_encode($array);
    }
}