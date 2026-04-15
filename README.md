# 🚀 Instalación del proyecto - Gestión Curricular

Este documento explica cómo instalar y ejecutar el proyecto en un entorno local.

---

## 📌 Requisitos

Antes de iniciar, asegúrate de tener instalado:

- PHP >= 8.2
- Composer
- MySQL
- Git

---

## 1. Clonar el repositorio
- git clone https://github.com/jajimenez638-jj/gestion_curricular_prueba.git


## 2. Instalar dependencias

- composer install

## 3. Configurar archivo .env
- Copia el archivo .env.example y renombralo con .env
- Luego editarlo, con las siguientes parametros:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_curricular
DB_USERNAME=UsuarioCompartido
DB_PASSWORD=ClaveCompartida
```

## 4. Ejecutas seed
En la base de datos Mysql ejecutar el script de SQL que está en la raiz llamado seed_DB.sql
Esto incluye el usuario, base de datos, tablas y data inicial.

## 5. Ejecutas proyecto
En la carpeta donde esta el proyecto ejecuta php artisan serve desde la consola cmd.