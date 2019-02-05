<?php $this->layout('layout', ['title' => 'Tasks']) ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>My tasks</h1>
        </div>
        <div class="col-md-12">
            <a href="create" class="btn btn-outline-success btn-block">Create task</a>
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Number</th>
                    <th scope="col">Title</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tasksInView as $task): ?>
                    <tr>
                        <td><?= $task['id']; ?></td>
                        <td><?= $task['title']; ?></td>
                        <td><?= $task['username']; ?></td>
                        <td>
                            <a href="show/<?= $task['id']; ?>" class="btn btn-primary">Show</a>
                            <a href="edit/<?= $task['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete/<?= $task['id']; ?>" class="btn btn-danger"
                               onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <a href="logout" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
