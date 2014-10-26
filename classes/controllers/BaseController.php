<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class BaseController {
    protected function requireAccessLevel($level) {
        if(!$_SESSION['logged_in'] || User::getByID($_SESSION['logged_user_id'])->access_level < $level) {
            throw new AccessLevelException("Nie masz dostępu do tej części systemu. Wymagany jest poziom dostępu $level.");
        }
    }

    protected function defaultView() {
        $view = new View('layout');
        $header = new View('partials/header');
        $left_sidebar = new View('partials/left_sidebar');
        $right_sidebar = new View('partials/right_sidebar');
        if($_SESSION['logged_in']) {
            try {
                $logged_user = User::getByID($_SESSION['logged_user_id']);
                $left_sidebar->set('logged_in', true)
                             ->set('logged_user', $logged_user);
                $right_sidebar->set('logged_in', true)
                              ->set('logged_user', $logged_user);
            } catch(UserNotFoundException $e) {
                Utils::destoyAndClearSession();
            }
        }
        $footer = new View('partials/footer');
        if($employees_added_in_session = count($_SESSION['employees'])) {
            $footer->set('employees_added_in_session', $employees_added_in_session);
        }
        $view->set('header', $header)
             ->set('left_sidebar', $left_sidebar)
             ->set('right_sidebar', $right_sidebar)
             ->set('footer', $footer);
        return $view;
    }

    protected function paginationView($pages_num, $current_page, $link) {
        $pagination_view = new View('partials/pagination');
        $pagination_view->set('pages_num', $pages_num)
                        ->set('current_page', $current_page)
                        ->set('link', $link);
        return $pagination_view;
    }

    public function error404() {
        header('HTTP/1.0 404 Not Found');
        $content_view = new View('partials/not_found');
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function PDOException($e) {
        switch($e->getCode()) {
            case 2002:
                $message = 'Wystąpił błąd podczas próby połączenia z bazą danych.';
                break;

            case 1045:
                $message = 'Podano błędne dane logowania do bazy danych.';
                break;

            case 1049:
                $message = 'Nie znaleziono bazy danych systemu.';
                break;

            default:
                $message = 'Wystąpił błąd przy połączeniu z bazą danych: ' . $e->getMessage();
        }
        $content_view = new View('partials/system_error');
        $content_view->set('message', $message);
        return $this->defaultView()->set('main_content', $content_view);
    }

    public function Exception($e) {
        $message = $e->getMessage();
        # $message = print_r($e, true);
        $content_view = new View('partials/system_error');
        $content_view->set('message', $message);
        return $this->defaultView()->set('main_content', $content_view);
    }
}

class AccessLevelException extends Exception { }