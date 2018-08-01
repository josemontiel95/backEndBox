CREATE TABLE rol_usuario(
	id_rol_usuario INT(11) NOT NULL AUTO_INCREMENT,
	rol VARCHAR(30) NOT NULL,
	root VARCHAR(60) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,
	
	PRIMARY KEY(id_rol_usuario)

)ENGINE=INNODB;
ALTER TABLE rol_usuario AUTO_INCREMENT=1001;


CREATE TABLE laboratorio(
	id_laboratorio INT(11) NOT NULL AUTO_INCREMENT,
	laboratorio VARCHAR(40) NOT NULL,
	estado VARCHAR(30) NOT NULL,
	municipio VARCHAR(30)NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_laboratorio)
)ENGINE=INNODB;

ALTER TABLE laboratorio AUTO_INCREMENT=1001;

CREATE TABLE usuario (
	id_usuario INT(11) NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(30) NOT NULL,
	apellido VARCHAR(45) NOT NULL,
	laboratorio_id INT(11),
	nss VARCHAR(11),
	email VARCHAR(30) NOT NULL,
	fechaDeNac DATE NOT NULL,
	foto VARCHAR(120) NULL DEFAULT "null",
	rol_usuario_id INT(11),
	contrasena VARCHAR(128) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_usuario),

	FOREIGN KEY(rol_usuario_id) 
	REFERENCES rol_usuario(id_rol_usuario)
	ON DELETE SET NULL 
	ON UPDATE CASCADE,

	FOREIGN KEY(laboratorio_id)
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL 
	ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE usuario AUTO_INCREMENT=1001;

CREATE TABLE cliente(
	id_cliente INT(11) NOT NULL AUTO_INCREMENT,
	rfc VARCHAR(13) NOT NULL,
	razonSocial VARCHAR(140) NOT NULL,
	nombre VARCHAR(140) NOT NULL,
	direccion TEXT NOT NULL,
	email VARCHAR(30) NOT NULL,
	telefono VARCHAR(30) NOT NULL,
	foto VARCHAR(120) NULL DEFAULT "null",
	contrasena VARCHAR(128) NOT NULL,
	nombreContacto VARCHAR(40) NOT NULL,
	telefonoDeContacto VARCHAR(13) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_cliente)
)ENGINE=INNODB;
ALTER TABLE cliente AUTO_INCREMENT=1001;

CREATE TABLE concretera(
	id_concretera INT(11) NOT NULL AUTO_INCREMENT,
	concretera VARCHAR(40) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_concretera)
)ENGINE=INNODB;
ALTER TABLE concretera AUTO_INCREMENT=1001;




CREATE TABLE obra(
	id_obra INT(11) NOT NULL AUTO_INCREMENT,
	obra VARCHAR(40) NOT NULL,
	prefijo VARCHAR(4) NOT NULL,
	fechaDeCreacion DATE NOT NULL,
	descripcion TEXT,
	nombre_residente VARCHAR(50) NOT NULL,
	telefono_residente VARCHAR(15) NOT NULL,
	correo_residente VARCHAR(40) NOT NULL,
	cliente_id INT(11),
	concretera_id INT(11),
	tipo INT(11) NOT NULL,
	revenimiento DOUBLE,
	incertidumbre DOUBLE,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_obra),

	FOREIGN KEY(cliente_id)
	REFERENCES cliente(id_cliente)
	ON DELETE SET NULL 
	ON UPDATE CASCADE,

	FOREIGN KEY(concretera_id)
	REFERENCES concretera(id_concretera)

	ON DELETE SET NULL 
	ON UPDATE CASCADE
	
)ENGINE=INNODB;

ALTER TABLE obra AUTO_INCREMENT=1001;



//El lugar no deberia estar porque ya lo contempla la obra PENDIENTE
CREATE TABLE ordenDeServicio(
	id_ordenDeServicio INT(11) NOT NULL AUTO_INCREMENT,
	cotizacion_id INT(11),
	obra_id INT(11),
	actividades TEXT,
	condicionesTrabajo TEXT,
	jefe_brigada_id INT(11),
	fechaInicio DATE NOT NULL,
	fechaFin DATE NOT NULL,
	horaInicio TIME NOT NULL,
	horaFin TIME NOT NULL,
	observaciones TEXT,

	lugar VARCHAR(150) NOT NULL,
	jefa_lab_id INT(11),
	laboratorio_id INT(11),
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_ordenDeServicio),

	FOREIGN KEY(obra_id) 
	REFERENCES obra(id_obra)
	ON DELETE SET NULL ON UPDATE CASCADE,


	FOREIGN KEY(jefa_lab_id) 
	REFERENCES usuario(id_usuario)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(jefe_brigada_id) 
	REFERENCES usuario(id_usuario)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(laboratorio_id) 
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;
ALTER TABLE ordenDeServicio AUTO_INCREMENT=1001;

