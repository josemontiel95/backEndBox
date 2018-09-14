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
	calle VARCHAR(40) NOT NULL,
	noExt VARCHAR(10) NOT NULL,
	noInt VARCHAR(10) NOT NULL,
	col VARCHAR(25) NOT NULL,
	municipio VARCHAR(50) NOT NULL,
	estado VARCHAR(30) NOT NULL,
	direccion TEXT NOT NULL,
	email VARCHAR(30) NOT NULL,
	telefono VARCHAR(30) NOT NULL,
	foto VARCHAR(120) NULL DEFAULT "null",
	contrasena VARCHAR(128) NOT NULL,
	nombreContacto VARCHAR(40) NOT NULL,
	telefonoDeContacto VARCHAR(13) NOT NULL,
	laboratorio_id INT(11),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_cliente),

	FOREIGN KEY(laboratorio_id) 
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL 
	ON UPDATE CASCADE
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
	localizacion TEXT NOT NULL,
	nombre_residente VARCHAR(50) NOT NULL,
	telefono_residente VARCHAR(15) NOT NULL,
	correo_residente VARCHAR(40) NOT NULL,
	cliente_id INT(11),
	concretera_id INT(11),
	tipo INT(11) NOT NULL,
	revenimiento DOUBLE,
	incertidumbre DOUBLE,
	cotizacion VARCHAR(15) NOT NULL,
	consecutivoProbeta INT NOT NULL DEFAULT 1,
	consecutivoDocumentos INT NOT NULL DEFAULT 1,
	laboratorio_id INT(11),


	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_obra),

	FOREIGN KEY(cliente_id)
	REFERENCES cliente(id_cliente)
	ON DELETE SET NULL 
	ON UPDATE CASCADE,

	FOREIGN KEY(laboratorio_id) 
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL 
	ON UPDATE CASCADE,

	FOREIGN KEY(concretera_id)
	REFERENCES concretera(id_concretera)
	ON DELETE SET NULL 
	ON UPDATE CASCADE
	
)ENGINE=INNODB;

ALTER TABLE obra AUTO_INCREMENT=1001;



INSERT INTO obra(obra,prefijo,fechaDeCreacion,descripcion,localizacion,nombre_residente,telefono_residente,correo_residente,cliente_id,concretera_id,tipo,revenimiento,incertidumbre) 
VALUES("obra1","prefijo1","fechaDeCreacion1","descripcion1","localizacion1","nombre_residente1",1234,"correo_residente1",1001,1001,1,123,123);



//El lugar no deberia estar porque ya lo contempla la obra PENDIENTE
CREATE TABLE ordenDeTrabajo(
	id_ordenDeTrabajo INT(11) NOT NULL AUTO_INCREMENT,
	area VARCHAR(20) NOT NULL,
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
	laboratorio_id INT(11),
	jefa_lab_id INT(11),

	status int(11) NOT NULL DEFAULT 0,
	
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_ordenDeTrabajo),

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
ALTER TABLE ordenDeTrabajo AUTO_INCREMENT=1001;

ALTER TABLE ordenDeTrabajo 
ADD COLUMN  status int(11) NOT NULL DEFAULT 0;


INSERT INTO ordenDeTrabajo(cotizacion_id,obra_id,actividades,condicionesTrabajo,jefe_brigada_id,fechaInicio,fechaFin,horaInicio,horaFin,observaciones,lugar,jefa_lab_id,laboratorio_id)
VALUES ("cotizacion_id1",1001,"actividades1","condicionesTrabajo1",1029,"fechaInicio1","fechaFin1","horaInicio1","horaFin1","observaciones1","lugar1",1028,1001);


//MODIFICAR LOS CA

CREATE TABLE tecnicos_ordenDeTrabajo(
	id_tecnicos_ordenDeTrabajo INT(11) NOT NULL AUTO_INCREMENT,
	tecnico_id INT(11),
	ordenDeTrabajo_id INT(11),
	asistencias INT NOT NULL DEFAULT 0,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_tecnicos_ordenDeTrabajo),

	FOREIGN KEY(tecnico_id) 
	REFERENCES usuario(id_usuario)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(ordenDeTrabajo_id) 
	REFERENCES ordenDeTrabajo(id_ordenDeTrabajo)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;
