<div class="authentication-wrapper authentication-cover">
  <div class="authentication-inner row m-0">
    
    <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-5" style="background-color: #ffffff;">
      <div class="w-100 d-flex justify-content-center">
        <img src="{{ asset('assets/img/icono-login.jpg') }}" class="img-fluid" alt="Logo DPL Vitae" style="max-width: 65%; height: auto; object-fit: contain;">
      </div>
    </div>

    <div class="card col-12 col-lg-5 col-xl-4 m-0 p-0 shadow-none border-start border-light">
      <div class="d-flex align-items-center authentication-bg p-sm-12 p-6 h-100 bg-white">
        <div class="w-px-400 mx-auto mt-sm-12 mt-8">
          {{ $slot }}
        </div>
      </div>
    </div>
    
  </div>
</div>