CREATE TABLE tecnicosDeOrden(
	tecnico_id INT(11),
	orden_id INT(11),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	FOREIGN KEY(tecnico_id) 
	REFERENCES usuario(id_usuario)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(orden_id) 
	REFERENCES ordenDeServicio(id_ordenDeServicio)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE herramienta_tipo(
	id_herramienta_tipo INT(11) NOT NULL AUTO_INCREMENT,
	tipo VARCHAR(30),
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_herramienta_tipo)
)ENGINE=INNODB;

ALTER TABLE herramienta_tipo AUTO_INCREMENT=1001;

CREATE TABLE herramientas(
	id_herramienta INT(11) NOT NULL AUTO_INCREMENT,
	herramienta_tipo_id INT(11),
	fechaDeCompra DATE NOT NULL,
	placas VARCHAR(15),
	condicion VARCHAR(10) NOT NULL,
	observaciones VARCHAR(180),
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_herramienta),

	FOREIGN KEY(herramienta_tipo_id)
	REFERENCES herramienta_tipo(id_herramienta_tipo)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE herramientas AUTO_INCREMENT=1001;


CREATE TABLE herramienta_ordenDeSevicio(
	ordenDeServicio_id INT(11),
	herramienta_id INT(11),
	fechaDevolucion DATE NOT NULL,
	status VARCHAR(10) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	FOREIGN KEY(herramienta_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(ordenDeServicio_id) 
	REFERENCES ordenDeServicio(id_ordenDeServicio)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;


CREATE TABLE formato(
	id_formato INT(11) NOT NULL AUTO_INCREMENT,
	formato VARCHAR(30) NOT NULL,
	titulo VARCHAR(80) NOT NULL,
	noCamposHeader INT NOT NULL,
	noCamposTecnico INT NOT NULL,
	noCamposMuestras INT NOT NULL,
	noCamposFooter INT NOT NULL,
	noFirmas INT NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_formato)

)ENGINE=INNODB;
ALTER TABLE formato AUTO_INCREMENT=1001;


CREATE TABLE tipo_campo(
	id_tipo_campo INT(11) NOT NULL AUTO_INCREMENT,
	tipo VARCHAR(30) NOT NULL,
	tamaño INT(11) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_tipo_campo)

)ENGINE=INNODB;
ALTER TABLE tipo_campo AUTO_INCREMENT=1001;


CREATE TABLE campos(
	id_campo INT(11) NOT NULL AUTO_INCREMENT,
	campo VARCHAR(30) NOT NULL,
	tipo_campo_id INT(11),
	formato_id INT(11),
	lugar INT(11) NOT NULL,
	foreignTable VARCHAR(40) NOT NULL,
	foreignColumn VARCHAR(40) NOT NULL,
	foreignID INT(11) NOT NULL,
	mathFormula VARCHAR(120) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_campo),

	FOREIGN KEY(formato_id) 
	REFERENCES formato(id_formato)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(tipo_campo_id) 
	REFERENCES tipo_campo(id_tipo_campo)
	ON DELETE SET NULL ON UPDATE CASCADE


)ENGINE=INNODB;
ALTER TABLE campos AUTO_INCREMENT=1001;



CREATE TABLE formatos_orden(
	id_formato_orden INT(11) NOT NULL AUTO_INCREMENT,
	formato_id INT(11),
	orden_id INT(11),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_formato_orden),

	FOREIGN KEY(formato_id) 
	REFERENCES formato(id_formato)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(orden_id) 
	REFERENCES ordenDeServicio(id_ordenDeServicio)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;
ALTER TABLE formatos_orden AUTO_INCREMENT=1001;

CREATE TABLE dato(
	id_dato INT(11) NOT NULL AUTO_INCREMENT,
	formatos_orden_id INT(11),
	dato_int INT(11) NOT NULL,
	dato_varchar VARCHAR(60) NOT NULL,
	campo_id INT(11),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_dato),

	FOREIGN KEY(formatos_orden_id) 
	REFERENCES formatos_orden(id_formato_orden)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(campo_id) 
	REFERENCES campos(id_campo)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE dato AUTO_INCREMENT=1001;



CREATE TABLE sesion(
	id_sesion INT(11) NOT NULL AUTO_INCREMENT,
	usuario_id INT(11),
	token VARCHAR(128) NOT NULL,
	consultasAlBack INT(11)  NOT NULL DEFAULT 1,
	
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_sesion),

	FOREIGN KEY(usuario_id) 
	REFERENCES usuario(id_usuario)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE sesion AUTO_INCREMENT=1001;



CREATE TABLE log(
	id_log INT(11) NOT NULL AUTO_INCREMENT,
	query TEXT,
	queryType VARCHAR(50),
	status VARCHAR(7),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_log)
)ENGINE=INNODB;



