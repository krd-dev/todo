<?php $this->layout('layout', ['title' => 'Create task']) ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Create task</h1>
            <form action="../store" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-outline-primary">Create task</button>
            </form>
        </div>
    </div>
</div>