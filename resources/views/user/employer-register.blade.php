@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h1>Looking for an employee?</h1>
            <h3>Please create an account</h3>
            <img src="{{ asset('image/register.png') }}" alt="Register">
        </div>

        <div class="col-md-6">
            <div class="card" id="card">
                <div class="card-header">Employer Registration</div>
                <form action="#" method="post" id="registrationForm">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Company name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-dark" id="btnRegister">Register</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="message" class="mt-2"></div>
        </div>
    </div>
</div>
<script>
    var url = "{{ Route('create.employer') }}";
    document.getElementById("btnRegister").addEventListener("click", function (event) {
        var form = document.getElementById("registrationForm");
        var card = document.getElementById("card");
        var messageDiv = document.getElementById("message");
        messageDiv.innerHTML = ''
        var formData = new FormData(form)

        var button = event.target
        button.disabled = true
        button.innerHTML = 'Sending email...'

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                return response.json()
            } else {
                throw new Error('Error')
            }
        }).then(data => {
            button.innerHTML = 'Register'
            button.disabled = false
            messageDiv.innerHTML = '<div class="alert alert-success">Registration was successful. Please check your email to verify it.</div>'
            card.style.display = 'none'
        }).catch(error => {
            button.innerHTML = 'Register'
            button.disabled = false
            messageDiv.innerHTML = '<div class="alert alert-danger">Something went wrong. Please try again.</div>'
        })
    })
</script>

@endsection