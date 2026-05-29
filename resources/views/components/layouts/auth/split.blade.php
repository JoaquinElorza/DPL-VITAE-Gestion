<div class="authentication-wrapper authentication-cover">
  <div class="authentication-inner row m-0">
    <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
      <div class="w-100 d-flex justify-content-center">
        <div>
          <a href="{{url('/')}}" class="app-brand auth-cover-brand gap-2"><x-app-logo /></a>
          @php
              $empresa = \App\Models\Empresa::first();
          @endphp
          @if($empresa && $empresa->logo_nombre)
              <img src="{{ asset('storage/' . $empresa->logo_nombre) }}" class="img-fluid" alt="{{ $empresa->nombre }}" style="max-height: 400px; object-fit: contain;"/>
          @else
              <i class="bx bx-ambulance" style="font-size: 15rem; color: #393395;"></i>
          @endif
        </div>
      </div>
    </div>
    <div class="card col-12 col-lg-5 col-xl-4">
      <div class="d-flex align-items-center authentication-bg p-sm-12 p-6 h-100">
        <div class="w-px-400 mx-auto mt-sm-12 mt-8">
          {{ $slot }}
        </div>
      </div>
    </div>
    </div>
</div>