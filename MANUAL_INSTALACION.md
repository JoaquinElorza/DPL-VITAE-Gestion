# Manual de Instalación Rápida 🚀

Este manual contiene los pasos esenciales para instalar, configurar y ejecutar el proyecto **DPL-VITAE-Gestion** en una computadora con Windows completamente nueva.

---

## 📋 1. Requisitos Previos (Instalación de Herramientas)

Instala estas 4 herramientas básicas en tu sistema:

1. **Git**
   * Descárgalo e instálalo desde [git-scm.com](https://git-scm.com/). (Deja las opciones por defecto).

2. **PHP (8.2 o superior)**
   * Descarga el instalador de **XAMPP** (con PHP 8.2+) desde [apachefriends.org](https://www.apachefriends.org/).
   * Durante la instalación, solo necesitas marcar **Apache** y **PHP** (y MySQL si planeas usarlo en el futuro). Esto instalará PHP y lo configurará en tu sistema.
   * *Asegúrate de que PHP esté en las variables de entorno (PATH) de Windows.*

3. **Composer (Gestor de dependencias de PHP)**
   * Descárgalo e instálalo desde [getcomposer.org](https://getcomposer.org/). El instalador detectará automáticamente tu ruta de PHP (ej. `C:\xampp\php\php.exe`).

4. **Node.js & PNPM (Frontend)**
   * Descarga e instala la versión LTS de Node.js desde [nodejs.org](https://nodejs.org/).
   * Una vez instalado, abre una terminal y ejecuta el siguiente comando para instalar **pnpm** de forma global:
     ```powershell
     npm install -g pnpm
     ```

---

## 🛠️ 2. Clonación y Configuración del Proyecto

Abre una terminal (PowerShell o CMD) y ejecuta los siguientes comandos en orden:

### Paso 1: Clonar el repositorio
```bash
git clone https://github.com/JoaquinElorza/DPL-VITAE-Gestion.git
cd DPL-VITAE-Gestion
```

### Paso 2: Crear y configurar el archivo `.env`
1. Copia la plantilla de configuración:
   ```powershell
   copy .env.example .env
   ```
2. Por defecto, el proyecto está configurado para usar **SQLite** (una base de datos ligera guardada en un archivo local, sin necesidad de configurar servidores de bases de datos complejos). Abre el archivo `.env` en un editor de texto y asegúrate de tener configurado lo siguiente:
   ```env
   DB_CONNECTION=sqlite
   ```
3. Crea el archivo de base de datos vacío donde Laravel guardará la información:
   * **En PowerShell:**
     ```powershell
     New-Item -ItemType File -Path database/database.sqlite -Force
     ```
   * **En CMD:**
     ```cmd
     type nul > database\database.sqlite
     ```

---

## 📦 3. Instalación de Dependencias e Inicialización

Ejecuta esta secuencia de comandos en tu terminal para preparar todo el proyecto:

```bash
# 1. Instalar librerías del backend (Laravel)
composer install

# 2. Generar clave única de seguridad de la aplicación
php artisan key:generate

# 3. Crear tablas de base de datos e insertar los datos de prueba (seeders)
php artisan migrate:fresh --seed

# 4. Instalar paquetes de javascript y estilos frontend
pnpm install
```

---

## 🏃‍♂️ 4. Ejecución del Programa

¡Todo listo! Para iniciar la aplicación completa (servidor de Laravel, cola de procesamiento y Vite para los estilos/scripts en tiempo real) ejecuta un solo comando unificado en tu terminal:

```bash
composer run dev
```

Abre tu navegador e ingresa a: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**. ¡El programa ya estará corriendo con éxito!
