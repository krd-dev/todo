<?php $this->layout('layout', ['title' => 'Login']) ?>


<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <form action="loginuser" method="post" class="col-md-6">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
            </div>
            <!--<div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>-->
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="registration" class="btn btn-primary">Registration</a>
        </form>
    </div>
</div>