HLC TO06 AMM

# Dominio

http://moya1987.es/alumnosAMM

# Características del alojamiento usado

VPS en Digital Ocean.
Droplet con LEMP preinstalado 2 CPUs & 2 GB RAM & 60 GB SSD

# Configuración de la aplicación en el servidor

1. Nos conectamos a nuestro servidor.
2. Entramos en la carpeta cd /var/www/html
3. sudo wget https://github.com/moya1987/HLCAMMT06/archive/refs/heads/main.zip
5. sudo unzip main.zip
6. sudo rm main.zip
7. sudo mv HLCAMMT06-main alumnosAMM
8. sudo chown -R www-data:www-data alumnosAMM
9. Pasamos a la parte de la base de datos. Para ello en la carpeta data tenemos el fichero migracion sql. Podemos ejecutar el script en mysql o ejecutarlo manualmente:

CREATE DATABASE alumnosAMM;

use alumnosAMM;

CREATE TABLE alumnos (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(30) NOT NULL,
  apellido VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL,
  telefono INT (15) NOT NULL,
  edad INT(3),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notas (
  id int unsigned NOT NULL AUTO_INCREMENT,
  id_alumno int unsigned NOT NULL,
  referencia varchar(50) DEFAULT NULL,
  nota double DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_relacion (id_alumno),
  CONSTRAINT fk_relacion FOREIGN KEY (id_alumno) REFERENCES alumnos (id) ON DELETE CASCADE ON UPDATE CASCADE
);


10. Ahora nos centramos en el usuario con el que vamos a acceder a la base de datos. Los datos son los siguientes:
 


<?php

return [
  'db' => [
    'host' => 'localhost',
    'user' => 'alumnomaster',
    'pass' => '1234',
    'name' => 'alumnosAMM',
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ]
];
.


11. Creamos el usuario 'alumnomaster' con la contraseña '1234'. Seguimos estos pasos:
CREATE USER 'alumnomaster'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON alumnosAMM.* TO 'alumnomaster'@'localhost';

.



12. Accedemos al moya1987.es/alumnosAMM


.


FIN
