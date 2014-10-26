<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class Employee {
    protected $id,
              $name,
              $surname,
              $gender,
              $family_name,
              $email,
              $postal_code;

    private $pdo, $validator;

    function __construct($name, $surname, $gender, $family_name, $email, $postal_code, $id=null) {
        $this->pdo = DB::instance();
        $this->validator = new Validator();
        $this->validator->addField('name', 'not_empty', $this->name, 'Nie podano imienia.');
        $this->validator->addField('surname', 'not_empty', $this->surname, 'Nie podano nazwiska.');
        $this->validator->addField('gender', 'equals_male_or_female', $this->gender, 'Nie wybrano płci.');
        $this->validator->addField('email', 'not_empty_and_email', $this->email, 'Nie podano poprawnego adresu e-mail.');
        $this->validator->addField('postal_code', 'not_empty_and_postal_code', $this->postal_code, 'Nie podano poprawnego kodu pocztowego.');
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->gender = $gender;
        $this->family_name = $family_name;
        $this->email = $email;
        $this->postal_code = $postal_code;
    }

    function __get($name) {
        if(isset($this->{$name}) && !in_array($name, ['pdo', 'validator'])) {
            return $this->{$name};
        }
    }

    public function safe_get($name) {
        if(isset($this->{$name}) && !in_array($name, ['pdo', 'validator'])) {
            return Utils::htmlentities($this->{$name});
        }
    }

    function __set($name, $value) {
        if(isset($this->{$name}) && !in_array($name, ['pdo', 'validator'])) {
            $this->{$name} = $value;
        }
    }

    public static function getByID($id) {
        $stmt = DB::instance()->prepare('SELECT * FROM employees WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if(!$result) {
            throw new EmployeeNotFoundException("Nie znaleziono pracownika o ID = $id.");
        }
        extract($result);
        return new Employee($name, $surname, $gender, $family_name, $email, $postal_code, $id);
    }

    public function validate() {
        $this->validator->validate();
    }

    public function save($validate = true) {
        if($validate) {
            $this->validate();
        }
        if(!$this->id) {
            $stmt = $this->pdo->prepare('INSERT INTO employees (name, surname, gender, family_name, email, postal_code) VALUES (:name, :surname, :gender, :family_name, :email, :postal_code)');
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':surname', $this->surname);
            $stmt->bindValue(':gender', $this->gender);
            $stmt->bindValue(':family_name', $this->family_name);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':postal_code', $this->postal_code);
            if($stmt->execute()) {
                $this->id = $this->pdo->lastInsertId();
                return true;
            } else {
                return false;
            }
        } else {
            $stmt = $this->pdo->prepare('UPDATE employees SET name = :name, surname = :surname, gender = :gender, family_name = :family_name, email = :email, postal_code = :postal_code WHERE id = :id');
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':surname', $this->surname);
            $stmt->bindValue(':gender', $this->gender);
            $stmt->bindValue(':family_name', $this->family_name);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':postal_code', $this->postal_code);
            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function delete() {
        if($this->id) {
            $stmt = $this->pdo->prepare('DELETE FROM employees WHERE id = :id');
            $stmt->bindValue(':id', $this->id);
            if($stmt->execute()) {
                $this->id = null;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}

class EmployeeNotFoundException extends Exception { }