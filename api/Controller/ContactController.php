<?php

namespace Controller;

use Model\ContactModel as ContactModel;
use View\ContactView as ContactView;
use Entity\ContactEntity as ContactEntity;

class ContactController
{
    private $_model;
    private $_view;


    public function __construct()
    {
        $this->_model = new ContactModel();
        $this->_view = new ContactView();
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function handleUrl() {
        $urlArr = array_keys($_GET);
        $url = explode('/', $urlArr[0]);
        
        if ($url[1] == "contacts" && count($url) == 3) {
            return $url[2];
        }

        return 0;
    }

    public function exportArray(ContactEntity $contact) {
        parse_str(file_get_contents("php://input"), $contactArray);
        $contact->setName($contactArray["name"]);
        $contact->setEmail($contactArray["email"]);
        $contact->setPhone($contactArray["phone"]);
    }

    public function convert(ContactEntity $contact) {
        $contactArray = [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone' => $contact->getPhone()
        ];

        return $contactArray;
    }

    public function get() {
        if (empty($this->handleUrl())) {
            $this->_view->displayJson($this->_model->getAll());
        } else {
            $toView = $this->_model->getContactById($this->handleUrl());
            $this->_view->displayJson($this->convert($toView));
        }
    }

    public function post() {
        $contact = new ContactEntity();
        $check = $this->_model->createContact($contact);

        if ($check) {
            $this->_view->displayJson([
                'status' => 'success',
                'message' => 'Novo Contato adicionado com sucesso.',
                'data' => $this->convert($contact)
            ]);
        } else {
            $this->_view->displayJson([
                'status' => 'error',
                'message' => 'Erro ao tentar adicionar o contato.'
            ]);

            http_response_code(500);
        }
    }

    public function put() {
        $contact = $this->_model->getContactById($this->handleUrl());
        $check = $this->_model->updateContact($contact);

        if ($check) {
            $this->_view->displayJson([
                'status' => 'success',
                'message' => 'Contato atualizado com sucesso.',
                'data' => $this->convert($contact)
            ]);
        } else {
            $this->_view->displayJson([
                'status' => 'error',
                'message' => 'Erro ao tentar atualizar o contato.'
            ]);

            http_response_code(500);
        }
    }

    public function delete() {
        $contact = $this->_model->getContactById($this->handleUrl());
        $check = $this->_model->deleteContact($contact);

        if ($check) {
            $this->_view->displayJson([
                'status' => 'success',
                'message' => 'Contato excluído com sucesso.'
            ]);
        } else {
            $this->_view->displayJson([
                'status' => 'error',
                'message' => 'Erro ao tentar excluir o contato.'
            ]);

            http_response_code(500);
        }
    }
}

