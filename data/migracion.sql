
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
