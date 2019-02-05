<?php $this->layout('layout', ['title' => 'Show task']) ?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Show task</h1>
            <table class="table table-hover">
                <tbody>
                <tr>
                    <td>ID</td>
                    <td><?= $tasksInView['id'] ?></td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td><?= $tasksInView['title'] ?></td>
                </tr>
                <tr>
                    <td>Content</td>
                    <td><?= $tasksInView['content'] ?></td>
                </tr>
                </tbody>
            </table>
            <a href="/" class="btn btn-outline-primary">Go back</a>
        </div>
    </div>
</div>