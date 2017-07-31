<?php

namespace Model;

use Model\DBConfig as DBConfig;
use Entity\ContactEntity;
use Controller\ContactController;

class ContactModel
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = self::Connect(new DBConfig());
    }

    public function __destruct()
    {

        $this->dbh = null;

    }

    static function Connect(DBConfig $dbConfig) {

        return new \PDO("mysql:host=" . $dbConfig->getServerName() . ";dbname=" . $dbConfig->getDatabase(), $dbConfig->getUserName(), $dbConfig->getPassword());

    }


    public function createContact(ContactEntity $contact) {

        $ctrl = new ContactController();
        $ctrl->exportArray($contact);

        $name = $contact->getName();
        $email = $contact->getEmail();
        $phone = $contact->getPhone();

        $stmt = $this->dbh->prepare("INSERT INTO contacts (name,email,phone,created_at,updated_at) VALUES(:name, :email,:phone, FROM_UNIXTIME(:created_at), FROM_UNIXTIME(:updated_at))");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':created_at', time());
        $stmt->bindParam(':updated_at', time());

        $bool = $stmt->execute();
        
        $contact->setId($this->dbh->lastInsertId());

        return $bool; // Return True or False

    }

    public function getContactById($contactId) {

        $stmt = $this->dbh->prepare("SELECT * FROM contacts WHERE id = $contactId");
        $stmt->execute();
        return new ContactEntity($stmt->fetch(\PDO::FETCH_ASSOC));

    }

    public function updateContact(ContactEntity $contact) {
        
        $ctrl = new ContactController();
        $ctrl->exportArray($contact);

        $id = $contact->getId();
        $name = $contact->getName();
        $email = $contact->getEmail();
        $phone = $contact->getPhone();

        $stmt = $this->dbh->prepare("UPDATE contacts SET name = :name, email = :email, phone = :phone, updated_at = FROM_UNIXTIME(:updated_at) WHERE id = $id ");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':updated_at', time());

        return $stmt->execute();
    }

    public function deleteContact(ContactEntity $contact) {

        $id = $contact->getId();
        $stmt = $this->dbh->prepare("DELETE FROM contacts WHERE id = $id");
        return $stmt->execute();

    }

    public function getAll() {
        if (isset($_GET['page'])) {
            $page  = $_GET['page']; 
        } else {
            $page = 1;
        }

        if (isset($_GET['per_page'])) {
            $page_page  = $_GET['per_page']; 
        } else {
            $per_page = 5;
        }

        $start_from = ($page - 1) * $per_page;

        $sqlTotal = "SELECT * FROM contacts";

        $sql = "SELECT * FROM contacts Order By name asc LIMIT $start_from, $per_page"; 

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        
        $data = array();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data['data'][] = $row;
        }

        $stmtTotal = $stmt = $this->dbh->prepare($sqlTotal);
        $stmtTotal->execute();

        if($per_page == 0) {
            $data['data'] = array();

            while ($row = $stmtTotal->fetch(\PDO::FETCH_ASSOC)) {
                $data['data'][] = $row;
            }
        }

        $data['total'] = $stmtTotal->rowCount();
        $data['per_page'] = $per_page;

        return $data;
    }

}