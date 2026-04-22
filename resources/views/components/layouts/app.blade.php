<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-menu-collapsed" data-base-url="{{url('/')}}" data-framework="laravel">
  <head>
    @include('partials.head')
  </head>

  <body>

    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Layout Content -->
        <x-layouts.menu.vertical :title="$title ?? null"></x-layouts.menu.vertical>
        <!--/ Layout Content -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <x-layouts.navbar.default :title="$title ?? null"></x-layouts.navbar.default>
          <!--/ Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              {{ $slot }}
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <x-layouts.footer.default :title="$title ?? null"></x-layouts.footer.default>
            <!--/ Footer -->
            <div class="content-backdrop fade"></div>
            <!-- / Content wrapper -->
          </div>
        </div>
        <!-- / Layout page -->
      </div>
    </div>

    <!-- Modal Editar Perfil (global) -->
    @auth
        <livewire:editar-perfil />
    @endauth

    <!-- Include Scripts -->
    @include('partials.scripts')
    <!-- / Include Scripts -->

    <!-- Backdrop para móvil -->
    <div class="menu-backdrop" id="menu-backdrop"></div>

    <script>
      (function () {
        var html = document.documentElement;

        function isMobile() { return window.innerWidth < 1200; }

        function isExpanded() {
          return isMobile()
            ? html.classList.contains('layout-menu-expanded')
            : !html.classList.contains('layout-menu-collapsed');
        }

        function syncIcon() {
          var btn = document.getElementById('sidebar-close-btn');
          if (btn) btn.classList.toggle('is-open', isExpanded());
        }

        function toggleMenu() {
          html.classList.add('layout-transitioning');
          if (isMobile()) {
            html.classList.toggle('layout-menu-expanded');
            var bd = document.getElementById('menu-backdrop');
            if (bd) bd.classList.toggle('show', html.classList.contains('layout-menu-expanded'));
          } else {
            html.classList.toggle('layout-menu-collapsed');
          }
          setTimeout(function () { html.classList.remove('layout-transitioning'); }, 300);
          syncIcon();
        }

        function bind() {
          var btn = document.getElementById('sidebar-close-btn');
          if (btn) btn.onclick = toggleMenu;

          var navBtn = document.getElementById('navbar-hamburger-btn');
          if (navBtn) navBtn.onclick = toggleMenu;

          var bd = document.getElementById('menu-backdrop');
          if (bd) bd.onclick = function () {
            html.classList.remove('layout-menu-expanded');
            bd.classList.remove('show');
            syncIcon();
          };

          syncIcon();
        }

        document.addEventListener('DOMContentLoaded', bind);
        document.addEventListener('livewire:navigated', bind);
      })();
    </script>
  </body>
</html>
