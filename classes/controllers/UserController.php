<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class UserController extends BaseController {
    public function login() {
        session_destroy();
        session_start();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            try {
                $user = User::getByLoginAndPassword($_POST['login'], $_POST['password']);
                $_SESSION['logged_in'] = true;
                $_SESSION['logged_user_id'] = $user->id;
                $content_view = new View('partials/successful_login');
                $content_view->set('name', $user->name);
                $content_view->set('surname', $user->surname);
                return $this->defaultView()->set('main_content', $content_view);
            } catch(UserNotFoundException $e) {
                $errors[] = $e->getMessage();
            }
        }
        $content_view = new View('partials/login_form');
        if($errors) {
            $content_view->set('errors', $errors);
        }
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function logout() {
        Utils::destroyAndClearSession();
        $content_view = new View('partials/successful_logout');
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            $user = new User($_POST['login'], 0, $_POST['name'], $_POST['surname']);
            try {
                $user->validate();
            } catch(ValidationException $e) {
                $errors = array_merge($errors, $e->errors);
            }
            $password_ok = true;
            try {
                $user->validatePassword($_POST['password'], $_POST['repeated_password']);
            } catch(ValidationException $e) {
                $password_ok = false;
                $errors = array_merge($errors, $e->errors);
            }
            if(!$errors) {
                try {
                    $user->save();
                } catch(LoginExistsException $e) {
                    $errors[] = $e->getMessage();
                }
            }
            if(!$errors) {
                $user->setPassword($_POST['password'], $_POST['repeated_password']);
                $content_view = new View('partials/successful_registration');
                $content_view->set('name', $user->name)
                             ->set('surname', $user->surname);
                return $this->defaultView()->set('main_content', $content_view);
            }
        }
        $content_view = new View('partials/user_form');
        $content_view->set('header', 'Rejestracja')
                     ->set('form_action', '/user/register');
        if($errors) {
            foreach($_POST as $name => $value) {
                if(!$password_ok && in_array($name, [ 'password', 'repeated_password' ])) {
                    continue;
                }
                $content_view->set($name, $value);
            }
            $content_view->set('errors', $errors);
        }
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function change_data() {
        $this->requireAccessLevel(1);
        $user = User::getByID($_SESSION['logged_user_id']);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            try {
                $user->login = $_POST['login'];
                $user->name = $_POST['name'];
                $user->surname = $_POST['surname'];
                $user->save();
            } catch(ValidationException $e) {
                $errors = array_merge($errors, $e->errors);
            } catch(LoginExistsException $e) {
                $errors[] = $e->getMessage();
            }
            if($_POST['password'] || $_POST['repeated_password']) {
                $password_ok = true;
                try {
                    $user->setPassword($_POST['password'], $_POST['repeated_password']);
                } catch(ValidationException $e) {
                    $password_ok = false;
                    $errors = array_merge($errors, $e->errors);
                }
            }
            if(!$errors) {
                $content_view = new View('partials/successful_edit');
                return $this->defaultView()->set('main_content', $content_view);
            }
        }
        $content_view = new View('partials/user_form');
        $content_view->set('header', 'Zmiana danych')
                     ->set('form_action', '/user/change-data')
                     ->set('message', 'Jeśli nie chcesz ustawiać nowego hasła, pola haseł pozostaw puste.')
                     ->set('editing', true);
        if($errors) {
            foreach($_POST as $name => $value) {
                if(!$password_ok && in_array($name, [ 'password', 'repeated_password' ])) {
                    continue;
                }
                $content_view->set($name, $value);
            }
            $content_view->set('errors', $errors);
        } else {
            $content_view->set('login', $user->login)
                         ->set('name', $user->name)
                         ->set('surname', $user->surname);
        }
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function change_access_lvl() {
        $this->requireAccessLevel(4);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user = User::getByID($_POST['id']);
                if($user->access_level < 4 || $user->id == $_SESSION['logged_user_id']) {
                    $user->access_level = $_POST['access_level'];
                    $user->save();
                    header('Location: /user/change-access-lvl');
                    exit;
                } else {
                    $error_message = 'Nie masz uprawnień, by wykonać żądaną czynność.';
                }
            } catch(ValidationException $e) {
                $error_message = $e->errors[0];
            }
        }
        $query = Users::all();
        $count = $query->getFullCount();

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        $users = $query->range(($current_page - 1) * $results_per_page, $results_per_page)->getAsArray();

        $pagination_view = $this->paginationView($pages_num, $current_page, '/user/change-access-lvl?page={page}');

        $content_view = new View('partials/change_access_level');
        $content_view->set('header', 'Zmiana uprawnień użytkowników')
                     ->set('users', $users)
                     ->set('logged_user_id', $_SESSION['logged_user_id'])
                     ->set('count', $count)
                     ->set('pagination', $pagination_view);

        if($error_message) {
            $content_view->set('error_message', $error_message);
        }

        return $this->defaultView()->set('main_content', $content_view);
    }

    public function delete() {
        $this->requireAccessLevel(4);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_POST['confirmation'] == 'Tak') {
                $user = User::getByID($_POST['id']);
                if($user->access_level < 4 || $user->id == $_SESSION['logged_user_id']) {
                    $user->delete();
                    header('Location: /user/delete');
                    exit;
                } else {
                    $error_message = 'Nie masz uprawnień, by wykonać żądaną czynność.';
                }
            }
        }
        if($_GET['id']) {
            $content_view = new View('partials/del_confirmation');
            try {
                $user = User::getByID($_GET['id']);
                if($user->access_level < 4 || $user->id == $_SESSION['logged_user_id']) {
                    $content_view->set('id', $user->id)
                                 ->set('header', 'Usuwanie użytkownika')
                                 ->set('form_action', '/user/delete')
                                 ->set('question', "Czy na pewno chcesz usunąć użytkownika $user->name $user->surname o ID = $user->id?");
                    return $this->defaultView()->set('main_content', $content_view);
                } else {
                    $error_message = 'Nie masz uprawnień, by wykonać żądaną czynność.';
                }
            } catch(EmployeeNotFoundException $e) {
                $error_message = "Nie istnieje użytkownik o ID = {$_GET['id']}, którego próbowano usunąć.";
            }
        }
        $query = Users::all();
        $count = $query->getFullCount();

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        $users = $query->range(($current_page - 1) * $results_per_page, $results_per_page)->getAsArray();

        $pagination_view = $this->paginationView($pages_num, $current_page, '/user/delete?page={page}');

        $content_view = new View('partials/delete_user');
        $content_view->set('users', $users)
                     ->set('logged_user_id', $_SESSION['logged_user_id'])
                     ->set('count', $count)
                     ->set('pagination', $pagination_view);

        if($error_message) {
            $content_view->set('error_message', $error_message);
        }

        return $this->defaultView()->set('main_content', $content_view);
    }
}