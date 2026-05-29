# Manual de Instalación Completo (Desde Cero) 🚀

Este manual contiene las instrucciones detalladas para instalar y configurar el proyecto **DPL-VITAE-Gestion** en una computadora con sistema operativo **Windows** completamente nueva.

---

## 📋 Requisitos Previos (Instalación de Herramientas)

Para ejecutar este proyecto, necesitarás instalar las siguientes herramientas en tu sistema. Sigue las instrucciones para cada una de ellas:

### 1. Git (Control de versiones)
1. Descarga el instalador de Git para Windows desde [git-scm.com](https://git-scm.com/).
2. Ejecuta el instalador. Puedes dejar todas las opciones por defecto.
3. Asegúrate de marcar la opción para usar Git desde la línea de comandos de Windows (ya viene seleccionada por defecto).
4. Verifica la instalación abriendo una terminal (PowerShell o CMD) y ejecutando:
   ```bash
   git --version
   ```

### 2. PHP 8.2 o Superior y Servidor Local
Para Windows, la manera más fácil de configurar PHP junto con una base de datos local es usando un entorno preconfigurado.
* **Opción Recomendada: Laragon** (Ligero y muy fácil para Laravel)
  1. Descarga e instala Laragon desde [laragon.org](https://laragon.org/).
  2. Al instalar, Laragon configurará automáticamente PHP, Apache y MySQL.
  3. Abre Laragon y haz clic en **"Iniciar Todo"**.
* **Opción Alternativa: XAMPP**
  1. Descarga e instala XAMPP con PHP 8.2+ desde [apachefriends.org](https://www.apachefriends.org/).
  2. Durante la instalación, asegúrate de activar PHP y MySQL.

> [!IMPORTANT]
> **Habilitar extensiones de PHP obligatorias:**
> Abre el archivo `php.ini` (en Laragon: clic derecho > PHP > php.ini; en XAMPP: panel de control > Config junto a Apache > php.ini) y asegúrate de quitar el punto y coma (`;`) al inicio de las siguientes líneas si están comentadas:
> - `extension=curl`
> - `extension=fileinfo`
> - `extension=mbstring`
> - `extension=openssl`
> - `extension=pdo_sqlite`
> - `extension=sqlite3`
>
> Guarda el archivo y reinicia tu servidor local.

### 3. Composer (Gestor de dependencias PHP)
1. Descarga el instalador `Composer-Setup.exe` desde [getcomposer.org](https://getcomposer.org/).
2. Ejecuta el instalador. Te preguntará la ubicación del ejecutable de PHP. Selecciona el `php.exe` de tu instalación de Laragon (`C:\laragon\bin\php\php-X.X.X\php.exe`) o XAMPP (`C:\xampp\php\php.exe`).
3. Termina la instalación y verifica en tu terminal con:
   ```bash
   composer --version
   ```

### 4. Node.js (Entorno de ejecución de JavaScript)
1. Descarga el instalador LTS de Node.js desde [nodejs.org](https://nodejs.org/).
2. Ejecuta el instalador con las opciones por defecto.
3. Verifica la instalación:
   ```bash
   node --version
   npm --version
   ```

### 5. PNPM (Gestor de paquetes rápido de JS)
Este proyecto utiliza `pnpm` en lugar de `npm`.
1. Abre tu terminal e instala `pnpm` globalmente ejecutando:
   ```bash
   npm install -g pnpm
   ```
2. Verifica la instalación:
   ```bash
   pnpm --version
   ```

### 6. Python 3.x (Opcional - Para entrenamiento de la IA)
El proyecto incluye un script de entrenamiento para predicción de tarifas (`entrenar_traslados.py`). Si deseas ejecutarlo:
1. Descarga e instala Python 3.10 o superior desde [python.org](https://www.python.org/).
2. **MUY IMPORTANTE**: En la primera pantalla del instalador, marca la casilla **"Add Python to PATH"** antes de dar clic en Instalar.
3. Verifica la instalación:
   ```bash
   python --version
   ```

---

## 🛠️ Configuración del Proyecto

Una vez que tengas todas las herramientas instaladas, sigue estos pasos para descargar y configurar el software:

### Paso 1: Clonar el Repositorio
Abre tu terminal, navega a la carpeta donde quieras almacenar tu proyecto (por ejemplo, `C:\Users\TuUsuario\Documents`) y ejecuta:
```bash
git clone https://github.com/JoaquinElorza/DPL-VITAE-Gestion.git
cd DPL-VITAE-Gestion
```

### Paso 2: Crear el archivo de entorno `.env`
Laravel utiliza un archivo de configuración llamado `.env` para almacenar variables secretas y de conexión.
1. Copia el archivo `.env.example` para crear el archivo `.env`:
   - **En CMD/PowerShell:**
     ```powershell
     copy .env.example .env
     ```
2. Abre el archivo `.env` recién creado en un editor de texto (como VS Code o Notepad).

### Paso 3: Configurar la Base de Datos
El proyecto está preparado para funcionar tanto con **SQLite** (una base de datos ligera guardada en un solo archivo) como con **MySQL/PostgreSQL**.

#### Opción A: SQLite (Más rápida y recomendada para pruebas locales)
1. Abre el `.env` y edita la sección de base de datos para que quede así:
   ```env
   DB_CONNECTION=sqlite
   # Puedes comentar las líneas de DB_HOST, DB_PORT, DB_USERNAME, etc.
   ```
2. En Laravel, si usas SQLite, por defecto buscará el archivo `database/database.sqlite`. Crea este archivo vacío:
   - **En PowerShell:**
     ```powershell
     New-Item -ItemType File -Path database/database.sqlite -Force
     ```
   - **En CMD:**
     ```cmd
     type nul > database\database.sqlite
     ```
   - *Nota:* Si tienes el archivo de base de datos preexistente en la raíz llamado `dplvitae` puedes indicarle su ruta absoluta en el `.env`:
     ```env
     DB_CONNECTION=sqlite
     DB_DATABASE=C:\Ruta\Completa\A\Tu\Proyecto\DPL-VITAE-Gestion\dplvitae
     ```

#### Opción B: MySQL o PostgreSQL
Si usas MySQL (a través de Laragon o XAMPP), configura los accesos en tu `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dplvitae
DB_USERNAME=root
DB_PASSWORD=TuContraseña
```
*Recuerda crear la base de datos vacía llamada `dplvitae` en tu gestor de base de datos (por ejemplo, phpMyAdmin o DBeaver) antes de continuar.*

### Paso 4: Instalar las dependencias de PHP (Composer)
Ejecuta el siguiente comando para instalar las librerías backend del framework Laravel:
```bash
composer install
```

### Paso 5: Generar la Clave de la Aplicación
Laravel necesita una clave de seguridad única para el cifrado de sesiones y datos. Generala con:
```bash
php artisan key:generate
```

### Paso 6: Ejecutar las Migraciones y Poblar la Base de Datos
Este paso creará las tablas necesarias en tu base de datos e insertará los registros de prueba (usuarios, ambulancias, padecimientos, etc.).
```bash
php artisan migrate:fresh --seed
```
*Si deseas además poblar la base de datos con los 500 registros simulados para las analíticas y minería de datos, ejecuta:*
```bash
php artisan db:seed --class=SimuladorMineriaSeeder
```

### Paso 7: Instalar dependencias Frontend
Instala los paquetes de Javascript y estilos necesarios:
```bash
pnpm install
```

---

## 🏃‍♂️ Ejecución del Programa

Para que la aplicación funcione, deben ejecutarse en paralelo el backend de PHP (Laravel) y el frontend de assets (Vite). Tienes dos métodos para hacerlo:

### Método A: Comando Unificado (Recomendado)
El archivo `composer.json` tiene programado un script que levanta el servidor, la cola de tareas y Vite a la vez en una sola consola:
```bash
composer run dev
```

### Método B: Consolas Separadas (Tradicional)
Si prefieres ver los logs detallados por separado, abre dos terminales diferentes en la raíz del proyecto:

* **Terminal 1 (Backend de Laravel):**
  ```bash
  php artisan serve
  ```
  *Esto iniciará el servidor web en `http://127.0.0.1:8000`.*

* **Terminal 2 (Compilador de Vite):**
  ```bash
  pnpm run dev
  ```
  *Esto compilará y actualizará dinámicamente el CSS, JavaScript y los componentes Livewire.*

* **Terminal 3 (Cola de trabajos en segundo plano - Opcional):**
  ```bash
  php artisan queue:listen
  ```

Abre tu navegador web e ingresa a: **[http://127.0.0.1:8000](http://127.0.0.1:8000)** para ver la aplicación funcionando.

---

## 🧠 Algoritmo de Minería de Datos e IA (Opcional)

Si necesitas utilizar el módulo de minería de datos y entrenar el modelo de regresión lineal para las tarifas:

### 1. Procesamiento de Minería (Limpieza de outliers y Clustering)
Ejecuta la limpieza estadística basada en Z-Score directamente desde Laravel:
```bash
php artisan mineria:procesar
```

### 2. Entrenamiento del Modelo con Python
El script `entrenar_traslados.py` entrena un modelo de regresión lineal y guarda los coeficientes en la tabla `modelo_traslados`.

* **Instalación de librerías de Python requeridas:**
  ```bash
  pip install pandas scikit-learn psycopg2-binary
  ```

> [!WARNING]
> **Compatibilidad con SQLite en Python:**
> El script `entrenar_traslados.py` por defecto intenta conectarse a una base de datos PostgreSQL en `localhost` mediante `psycopg2`.
> Si estás utilizando **SQLite**, debes adaptar el archivo de conexión. Aquí te mostramos cómo modificar el script:
>
> 1. Abre `entrenar_traslados.py`.
> 2. Reemplaza la conexión de `psycopg2` por `sqlite3`:
>    ```python
>    import sqlite3
>    # Reemplaza la conexión original por esta:
>    conexion = sqlite3.connect("dplvitae") # O la ruta absoluta de tu archivo SQLite
>    ```
> 3. En la línea de inserción final (alrededor de la línea 56), reemplaza los marcadores `%s` por `?` y ajusta `NOW()` a `datetime('now')` de la siguiente manera:
>    ```python
>    sql = """
>    INSERT INTO modelo_traslados (
>        b0, b_distancia, b_horas, b_oxigeno, b_padecimiento, b_ambulancia, created_at, updated_at
>    ) VALUES (?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))
>    """
>    cursor.execute(sql, (intercepto, coef_km, coef_horas, coef_oxigeno, coef_padecimiento, coef_ambulancia))
>    ```
> 4. Ejecuta el script:
>    ```bash
>    python entrenar_traslados.py
>    ```
