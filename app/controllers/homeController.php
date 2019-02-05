<?php

namespace App\controllers;

use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use League\Plates\Engine;

class HomeController
{
    private $view;
    private $db;
    private $pdo;
    private $auth;

    public function __construct(Engine $view, QueryFactory $db, \PDO $pdo, Auth $auth)
    {

        $this->view = $view;
        $this->db = $db;
        $this->pdo = $pdo;
        $this->auth = $auth;
    }

    // Список задач пользователя
    public function index()
    {
        $select = $this->db->newSelect();
        $select->cols([
            'tasks.id',
            'title',
            'content',
            'username'
        ])
            ->from('tasks')
            ->where('user=:user')
            ->bindValue(':user', $this->auth->getUserId())
            ->join(
                'INNER',
                'users',
                'tasks.user = users.id'
            );
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $myTasks = $sth->fetchAll(\PDO::FETCH_ASSOC);
        echo $this->view->render('tasks', ['tasksInView' => $myTasks]);
    }

    // Детализация задачи
    public function show($id)
    {
        $select = $this->db->newSelect();
        $select->cols(['*'])
            ->from('tasks')
            ->where('id=:id')
            ->bindValue(':id', $id);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $myTask = $sth->fetch(\PDO::FETCH_ASSOC);

        echo $this->view->render('show', ['tasksInView' => $myTask]);
    }

    // Вывод деталей задачи для коректировки
    public function edit($id)
    {
        $select = $this->db->newSelect();
        $select->cols(['*'])
            ->from('tasks')
            ->where('id=:id')
            ->bindValue(':id', $id);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $myTask = $sth->fetch(\PDO::FETCH_ASSOC);

        echo $this->view->render('edit', ['tasksInView' => $myTask]);

    }

    // Обновление задачи
    public function update($id)
    {
        $update = $this->db->newUpdate();
        $update->table('tasks')
            ->cols(['title', 'content'])
            ->where('id=:id')
            ->bindValues([
                ':title' => $_POST['title'],
                ':content' => $_POST['content'],
                ':id' => $id
            ]);
        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
        header('Location: /');
    }

    // Удаление задачи
    public function delete($id)
    {
        $delete = $this->db->newDelete();
        $delete->from('tasks')
            ->where('id=:id')
            ->bindValue(':id', $id);
        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
        header('Location: /');
    }

    // Генерирую страницу с формами для создания задачи
    public function create()
    {
        echo $this->view->render('create');
    }

    // Создание задачи
    public function store()
    {
        $insert = $this->db->newInsert();
        $insert->into('tasks')
            ->cols([
                'title',
                'content',
                'user',
            ])
            ->bindValues([
                ':title' => $_POST['title'],
                ':content' => $_POST['content'],
                ':user' => $this->auth->getUserId(),
            ]);
        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
        header('Location: /');
    }

    // Генерирую страницу ввода данных для аутентификации
    public function loginForm()
    {
        echo $this->view->render('loginform');
    }

    // Аутентификация юзера
    public function loginUser()
    {
        try {
            $this->auth->login($_POST['email'], $_POST['password']);
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
        header('Location: /');
    }

    // Генерирую страницу ввода данных для регистрации
    public function registrationForm()
    {
        echo $this->view->render('registrationform');
    }

    // Регистрация юзера
    public function registrationUser()
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['name']);
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
        header('Location: /login');
    }

    // Логаут
    public function logout()
    {
        $this->auth->logOut();
        header('Location: /');
    }
}
