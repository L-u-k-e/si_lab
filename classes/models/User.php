<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class User {
    protected $id,
              $login,
              $access_level,
              $name,
              $surname;

    private $pdo, $validator;

    function __construct($login, $access_level, $name, $surname, $id = null) {
        $this->pdo = DB::instance();
        $this->validator = new Validator();
        $this->validator->addField('login', 'min_length(6)', $this->login, 'Login musi mieć przynajmniej 6 znaków.');
        $this->validator->addField('access_level', 'min_value(0)_and_max_value(4)', $this->access_level, 'Poziom dostępu musi zawierać się pomiędzy 0 i 4.');
        $this->validator->addField('name', 'not_empty', $this->name, 'Nie podano imienia.');
        $this->validator->addField('surname', 'not_empty', $this->surname, 'Nie podano nazwiska.');
        $this->id = $id;
        $this->login = $login;
        $this->access_level = $access_level;
        $this->name = $name;
        $this->surname = $surname;
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
        $stmt = DB::instance()->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if(!$result) {
            throw new UserNotFoundException("Nie znaleziono użytkownika o ID = $id.");
        }
        extract($result);
        return new User($login, $access_level, $name, $surname, $id);
    }

    public static function getByLoginAndPassword($login, $password) {
        $password_hash = Utils::hash($password);
        $stmt = DB::instance()->prepare('SELECT * FROM users WHERE login = :login AND password_hash = :password_hash');
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':password_hash', $password_hash);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if(!$result) {
            throw new UserNotFoundException("Nie znaleziono w bazie takiej kombinacji użytkownika i hasła.");
        }
        extract($result);
        return new User($login, $access_level, $name, $surname, $id);
    }

    public function validate() {
        $this->validator->validate();
    }

    public function setPassword($password, $repeated_password) {
        $password_validator = new Validator();
        $password_validator->addField('password', "min_length(6)_and_equal_to($repeated_password)", $password, 'Hasło musi mieć minimum 6 znaków i zostać wpisane dwa razy tak samo.');
        $password_validator->validate();
        if($this->id) {
            $password_hash = Utils::hash($password);
            $stmt = $this->pdo->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':password_hash', $password_hash);
            if($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function save($validate = true) {
        if($validate) {
            $this->validate();
        }
        try {
            if(!$this->id) {
                $stmt = $this->pdo->prepare('INSERT INTO users (login, access_level, name, surname) VALUES (:login, :access_level, :name, :surname)');
                $stmt->bindValue(':login', $this->login);
                $stmt->bindValue(':access_level', $this->access_level);
                $stmt->bindValue(':name', $this->name);
                $stmt->bindValue(':surname', $this->surname);
                if($stmt->execute()) {
                    $this->id = $this->pdo->lastInsertId();
                    return true;
                }
            } else {
                $stmt = $this->pdo->prepare('UPDATE users SET login = :login, access_level = :access_level, name = :name, surname = :surname WHERE id = :id');
                $stmt->bindValue(':id', $this->id);
                $stmt->bindValue(':login', $this->login);
                $stmt->bindValue(':access_level', $this->access_level);
                $stmt->bindValue(':name', $this->name);
                $stmt->bindValue(':surname', $this->surname);
                if($stmt->execute()) {
                    return true;
                }
            }
        } catch(PDOException $e) {
            if($e->getCode() == 23000) {
                throw new LoginExistsException('Taki login już istnieje w bazie danych.');
            }
            throw $e;
        }
        return false;
    }

    public function delete() {
        if($this->id) {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
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

class UserNotFoundException extends Exception { }
class LoginExistsException extends Exception { }