================
DROP TABLE dato;
DROP TABLE campos;
DROP TABLE tipo_campo;
DROP TABLE formatos_orden;
DROP TABLE formato;
DROP TABLE herramientas;
DROP TABLE herramienta_tipo;
DROP TABLE tecnicosDeOrden;
DROP TABLE herramienta_ordenDeSevicio;
DROP TABLE ordenDeServicio;
DROP TABLE obra;
DROP TABLE concretera;
DROP TABLE cliente;
DROP table sesion;
DROP TABLE usuario;
DROP TABLE laboratorio;
DROP TABLE rol_usuario;
DROP TABLE log;

================

INSERT INTO rol_usuario (rol, root) VALUES ("Administrador","administrador");
INSERT INTO rol_usuario (rol, root) VALUES ("Jefe de Laboratorio","jefeLaboratorio");

---			Pruebas			----
INSERT INTO rol_usuario (rol) VALUES ("Administrador");

INSERT INTO usuario(nombre) VALUES("Raul","Escobedo","");

INSERT INTO concretera (concretera) VALUES ("concretera1");



------------------------------
INSERT INTO usuario (nombre, apellido,laboratorio_id,nss, email, fechaDeNac, rol_usuario_id, contrasena) VALUES("mike", "soto",1001,12345678901, "maike.soto@lacocs.com","1993-08-26", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413");
INSERT INTO usuario (nombre, apellido,laboratorio_id,nss, email, fechaDeNac, rol_usuario_id, contrasena) VALUES("jose", "montiel",1001,12345678901, "jose.montiel@lacocs.com","1995-08-18", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413");
INSERT INTO usuario (nombre, apellido,laboratorio_id,nss, email, fechaDeNac, rol_usuario_id, contrasena) VALUES("marco", "cervantes",1001,12345678901, "marco.cervantes@lacocs.com","1995-01-27", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413");
INSERT INTO usuario (nombre, apellido,laboratorio_id,nss, email, fechaDeNac, rol_usuario_id, contrasena) VALUES("valerie", "bartsch",1001,12345678901, "valerie.bartsch@lacocs.com","1996-07-24", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413");
INSERT INTO usuario (nombre, apellido,laboratorio_id,nss, email, fechaDeNac, rol_usuario_id, contrasena) VALUES("Daniel", "Furlong",1001,12345678901, "daniel.furlong@lacocs.com","1997-10-23", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413");
INSERT INTO usuario (nombre, apellido,laboratorio_id,nss, email, fechaDeNac, rol_usuario_id, contrasena) VALUES("Bryan", "Tlatelp",1001,12345678901, "bryan@lacocs.com","1997-10-23", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413");


INSERT INTO laboratorio (laboratorio,estado,municipio) VALUES ("Nueva Aurora","Puebla","Puebla");
INSERT INTO laboratorio (laboratorio,estado,municipio) VALUES ("Simi","Puebla","Puebla");


INSERT INTO herramienta_tipo (tipo) VALUES ("cubo");
INSERT INTO herramienta_tipo (tipo) VALUES ("cilindro");
INSERT INTO herramienta_tipo (tipo) VALUES ("vigas");

INSERT INTO herramientas(herramienta_tipo_id,fechaDeCompra,placas,condicion) VALUES (1001,2000-10-10,"qwe-12-45",8);
INSERT INTO herramientas(herramienta_tipo_id,fechaDeCompra,placas,condicion) VALUES (1001,2000-10-10,"lde-12-56",5);



INSERT INTO obra(obra,prefijo,fechaDeCreacion,descripcion,cliente_id,concretera_id,tipo,revenimiento,incertidumbre) VALUES ("obra1","prefijo1","2000-10-10","descripcion1",1003,1001,5,12,14);
INSERT INTO obra(obra,prefijo,fechaDeCreacion,descripcion,cliente_id,concretera_id,tipo,revenimiento,incertidumbre) VALUES ("obra2","pre2","2000-10-10","descripcion2",1003,1001,6,9,14);

[10:22, 20/7/2018] +52 1 222 578 0650: INSERT INTO concretera (concretera) VALUES("Cruz Azul");
[10:28, 20/7/2018] +52 1 222 578 0650: INSERT INTO laboratorio (laboratorio) VALUES("CDMX");
[10:28, 20/7/2018] +52 1 222 578 0650: INSERT INTO concretera (concretera) VALUES("Apasco");


INSERT INTO ordenDeServicio(cotizacion_id,obra_id,actividades,condicionesTrabajo,jefe_brigada_id,fechaInicio,fechaFin,horaInicio,horaFin,observaciones) VALUES (1001,1001,"actividades1","condiciones1",1007,"2002-12-12","2003-12-12","15:32","15:32","observaciones1");


---------------------------

INSERT INTO usuario (nombre, apellido, email, fechaDeNac, rol_usuario_id, contraseña) VALUES("mike", "soto", "maike.soto@lacocs.com","1993-08-26", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413")

select id_usuario,nombre,rol,laboratorio from usuario,laboratorio,rol_usuario where id_usuario=id_rol_usuario AND laboratorio_id=id_laboratorio;