ALTER TABLE tecnicos_ordenDeTrabajo AUTO_INCREMENT=1001;

CREATE TABLE herramienta_tipo(
	id_herramienta_tipo INT(11) NOT NULL AUTO_INCREMENT,
	tipo VARCHAR(30),
	asignableenOrdenDeTrabajo INT(11) DEFAULT 1,
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
	laboratorio_id INT(11),

	PRIMARY KEY(id_herramienta),

	FOREIGN KEY(laboratorio_id) 
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(herramienta_tipo_id)
	REFERENCES herramienta_tipo(id_herramienta_tipo)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE herramientas AUTO_INCREMENT=1001;



CREATE TABLE herramienta_ordenDeTrabajo(
	ordenDeTrabajo_id INT(11),
	herramienta_id INT(11),
	fechaDevolucion DATE NOT NULL,
	status VARCHAR(10) NOT NULL,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	FOREIGN KEY(herramienta_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(ordenDeTrabajo_id) 
	REFERENCES ordenDeTrabajo(id_ordenDeTrabajo)
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
	REFERENCES ordenDeTrabajo_id(id_ordenDeTrabajo_id)
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


//PENDIENTE
CREATE TABLE formatoCampo(
	id_formatoCampo INT(11) NOT NULL AUTO_INCREMENT,
	informeNo VARCHAR(30) NOT NULL,
	ordenDeTrabajo_id INT(11),
	observaciones TEXT,
	tipo VARCHAR(20) NOT NULL,

	tipoConcreto VARCHAR(5),
	prueba1 INT NOT NULL,
	prueba2 INT NOT NULL,
	prueba3 INT NOT NULL,
	prueba4 INT NOT NULL,

	cono_id INT(11),
	varilla_id INT(11),
	flexometro_id INT(11),
	termometro_id INT(11),

	status int(11) NOT NULL,

	posInicial POINT NOT NULL,
	posFinal POINT,


	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,


	PRIMARY KEY(id_formatoCampo),

	FOREIGN KEY(ordenDeTrabajo_id) 
	REFERENCES ordenDeTrabajo(id_ordenDeTrabajo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(cono_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(varilla_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(flexometro_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(termometro_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE


)ENGINE=INNODB;
ALTER TABLE formatoCampo AUTO_INCREMENT=1001;

ALTER TABLE formatoCampo 
ADD COLUMN  status int(11) NOT NULL;


CREATE TABLE registrosCampo(
	id_registrosCampo INT(11) NOT NULL AUTO_INCREMENT,
	formatoCampo_id INT(11),
	claveEspecimen VARCHAR(50),
	fecha DATE,
	fprima VARCHAR(10),
	revProyecto INT, 
	revObra INT,
	tamAgregado INT,
	volumen FLOAT(5.2),
	unidad VARCHAR(20),
	horaMuestreo TIME,
	tempMuestreo INT,
	tempRecoleccion INT,
	localizacion TEXT,
	diasEnsaye INT,
	herramienta_id INT(11),
	consecutivoProbeta INT NOT NULL,

	status INT NOT NULL DEFAULT 0,




	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY (id_registrosCampo),

	FOREIGN KEY(herramienta_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;

ALTER TABLE registrosCampo AUTO_INCREMENT=1001;

ALTER TABLE registrosCampo ADD diasEnsaye INT;

CREATE TABLE formatoRegistroRev(
	id_formatoRegistroRev INT(11) NOT NULL AUTO_INCREMENT,
	regNo VARCHAR(30) NOT NULL,
	ordenDeTrabajo_id INT(11),
	localizacion TEXT NOT NULL,
	observaciones TEXT,

	cono_id INT(11),
	varilla_id INT(11),
	flexometro_id INT(11),

	posInicial POINT NOT NULL,
	posFinal POINT,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_formatoRegistroRev),

	FOREIGN KEY(ordenDeTrabajo_id) 
	REFERENCES ordenDeTrabajo(id_ordenDeTrabajo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(cono_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(varilla_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(flexometro_id) 
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;

ALTER TABLE formatoRegistroRev AUTO_INCREMENT=1001;


CREATE TABLE registrosRev(
	id_registrosRev INT(11) NOT NULL AUTO_INCREMENT,
	formatoRegistroRev_id INT(11),
	fecha DATE,
	revProyecto INT,
	revObtenido INT,
	tamAgregado INT,
	idenConcreto VARCHAR(20),
	volumen FLOAT(5.2),
	horaDeterminacion TIME,
	unidad VARCHAR(20),
	concretera_id INT,
	remisionNo INT,
	horaSalida TIME,
	horaLlegada TIME,

	status INT NOT NULL DEFAULT 0,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_registrosRev),

	FOREIGN KEY(concretera_id)
	REFERENCES concretera(id_concretera)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;

ALTER TABLE registrosRev AUTO_INCREMENT=1001;


CREATE TABLE formatoEnsayo(
	id_formatoEnsayo INT(11) NOT NULL AUTO_INCREMENT,
	fecha DATE NOT NULL,
	formatoCampo_id INT(11),

)ENGINE=INNODB;


CREATE TABLE listaAsistencia(
	id_listaAsistencia INT(11) NOT NULL AUTO_INCREMENT,
	tecnicos_ordenDeTrabajo_id INT(11),
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_listaAsistencia),

	FOREIGN KEY(tecnicos_ordenDeTrabajo_id)
	REFERENCES tecnicos_ordenDeTrabajo(id_tecnicos_ordenDeTrabajo)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE listaAsistencia AUTO_INCREMENT=1001;


CREATE TABLE ensayoCilindro(
	id_ensayoCilindro INT(11) NOT NULL AUTO_INCREMENT,
	registrosCampo_id INT(11),
	formatoCampo_id INT(11),
	footerEnsayo_id INT(11),

	peso FLOAT(5.3) NOT NULL,
	d1 FLOAT(5.3) NOT NULL,
	d2 FLOAT(5.3) NOT NULL,
	h1 FLOAT(5.3) NOT NULL,
	h2 FLOAT(5.3) NOT NULL,
	carga FLOAT(7.3) NOT NULL,
	falla INT(11) NOT NULL,
	fecha DATE,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_ensayoCilindro),

	FOREIGN KEY(registrosCampo_id)
	REFERENCES registrosCampo(id_registrosCampo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(formatoCampo_id)
	REFERENCES formatoCampo(id_formatoCampo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(footerEnsayo_id)
	REFERENCES footerEnsayo(id_footerEnsayo)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;

ALTER TABLE ensayoCilindro AUTO_INCREMENT=1001;
ALTER TABLE ensayoCilindro ADD status INT NOT NULL DEFAULT 0;


CREATE TABLE ensayoCubo(
	id_ensayoCubo INT(11) NOT NULL AUTO_INCREMENT,
	registrosCampo_id INT(11),
	formatoCampo_id INT(11),
	footerEnsayo_id INT(11),

	l1 FLOAT(5.3),
	l2 FLOAT(5.3),
	carga FLOAT(5.3),
	falla INT(11) NOT NULL,
	fecha DATE,

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_ensayoCubo),

	FOREIGN KEY(registrosCampo_id)
	REFERENCES registrosCampo(id_registrosCampo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(formatoCampo_id)
	REFERENCES formatoCampo(id_formatoCampo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(footerEnsayo_id)
	REFERENCES footerEnsayo(id_footerEnsayo)
	ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;

ALTER TABLE ensayoCubo AUTO_INCREMENT=1001;
ALTER TABLE ensayoCubo ADD falla INT(11) NOT NULL;

CREATE TABLE ensayoViga(
	id_ensayoViga INT(11) NOT NULL AUTO_INCREMENT,
	registrosCampo_id INT(11),
	formatoCampo_id INT(11),
	footerEnsayo_id INT(11),

	condiciones VARCHAR(30) NOT NULL,
	lijado VARCHAR(30) NOT NULL,
	cuero VARCHAR(30) NOT NULL,
	ancho1 FLOAT(5.3),
	ancho2 FLOAT(5.3),
 	per1 FLOAT(5.3),
 	per2 FLOAT(5.3),
 	l1 FLOAT(5.3),
 	l2 FLOAT(5.3),
 	l3 FLOAT(5.3),
 	disApoyo FLOAT(5.3),
 	disCarga FLOAT(5.3),
 	carga FLOAT(5.3),
 	defectos VARCHAR(20),
 	fecha DATE,
	
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_ensayoViga),

	FOREIGN KEY(registrosCampo_id)
	REFERENCES registrosCampo(id_registrosCampo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(formatoCampo_id)
	REFERENCES formatoCampo(id_formatoCampo)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(footerEnsayo_id)
	REFERENCES footerEnsayo(id_footerEnsayo)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;

ALTER TABLE ensayoViga AUTO_INCREMENT=1001;
ALTER TABLE ensayoViga ADD defectos VARCHAR(20);


CREATE TABLE footerEnsayo(
	id_footerEnsayo INT(11) NOT NULL AUTO_INCREMENT,
	encargado_id INT(11),


	buscula_id INT(11),
	regVerFle_id INT(11),
	prensa_id INT(11),
	tipo VARCHAR(20) NOT NULL,
	observaciones VARCHAR(30),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_footerEnsayo),

	FOREIGN KEY(buscula_id)
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(regVerFle_id)
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(prensa_id)
	REFERENCES herramientas(id_herramienta)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(encargado_id)
	REFERENCES usuario(id_usuario)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE footerEnsayo AUTO_INCREMENT=1001;

CREATE TABLE laboratorio_cliente(
	id_laboratorio_cliente INT(11) NOT NULL AUTO_INCREMENT,
	laboratorio_id INT(11),
	cliente_id INT(11),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_laboratorio_cliente),

	FOREIGN KEY(laboratorio_id) 
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(cliente_id) 
	REFERENCES cliente(id_cliente)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE laboratorio_cliente AUTO_INCREMENT=1001;

CREATE TABLE ticket(
	id_ticket INT(11) NOT NULL AUTO_INCREMENT,
	creador_id INT(11),
	prioridad INT(11),
	status INT(11),
	tipo INT(11),
	grupo_id INT(11),
	user_id INT(11),
	threadsNo INT(11),

	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_laboratorio_cliente),

	FOREIGN KEY(laboratorio_id) 
	REFERENCES laboratorio(id_laboratorio)
	ON DELETE SET NULL ON UPDATE CASCADE,

	FOREIGN KEY(cliente_id) 
	REFERENCES cliente(id_cliente)
	ON DELETE SET NULL ON UPDATE CASCADE

)ENGINE=INNODB;
ALTER TABLE ticket AUTO_INCREMENT=1001;


DROP TABLE laboratorio_cliente;
================
DROP TABLE dato;
DROP TABLE campos;
DROP TABLE tipo_campo;
DROP TABLE formatos_orden;
DROP TABLE formato;
DROP TABLE formatoCampo;
DROP TABLE herramientas;
DROP TABLE herramienta_tipo;
DROP TABLE tecnicosDeOrden;


DROP TABLE registrosCampo;


DROP TABLE ordenDeTrabajo;
DROP TABLE obra;
DROP TABLE concretera;
DROP TABLE cliente;
DROP table sesion;
DROP TABLE usuario;
DROP TABLE laboratorio;
DROP TABLE rol_usuario;
DROP TABLE log;


--INTENTO DE "SYSTEMSTATUS"

CREATE TABLE systemstatus(
	id_systemstatus INT(11) NOT NULL AUTO_INCREMENT,

	cch_def_prueba1 INT NOT NULL,
	cch_def_prueba2 INT NOT NULL,
	cch_def_prueba3 INT NOT NULL,
	cch_def_prueba4 INT NOT NULL,
	ensayo_def_buscula_id INT NOT NULL,
	ensayo_def_regVerFle_id INT NOT NULL,
	ensayo_def_prensa_id INT NOT NULL,
	ensayo_def_observaciones VARCHAR(30),
	ensayo_def_pi INT NOT NULL,
	ensayo_def_distanciaApoyos INT NOT NULL,
	ensayo_def_kN INT NOT NULL,
	ensayo_def_divisorKn INT NOT NULL,
	maxNoOfRegistrosCCH INT NOT NULL,
	multiplosNoOfRegistrosCCH INT NOT NULL,

	ensayo_def_MPa INT NOT NULL,
	createdON TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	lastEditedON TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	active INT NOT NULL DEFAULT 1,

	PRIMARY KEY(id_systemstatus)

)ENGINE = INNODB;
ALTER TABLE systemstatus AUTO_INCREMENT=1001;

ALTER TABLE systemstatus ADD ensayo_def_buscula_id INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_regVerFle_id INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_prensa_id INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_observaciones VARCHAR(30);
ALTER TABLE systemstatus ADD ensayo_def_pi INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_distanciaApoyos INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_kN INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_MPa INT NOT NULL;
ALTER TABLE systemstatus ADD ensayo_def_divisorKn INT NOT NULL;
ALTER TABLE systemstatus ADD maxNoOfRegistrosCCH INT NOT NULL;
ALTER TABLE systemstatus ADD multiplosNoOfRegistrosCCH INT NOT NULL;


INSERT INTO 

	systemstatus(
			cch_def_prueba1,
			cch_def_prueba2,
			cch_def_prueba3,
			cch_def_prueba4,
			ensayo_def_buscula_id,
			ensayo_def_regVerFle_id,
			ensayo_def_prensa_id,
			ensayo_def_observaciones,
			ensayo_def_pi,
			ensayo_def_distanciaApoyos,
			ensayo_def_kN,
			ensayo_def_MPa,
			ensayo_def_divisorKn
		)

VALUES(
	7,
	14,
	28,
	28,
	1005,
	1006,
	1008,
	"NO HAY OBSERVACIONES",
	3.1416,
	'45',
	101.971,
	10.2,
	1000
)


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


INSERT INTO ordenDeTrabajo(cotizacion_id,obra_id,actividades,condicionesTrabajo,jefe_brigada_id,fechaInicio,fechaFin,horaInicio,horaFin,observaciones) VALUES (1001,1001,"actividades1","condiciones1",1007,"2002-12-12","2003-12-12","15:32","15:32","observaciones1");


---------------------------

INSERT INTO usuario (nombre, apellido, email, fechaDeNac, rol_usuario_id, contraseña) VALUES("mike", "soto", "maike.soto@lacocs.com","1993-08-26", 1001, "ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413")

select id_usuario,nombre,rol,laboratorio from usuario,laboratorio,rol_usuario where id_usuario=id_rol_usuario AND laboratorio_id=id_laboratorio;


./mysqldump -t --complete-insert -h 160.153.46.5  -u lacocsAdmin -pJulio2018% boxLacocs systemstatus --where="id_systemstatus = 1010"

INSERT INTO `systemstatus` (`id_systemstatus`, `cch_def_prueba1`, `cch_def_prueba2`, `cch_def_prueba3`, `createdON`, `lastEditedON`, `active`, `ensayo_def_buscula_id`, `ensayo_def_prensa_id`, `ensayo_def_regVerFle_id`, `cch_def_prueba4`, `ensayo_def_observaciones`, `ensayo_def_pi`, `ensayo_def_distanciaApoyos`, `ensayo_def_kN`, `ensayo_def_MPa`, `ensayo_def_divisorKn`, `maxNoOfRegistrosCCH`, `multiplosNoOfRegistrosCCH`) VALUES (1010,7,14,28,'2018-09-08 16:54:31','2018-09-08 16:54:31',1,1005,1008,1006,28,'NO HAY OBSERVACIONES',3,45,102,10,1000,0,0);

INSERT INTO `systemstatus` (
	`cch_def_prueba1`, 
	`cch_def_prueba2`, 
	`cch_def_prueba3`, 
	`createdON`, 
	`lastEditedON`, 
	`active`, 
	`ensayo_def_buscula_id`, 
	`ensayo_def_prensa_id`, 
	`ensayo_def_regVerFle_id`, 
	`cch_def_prueba4`, 
	`ensayo_def_observaciones`, 
	`ensayo_def_pi`, 
	`ensayo_def_distanciaApoyos`, 
	`ensayo_def_kN`, 
	`ensayo_def_MPa`, 
	`ensayo_def_divisorKn`, 
	`maxNoOfRegistrosCCH`, 
	`multiplosNoOfRegistrosCCH`)
VALUES (
	7,
	14,
	28,
	'2018-09-08 16:54:31',
	'2018-09-08 16:54:31',
	1,
	1005,
	1008,
	1006,
	28,
	'NO HAY OBSERVACIONES',
	3,
	45,
	102,
	10,
	1000,
	8,
	4
	);















