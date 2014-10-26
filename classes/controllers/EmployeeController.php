<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class EmployeeController extends BaseController {
    public function add() {
        $this->requireAccessLevel(1);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $employee = new Employee($_POST['name'], $_POST['surname'], $_POST['gender'], $_POST['family_name'], $_POST['email'], $_POST['postal_code']);
                $employee->save();
                $_SESSION['employees'][$employee->id] = [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'surname' => $employee->surname,
                    'gender' => $employee->gender,
                    'family_name' => $employee->family_name,
                    'email' => $employee->email,
                    'postal_code' => $employee->postal_code
                ];
                header('Location: /employee/all');
                exit;
            } catch(ValidationException $e) {
                $errors = $e->errors;
            }
        }
        $content_view = new View('partials/employee_form');
        $content_view->set('header', 'Dodawanie nowego pracownika')
                     ->set('form_action', '/employee/add');
        if($errors) {
            foreach($_POST as $name => $value) {
                $content_view->set($name, $value);
            }
            $content_view->set('errors', $errors);
        }
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function search() {
        $this->requireAccessLevel(1);
        $where_clauses = [];
        $where_variables = [];
        $q_words = explode(' ', $_GET['q']);
        foreach ($q_words as $index => $word) {
            $where_clauses[] = "surname LIKE :var_{$index}";
            $where_variables[":var_{$index}"] = "%$word%";
        }
        $query = Employees::where(implode(' OR ', $where_clauses), $where_variables);
        $count = $query->getFullCount();

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        $employees = $query->range(($current_page - 1) * $results_per_page, $results_per_page)->getAsArray();

        $pagination_view = $this->paginationView($pages_num, $current_page, '/search-employees?q=' . $_GET['q'] . '&page={page}');

        $content_view = new View('partials/employees_list');
        $content_view->set('header', "Wyniki wyszukiwania dla: \"{$_GET['q']}\"")
                     ->set('employees', $employees)
                     ->set('count', $count)
                     ->set('pagination', $pagination_view);
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function all() {
        $this->requireAccessLevel(1);
        $query = Employees::all();
        $count = $query->getFullCount();

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        $employees = $query->range(($current_page - 1) * $results_per_page, $results_per_page)->getAsArray();

        $pagination_view = $this->paginationView($pages_num, $current_page, '/employee/all?page={page}');

        $content_view = new View('partials/employees_list');
        $content_view->set('header', 'Baza pracowników')
                     ->set('employees', $employees)
                     ->set('count', $count)
                     ->set('pagination', $pagination_view);
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function edit() {
        $this->requireAccessLevel(2);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $employee = Employee::getByID($_POST['id']);
                $employee->name = $_POST['name'];
                $employee->surname = $_POST['surname'];
                $employee->gender = $_POST['gender'];
                $employee->family_name = $_POST['family_name'];
                $employee->email = $_POST['email'];
                $employee->postal_code = $_POST['postal_code'];
                $employee->save();
                $_SESSION['employees'][$employee->id] = [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'surname' => $employee->surname,
                    'gender' => $employee->gender,
                    'family_name' => $employee->family_name,
                    'email' => $employee->email,
                    'postal_code' => $employee->postal_code
                ];
                header('Location: /employee/edit');
                exit;
            } catch(ValidationException $e) {
                $errors = $e->errors;
            }
        }
        if($_GET['id'] || $errors) {
            $content_view = new View('partials/employee_form');
            $content_view->set('header', 'Edycja danych pracownika')
                         ->set('editing', true)
                         ->set('form_action', '/employee/edit');
            if($errors) {
                $content_view->set('errors', $errors);
                foreach($_POST as $name => $value) {
                    $content_view->set($name, $value);
                }
                return $this->defaultView()->set('main_content', $content_view);
            } else {
                try {
                    $employee = Employee::getByID($_GET['id']);
                    $content_view->set('id', $employee->id);
                    $content_view->set('name', $employee->name);
                    $content_view->set('surname', $employee->surname);
                    $content_view->set('gender', $employee->gender);
                    $content_view->set('family_name', $employee->family_name);
                    $content_view->set('email', $employee->email);
                    $content_view->set('postal_code', $employee->postal_code);
                    return $this->defaultView()->set('main_content', $content_view);
                } catch(EmployeeNotFoundException $e) {
                    $error_message = "Nie istnieje pracownik o ID = {$_GET['id']}, którego próbowano edytować.";
                }
            }
        }
        $query = Employees::all();
        $count = $query->getFullCount();

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        $employees = $query->range(($current_page - 1) * $results_per_page, $results_per_page)->getAsArray();

        $pagination_view = $this->paginationView($pages_num, $current_page, '/employee/edit?page={page}');

        $content_view = new View('partials/employees_list');
        $content_view->set('header', 'Wybór pracownika do edycji')
                     ->set('employees', $employees)
                     ->set('count', $count)
                     ->set('pagination', $pagination_view)
                     ->set('additional_option', true)
                     ->set('option_name', 'Edycja')
                     ->set('option_link', '/employee/edit?id={id}');

        if($error_message) {
            $content_view->set('error_message', $error_message);
        }

        return $this->defaultView()->set('main_content', $content_view);
    }

    public function delete() {
        $this->requireAccessLevel(3);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_POST['confirmation'] == 'Tak') {
                $employee = Employee::getByID($_POST['id']);
                unset($_SESSION['employees'][$employee->id]);
                $employee->delete();
            }
            header('Location: /employee/delete');
            exit;
        }
        if($_GET['id']) {
            $content_view = new View('partials/del_confirmation');
            try {
                $employee = Employee::getByID($_GET['id']);
                $content_view->set('id', $employee->id)
                             ->set('header', 'Usuwanie pracownika')
                             ->set('form_action', '/employee/delete')
                             ->set('question', "Czy na pewno chcesz usunąć pracownika $employee->name $employee->surname o ID = $employee->id?");
                return $this->defaultView()->set('main_content', $content_view);
            } catch(EmployeeNotFoundException $e) {
                $error_message = "Nie istnieje pracownik o ID = {$_GET['id']}, którego próbowano usunąć.";
            }
        }
        $query = Employees::all();
        $count = $query->getFullCount();

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        $employees = $query->range(($current_page - 1) * $results_per_page, $results_per_page)->getAsArray();

        $pagination_view = $this->paginationView($pages_num, $current_page, '/employee/delete?page={page}');

        $content_view = new View('partials/employees_list');
        $content_view->set('header', 'Wybór pracownika do usunięcia')
                     ->set('employees', $employees)
                     ->set('count', $count)
                     ->set('pagination', $pagination_view)
                     ->set('additional_option', true)
                     ->set('option_name', 'Usuń')
                     ->set('option_link', '/employee/delete?id={id}');

        if($error_message) {
            $content_view->set('error_message', $error_message);
        }

        return $this->defaultView()->set('main_content', $content_view);
    }

    public function session() {
        $this->requireAccessLevel(1);
        $count = count($_SESSION['employees']);

        $results_per_page = 5;
        $current_page = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
        $pages_num = ceil($count / $results_per_page);

        if($_SESSION['employees']) {
            $employees = array_slice($_SESSION['employees'], ($current_page - 1) * $results_per_page, $results_per_page);
        } else {
            $employees = [];
        }

        $pagination_view = $this->paginationView($pages_num, $current_page, '/employee/session?page={page}');

        $content_view = new View('partials/employees_list');
        $content_view->set('header', 'Pracownicy dodani lub edytowani w bieżącej sesji')
                     ->set('employees', $employees)
                     ->set('count', $count)
                     ->set('pagination', $pagination_view);
        return $this->defaultView()->set('main_content', $content_view);
    }
}