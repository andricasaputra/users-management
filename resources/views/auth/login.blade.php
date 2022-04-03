<!DOCTYPE html>
<html lang="en">

@include('layouts/header')

<body class="bg-default">
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
      <div class="container px-4">
        <a class="navbar-brand" href="../index.html">
          <img src="./assets/img/brand/white.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-collapse-main">
          <!-- Collapse header -->
          <div class="navbar-collapse-header d-md-none">
            <div class="row">
              <div class="col-6 collapse-brand">
                <a href="../index.html">
                  <img src="./assets/img/brand/blue.png">
                </a>
              </div>
              <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>
          <!-- Navbar items -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="http://sumbawa.karantina.pertanian.go.id/">
                <i class="ni ni-planet"></i>
                <span class="nav-link-inner--text">Website</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="http://e-office.eqosistem.com/login">
                <i class="ni ni-circle-08"></i>
                <span class="nav-link-inner--text">E-office</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="http://ipnbk.eqosistem.com/">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">E-IPNBK</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="http://lab.eqosistem.com/">
                <i class="ni ni-single-02"></i>
                <span class="nav-link-inner--text">Sile</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8">
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                @include('includes.message')
                <small>Please login with your E-Personal account</small>
              </div>
              <form role="form" id="submitForm">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input name="username" id="username" class="form-control" placeholder="Username" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input name="password" id="password" class="form-control" placeholder="Password" type="password">
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span class="text-muted">Remember me</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" id="login-btn" class="btn btn-primary my-4">Sign in</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  @include('layouts/footer')

  <script>

    const submitLogin = async (e) =>
    {
        e.preventDefault();

        const button = document.querySelector('#login-btn');
        button.innerHTML = 'Sign you in...';
        button.disabled = true;

        const body = {
            username: document.querySelector('#username').value,
            password : document.querySelector('#password').value
        };

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        };

        try{
            const response = await fetch('{{ route('login') }}', {
                method: "POST",
                headers: headers,
                body: JSON.stringify(body)
            });

            const data = await response.json();

            if (response.ok) {
                window.location = data.redirect;
            }else if(response.status == 401) {
               throw new Error('Username atau password salah'); 
            }else {
               throw new Error(response.statusText); 
            }

        }catch(err){
            const container = document.querySelector('#message-flash');
            container.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
            button.innerHTML = 'Sign in';
            button.disabled = false;
        }
    }

    document.querySelector('#submitForm').addEventListener('submit', submitLogin);

  </script>

  <script>
    function getQueryParams()
    {
      return new Proxy(new URLSearchParams(window.location.search), {
      get: (searchParams, prop) => searchParams.get(prop),
      });
    }

    const autoLogin = async () =>
          {
    try{

      const params = getQueryParams();

      let _sk = params._sk; 

      console.log(_sk);
      const headers = {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      };

      const response = await fetch(`{{ route('sso.login') }}/${_sk}`, {
          method: "POST",
          headers: headers
      });

      const data = await response.json();

      if (response.ok) {
          window.location = data.redirect;
      }else if(response.status == 401) {
         throw new Error('Username atau password salah'); 
      }else {
         throw new Error(response.statusText); 
          }

      }catch(err){
          
        container.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
          
      }
    }

    const params = getQueryParams();

    let _sk = params._sk; 

    if (_sk && '{{ !session()->has('logout') }}') autoLogin();
  </script>

  </body>
</html>