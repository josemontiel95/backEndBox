
cliente...................... DONE
concretera................... DONE
correoDeLote................. DONE
ensayoCilindro............... DONE
ensayoCubo................... DONE
ensayoViga................... DONE
footerEnsayo................. DONE
formatoCampo................. DONE
formatoRegistroRev........... DONE
herramienta_ordenDeTrabajo... DONE
herramienta_tipo............. DONE
herramientas................. DONE
laboratorio.................. DONE
laboratorio_cliente.......... DONE
listaAsistencia.............. DONE
log.......................... DONE
loteCorreos.................. DONE
obra......................... DONE
ordenDeTrabajo............... DONE
registrosCampo............... DONE 
registrosRev................. DONE
rol_usuario.................. DONE
sesion....................... DONE
systemstatus ................ DONE
tecnicos_ordenDeTrabajo...... DONE
usuario...................... DONE

CREATE TABLE `systemstatus` (
  `id_systemstatus` int(11) NOT NULL AUTO_INCREMENT,
  `cch_def_prueba1` int(11) NOT NULL,
  `cch_def_prueba2` int(11) NOT NULL,
  `cch_def_prueba3` int(11) NOT NULL,
  `ensayo_def_buscula_id` int(11) NOT NULL,
  `ensayo_def_prensa_id` int(11) NOT NULL,
  `ensayo_def_regVerFle_id` int(11) NOT NULL,
  `cch_def_prueba4` int(11) NOT NULL,
  `ensayo_def_observaciones` varchar(30) DEFAULT NULL,
  `ensayo_def_pi` double DEFAULT NULL,
  `ensayo_def_distanciaApoyos` int(11) NOT NULL,
  `ensayo_def_kN` int(11) NOT NULL,
  `ensayo_def_MPa` int(11) NOT NULL,
  `ensayo_def_divisorKn` int(11) NOT NULL,
  `maxNoOfRegistrosCCH` int(11) NOT NULL,
  `multiplosNoOfRegistrosCCH` int(11) NOT NULL,
  `apiRoot` varchar(100) NOT NULL,
  `maxNoOfRegistrosRev` int(11) NOT NULL,
  `cch_vigaDef_prueba1` int(11) NOT NULL,
  `cch_vigaDef_prueba2` int(11) NOT NULL,
  `cch_vigaDef_prueba3` int(11) NOT NULL,
  `maxNoOfRegistrosCCH_VIGAS` int(11) NOT NULL,
  `multiplosNoOfRegistrosCCH_VIGAS` int(11) NOT NULL,
  `nombreG` varchar(30) NOT NULL,
  `firmaG` varchar(120) DEFAULT 'null',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_systemstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `query` text,
  `queryType` varchar(100) DEFAULT NULL,
  `status` varchar(7) DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;

CREATE TABLE `rol_usuario` (
  `id_rol_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(30) NOT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `root` varchar(60) DEFAULT NULL,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_rol_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `rfc` varchar(13) NOT NULL,
  `razonSocial` varchar(140) NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(30) NOT NULL,
  `foto` varchar(120) DEFAULT 'null',
  `contrasena` varchar(128) NOT NULL,
  `nombreContacto` varchar(40) NOT NULL,
  `telefonoDeContacto` varchar(13) NOT NULL,
  `calle` varchar(40) NOT NULL,
  `noExt` varchar(10) NOT NULL,
  `noInt` varchar(10) NOT NULL,
  `col` varchar(25) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=latin1;



CREATE TABLE `concretera` (
  `id_concretera` int(11) NOT NULL AUTO_INCREMENT,
  `concretera` varchar(40) NOT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_concretera`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;

CREATE TABLE `laboratorio` (
  `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT,
  `laboratorio` varchar(40) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `municipio` varchar(30) NOT NULL,
  `nombreG` varchar(30) NOT NULL,
  `firmaG` varchar(120) DEFAULT 'null',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_laboratorio`)
) ENGINE=InnoDB AUTO_INCREMENT=1003 DEFAULT CHARSET=latin1;

CREATE TABLE `laboratorio_cliente` (
  `id_laboratorio_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `laboratorio_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_laboratorio_cliente`),
  KEY `laboratorio_id` (`laboratorio_id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `laboratorio_cliente_ibfk_1` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `laboratorio_cliente_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id_cliente`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `herramienta_tipo` (
  `id_herramienta_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(30) DEFAULT NULL,
  `asignableenOrdenDeTrabajo` int(11) DEFAULT '1',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_herramienta_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;

CREATE TABLE `herramientas` (
  `id_herramienta` int(11) NOT NULL AUTO_INCREMENT,
  `herramienta_tipo_id` int(11) DEFAULT NULL,
  `fechaDeCompra` date NOT NULL,
  `placas` varchar(60) DEFAULT NULL,
  `condicion` varchar(10) NOT NULL,
  `observaciones` varchar(180) DEFAULT NULL,
  `laboratorio_id` int(11) DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_herramienta`),
  KEY `herramienta_tipo_id` (`herramienta_tipo_id`),
  KEY `laboratorio_id2` (`laboratorio_id`),
  CONSTRAINT `herramientas_ibfk_1` FOREIGN KEY (`herramienta_tipo_id`) REFERENCES `herramienta_tipo` (`id_herramienta_tipo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `laboratorio_id2` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `laboratorio_id` int(11) DEFAULT NULL,
  `nss` varchar(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fechaDeNac` date NOT NULL,
  `foto` varchar(120) DEFAULT 'null',
  `rol_usuario_id` int(11) DEFAULT NULL,
  `contrasena` varchar(128) NOT NULL,
  `correo_alterno` varchar(100) NOT NULL,
  `firma` varchar(120) DEFAULT 'null',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  KEY `rol_usuario_id` (`rol_usuario_id`),
  KEY `laboratorio_id` (`laboratorio_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_usuario_id`) REFERENCES `rol_usuario` (`id_rol_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `sesion` (
  `id_sesion` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `token` varchar(128) NOT NULL,
  `consultasAlBack` int(11) NOT NULL DEFAULT '1',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_sesion`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `sesion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `obra` (
  `id_obra` int(11) NOT NULL AUTO_INCREMENT,
  `obra` text,
  `prefijo` varchar(4) NOT NULL,
  `fechaDeCreacion` date NOT NULL,
  `descripcion` text,
  `localizacion` text NOT NULL,
  `nombre_residente` varchar(50) NOT NULL,
  `telefono_residente` varchar(15) NOT NULL,
  `correo_residente` varchar(100) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `concretera_id` int(11) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `revenimiento` double DEFAULT NULL,
  `incertidumbre` double DEFAULT NULL,
  `cotizacion` varchar(15) NOT NULL,
  `consecutivoProbetaCCH_VIGA` int(11) NOT NULL DEFAULT '1',
  `consecutivoDocumentosCCH_VIGA` int(11) NOT NULL DEFAULT '1',
  `laboratorio_id` int(11) DEFAULT NULL,
  `incertidumbreCilindro` double DEFAULT NULL,
  `incertidumbreCubo` double DEFAULT NULL,
  `incertidumbreVigas` double DEFAULT NULL,
  `correo_alterno` varchar(100) DEFAULT NULL,
  `consecutivoDocumentosCCH_CILINDRO` int(11) NOT NULL DEFAULT '1',
  `consecutivoDocumentosCCH_CUBO` int(11) NOT NULL DEFAULT '1',
  `consecutivoDocumentosCCH_REV` int(11) NOT NULL DEFAULT '1',
  `consecutivoProbetaCCH_CILINDRO` int(11) NOT NULL DEFAULT '1',
  `consecutivoProbetaCCH_CUBO` int(11) NOT NULL DEFAULT '1',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_obra`),
  KEY `cliente_id` (`cliente_id`),
  KEY `concretera_id` (`concretera_id`),
  KEY `laboratorio_id` (`laboratorio_id`),
  CONSTRAINT `laboratorio_id` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `obra_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id_cliente`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `obra_ibfk_2` FOREIGN KEY (`concretera_id`) REFERENCES `concretera` (`id_concretera`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `ordenDeTrabajo` (
  `id_ordenDeTrabajo` int(11) NOT NULL AUTO_INCREMENT,
  `obra_id` int(11) DEFAULT NULL,
  `actividades` text,
  `condicionesTrabajo` text,
  `jefe_brigada_id` int(11) DEFAULT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `observaciones` text,
  `lugar` varchar(150) NOT NULL,
  `laboratorio_id` int(11) DEFAULT NULL,
  `area` varchar(20) NOT NULL,
  `jefa_lab_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_ordenDeTrabajo`),
  KEY `obra_id` (`obra_id`),
  KEY `jefe_brigada_id` (`jefe_brigada_id`),
  KEY `laboratorio_id` (`laboratorio_id`),
  KEY `jefa_lab_id` (`jefa_lab_id`),
  CONSTRAINT `jefa_lab_id` FOREIGN KEY (`jefa_lab_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ordenDeTrabajo_ibfk_1` FOREIGN KEY (`obra_id`) REFERENCES `obra` (`id_obra`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ordenDeTrabajo_ibfk_2` FOREIGN KEY (`jefe_brigada_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ordenDeTrabajo_ibfk_3` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `herramienta_ordenDeTrabajo` (
  `ordenDeTrabajo_id` int(11) DEFAULT NULL,
  `herramienta_id` int(11) DEFAULT NULL,
  `fechaDevolucion` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `observaciones` varchar(180) DEFAULT NULL,
  KEY `herramienta_id` (`herramienta_id`),
  KEY `ordenDeTrabajo_id` (`ordenDeTrabajo_id`),
  CONSTRAINT `herramienta_ordenDeTrabajo_ibfk_1` FOREIGN KEY (`herramienta_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `herramienta_ordenDeTrabajo_ibfk_2` FOREIGN KEY (`ordenDeTrabajo_id`) REFERENCES `ordenDeTrabajo` (`id_ordenDeTrabajo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tecnicos_ordenDeTrabajo` (
  `id_tecnicos_ordenDeTrabajo` int(11) NOT NULL AUTO_INCREMENT,
  `tecnico_id` int(11) DEFAULT NULL,
  `ordenDeTrabajo_id` int(11) DEFAULT NULL,
  `asistencias` int(11) NOT NULL DEFAULT '0',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_tecnicos_ordenDeTrabajo`),
  KEY `tecnico_id` (`tecnico_id`),
  KEY `ordenDeTrabajo_id` (`ordenDeTrabajo_id`),
  CONSTRAINT `tecnicos_ordenDeTrabajo_ibfk_1` FOREIGN KEY (`tecnico_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tecnicos_ordenDeTrabajo_ibfk_2` FOREIGN KEY (`ordenDeTrabajo_id`) REFERENCES `ordenDeTrabajo` (`id_ordenDeTrabajo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `listaAsistencia` (
  `id_listaAsistencia` int(11) NOT NULL AUTO_INCREMENT,
  `tecnicos_ordenDeTrabajo_id` int(11) DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_listaAsistencia`),
  KEY `tecnicos_ordenDeTrabajo_id` (`tecnicos_ordenDeTrabajo_id`),
  CONSTRAINT `listaAsistencia_ibfk_1` FOREIGN KEY (`tecnicos_ordenDeTrabajo_id`) REFERENCES `tecnicos_ordenDeTrabajo` (`id_tecnicos_ordenDeTrabajo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `formatoRegistroRev` (
  `id_formatoRegistroRev` int(11) NOT NULL AUTO_INCREMENT,
  `regNo` varchar(30) NOT NULL,
  `ordenDeTrabajo_id` int(11) DEFAULT NULL,
  `localizacion` text NOT NULL,
  `observaciones` text,
  `cono_id` int(11) DEFAULT NULL,
  `varilla_id` int(11) DEFAULT NULL,
  `flexometro_id` int(11) DEFAULT NULL,
  `posInicial` point NOT NULL,
  `posFinal` point DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  `preliminar` varchar(200) DEFAULT NULL,
  `jefaLabApproval_id` int(11) DEFAULT NULL,
  `notVistoJLForBrigadaApproval` int(11) DEFAULT '0',
  `pdfFinal` varchar(200) DEFAULT NULL,
  `sentToClientFinal` int(11) DEFAULT '0',
  `dateSentToClientFinal` date DEFAULT NULL,
  `loteStatus` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_formatoRegistroRev`),
  KEY `ordenDeTrabajo_id` (`ordenDeTrabajo_id`),
  KEY `cono_id` (`cono_id`),
  KEY `varilla_id` (`varilla_id`),
  KEY `flexometro_id` (`flexometro_id`),
  KEY `revJefaLabApproval_id` (`jefaLabApproval_id`),
  CONSTRAINT `formatoRegistroRev_ibfk_1` FOREIGN KEY (`ordenDeTrabajo_id`) REFERENCES `ordenDeTrabajo` (`id_ordenDeTrabajo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoRegistroRev_ibfk_2` FOREIGN KEY (`cono_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoRegistroRev_ibfk_3` FOREIGN KEY (`varilla_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoRegistroRev_ibfk_4` FOREIGN KEY (`flexometro_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `revJefaLabApproval_id` FOREIGN KEY (`jefaLabApproval_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `registrosRev` (
  `id_registrosRev` int(11) NOT NULL AUTO_INCREMENT,
  `formatoRegistroRev_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `revProyecto` int(11) DEFAULT NULL,
  `revObtenido` int(11) DEFAULT NULL,
  `tamAgregado` int(11) DEFAULT NULL,
  `idenConcreto` varchar(20) DEFAULT NULL,
  `volumen` float DEFAULT NULL,
  `horaDeterminacion` time DEFAULT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  `concretera_id` int(11) DEFAULT NULL,
  `remisionNo` int(11) DEFAULT NULL,
  `horaSalida` time DEFAULT NULL,
  `horaLlegada` time DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_registrosRev`),
  KEY `concretera_id` (`concretera_id`),
  CONSTRAINT `registrosRev_ibfk_1` FOREIGN KEY (`concretera_id`) REFERENCES `concretera` (`id_concretera`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `formatoCampo` (
  `id_formatoCampo` int(11) NOT NULL AUTO_INCREMENT,
  `informeNo` varchar(30) NOT NULL,
  `ordenDeTrabajo_id` int(11) DEFAULT NULL,
  `observaciones` text,
  `tipo` varchar(20) NOT NULL,
  `tipoConcreto` varchar(5) DEFAULT NULL,
  `prueba1` int(11) NOT NULL,
  `prueba2` int(11) NOT NULL,
  `prueba3` int(11) NOT NULL,
  `cono_id` int(11) DEFAULT NULL,
  `varilla_id` int(11) DEFAULT NULL,
  `flexometro_id` int(11) DEFAULT NULL,
  `termometro_id` int(11) DEFAULT NULL,
  `posInicial` point NOT NULL,
  `posFinal` point DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  `prueba4` int(11) NOT NULL,
  `ensayadoFin` int(11) NOT NULL DEFAULT '8',
  `preliminar` varchar(200) DEFAULT NULL,
  `registrosNo` int(11) NOT NULL DEFAULT '0',
  `notVistoJLForBrigadaApproval` int(11) DEFAULT '0',
  `loteStatus` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_formatoCampo`),
  KEY `ordenDeTrabajo_id` (`ordenDeTrabajo_id`),
  KEY `cono_id` (`cono_id`),
  KEY `varilla_id` (`varilla_id`),
  KEY `flexometro_id` (`flexometro_id`),
  KEY `termometro_id` (`termometro_id`),
  CONSTRAINT `formatoCampo_ibfk_1` FOREIGN KEY (`ordenDeTrabajo_id`) REFERENCES `ordenDeTrabajo` (`id_ordenDeTrabajo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoCampo_ibfk_2` FOREIGN KEY (`cono_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoCampo_ibfk_3` FOREIGN KEY (`varilla_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoCampo_ibfk_4` FOREIGN KEY (`flexometro_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoCampo_ibfk_5` FOREIGN KEY (`termometro_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `footerEnsayo` (
  `id_footerEnsayo` int(11) NOT NULL AUTO_INCREMENT,
  `buscula_id` int(11) DEFAULT NULL,
  `regVerFle_id` int(11) DEFAULT NULL,
  `prensa_id` int(11) DEFAULT NULL,
  `tipo` varchar(20) NOT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `observaciones` double DEFAULT NULL,
  `encargado_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `pendingEnsayos` int(11) DEFAULT '0',
  `formatoCampo_id` int(11) DEFAULT NULL,
  `preliminarGabs` varchar(200) DEFAULT NULL,
  `ensayosAwaitingApproval` int(11) DEFAULT '0',
  `notVistoJLForEnsayoApproval` int(11) DEFAULT '0',
  PRIMARY KEY (`id_footerEnsayo`),
  KEY `buscula_id` (`buscula_id`),
  KEY `regVerFle_id` (`regVerFle_id`),
  KEY `prensa_id` (`prensa_id`),
  KEY `encargado_id` (`encargado_id`),
  CONSTRAINT `footerEnsayo_ibfk_1` FOREIGN KEY (`buscula_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `footerEnsayo_ibfk_2` FOREIGN KEY (`regVerFle_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `footerEnsayo_ibfk_3` FOREIGN KEY (`prensa_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `footerEnsayo_ibfk_4` FOREIGN KEY (`encargado_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `registrosCampo` (
  `id_registrosCampo` int(11) NOT NULL AUTO_INCREMENT,
  `formatoCampo_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fprima` varchar(10) DEFAULT NULL,
  `revProyecto` int(11) DEFAULT NULL,
  `revObra` int(11) DEFAULT NULL,
  `tamAgregado` int(11) DEFAULT NULL,
  `volumen` float DEFAULT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  `horaMuestreo` time DEFAULT NULL,
  `tempMuestreo` int(11) DEFAULT NULL,
  `tempRecoleccion` int(11) DEFAULT NULL,
  `localizacion` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `diasEnsaye` int(11) DEFAULT NULL,
  `herramienta_id` int(11) DEFAULT NULL,
  `claveEspecimen` varchar(50) DEFAULT NULL,
  `consecutivoProbeta` int(11) NOT NULL,
  `footerEnsayo_id` int(11) DEFAULT NULL,
  `statusEnsayo` int(11) NOT NULL DEFAULT '0',
  `grupo` int(11) NOT NULL DEFAULT '0',
  `loteStatus` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_registrosCampo`),
  KEY `herramienta_id` (`herramienta_id`),
  KEY `footerEnsayo_id` (`footerEnsayo_id`),
  CONSTRAINT `footerEnsayo_id` FOREIGN KEY (`footerEnsayo_id`) REFERENCES `footerEnsayo` (`id_footerEnsayo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `registrosCampo_ibfk_1` FOREIGN KEY (`herramienta_id`) REFERENCES `herramientas` (`id_herramienta`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `ensayoViga` (
  `id_ensayoViga` int(11) NOT NULL AUTO_INCREMENT,
  `registrosCampo_id` int(11) DEFAULT NULL,
  `formatoCampo_id` int(11) DEFAULT NULL,
  `footerEnsayo_id` int(11) DEFAULT NULL,
  `condiciones` varchar(30) NOT NULL,
  `lijado` varchar(30) NOT NULL,
  `cuero` varchar(30) NOT NULL,
  `ancho1` float DEFAULT NULL,
  `ancho2` float DEFAULT NULL,
  `per1` float DEFAULT NULL,
  `per2` float DEFAULT NULL,
  `l1` float DEFAULT NULL,
  `l2` float DEFAULT NULL,
  `l3` float DEFAULT NULL,
  `disApoyo` float DEFAULT NULL,
  `disCarga` float DEFAULT NULL,
  `carga` float DEFAULT NULL,
  `defectos` varchar(20) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `posFractura` int(11) DEFAULT NULL,
  `velAplicacionExp` double DEFAULT NULL,
  `tiempoDeCarga` int(11) DEFAULT NULL,
  `mr` double DEFAULT NULL,
  `prom` double DEFAULT NULL,
  `jefaLabApproval_id` int(11) DEFAULT NULL,
  `pdfFinal` varchar(200) DEFAULT NULL,
  `sentToClientFinal` int(11) DEFAULT '0',
  `dateSentToClientFinal` date DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_ensayoViga`),
  KEY `registrosCampo_id` (`registrosCampo_id`),
  KEY `formatoCampo_id` (`formatoCampo_id`),
  KEY `footerEnsayo_id` (`footerEnsayo_id`),
  KEY `jefaLabApproval_id` (`jefaLabApproval_id`),
  CONSTRAINT `ensayoViga_ibfk_1` FOREIGN KEY (`registrosCampo_id`) REFERENCES `registrosCampo` (`id_registrosCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoViga_ibfk_2` FOREIGN KEY (`formatoCampo_id`) REFERENCES `formatoCampo` (`id_formatoCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoViga_ibfk_3` FOREIGN KEY (`footerEnsayo_id`) REFERENCES `footerEnsayo` (`id_footerEnsayo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `jefaLabApproval_id` FOREIGN KEY (`jefaLabApproval_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `ensayoCubo` (
  `id_ensayoCubo` int(11) NOT NULL AUTO_INCREMENT,
  `registrosCampo_id` int(11) DEFAULT NULL,
  `formatoCampo_id` int(11) DEFAULT NULL,
  `footerEnsayo_id` int(11) DEFAULT NULL,
  `l1` float DEFAULT NULL,
  `l2` float DEFAULT NULL,
  `carga` float DEFAULT NULL,
  `falla` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `velAplicacionExp` double DEFAULT NULL,
  `tiempoDeCarga` int(11) DEFAULT NULL,
  `area` double DEFAULT NULL,
  `resistencia` double DEFAULT NULL,
  `jefaLabApproval_id` int(11) DEFAULT NULL,
  `pdfFinal` varchar(200) DEFAULT NULL,
  `sentToClientFinal` int(11) DEFAULT '0',
  `dateSentToClientFinal` date DEFAULT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_ensayoCubo`),
  KEY `registrosCampo_id` (`registrosCampo_id`),
  KEY `formatoCampo_id` (`formatoCampo_id`),
  KEY `footerEnsayo_id` (`footerEnsayo_id`),
  KEY `cuboJefaLabApproval_id` (`jefaLabApproval_id`),
  CONSTRAINT `cuboJefaLabApproval_id` FOREIGN KEY (`jefaLabApproval_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoCubo_ibfk_1` FOREIGN KEY (`registrosCampo_id`) REFERENCES `registrosCampo` (`id_registrosCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoCubo_ibfk_2` FOREIGN KEY (`formatoCampo_id`) REFERENCES `formatoCampo` (`id_formatoCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoCubo_ibfk_3` FOREIGN KEY (`footerEnsayo_id`) REFERENCES `footerEnsayo` (`id_footerEnsayo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

CREATE TABLE `ensayoCilindro` (
  `id_ensayoCilindro` int(11) NOT NULL AUTO_INCREMENT,
  `registrosCampo_id` int(11) DEFAULT NULL,
  `formatoCampo_id` int(11) DEFAULT NULL,
  `footerEnsayo_id` int(11) DEFAULT NULL,
  `peso` float NOT NULL,
  `d1` float NOT NULL,
  `d2` float NOT NULL,
  `h1` float NOT NULL,
  `h2` float NOT NULL,
  `carga` float NOT NULL,
  `falla` int(11) NOT NULL,
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `fecha` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `velAplicacionExp` double DEFAULT NULL,
  `tiempoDeCarga` int(11) DEFAULT NULL,
  `area` double DEFAULT NULL,
  `resistencia` double DEFAULT NULL,
  `jefaLabApproval_id` int(11) DEFAULT NULL,
  `pdfFinal` varchar(200) DEFAULT NULL,
  `sentToClientFinal` int(11) DEFAULT '0',
  `dateSentToClientFinal` date DEFAULT NULL,
  PRIMARY KEY (`id_ensayoCilindro`),
  KEY `registrosCampo_id` (`registrosCampo_id`),
  KEY `formatoCampo_id` (`formatoCampo_id`),
  KEY `footerEnsayo_id` (`footerEnsayo_id`),
  KEY `cilindroJefaLabApproval_id` (`jefaLabApproval_id`),
  CONSTRAINT `cilindroJefaLabApproval_id` FOREIGN KEY (`jefaLabApproval_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoCilindro_ibfk_1` FOREIGN KEY (`registrosCampo_id`) REFERENCES `registrosCampo` (`id_registrosCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoCilindro_ibfk_2` FOREIGN KEY (`formatoCampo_id`) REFERENCES `formatoCampo` (`id_formatoCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ensayoCilindro_ibfk_3` FOREIGN KEY (`footerEnsayo_id`) REFERENCES `footerEnsayo` (`id_footerEnsayo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;


CREATE TABLE `loteCorreos` (
  `id_loteCorreos` int(11) NOT NULL AUTO_INCREMENT,
  `creador_id` int(11) DEFAULT NULL,
  `correosNo` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `factua` varchar(30) DEFAULT NULL,
  `observaciones` text,
  `customMailStatus` int(11) DEFAULT '0',
  `customText` text,
  `customMail` int(11) DEFAULT '0',
  `adjunto` int(11) DEFAULT '0',
  `pdfPath` varchar(200) DEFAULT NULL,
  `xmlPath` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_loteCorreos`),
  KEY `creador_id` (`creador_id`),
  CONSTRAINT `loteCorreos_ibfk_1` FOREIGN KEY (`creador_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;


CREATE TABLE `correoDeLote` (
  `id_correoDeLote` int(11) NOT NULL AUTO_INCREMENT,
  `loteCorreos_id` int(11) DEFAULT NULL,
  `pdf` varchar(150) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `createdON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastEditedON` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `registrosCampo_id` int(11) DEFAULT NULL,
  `formatoRegistroRev_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_correoDeLote`),
  KEY `loteCorreos_id` (`loteCorreos_id`),
  KEY `correoDeLote_ibfk_2` (`registrosCampo_id`),
  KEY `formatoRegistroRev_ibfk_5` (`formatoRegistroRev_id`),
  CONSTRAINT `correoDeLote_ibfk_1` FOREIGN KEY (`loteCorreos_id`) REFERENCES `loteCorreos` (`id_loteCorreos`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `correoDeLote_ibfk_2` FOREIGN KEY (`registrosCampo_id`) REFERENCES `registrosCampo` (`id_registrosCampo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formatoRegistroRev_ibfk_5` FOREIGN KEY (`formatoRegistroRev_id`) REFERENCES `formatoRegistroRev` (`id_formatoRegistroRev`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;


//=================//=================//=================//=================
//=================//=================//=================//=================
//=================//=================//=================//=================
//=================//=================//=================//=================
//=================//=================//=================//=================
//=================//=================//=================//=================
//=================//=================//=================//=================
//=================//=================//=================//=================

INSERT INTO `systemstatus`(id_systemstatus,cch_def_prueba1,cch_def_prueba2,cch_def_prueba3,createdON,lastEditedON,active,ensayo_def_buscula_id,ensayo_def_prensa_id,ensayo_def_regVerFle_id,cch_def_prueba4,ensayo_def_observaciones,ensayo_def_pi,ensayo_def_distanciaApoyos,ensayo_def_kN,ensayo_def_MPa,ensayo_def_divisorKn,maxNoOfRegistrosCCH,multiplosNoOfRegistrosCCH,apiRoot,maxNoOfRegistrosRev,cch_vigaDef_prueba1,cch_vigaDef_prueba2,cch_vigaDef_prueba3,maxNoOfRegistrosCCH_VIGAS,multiplosNoOfRegistrosCCH_VIGAS,nombreG,firmaG) VALUES
(1017,7,14,28,'2018-09-08 20:54:31','2018-09-08 20:54:31',1,1005,1008,1006,28,'NO HAY OBSERVACIONES',3.141593,45,102,10,1000,8,4,'http://qualitycontrol.lacocsmex.com.mx/',10,14,28,28,9,3,'','null');

INSERT INTO `rol_usuario`(id_rol_usuario,rol,root) VALUES
(1001,'Administrador','administrador'),
(1002,'Jefe de Laboratorio','jefeLaboratorio'),
(1003,'Jefe de Brigada','jefeBrigada'),
(1004,'Tecnico',''),
(1005,'Tecnico de muestras','tecnico'),
(1006,'ADMINISTRATIVO','administrativo'),
(1007,'SOPORTE','soporte');

INSERT INTO `cliente` (rfc,razonSocial,nombre,email,telefono,foto,contrasena,nombreContacto,telefonoDeContacto,createdON,lastEditedON,active,calle,noExt,noInt,col,municipio,estado)VALUES
('INDI140616UAA','INDHR','INDHR','jldh@live.com','2315836','null','','ISRAEL SANCHEZ ALVAREZ','2221236145','2018-10-09 17:28:08','2018-11-24 23:07:18',1,'18 ORIENTE ','205','','CENTRO','PUEBLA','Puebla'),
('DUPP151008LX6','DESARROLLO URBANO PUEBLA CRECE S.A. DE C.V.','DESARROLLO URBANO PUEBLA CRECE S.A. DE C.V.','ed.elementa@gmail.com','2221697458','null','','HUGO DORANTES','2211665859','2018-10-10 16:02:14','2018-11-30 17:39:43',1,'BOULEVARD ATLIXCAYOTL','52410','PISO 7C','RESERVA TERRITORIAL ATLIX','PUEBLA','Puebla'),
('LCC010330HK5','ALFA PROVEDORES Y CONTRATISTAS S.A. DE C.V.','ALFA PROVEDORES Y CONTRATISTAS S.A. DE C.V.','yuli.patino@lacocsmex.com.mx','2315836','null','','MARCO ANTONIO CERVANTES','2221236145','2018-10-19 15:13:28','2018-10-19 15:13:28',1,'AVENIDA REFORMA','305','','CENTRO','PUEBLA','Puebla');

INSERT INTO `concretera` (concretera) VALUES 
('HOLCIM'),('APASCO'),
('HOLCIM '),('SAMBLEN'),
('CATSA'),('CEMEX'),
('CRUZ AZUL'),('CONCRETOS ARA');

INSERT INTO laboratorio (laboratorio,estado,municipio,nombreG,firmaG) VALUES
("LABORATORIO CENTRAL"      , "Puebla", "Puebla"   ,"M en I. MARCO ANTONIO CERVANTE","./../../disenoFormatos/firmas/firma.png");

INSERT INTO `laboratorio_cliente`(id_laboratorio_cliente,laboratorio_id,cliente_id,createdON,lastEditedON,active) VALUES 
(1018,1003,1002,'2018-10-31 18:45:01','2018-10-31 18:45:01',1),(1019,1003,1003,'2018-11-30 16:20:10','2018-11-30 16:20:10',1);

INSERT INTO herramienta_tipo (id_herramienta_tipo,tipo,asignableenOrdenDeTrabajo) VALUES
(1001, "CONO DE REVENIMIENTO"           ,1),
(1002, "VARILLAS DE COMPACTACION 300"   ,1),
(1003, "FLEXOMETRO"            			,1),
(1004, "TERMOMETRO BULBO SECO Y HUMEDO" ,1),
(1005, "BASCULA DIGITAL"               	,0),
(1006, "REGLA"                 			,1),
(1007, "VERNIER"               			,1),
(1008, "PRENSA"                			,0),
(1009, "CUBO"                  			,1),
(1010, "VIGAS"                  		,1),
(1011, "MOLDE CILINDRICO"              	,1),
(1012, "Prueba33"              			,1),
(1013, "FORD PICKUP XL"        			,0),
(1014, "PLACA PARA REVENIMIENTO"        ,1),
(1016, "CUCHARON"              			,1),
(1017, "MAZO DE HULE"                  	,1),
(1019, "ENRASADOR"             			,1),
(1020, "CARRETILLA"            			,1),
(1021, "PALA"                  			,1),
(1022, "CUCHARA DE ALBANIL"   			,1),
(1023, "DISPOSITIVO PARA VIGAS"			,1),
(1024, "CUBOS DE 10*10 CM"     			,1),
(1025, "CUBOS DE 15*15 CM"     			,1),
(1026, "MAZO DE HULE"   	   			,1),
(1027, "DISPOSITIVO DE ALINEAMIENTO"    ,1),
(1028, "PLATO DE CABECEO"               ,1),
(1029, "CUBO DE AZUFRE"                 ,1),
(1030, "VARILLAS DE COMPACTACION 600"   ,1),
(1031, "TERMOMETRO DE LIQUIDO EN VIDRIO",1),
(1032, "TERMOMETRO BIMETALICO"          ,1),
(1033, "TERMOMETRO INFRARROJO DIGITAL"  ,1),
(1034, "TERMOHIGROMETRO"  				,1),
(1035, "BALANZA ANALOGA"  				,0),
(1036, "BALANZA"  						,0),
(1037, "COMPAS EXTERIORES"  			,1),
(1038, "OLLA PARA FUNDIR AZUFRE"  		,1),
(1039, "LAINAS"  						,1),
(1040, "PALA"  							,1),
(1041, "HUMIDIFICADOR"  				,1),
(1042, "CARRETILLA"  					,1),
(1043, "NIVELETA"  					    ,1);

INSERT INTO herramientas (id_herramienta,herramienta_tipo_id,laboratorio_id,fechaDeCompra,placas,condicion) VALUES
(1289,1010,1003,"2018-12-05","GLCC-02","Buena"),
(1291,1010,1003,"2018-12-05","GLCC-09","Buena"),
(1296,1010,1003,"2018-12-05","GLCC-11","Buena"),
(1297,1010,1003,"2018-12-05","GLCC-12","Buena"),
(1302,1020,1003,"2018-12-05","CA-01"  ,"Regular"),
(1287,1001,1003,"2018-12-05","RLCC-17","Buena"),
(1303,1016,1003,"2018-12-05","HLCC-02","Muy DaÃ±ad"),
(1286,1002,1003,"2018-12-05","VLCC-09","Buena"),
(1288,1014,1003,"2018-12-05","LLCC-18","Buena"),
(1292,1010,1003,"2018-12-05","GLCC-10","Buena"),
(1298,1010,1003,"2018-12-05","GLCC-16","Buena"),
(1299,1010,1003,"2018-12-05","GLCC-18","Buena"),
(1300,1010,1003,"2018-12-05","GLCC-19","Buena"),
(1320,1019,1003,"2018-12-05","NLCC-27","Regular"),
(1321,1019,1003,"2018-12-05","NLCC-28","DaÃ±ado"),
(1301,1003,1003,"2018-12-05","FLCC-01","Buena"),
(1290,1010,1003,"2018-12-05","GLCC-06","Buena"),
(1323,1010,1003,"2018-12-05","GLCC-17","Regular"),
(1324,1010,1003,"2018-12-05","GLCC-23","Regular"),
(1285,1017,1003,"2018-12-05","ZLCC-19","Buena"),
(1340,1021,1003,"2018-12-05","P-01"   ,"Buena"),
(1342,1022,1003,"2018-12-05","CU-01"  ,"Muy DaÃ±ad"),
(1346,1010,1003,"2018-12-05","GLCC-37","DaÃ±ado"),
(1344,1010,1003,"2018-12-05","GLCC-38","DaÃ±ado"),
(1345,1010,1003,"2018-12-05","GLCC-39","DaÃ±ado"),
(1322,1010,1003,'2018-11-07','GLCC - 13','Regular'),
(1325,1010,1003,'2018-11-07','GLCC - 26','Regular'),
(1326,1010,1003,'2018-11-07','GLCC - 27','Regular'),
(1327,1010,1003,'2018-11-07','GLCC - 30','Regular'),
(1328,1010,1003,'2018-11-09','GLCC-31','Regular'),
(1329,1010,1003,'2018-11-09','GLCC-33','Regular'),
(1330,1010,1003,'2018-11-09','GLCC-35','Regular'),
(10,1001,1003,'0000-00-00','No usare cono','REGULAR'),
(20,1002,1003,'0000-00-00','No usare varilla','REGULAR'),
(30,1003,1003,'0000-00-00','No usare flexometro','REGULAR'),
(40,1004,1003,'0000-00-00','No usare termometro','REGULAR'),
(50,1009,1003,'2018-01-01','No usare cubo','REGULAR'),
(60,1010,1003,'2018-01-01','No usare viga','REGULAR'),
(70,1011,1003,'2018-01-01','No usare cilindro','REGULAR'),
(80,NULL,1003,'0000-00-00','No usare herramienta','REGULAR'),
(90,1006,1003,'0000-00-00','NO USARE REGLA,','REGULAR'),
(1005,NULL,1003,'2018-08-03','TR-001','REGULAR'),
(1006,NULL,1003,'2018-08-03','VR-001','REGULAR'),
(1008,1005,1003,'2018-08-04','BAS-001','REGULAR'),
(1339,1003,1003,'2018-11-12','FLCC-02','Buena'),
(1331,1008,1003,'2018-11-12','PLCC-11','Buena');

UPDATE  herramientas SET herramienta_tipo_id = 1005 WHERE id_herramienta=1008;
UPDATE  herramientas SET herramienta_tipo_id = 1003 WHERE id_herramienta=1339;
UPDATE  herramientas SET herramienta_tipo_id = 1008 WHERE id_herramienta=1331;
UPDATE footerEnsayo SET prensa_id = 1331 WHERE prensa_id=1008;

INSERT INTO `usuario`(id_usuario,nombre,apellido,laboratorio_id,nss,email,fechaDeNac,foto,rol_usuario_id,contrasena,correo_alterno,firma) VALUES 
(1001,'Marco','Cervantes',1003,'1814181499','marco.cervantes@lacocsmex.com.mx','1992-08-26','SystemData/UserData/1007/foto_perfil.jpg',1001,'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','','null'),
(1059,'Serafin','GarcÃ­a',1003,'','serafin.garcia@lacocsmex.com.mx','1960-03-12','null',1003,'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','','./../../disenoFormatos/firmas/1062.png'),
(1061,'Enrique ','Alonso',1003,'','enrique.alonso@lacocsmex.com.mx','2018-10-11','null',1003,'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','','null'),
(1062,'Gabino','Mena',1003,'1234567','gabino.mena@lacocsmex.com.mx','2018-06-05','null',1005,'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','','./../../disenoFormatos/firmas/1062.png'),
(1063,'Laura ','Castillo',1003,'123456','laura.castillo@lacocsmex.com.mx','2018-10-02','null',1002,'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','','null'),
(1071,'Myrna ','Iglesias Martinez',1003,'','myrna.iglesias@lacocsmex.com.mx','1970-09-08','null',1006,'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','','null');

INSERT INTO `obra`(id_obra,obra,prefijo,fechaDeCreacion,descripcion,localizacion,nombre_residente,telefono_residente,correo_residente,cliente_id,concretera_id,tipo,revenimiento,incertidumbre,createdON,lastEditedON,active,cotizacion,consecutivoProbetaCCH_VIGA,consecutivoDocumentosCCH_VIGA,laboratorio_id,incertidumbreCilindro,incertidumbreCubo,incertidumbreVigas,correo_alterno,consecutivoDocumentosCCH_CILINDRO,consecutivoDocumentosCCH_CUBO,consecutivoDocumentosCCH_REV,consecutivoProbetaCCH_CILINDRO,consecutivoProbetaCCH_CUBO) VALUES
(1027,'EDIFICIO ELEMENTA','EDE','2018-11-30','CONTROL DE CALIDAD DE CONCRETOS ','BOULEVARD AMERICA #308, SAN ANTONIO CACALOTEPEC','ARQ. ANDRES','2221236145','andres.elementa@gmail.com',1003,1004,1,20,3.12,'2018-10-10 16:17:50','2018-12-03 16:57:26',1,'186',0,0,1003,24.93,6.16,2.07,'arq.osvaldosanchez@gmail.com',196,21,198,834,63),
(1031,'RECONSTRUCCION CON CONCRETO HIDRALICO DE LA TRONCAL DEL PERIFERICO ECOLOGICO DE LOS MUNICIPIOS DE SAN PEDRO CHOLULA Y SAN ANDRES CHOLULA, DEL DISTRIBUIDOR VIAL  BOULEVARD FORJADORES AL DISTRIBUIDOR VIAL BOULEVARD ATLIXCAYOTL, EN EL ESTADO DE PUEBLA','PVF','2018-05-31','Control de calidad de concreto hidraulico ','Periferico Ecologico de la ciudad de Puebla','Adrian Sanchez Gonzalez','2721940755','ingmariorg@gmail.com',1002,1001,1,10,3.12,'2018-10-31 21:12:21','2018-12-04 03:38:23',1,'339',570,35,1003,24.93,6.17,2.07,'isanchez197617@yahoo.com.mx',2,1,4,5,1);

INSERT INTO `ordenDeTrabajo`(id_ordenDeTrabajo,obra_id,actividades,condicionesTrabajo,jefe_brigada_id,fechaInicio,fechaFin,horaInicio,horaFin,observaciones,lugar,laboratorio_id,createdON,lastEditedON,active,area,jefa_lab_id,status) VALUES 
(1041,1031,'Prueba de Revenimiento y toma de muestras de concreto fresco ','optimas ',1059,'2018-10-31','2018-11-22','14:02:00','17:03:00','ninguna','Periferico Ecologico',1003,'2018-11-01 01:59:32','2018-11-26 05:55:30',0,'CONCRETO',1063,1),
(1043,1027,'colado','lluvioso',1061,'2018-11-30','2019-01-24','08:00:00','19:00:00','no hay ','Boulevard Atlixcayotl',1003,'2018-11-30 23:35:43','2018-12-01 02:26:16',1,'CONCRETO',1063,1);

INSERT INTO `herramienta_ordenDeTrabajo` (ordenDeTrabajo_id,herramienta_id,fechaDevolucion,status,createdON,lastEditedON,active,observaciones) VALUES 
(1041,1289,'0000-00-00','PENDIENTE','2018-11-01 16:18:15','2018-11-01 16:18:15',1,NULL),(1041,1291,'0000-00-00','PENDIENTE','2018-11-01 16:18:53','2018-11-01 16:18:53',1,NULL),(1041,1296,'0000-00-00','PENDIENTE','2018-11-01 16:19:02','2018-11-01 16:19:02',1,NULL),(1041,1297,'0000-00-00','PENDIENTE','2018-11-01 16:19:16','2018-11-01 16:19:16',1,NULL),(1041,1290,'0000-00-00','PENDIENTE','2018-11-01 16:19:49','2018-11-09 17:20:09',0,NULL),(1041,1302,'0000-00-00','PENDIENTE','2018-11-01 16:38:56','2018-11-01 16:38:56',1,NULL),(1041,1287,'0000-00-00','PENDIENTE','2018-11-01 16:39:26','2018-11-01 16:39:26',1,NULL),(1041,1303,'0000-00-00','PENDIENTE','2018-11-01 16:42:14','2018-11-01 16:42:14',1,NULL),(1041,1286,'0000-00-00','PENDIENTE','2018-11-01 16:43:03','2018-11-01 16:43:03',1,NULL),(1041,1288,'0000-00-00','PENDIENTE','2018-11-01 16:43:45','2018-11-01 16:43:45',1,NULL),(1041,1292,'0000-00-00','PENDIENTE','2018-11-01 16:44:46','2018-11-01 16:44:46',1,NULL),(1041,1298,'0000-00-00','PENDIENTE','2018-11-01 16:45:18','2018-11-01 16:45:18',1,NULL),(1041,1299,'0000-00-00','PENDIENTE','2018-11-01 16:45:29','2018-11-01 16:45:29',1,NULL),(1041,1300,'0000-00-00','PENDIENTE','2018-11-01 16:45:37','2018-11-01 16:45:37',1,NULL),(1041,1320,'0000-00-00','PENDIENTE','2018-11-06 19:14:24','2018-11-06 19:14:24',1,NULL),(1041,1321,'0000-00-00','PENDIENTE','2018-11-06 19:18:34','2018-11-06 19:18:34',1,NULL),(1041,1322,'0000-00-00','PENDIENTE','2018-11-07 23:55:43','2018-11-09 17:25:31',0,NULL),(1041,1323,'0000-00-00','PENDIENTE','2018-11-07 23:56:11','2018-11-09 17:25:20',0,NULL),(1041,1324,'0000-00-00','PENDIENTE','2018-11-07 23:58:52','2018-11-09 17:20:19',0,NULL),(1041,1325,'0000-00-00','PENDIENTE','2018-11-07 23:59:08','2018-11-09 17:20:01',0,NULL),(1041,1326,'0000-00-00','PENDIENTE','2018-11-07 23:59:27','2018-11-09 17:19:49',0,NULL),(1041,1327,'0000-00-00','PENDIENTE','2018-11-07 23:59:40','2018-11-09 17:19:39',0,NULL),(1041,1328,'0000-00-00','PENDIENTE','2018-11-09 17:04:13','2018-11-09 17:19:06',0,NULL),(1041,1329,'0000-00-00','PENDIENTE','2018-11-09 17:04:28','2018-11-09 17:18:27',0,NULL),(1041,1330,'0000-00-00','PENDIENTE','2018-11-09 17:04:39','2018-11-09 17:18:10',0,NULL),(1041,1322,'0000-00-00','PENDIENTE','2018-11-09 17:47:40','2018-11-09 22:16:33',0,NULL),(1041,1301,'0000-00-00','PENDIENTE','2018-11-09 17:48:31','2018-11-09 17:48:31',1,NULL),(1041,1290,'0000-00-00','PENDIENTE','2018-11-09 23:54:19','2018-11-09 23:54:19',1,NULL),(1041,1322,'0000-00-00','PENDIENTE','2018-11-12 21:19:26','2018-11-12 21:19:26',1,NULL),(1041,1323,'0000-00-00','PENDIENTE','2018-11-12 21:19:47','2018-11-12 21:19:47',1,NULL),(1041,1324,'0000-00-00','PENDIENTE','2018-11-12 21:19:58','2018-11-12 21:19:58',1,NULL),(1041,1285,'0000-00-00','PENDIENTE','2018-11-15 23:56:14','2018-11-15 23:56:14',1,NULL),(1041,1340,'0000-00-00','PENDIENTE','2018-11-16 21:56:59','2018-11-16 21:56:59',1,NULL),(1041,1342,'0000-00-00','PENDIENTE','2018-11-16 22:00:56','2018-11-16 22:00:56',1,NULL),(1041,1346,'0000-00-00','PENDIENTE','2018-11-27 00:10:41','2018-11-27 00:10:41',1,NULL),(1041,1344,'0000-00-00','PENDIENTE','2018-11-27 00:11:06','2018-11-27 00:11:06',1,NULL),(1041,1345,'0000-00-00','PENDIENTE','2018-11-27 00:11:54','2018-11-27 00:11:54',1,NULL);

INSERT INTO `formatoRegistroRev` (id_formatoRegistroRev,regNo,ordenDeTrabajo_id,localizacion,observaciones,cono_id,varilla_id,flexometro_id,posInicial,posFinal,createdON,lastEditedON,active,status,preliminar,jefaLabApproval_id,notVistoJLForBrigadaApproval,pdfFinal,sentToClientFinal,dateSentToClientFinal,loteStatus) VALUES 
(1056,'2',1041,'LOSA MR ','NO HAY OBSERVACIONES',1287,1286,1301,'',NULL,'2018-11-28 04:11:39','2018-11-28 16:28:58',1,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosDataRev/1002/1031/1041/1056/preliminarRev(22-26-55).pdf',1063,0,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/Revenimiento/1056/FinalRevenimiento(10-28-33).pdf',1,'2018-11-28',0);

INSERT INTO `registrosRev` (id_registrosRev,formatoRegistroRev_id,fecha,revProyecto,revObtenido,tamAgregado,idenConcreto,volumen,horaDeterminacion,unidad,concretera_id,remisionNo,horaSalida,horaLlegada,status,createdON,lastEditedON,active) VALUES 
(1061,1056,'2018-11-27',10,8,40,'NORMAL',NULL,'02:20:00','2457',1001,20912,'10:11:00','10:18:00',5,'2018-11-28 04:12:39','2018-11-28 16:28:57',1),(1062,1056,'2018-11-27',10,9,40,'NORMAL ',NULL,'12:50:00','2491',1001,27496,'11:24:00','12:42:00',5,'2018-11-28 04:15:30','2018-11-28 16:28:57',1),(1063,1056,'2018-11-27',10,9,40,'NORMAL ',NULL,'16:42:00','2460',1001,20850,'16:28:00','16:40:00',5,'2018-11-28 04:17:42','2018-11-28 16:28:57',1),(1064,1056,'2018-11-27',10,10,40,'NORMAL  ',NULL,'17:40:00','2552',1001,20857,'17:20:00','17:32:00',5,'2018-11-28 04:19:59','2018-11-28 16:28:57',1),(1065,1056,'2018-11-27',10,9,40,'NORMAL  ',NULL,'18:10:00','2455',1001,20860,'17:49:00','17:59:00',5,'2018-11-28 04:21:51','2018-11-28 16:28:57',1);

INSERT INTO `formatoCampo`(id_formatoCampo,informeNo,ordenDeTrabajo_id,observaciones,tipo,tipoConcreto,prueba1,prueba2,prueba3,cono_id,varilla_id,flexometro_id,termometro_id,posInicial,posFinal,createdON,lastEditedON,active,status,prueba4,ensayadoFin,preliminar,registrosNo,notVistoJLForBrigadaApproval,loteStatus) VALUES 
(1161,'PVF/339/18/1',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-06 00:00:39','2018-12-05 00:38:29',1,1,0,0,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1161/preliminarCCH(9-53-59).pdf',9,0,3),
(1162,'PVF/339/18/2',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-06 18:39:39','2018-12-05 00:38:29',1,1,0,0,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1162/preliminarCCH(9-52-59).pdf',3,0,1),
(1163,'PVF/339/18/3',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-07 03:12:00','2018-12-05 00:38:29',1,1,0,0,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1163/preliminarCCH(9-53-2).pdf',3,0,1),
(1165,'PVF/339/18/4',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-07 23:28:08','2018-12-05 00:38:29',1,1,0,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1165/preliminarCCH(9-53-13).pdf',3,0,1),
(1166,'PVF/339/18/5',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-07 23:47:14','2018-12-05 00:38:29',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1166/preliminarCCH(9-53-18).pdf',9,0,3),
(1167,'PVF/339/18/6',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-08 00:37:53','2018-12-05 00:38:30',1,1,0,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1167/preliminarCCH(9-53-23).pdf',3,0,1),
(1169,'PVF/339/18/7',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,30,40,'',NULL,'2018-11-09 17:12:56','2018-12-05 00:38:30',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1169/preliminarCCH(9-56-13).pdf',9,0,3),
(1170,'PVF/339/18/8',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-09 20:37:31','2018-12-05 00:38:30',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1170/preliminarCCH(9-53-52).pdf',9,0,3),
(1171,'PVF/339/18/9',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-10 21:53:37','2018-12-05 00:38:30',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1171/preliminarCCH(9-54-0).pdf',6,0,2),
(1172,'PVF/339/18/10',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-12 21:43:57','2018-12-05 00:38:30',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1172/preliminarCCH(9-54-4).pdf',9,0,3),
(1173,'PVF/339/18/11',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-13 03:46:35','2018-12-05 00:38:30',1,1,0,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1173/preliminarCCH(9-54-11).pdf',3,0,1),
(1174,'PVF/339/18/12',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-13 18:03:29','2018-12-05 00:38:30',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1174/preliminarCCH(9-54-17).pdf',9,0,3),
(1175,'PVF/339/18/13',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-13 22:09:12','2018-12-05 00:38:30',1,1,0,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1175/preliminarCCH(9-56-48).pdf',3,0,1),
(1176,'PVF/339/18/14',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-14 23:05:47','2018-12-05 00:38:30',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1176/preliminarCCH(17-16-25).pdf',6,0,2),
(1178,'PVF/339/18/15',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-16 00:22:11','2018-12-05 00:38:30',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1178/preliminarCCH(18-30-37).pdf',9,0,3),
(1179,'PVF/339/18/16',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-16 22:32:18','2018-12-05 00:38:31',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1179/preliminarCCH(16-48-30).pdf',6,0,2),
(1181,'PVF/339/18/17',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-18 03:33:41','2018-12-05 00:38:31',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1181/preliminarCCH(21-57-46).pdf',6,0,0),
(1182,'PVF/339/18/18',1041,'NO HAY OBSERVACIONES','VIGAS','N',5,28,28,1287,1286,1301,40,'',NULL,'2018-11-20 00:05:50','2018-12-05 00:38:31',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1182/preliminarCCH(0-35-17).pdf',9,0,0),
(1183,'PVF/339/18/19',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-21 04:21:27','2018-12-05 00:38:31',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1183/preliminarCCH(9-35-35).pdf',6,0,0),
(1185,'PVF/339/18/20',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-23 02:18:06','2018-12-05 00:38:31',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1185/preliminarCCH(12-11-26).pdf',9,0,0),
(1186,'PVF/339/18/21',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-23 02:37:54','2018-12-05 00:38:31',1,1,0,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1186/preliminarCCH(12-15-21).pdf',3,0,0),
(1187,'PVF/339/18/22',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-23 23:27:10','2018-12-05 00:38:31',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1187/preliminarCCH(17-37-30).pdf',9,0,0),
(1188,'PVF/339/18/23',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-26 02:29:38','2018-12-05 00:38:31',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1188/preliminarCCH(10-16-33).pdf',9,0,0),
(1189,'PVF/339/18/24',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-26 02:42:07','2018-12-05 00:38:31',1,1,0,2,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1189/preliminarCCH(10-22-4).pdf',3,0,0),
(1192,'PVF/339/18/25',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-27 03:04:45','2018-12-05 00:39:53',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1192/preliminarCCH(12-27-23).pdf',9,0,0),
(1193,'PVF/339/18/26',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-27 03:19:24','2018-12-05 00:39:53',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1193/preliminarCCH(12-33-51).pdf',6,0,0),
(1194,'PVF/339/18/27',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-28 04:31:30','2018-12-05 00:38:32',1,1,0,6,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1194/preliminarCCH(17-19-17).pdf',9,0,0),
(1195,'PVF/339/18/28',1041,'CONCRETO CON FIBRA','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-28 04:49:25','2018-12-05 00:38:40',1,1,0,4,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1195/preliminarCCH(17-25-21).pdf',6,0,0),
(1196,'PVF/339/18/29',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-29 03:25:42','2018-12-05 00:27:42',1,1,0,1161,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1196/preliminarCCH(16-46-15).pdf',9,0,0),
(1197,'PVF/339/18/30',1041,'COLADO SE REALIZA EN CONDICIONES LLUVIOSAS','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-11-30 01:06:52','2018-12-05 00:27:42',1,1,0,1161,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1197/preliminarCCH(22-9-52).pdf',9,0,0),
(1201,'PVF/339/18/1',1041,'NO HAY OBSERVACIONES','CILINDRO','N',0,0,0,NULL,NULL,NULL,NULL,'',NULL,'2018-12-01 03:28:55','2018-12-05 00:27:42',1,0,0,1161,NULL,0,0,0),
(1202,'PVF/339/18/31',1041,'EL COLADO SE REALIZA EN CONDICIONES DE LLUVIA ','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-12-01 03:32:52','2018-12-05 00:27:42',1,1,0,1161,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1202/preliminarCCH(22-28-6).pdf',9,0,0),
(1203,'PVF/339/18/32',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-12-02 04:31:42','2018-12-05 00:27:42',1,1,0,1161,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1203/preliminarCCH(9-21-10).pdf',9,0,0),
(1204,'PVF/339/18/33',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-12-03 04:22:27','2018-12-05 00:27:42',1,1,0,1161,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1204/preliminarCCH(9-26-27).pdf',6,0,0),
(1205,'PVF/339/18/34',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-12-04 03:34:21','2018-12-05 02:53:53',1,1,0,9,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosData/1002/1031/1041/1205/preliminarCCH(20-52-0).pdf',9,1,0),
(1207,'PVF/339/18/35',1041,'NO HAY OBSERVACIONES','VIGAS','N',7,28,28,1287,1286,1301,40,'',NULL,'2018-12-05 03:50:55','2018-12-05 03:51:52',1,0,0,8,NULL,0,0,0);


INSERT INTO `footerEnsayo`(id_footerEnsayo,buscula_id,regVerFle_id,prensa_id,tipo,createdON,lastEditedON,active,observaciones,encargado_id,status,pendingEnsayos,formatoCampo_id,preliminarGabs,ensayosAwaitingApproval,notVistoJLForEnsayoApproval) VALUES 
(55,1005,1339,1331,'VIGAS','2018-11-12 16:17:20','2018-12-04 17:58:44',1,0,1062,0,-2,1161,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/55/ensayosVIGAS(15-39-15).pdf',2,0),
(57,1005,1006,1008,'VIGAS','2018-11-14 16:26:41','2018-12-04 21:47:54',1,0,1062,0,0,1162,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/57/ensayosVIGAS(13-46-54).pdf',0,0),
(58,1005,1006,1008,'VIGAS','2018-11-14 17:33:39','2018-11-21 23:23:26',1,0,1062,0,0,1165,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/58/ensayosVIGAS(14-56-15).pdf',0,0),
(59,1005,1339,1331,'VIGAS','2018-11-14 17:34:48','2018-11-21 20:58:56',1,0,1062,0,0,1166,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/59/ensayosVIGAS(12-25-24).pdf',0,0),
(60,1005,1006,1008,'VIGAS','2018-11-14 17:56:39','2018-12-04 21:50:29',1,0,1062,0,0,1163,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/60/ensayosVIGAS(13-45-49).pdf',0,0),
(61,1005,1339,1331,'VIGAS','2018-11-14 18:15:33','2018-11-21 23:33:58',1,0,1062,0,0,1167,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/61/ensayosVIGAS(12-29-6).pdf',0,0),
(62,1005,1339,1331,'VIGAS','2018-11-15 21:07:04','2018-11-22 00:11:44',1,0,1062,0,0,1169,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/62/ensayosVIGAS(19-25-34).pdf',0,0),
(63,1005,1339,1331,'VIGAS','2018-11-16 15:01:23','2018-11-22 00:21:09',1,0,1062,0,0,1170,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/63/ensayosVIGAS(9-11-52).pdf',0,0),
(64,1005,1339,1331,'VIGAS','2018-11-17 14:54:48','2018-11-22 00:34:01',1,0,1062,0,0,1171,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/64/ensayosVIGAS(9-20-21).pdf',0,0),
(66,1005,1339,1331,'VIGAS','2018-11-20 21:22:45','2018-11-21 23:48:52',1,0,1062,0,0,1172,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/66/ensayosVIGAS(15-43-16).pdf',0,0),
(67,1005,1339,1331,'VIGAS','2018-11-20 21:31:13','2018-11-21 23:49:51',1,0,1062,0,0,1173,NULL,0,0),
(68,1005,1339,1331,'VIGAS','2018-11-20 21:33:57','2018-11-21 23:51:57',1,0,1062,0,0,1174,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/68/ensayosVIGAS(15-46-21).pdf',0,0),
(69,1005,1339,1331,'VIGAS','2018-11-20 21:40:21','2018-11-22 00:34:51',1,0,1062,0,0,1175,NULL,0,0),
(70,1005,1339,1331,'VIGAS','2018-11-21 15:38:00','2018-11-21 23:54:30',1,0,1062,0,0,1176,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/70/ensayosVIGAS(9-45-3).pdf',0,0),
(71,1005,1339,1331,'VIGAS','2018-11-22 16:03:03','2018-11-27 19:14:57',1,0,1062,0,0,1178,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/71/ensayosVIGAS(10-14-57).pdf',0,0),
(72,1005,1339,1331,'VIGAS','2018-11-23 16:25:42','2018-11-27 15:56:42',1,0,1062,0,0,1179,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/72/ensayosVIGAS(13-37-38).pdf',0,0),
(73,1005,1339,1331,'VIGAS','2018-11-24 15:57:02','2018-11-28 22:16:08',1,0,1062,0,0,1181,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/73/ensayosVIGAS(16-16-8).pdf',0,0),
(74,1005,1339,1331,'VIGAS','2018-11-26 16:15:33','2018-11-27 00:17:56',1,0,1062,0,0,1182,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/74/ensayosVIGAS(14-2-3).pdf',0,0),
(75,1005,1339,1331,'VIGAS','2018-11-27 16:19:34','2018-11-28 01:23:23',1,0,1062,0,0,1183,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/75/ensayosVIGAS(19-23-23).pdf',0,0),
(76,1005,1339,1331,'VIGAS','2018-11-29 16:24:37','2018-11-29 22:56:52',1,0,1062,0,0,1185,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/76/ensayosVIGAS(16-56-52).pdf',0,0),
(77,1005,1339,1331,'VIGAS','2018-11-29 16:32:40','2018-12-03 19:56:35',1,0,1062,0,0,1186,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/77/ensayosVIGAS(13-56-34).pdf',0,0),
(78,1005,1339,1331,'VIGAS','2018-11-30 18:47:38','2018-12-03 19:58:22',1,0,1062,0,0,1187,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/78/ensayosVIGAS(13-58-22).pdf',0,0),
(79,1005,1339,1331,'VIGAS','2018-12-03 17:43:02','2018-12-03 22:11:25',1,0,1062,0,0,1188,NULL,0,0),
(80,1005,1339,1331,'VIGAS','2018-12-03 19:42:18','2018-12-03 22:12:52',1,0,1062,0,0,1189,NULL,0,0),
(81,1005,1339,1331,'VIGAS','2018-12-03 19:44:48','2018-12-03 22:15:05',1,0,1062,0,0,1192,NULL,0,0),
(82,1005,1339,1331,'VIGAS','2018-12-03 19:50:37','2018-12-03 22:16:56',1,0,1062,0,0,1193,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/82/ensayosVIGAS(13-54-47).pdf',0,0),
(83,1005,1339,1331,'VIGAS','2018-12-04 19:29:41','2018-12-04 21:53:15',1,0,1062,0,0,1194,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/83/ensayosVIGAS(13-42-20).pdf',0,0),
(84,1005,1339,1331,'VIGAS','2018-12-04 19:36:53','2018-12-04 21:58:04',1,0,1062,0,0,1195,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosGabsData/VIGAS/84/ensayosVIGAS(13-40-11).pdf',0,0);

INSERT INTO `registrosCampo` (id_registrosCampo,formatoCampo_id,fecha,fprima,revProyecto,revObra,tamAgregado,volumen,unidad,horaMuestreo,tempMuestreo,tempRecoleccion,localizacion,status,createdON,lastEditedON,active,diasEnsaye,herramienta_id,claveEspecimen,consecutivoProbeta,footerEnsayo_id,statusEnsayo,grupo,loteStatus) VALUES 
(1454,1161,'2018-11-05','48',10,9,40,8,'2460','11:50:00',18,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 00:03:45','2018-11-21 20:29:29',1,1,1289,'PVF-XI-5-GLCC-02-336',336,55,1,1,0),(1455,1161,'2018-11-05','48',10,9,40,8,'2460','11:50:00',18,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 00:03:45','2018-12-03 16:43:40',1,2,1290,'PVF-XI-5-GLCC-06-337',337,55,1,1,0),(1456,1161,'2018-11-05','48',10,9,40,8,'2460','11:50:00',18,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 00:03:45','2018-12-03 16:46:40',1,3,1291,'PVF-XI-5-GLCC-09-338',338,55,1,1,0),(1457,1161,'2018-11-05','48',10,10,40,8,'2440','14:40:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 01:09:55','2018-11-21 20:38:12',1,4,1292,'PVF-XI-6-GLCC-10-339',339,55,1,2,0),(1458,1161,'2018-11-05','48',10,10,40,8,'2440','14:40:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 01:09:55','2018-12-03 16:40:29',1,5,1296,'PVF-XI-6-GLCC-11-340',340,55,1,2,0),(1459,1161,'2018-11-05','48',10,10,40,8,'2440','14:40:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 01:09:55','2018-12-03 16:53:44',1,6,1297,'PVF-XI-6-GLCC-12-341',341,55,1,2,0),(1460,1161,'2018-11-06','48',10,11,40,8,'2552','15:50:00',21,21,'LOSA MR   CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 17:59:52','2018-11-21 20:43:27',1,7,1298,'PVF-XI-6-GLCC-16-342',342,55,1,3,0),(1461,1161,'2018-11-06','48',10,11,40,8,'2552','15:50:00',21,21,'LOSA MR   CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 17:59:52','2018-12-04 17:00:56',1,8,1299,'PVF-XI-6-GLCC-18-343',343,55,1,3,0),(1462,1161,'2018-11-06','48',10,11,40,8,'2552','15:50:00',21,21,'LOSA MR   CUERPO IZQUIERDO LADO IZQUIERDO KM 12+125 AL KM 11+906 ',4,'2018-11-06 17:59:52','2018-12-04 17:03:18',1,9,1300,'PVF-XI-6-GLCC-19-344',344,55,1,3,0),(1463,1162,'2018-11-06','48',10,10,40,8,'2472','12:00:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906  AL KM 11+890',4,'2018-11-06 18:40:48','2018-11-21 23:20:07',1,1,1289,'PVF-XI-6-GLCC-02-345',345,57,1,1,0),(1464,1162,'2018-11-06','48',10,10,40,8,'2472','12:00:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906  AL KM 11+890',4,'2018-11-06 18:40:48','2018-12-04 19:22:11',1,2,1290,'PVF-XI-6-GLCC-06-346',346,57,1,1,0),(1465,1162,'2018-11-06','48',10,10,40,8,'2472','12:00:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906  AL KM 11+890',4,'2018-11-06 18:40:48','2018-12-04 19:24:15',1,3,1291,'PVF-XI-6-GLCC-09-347',347,57,1,1,0),(1467,1163,'2018-11-06','48',10,10,40,8,'2410','14:20:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906 AL KM ',4,'2018-11-07 03:13:19','2018-11-21 23:26:53',1,1,1296,'PVF-XI-6-GLCC-11-348',348,60,1,1,0),(1468,1163,'2018-11-06','48',10,10,40,8,'2410','14:20:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906 AL KM ',4,'2018-11-07 03:13:19','2018-12-04 19:26:35',1,2,1297,'PVF-XI-6-GLCC-12-349',349,60,1,1,0),(1469,1163,'2018-11-06','48',10,10,40,8,'2410','14:20:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906 AL KM ',4,'2018-11-07 03:13:19','2018-12-04 19:29:17',1,3,1292,'PVF-XI-6-GLCC-10-350',350,60,1,1,0),(1470,1165,'2018-11-07','48',10,10,40,8,'2466','17:40:00',22,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906 AL KM 11+683',4,'2018-11-07 23:28:55','2018-11-21 23:23:26',1,1,1298,'PVF-XI-7-GLCC-16-351',351,58,1,1,0),(1471,1165,'2018-11-07','48',10,10,40,8,'2466','17:40:00',22,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906 AL KM 11+683',4,'2018-11-07 23:28:55','2018-11-21 23:23:26',1,2,1299,'PVF-XI-7-GLCC-18-352',352,NULL,0,1,0),(1472,1165,'2018-11-07','48',10,10,40,8,'2466','17:40:00',22,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+906 AL KM 11+683',4,'2018-11-07 23:28:55','2018-11-21 23:23:26',1,3,1300,'PVF-XI-7-GLCC-19-353',353,NULL,0,1,0),(1473,1166,'2018-11-07','48',10,9,40,8,'2472','21:20:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+356',4,'2018-11-08 00:08:21','2018-11-21 20:56:47',1,1,1289,'PVF-XI-7-GLCC-02-354',354,59,1,1,0),(1474,1166,'2018-11-07','48',10,9,40,8,'2472','21:20:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+356',4,'2018-11-08 00:08:21','2018-11-21 20:56:47',1,2,1290,'PVF-XI-7-GLCC-06-355',355,NULL,0,1,0),(1475,1166,'2018-11-07','48',10,9,40,8,'2472','21:20:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+356',4,'2018-11-08 00:08:21','2018-11-21 20:56:47',1,3,1291,'PVF-XI-7-GLCC-09-356',356,NULL,0,1,0),(1476,1166,'2018-11-07','48',10,8,40,8,'2440','12:05:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356',4,'2018-11-08 00:14:56','2018-11-21 20:58:04',1,4,1296,'PVF-XI-7-GLCC-11-357',357,59,1,2,0),(1477,1166,'2018-11-07','48',10,8,40,8,'2440','12:05:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356',4,'2018-11-08 00:14:56','2018-11-21 20:58:04',1,5,1297,'PVF-XI-7-GLCC-12-358',358,NULL,0,2,0),(1478,1166,'2018-11-07','48',10,8,40,8,'2440','12:05:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356',4,'2018-11-08 00:14:56','2018-11-21 20:58:04',1,6,1292,'PVF-XI-7-GLCC-10-359',359,NULL,0,2,0),(1479,1166,'2018-11-07','48',10,10,40,8,'2455','14:20:00',22,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356 ',4,'2018-11-08 00:23:10','2018-11-21 20:58:56',1,7,1322,'PVF-XI-7-GLCC - 13-360',360,59,1,3,0),(1480,1166,'2018-11-07','48',10,10,40,8,'2455','14:20:00',22,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356 ',4,'2018-11-08 00:23:10','2018-11-21 20:58:56',1,8,1323,'PVF-XI-7-GLCC - 17-361',361,NULL,0,3,0),(1481,1166,'2018-11-07','48',10,10,40,8,'2455','14:20:00',22,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356 ',4,'2018-11-08 00:23:10','2018-11-21 20:58:56',1,9,1324,'PVF-XI-7-GLCC - 23-362',362,NULL,0,3,0),(1482,1167,'2018-11-07','48',10,10,40,8,'2466','18:00:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356',4,'2018-11-08 00:39:31','2018-11-21 23:33:58',1,1,1325,'PVF-XI-7-GLCC - 26-363',363,61,1,1,0),(1483,1167,'2018-11-07','48',10,10,40,8,'2466','18:00:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356',4,'2018-11-08 00:39:31','2018-11-21 23:33:58',1,2,1326,'PVF-XI-7-GLCC - 27-364',364,NULL,0,1,0),(1484,1167,'2018-11-07','48',10,10,40,8,'2466','18:00:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+683 AL KM 11+356',4,'2018-11-08 00:39:31','2018-11-21 23:33:58',1,3,1327,'PVF-XI-7-GLCC - 30-365',365,NULL,0,1,0),(1485,1169,'2018-11-08','48',10,10,40,8,'2057','08:10:00',18,18,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:15:17','2018-11-22 00:05:38',1,1,1289,'PVF-XI-15-GLCC-02-366',366,62,1,1,0),(1486,1169,'2018-11-08','48',10,10,40,8,'2057','08:10:00',18,18,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:15:18','2018-11-22 00:05:38',1,2,1290,'PVF-XI-15-GLCC-06-367',367,NULL,0,1,0),(1487,1169,'2018-11-08','48',10,10,40,8,'2057','08:10:00',18,18,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:15:18','2018-11-22 00:05:38',1,3,1291,'PVF-XI-15-GLCC-09-368',368,NULL,0,1,0),(1488,1169,'2018-11-08','48',10,9,40,8,'2410','10:20:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:20:00','2018-11-22 00:07:09',1,4,1292,'PVF-XI-15-GLCC-10-369',369,62,1,2,0),(1489,1169,'2018-11-08','48',10,9,40,8,'2410','10:20:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:20:00','2018-11-22 00:07:09',1,5,1296,'PVF-XI-15-GLCC-11-370',370,NULL,0,2,0),(1490,1169,'2018-11-08','48',10,9,40,8,'2410','10:20:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:20:00','2018-11-22 00:07:09',1,6,1297,'PVF-XI-15-GLCC-12-371',371,NULL,0,2,0),(1491,1169,'2018-11-08','48',10,10,40,8,'2466 ','11:57:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:23:55','2018-11-22 00:11:44',1,7,1298,'PVF-XI-15-GLCC-16-372',372,62,1,3,0),(1492,1169,'2018-11-08','48',10,10,40,8,'2466 ','11:57:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:23:55','2018-11-22 00:11:44',1,8,1299,'PVF-XI-15-GLCC-18-373',373,NULL,0,3,0),(1493,1169,'2018-11-08','48',10,10,40,8,'2466 ','11:57:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+357.5 AL KM 11+262.80 Y 10+997 AL KM 11+077',4,'2018-11-08 17:23:55','2018-11-22 00:11:44',1,9,1300,'PVF-XI-15-GLCC-19-374',374,NULL,0,3,0),(1494,1170,'2018-11-09','48',10,11,40,8,'2232','09:30:00',18,18,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077',4,'2018-11-09 20:42:02','2018-11-21 23:42:53',1,1,1289,'PVF-XI-9-GLCC-02-375',375,63,1,1,0),(1495,1170,'2018-11-09','48',10,11,40,8,'2232','09:30:00',18,18,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077',4,'2018-11-09 20:42:03','2018-11-21 23:42:53',1,2,1290,'PVF-XI-9-GLCC-06-376',376,NULL,0,1,0),(1496,1170,'2018-11-09','48',10,11,40,8,'2232','09:30:00',18,18,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077',4,'2018-11-09 20:42:03','2018-11-21 23:42:53',1,3,1291,'PVF-XI-9-GLCC-09-377',377,NULL,0,1,0),(1497,1170,'2018-11-09','48',10,12,40,8,'2410','12:20:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077',4,'2018-11-10 00:24:35','2018-11-22 00:20:02',1,4,1292,'PVF-XI-9-GLCC-10-378',378,63,1,2,0),(1498,1170,'2018-11-09','48',10,12,40,8,'2410','12:20:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077',4,'2018-11-10 00:24:35','2018-11-22 00:20:02',1,5,1296,'PVF-XI-9-GLCC-11-379',379,NULL,0,2,0),(1499,1170,'2018-11-09','48',10,12,40,8,'2410','12:20:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077',4,'2018-11-10 00:24:35','2018-11-22 00:20:02',1,6,1297,'PVF-XI-9-GLCC-12-380',380,NULL,0,2,0),(1500,1170,'2018-11-09','48',10,10,40,8,'2457','14:30:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077 ',4,'2018-11-10 00:26:49','2018-11-22 00:21:09',1,7,1298,'PVF-XI-9-GLCC-16-381',381,63,1,3,0),(1501,1170,'2018-11-09','48',10,10,40,8,'2457','14:30:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077 ',4,'2018-11-10 00:26:49','2018-11-22 00:21:09',1,8,1299,'PVF-XI-9-GLCC-18-382',382,NULL,0,3,0),(1502,1170,'2018-11-09','48',10,10,40,8,'2457','14:30:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 11+262.80 AL KM 11+077 ',4,'2018-11-10 00:26:49','2018-11-22 00:21:09',1,9,1300,'PVF-XI-9-GLCC-19-383',383,NULL,0,3,0),(1503,1171,'2018-11-10','48',10,9,40,8,'2462','10:31:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+874 AL KM 12+889 Y KM 10+920 AL KM 10+961',4,'2018-11-10 21:56:39','2018-11-21 23:45:36',1,1,1289,'PVF-XI-10-GLCC-02-384',384,64,1,1,0),(1504,1171,'2018-11-10','48',10,9,40,8,'2462','10:31:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+874 AL KM 12+889 Y KM 10+920 AL KM 10+961',4,'2018-11-10 21:56:39','2018-11-21 23:45:36',1,2,1290,'PVF-XI-10-GLCC-06-385',385,NULL,0,1,0),(1505,1171,'2018-11-10','48',10,9,40,8,'2462','10:31:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+874 AL KM 12+889 Y KM 10+920 AL KM 10+961',4,'2018-11-10 21:56:39','2018-11-21 23:45:36',1,3,1291,'PVF-XI-10-GLCC-09-386',386,NULL,0,1,0),(1506,1171,'2018-11-10','48',10,10,40,8,'2232','12:03:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+874 AL KM 12+889 Y KM 10+920 AL KM 10+961 ',4,'2018-11-10 22:01:53','2018-11-22 00:34:01',1,4,1292,'PVF-XI-10-GLCC-10-387',387,64,1,2,0),(1507,1171,'2018-11-10','48',10,10,40,8,'2232','12:03:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+874 AL KM 12+889 Y KM 10+920 AL KM 10+961 ',4,'2018-11-10 22:01:53','2018-11-22 00:34:01',1,5,1296,'PVF-XI-10-GLCC-11-388',388,NULL,0,2,0),(1508,1171,'2018-11-10','48',10,10,40,8,'2232','12:03:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO IZQUIERDO KM 12+874 AL KM 12+889 Y KM 10+920 AL KM 10+961 ',4,'2018-11-10 22:01:53','2018-11-22 00:34:01',1,6,1297,'PVF-XI-10-GLCC-12-389',389,NULL,0,2,0),(1509,1172,'2018-11-12','48',10,8,40,8,'2552','09:39:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20',4,'2018-11-12 21:45:09','2018-11-21 23:47:43',1,1,1289,'PVF-XI-12-GLCC-02-390',390,66,1,1,0),(1510,1172,'2018-11-12','48',10,8,40,8,'2552','09:39:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20',4,'2018-11-12 21:45:09','2018-11-21 23:47:43',1,2,1290,'PVF-XI-12-GLCC-06-391',391,NULL,0,1,0),(1511,1172,'2018-11-12','48',10,8,40,8,'2552','09:39:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20',4,'2018-11-12 21:45:09','2018-11-21 23:47:43',1,3,1291,'PVF-XI-12-GLCC-09-392',392,NULL,0,1,0),(1512,1172,'2018-11-12','48',10,9,40,8,'2466','11:40:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20 ',4,'2018-11-12 21:49:37','2018-11-21 23:48:17',1,4,1292,'PVF-XI-12-GLCC-10-393',393,66,1,2,0),(1513,1172,'2018-11-12','48',10,9,40,8,'2466','11:40:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20 ',4,'2018-11-12 21:49:37','2018-11-21 23:48:17',1,5,1296,'PVF-XI-12-GLCC-11-394',394,NULL,0,2,0),(1514,1172,'2018-11-12','48',10,9,40,8,'2466','11:40:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20 ',4,'2018-11-12 21:49:37','2018-11-21 23:48:17',1,6,1297,'PVF-XI-12-GLCC-12-395',395,NULL,0,2,0),(1515,1172,'2018-11-12','48',10,8,40,8,'2410','14:30:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20  ',4,'2018-11-12 21:55:56','2018-11-21 23:48:52',1,7,1322,'PVF-XI-12-GLCC - 13-396',396,66,1,3,0),(1516,1172,'2018-11-12','48',10,8,40,8,'2410','14:30:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20  ',4,'2018-11-12 21:55:56','2018-11-21 23:48:52',1,8,1298,'PVF-XI-12-GLCC-16-397',397,NULL,0,3,0),(1517,1172,'2018-11-12','48',10,8,40,8,'2410','14:30:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20  ',4,'2018-11-12 21:55:56','2018-11-21 23:48:52',1,9,1323,'PVF-XI-12-GLCC - 17-398',398,NULL,0,3,0),(1518,1173,'2018-11-12','48',10,8,40,8,'2460','16:40:00',21,21,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20  AL KM  15+260',4,'2018-11-13 03:48:10','2018-11-21 23:49:51',1,1,1299,'PVF-XI-12-GLCC-18-399',399,67,1,1,0),(1519,1173,'2018-11-12','48',10,8,40,8,'2460','16:40:00',21,21,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20  AL KM  15+260',4,'2018-11-13 03:48:10','2018-11-21 23:49:51',1,2,1300,'PVF-XI-12-GLCC-19-400',400,NULL,0,1,0),(1520,1173,'2018-11-12','48',10,8,40,8,'2460','16:40:00',21,21,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+516.20  AL KM  15+260',4,'2018-11-13 03:48:10','2018-11-21 23:49:51',1,3,1324,'PVF-XI-12-GLCC - 23-401',401,NULL,0,1,0),(1521,1174,'2018-11-13','48',10,8,40,8,'2552','08:55:00',18,18,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM ',4,'2018-11-13 18:06:33','2018-11-21 23:51:04',1,1,1289,'PVF-XI-13-GLCC-02-402',402,68,1,1,0),(1522,1174,'2018-11-13','48',10,8,40,8,'2552','08:55:00',18,18,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM ',4,'2018-11-13 18:06:33','2018-11-21 23:51:04',1,2,1290,'PVF-XI-13-GLCC-06-403',403,NULL,0,1,0),(1523,1174,'2018-11-13','48',10,8,40,8,'2552','08:55:00',18,18,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM ',4,'2018-11-13 18:06:33','2018-11-21 23:51:04',1,3,1291,'PVF-XI-13-GLCC-09-404',404,NULL,0,1,0),(1524,1174,'2018-11-13','48',10,8,40,8,'2410','10:45:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM  ',4,'2018-11-13 21:05:56','2018-11-21 23:51:32',1,4,1292,'PVF-XI-13-GLCC-10-405',405,68,1,2,0),(1525,1174,'2018-11-13','48',10,8,40,8,'2410','10:45:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM  ',4,'2018-11-13 21:05:56','2018-11-21 23:51:32',1,5,1296,'PVF-XI-13-GLCC-11-406',406,NULL,0,2,0),(1526,1174,'2018-11-13','48',10,8,40,8,'2410','10:45:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM  ',4,'2018-11-13 21:05:56','2018-11-21 23:51:32',1,6,1297,'PVF-XI-13-GLCC-12-407',407,NULL,0,2,0),(1527,1174,'2018-11-13','48',10,9,40,8,'2457 ','14:20:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM ',4,'2018-11-13 21:09:41','2018-11-21 23:51:57',1,7,1322,'PVF-XI-13-GLCC - 13-408',408,68,1,3,0),(1528,1174,'2018-11-13','48',10,9,40,8,'2457 ','14:20:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM ',4,'2018-11-13 21:09:41','2018-11-21 23:51:57',1,8,1298,'PVF-XI-13-GLCC-16-409',409,NULL,0,3,0),(1529,1174,'2018-11-13','48',10,9,40,8,'2457 ','14:20:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM ',4,'2018-11-13 21:09:41','2018-11-21 23:51:57',1,9,1323,'PVF-XI-13-GLCC - 17-410',410,NULL,0,3,0),(1530,1175,'2018-11-13','48',10,10,40,8,'2466','16:15:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM  15+008 ',4,'2018-11-13 22:10:02','2018-11-22 00:34:51',1,1,1299,'PVF-XI-13-GLCC-18-411',411,69,1,1,0),(1531,1175,'2018-11-13','48',10,10,40,8,'2466','16:15:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM  15+008 ',4,'2018-11-13 22:10:02','2018-11-22 00:34:51',1,2,1300,'PVF-XI-13-GLCC-19-412',412,NULL,0,1,0),(1532,1175,'2018-11-13','48',10,10,40,8,'2466','16:15:00',20,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+258.60 AL KM  15+008 ',4,'2018-11-13 22:10:02','2018-11-22 00:34:51',1,3,1324,'PVF-XI-13-GLCC - 23-413',413,NULL,0,1,0),(1536,1176,'2018-11-14','48',10,10,40,8,'2457 ','08:10:00',16,16,'LOSA MR CUERPO DERECHO LADO IZQUIERDO DEL KM 15+008 AL KM 14+886',4,'2018-11-14 23:06:58','2018-11-21 23:53:30',1,1,1289,'PVF-XI-14-GLCC-02-414',414,70,1,1,0),(1537,1176,'2018-11-14','48',10,10,40,8,'2457 ','08:10:00',16,16,'LOSA MR CUERPO DERECHO LADO IZQUIERDO DEL KM 15+008 AL KM 14+886',4,'2018-11-14 23:06:58','2018-11-21 23:53:30',1,2,1290,'PVF-XI-14-GLCC-06-415',415,NULL,0,1,0),(1538,1176,'2018-11-14','48',10,10,40,8,'2457 ','08:10:00',16,16,'LOSA MR CUERPO DERECHO LADO IZQUIERDO DEL KM 15+008 AL KM 14+886',4,'2018-11-14 23:06:58','2018-11-21 23:53:30',1,3,1291,'PVF-XI-14-GLCC-09-416',416,NULL,0,1,0),(1539,1176,'2018-11-14','48',10,9,40,8,'2460','12:40:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+008 AL KM 14+886',4,'2018-11-14 23:10:42','2018-11-21 23:54:30',1,4,1292,'PVF-XI-14-GLCC-10-417',417,70,1,2,0),(1540,1176,'2018-11-14','48',10,9,40,8,'2460','12:40:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+008 AL KM 14+886',4,'2018-11-14 23:10:42','2018-11-21 23:54:30',1,5,1296,'PVF-XI-14-GLCC-11-418',418,NULL,0,2,0),(1541,1176,'2018-11-14','48',10,9,40,8,'2460','12:40:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+008 AL KM 14+886',4,'2018-11-14 23:10:42','2018-11-21 23:54:30',1,6,1297,'PVF-XI-14-GLCC-12-419',419,NULL,0,2,0),(1545,1178,'2018-11-15','48',10,8,40,8,'2457','10:24:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720',4,'2018-11-16 00:22:54','2018-11-27 19:11:40',1,1,1289,'PVF-XI-15-GLCC-02-420',420,71,1,1,0),(1546,1178,'2018-11-15','48',10,8,40,8,'2457','10:24:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720',4,'2018-11-16 00:22:54','2018-11-27 19:11:40',1,2,1290,'PVF-XI-15-GLCC-06-421',421,NULL,0,1,0),(1547,1178,'2018-11-15','48',10,8,40,8,'2457','10:24:00',19,19,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720',4,'2018-11-16 00:22:54','2018-11-27 19:11:40',1,3,1291,'PVF-XI-15-GLCC-09-422',422,NULL,0,1,0),(1548,1178,'2018-11-15','48',10,8,40,8,'2232','14:25:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720 ',4,'2018-11-16 00:25:31','2018-11-27 19:14:07',1,4,1292,'PVF-XI-15-GLCC-10-423',423,71,1,2,0),(1549,1178,'2018-11-15','48',10,8,40,8,'2232','14:25:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720 ',4,'2018-11-16 00:25:31','2018-11-27 19:14:07',1,5,1296,'PVF-XI-15-GLCC-11-424',424,NULL,0,2,0),(1550,1178,'2018-11-15','48',10,8,40,8,'2232','14:25:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720 ',4,'2018-11-16 00:25:31','2018-11-27 19:14:07',1,6,1297,'PVF-XI-15-GLCC-12-425',425,NULL,0,2,0),(1551,1178,'2018-11-15','48',10,9,40,8,'2460','15:15:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720  ',4,'2018-11-16 00:27:24','2018-11-27 19:14:57',1,7,1298,'PVF-XI-15-GLCC-16-426',426,71,1,3,0),(1552,1178,'2018-11-15','48',10,9,40,8,'2460','15:15:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720  ',4,'2018-11-16 00:27:24','2018-11-27 19:14:57',1,8,1299,'PVF-XI-15-GLCC-18-427',427,NULL,0,3,0),(1553,1178,'2018-11-15','48',10,9,40,8,'2460','15:15:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+886 AL KM 14+720  ',4,'2018-11-16 00:27:24','2018-11-27 19:14:57',1,9,1300,'PVF-XI-15-GLCC-19-428',428,NULL,0,3,0),(1554,1179,'2018-11-16','48',9,9,40,8,'2466 ','10:00:00',18,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO DEL KM 15+529.20 AL KM 15+516.20 Y KM 14+682 AL KM 14+604.60',4,'2018-11-16 22:33:43','2018-11-23 17:24:18',1,1,1289,'PVF-XI-16-GLCC-02-429',429,72,1,1,0),(1555,1179,'2018-11-16','48',9,9,40,8,'2466 ','10:00:00',18,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO DEL KM 15+529.20 AL KM 15+516.20 Y KM 14+682 AL KM 14+604.60',4,'2018-11-16 22:33:43','2018-11-21 20:51:19',1,2,1290,'PVF-XI-16-GLCC-06-430',430,NULL,0,1,0),(1556,1179,'2018-11-16','48',9,9,40,8,'2466 ','10:00:00',18,20,'LOSA MR CUERPO DERECHO LADO IZQUIERDO DEL KM 15+529.20 AL KM 15+516.20 Y KM 14+682 AL KM 14+604.60',4,'2018-11-16 22:33:44','2018-11-21 20:51:19',1,3,1291,'PVF-XI-16-GLCC-09-431',431,NULL,0,1,0),(1557,1179,'2018-11-16','48',10,9,40,8,'2457','15:10:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+529.20 AL KM 15+516.20 Y KM 14+682 AL KM 14+604.60',4,'2018-11-16 22:41:12','2018-11-27 15:56:42',1,4,1292,'PVF-XI-16-GLCC-10-432',432,72,1,2,0),(1558,1179,'2018-11-16','48',10,9,40,8,'2457','15:10:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+529.20 AL KM 15+516.20 Y KM 14+682 AL KM 14+604.60',4,'2018-11-16 22:41:12','2018-11-27 15:56:42',1,5,1296,'PVF-XI-16-GLCC-11-433',433,NULL,0,2,0),(1559,1179,'2018-11-16','48',10,9,40,8,'2457','15:10:00',22,22,'LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 15+529.20 AL KM 15+516.20 Y KM 14+682 AL KM 14+604.60',4,'2018-11-16 22:41:12','2018-11-27 15:56:42',1,6,1297,'PVF-XI-16-GLCC-12-434',434,NULL,0,2,0),(1560,1181,'2018-11-17','48',10,8,40,8,'2457  ','10:45:00',18,18,'COLADO DE LOSA REMATE DE CAMELLÃ“N KM 15+516 AL KM 15+016 Y LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+604 AL KM 14+592',4,'2018-11-18 03:35:16','2018-11-27 23:31:06',1,1,1289,'PVF-XI-17-GLCC-02-435',435,73,1,1,0),(1561,1181,'2018-11-17','48',10,8,40,8,'2457  ','10:45:00',18,18,'COLADO DE LOSA REMATE DE CAMELLÃ“N KM 15+516 AL KM 15+016 Y LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+604 AL KM 14+592',4,'2018-11-18 03:35:16','2018-11-27 23:31:06',1,2,1290,'PVF-XI-17-GLCC-06-436',436,NULL,0,1,0),(1562,1181,'2018-11-17','48',10,8,40,8,'2457  ','10:45:00',18,18,'COLADO DE LOSA REMATE DE CAMELLÃ“N KM 15+516 AL KM 15+016 Y LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+604 AL KM 14+592',4,'2018-11-18 03:35:16','2018-11-27 23:31:06',1,3,1291,'PVF-XI-17-GLCC-09-437',437,NULL,0,1,0),(1563,1181,'2018-11-17','48',10,8,40,8,'2455','16:00:00',22,22,'COLADO DE LOSA REMATE DE CAMELLÃ“N KM 15+516 AL KM 15+016 Y LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+604 AL KM 14+592 ',4,'2018-11-18 03:51:33','2018-11-27 23:37:13',1,4,1292,'PVF-XI-17-GLCC-10-438',438,73,1,2,0),(1564,1181,'2018-11-17','48',10,8,40,8,'2455','16:00:00',22,22,'COLADO DE LOSA REMATE DE CAMELLÃ“N KM 15+516 AL KM 15+016 Y LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+604 AL KM 14+592 ',4,'2018-11-18 03:51:33','2018-11-27 23:37:13',1,5,1296,'PVF-XI-17-GLCC-11-439',439,NULL,0,2,0),(1565,1181,'2018-11-17','48',10,8,40,8,'2455','16:00:00',22,22,'COLADO DE LOSA REMATE DE CAMELLÃ“N KM 15+516 AL KM 15+016 Y LOSA MR CUERPO DERECHO LADO IZQUIERDO KM 14+604 AL KM 14+592 ',4,'2018-11-18 03:51:33','2018-11-27 23:37:13',1,6,1297,'PVF-XI-17-GLCC-12-440',440,NULL,0,2,0),(1566,1182,'2018-11-19','48',10,10,40,8,'2440 ','09:30:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30',4,'2018-11-20 00:06:40','2018-11-27 00:15:55',1,1,1289,'PVF-XI-19-GLCC-02-441',441,74,1,1,0),(1567,1182,'2018-11-19','48',10,10,40,8,'2440 ','09:30:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30',4,'2018-11-20 00:06:40','2018-11-27 00:15:55',1,2,1290,'PVF-XI-19-GLCC-06-442',442,NULL,0,1,0),(1568,1182,'2018-11-19','48',10,10,40,8,'2440 ','09:30:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30',4,'2018-11-20 00:06:40','2018-11-27 00:15:55',1,3,1291,'PVF-XI-19-GLCC-09-443',443,NULL,0,1,0),(1569,1182,'2018-11-19','48',10,9,40,8,'2232 ','13:00:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30 ',4,'2018-11-20 00:11:30','2018-11-27 00:17:28',1,4,1292,'PVF-XI-19-GLCC-10-444',444,74,1,2,0),(1570,1182,'2018-11-19','48',10,9,40,8,'2232 ','13:00:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30 ',4,'2018-11-20 00:11:30','2018-11-27 00:17:28',1,5,1296,'PVF-XI-19-GLCC-11-445',445,NULL,0,2,0),(1571,1182,'2018-11-19','48',10,9,40,8,'2232 ','13:00:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30 ',4,'2018-11-20 00:11:30','2018-11-27 00:17:28',1,6,1297,'PVF-XI-19-GLCC-12-446',446,NULL,0,2,0),(1572,1182,'2018-11-19','48',10,10,40,8,'2552 ','15:00:00',22,22,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30 ',4,'2018-11-20 00:14:47','2018-11-27 00:17:56',1,7,1298,'PVF-XI-19-GLCC-16-447',447,74,1,3,0),(1573,1182,'2018-11-19','48',10,10,40,8,'2552 ','15:00:00',22,22,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30 ',4,'2018-11-20 00:14:47','2018-11-27 00:17:56',1,8,1299,'PVF-XI-19-GLCC-18-448',448,NULL,0,3,0),(1574,1182,'2018-11-19','48',10,10,40,8,'2552 ','15:00:00',22,22,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+529.20 AL KM 15+394.15 Y REMATE DE CAMELLÃ“N KM 15+016 AL KM 14+766.30 ',4,'2018-11-20 00:14:47','2018-11-27 00:17:56',1,9,1300,'PVF-XI-19-GLCC-19-449',449,NULL,0,3,0),(1575,1183,'2018-11-20','48',10,9,40,8,'2471 ','17:20:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+394.15 AL KM 15+329  Y REMATE DE CAMELLÃ“N KM 14+766.30 Al KM 14+592',4,'2018-11-21 04:22:39','2018-11-27 18:18:15',1,1,1289,'PVF-XI-20-GLCC-02-450',450,75,1,1,0),(1576,1183,'2018-11-20','48',10,9,40,8,'2471 ','17:20:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+394.15 AL KM 15+329  Y REMATE DE CAMELLÃ“N KM 14+766.30 Al KM 14+592',4,'2018-11-21 04:22:39','2018-11-27 18:18:15',1,2,1290,'PVF-XI-20-GLCC-06-451',451,NULL,0,1,0),(1577,1183,'2018-11-20','48',10,9,40,8,'2471 ','17:20:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+394.15 AL KM 15+329  Y REMATE DE CAMELLÃ“N KM 14+766.30 Al KM 14+592',4,'2018-11-21 04:22:39','2018-11-27 18:18:15',1,3,1291,'PVF-XI-20-GLCC-09-452',452,NULL,0,1,0),(1578,1183,'2018-11-20','48',10,8,40,8,'2440','18:00:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+394.15 AL KM 15+329  Y REMATE DE CAMELLÃ“N KM 14+766.30  AL KM 14+592',4,'2018-11-21 04:27:43','2018-11-27 23:41:30',1,4,1292,'PVF-XI-20-GLCC-10-453',453,75,1,2,0),(1579,1183,'2018-11-20','48',10,8,40,8,'2440','18:00:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+394.15 AL KM 15+329  Y REMATE DE CAMELLÃ“N KM 14+766.30  AL KM 14+592',4,'2018-11-21 04:27:43','2018-11-27 23:41:30',1,5,1296,'PVF-XI-20-GLCC-11-454',454,NULL,0,2,0),(1580,1183,'2018-11-20','48',10,8,40,8,'2440','18:00:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO  KM 15+394.15 AL KM 15+329  Y REMATE DE CAMELLÃ“N KM 14+766.30  AL KM 14+592',4,'2018-11-21 04:27:43','2018-11-27 23:41:30',1,6,1297,'PVF-XI-20-GLCC-12-455',455,NULL,0,2,0),(1585,1185,'2018-11-22','48',10,10,40,8,'2232 ','12:15:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75',4,'2018-11-23 02:20:09','2018-11-29 22:46:16',1,1,1289,'PVF-XI-22-GLCC-02-456',456,76,1,1,0),(1586,1185,'2018-11-22','48',10,10,40,8,'2232 ','12:15:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75',4,'2018-11-23 02:20:09','2018-11-29 22:46:16',1,2,1290,'PVF-XI-22-GLCC-06-457',457,NULL,0,1,0),(1587,1185,'2018-11-22','48',10,10,40,8,'2232 ','12:15:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75',4,'2018-11-23 02:20:09','2018-11-29 22:46:16',1,3,1291,'PVF-XI-22-GLCC-09-458',458,NULL,0,1,0),(1588,1185,'2018-11-22','48',10,8,40,8,'2388','14:10:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75 ',4,'2018-11-23 02:25:19','2018-11-29 22:47:21',1,4,1292,'PVF-XI-22-GLCC-10-459',459,76,1,2,0),(1589,1185,'2018-11-22','48',10,8,40,8,'2388','14:10:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75 ',4,'2018-11-23 02:25:19','2018-11-29 22:47:21',1,5,1296,'PVF-XI-22-GLCC-11-460',460,NULL,0,2,0),(1590,1185,'2018-11-22','48',10,8,40,8,'2388','14:10:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75 ',4,'2018-11-23 02:25:19','2018-11-29 22:47:21',1,6,1297,'PVF-XI-22-GLCC-12-461',461,NULL,0,2,0),(1591,1185,'2018-11-22','48',10,8,40,8,'2472','16:10:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75  ',4,'2018-11-23 02:30:05','2018-11-29 22:55:55',1,7,1322,'PVF-XI-22-GLCC - 13-462',462,76,1,3,0),(1592,1185,'2018-11-22','48',10,8,40,8,'2472','16:10:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75  ',4,'2018-11-23 02:30:05','2018-11-29 22:55:55',1,8,1298,'PVF-XI-22-GLCC-16-463',463,NULL,0,3,0),(1593,1185,'2018-11-22','48',10,8,40,8,'2472','16:10:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75  ',4,'2018-11-23 02:30:05','2018-11-29 22:55:55',1,9,1323,'PVF-XI-22-GLCC - 17-464',464,NULL,0,3,0),(1594,1186,'2018-11-22','48',10,9,40,8,'2552','18:30:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75   ',4,'2018-11-23 02:39:41','2018-12-03 15:53:32',1,1,1299,'PVF-XI-22-GLCC-18-465',465,77,1,1,0),(1595,1186,'2018-11-22','48',10,9,40,8,'2552','18:30:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75   ',4,'2018-11-23 02:39:41','2018-12-03 15:53:32',1,2,1300,'PVF-XI-22-GLCC-19-466',466,NULL,0,1,0),(1596,1186,'2018-11-22','48',10,9,40,8,'2552','18:30:00',20,20,'LOSA MR CUERPO IZQUIERDO LADO DERECHO  KM 15+534 AL KM 15+357 Y CUERPO DERECHO LADO DERECHO KM 15+329.60 AL KM 15+237.75   ',4,'2018-11-23 02:39:41','2018-12-03 15:53:32',1,3,1324,'PVF-XI-22-GLCC - 23-467',467,NULL,0,1,0),(1597,1187,'2018-11-23','48',10,8,40,8,'2460','11:58:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196',4,'2018-11-23 23:27:50','2018-12-03 15:54:59',1,1,1289,'PVF-XI-23-GLCC-02-468',468,78,1,1,0),(1598,1187,'2018-11-23','48',10,8,40,8,'2460','11:58:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196',4,'2018-11-23 23:27:50','2018-12-03 15:54:59',1,2,1290,'PVF-XI-23-GLCC-06-469',469,NULL,0,1,0),(1599,1187,'2018-11-23','48',10,8,40,8,'2460','11:58:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196',4,'2018-11-23 23:27:50','2018-12-03 15:54:59',1,3,1291,'PVF-XI-23-GLCC-09-470',470,NULL,0,1,0),(1600,1187,'2018-11-23','48',10,9,40,8,'2472','13:55:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196 ',4,'2018-11-23 23:30:50','2018-12-03 15:58:25',1,4,1292,'PVF-XI-23-GLCC-10-471',471,78,1,2,0),(1601,1187,'2018-11-23','48',10,9,40,8,'2472','13:55:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196 ',4,'2018-11-23 23:30:50','2018-12-03 15:58:25',1,5,1296,'PVF-XI-23-GLCC-11-472',472,NULL,0,2,0),(1602,1187,'2018-11-23','48',10,9,40,8,'2472','13:55:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196 ',4,'2018-11-23 23:30:50','2018-12-03 15:58:25',1,6,1297,'PVF-XI-23-GLCC-12-473',473,NULL,0,2,0),(1603,1187,'2018-11-23','48',10,8,40,8,'2440 ','16:00:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196  ',4,'2018-11-23 23:33:46','2018-12-03 15:59:04',1,7,1298,'PVF-XI-23-GLCC-16-474',474,78,1,3,0),(1604,1187,'2018-11-23','48',10,8,40,8,'2440 ','16:00:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196  ',4,'2018-11-23 23:33:46','2018-12-03 15:59:04',1,8,1299,'PVF-XI-23-GLCC-18-475',475,NULL,0,3,0),(1605,1187,'2018-11-23','48',10,8,40,8,'2440 ','16:00:00',22,22,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+357 AL KM 15+196  ',4,'2018-11-23 23:33:46','2018-12-03 15:59:04',1,9,1300,'PVF-XI-23-GLCC-19-476',476,NULL,0,3,0),(1606,1188,'2018-11-25','48',10,8,40,8,'1455','10:40:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14+947  ',4,'2018-11-26 02:30:28','2018-12-03 22:08:57',1,1,1289,'PVF-XI-25-GLCC-02-477',477,79,1,1,0),(1607,1188,'2018-11-25','48',10,8,40,8,'1455','10:40:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14+947  ',4,'2018-11-26 02:30:29','2018-12-03 22:08:57',1,2,1290,'PVF-XI-25-GLCC-06-478',478,NULL,0,1,0),(1608,1188,'2018-11-25','48',10,8,40,8,'1455','10:40:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14+947  ',4,'2018-11-26 02:30:29','2018-12-03 22:08:57',1,3,1291,'PVF-XI-25-GLCC-09-479',479,NULL,0,1,0),(1609,1188,'2018-11-25','48',10,9,40,8,'2457','11:50:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 al km 14+947  ',4,'2018-11-26 02:33:31','2018-12-03 22:10:50',1,4,1292,'PVF-XI-25-GLCC-10-480',480,79,1,2,0),(1610,1188,'2018-11-25','48',10,9,40,8,'2457','11:50:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 al km 14+947  ',4,'2018-11-26 02:33:31','2018-12-03 22:10:50',1,5,1296,'PVF-XI-25-GLCC-11-481',481,NULL,0,2,0),(1611,1188,'2018-11-25','48',10,9,40,8,'2457','11:50:00',19,19,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 al km 14+947  ',4,'2018-11-26 02:33:31','2018-12-03 22:10:50',1,6,1297,'PVF-XI-25-GLCC-12-482',482,NULL,0,2,0),(1612,1188,'2018-11-25','48',10,8,40,8,'2269','13:30:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14-947',4,'2018-11-26 02:38:05','2018-12-03 22:11:25',1,7,1298,'PVF-XI-25-GLCC-16-483',483,79,1,3,0),(1613,1188,'2018-11-25','48',10,8,40,8,'2269','13:30:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14-947',4,'2018-11-26 02:38:05','2018-12-03 22:11:25',1,8,1299,'PVF-XI-25-GLCC-18-484',484,NULL,0,3,0),(1614,1188,'2018-11-25','48',10,8,40,8,'2269','13:30:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14-947',4,'2018-11-26 02:38:05','2018-12-03 22:11:25',1,9,1300,'PVF-XI-25-GLCC-19-485',485,NULL,0,3,0),(1615,1189,'2018-11-25','48',10,8,40,8,'2440','15:20:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14+947',4,'2018-11-26 02:43:26','2018-12-03 22:12:52',1,1,1322,'PVF-XI-25-GLCC - 13-486',486,80,1,1,0),(1616,1189,'2018-11-25','48',10,8,40,8,'2440','15:20:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14+947',4,'2018-11-26 02:43:26','2018-12-03 22:12:52',1,2,1323,'PVF-XI-25-GLCC - 17-487',487,NULL,0,1,0),(1617,1189,'2018-11-25','48',10,8,40,8,'2440','15:20:00',21,21,'LOSA MR CUERPO IZQUIERDO LADO DERECHO KM 15+196.4 AL KM 14+947',4,'2018-11-26 02:43:26','2018-12-03 22:12:52',1,3,1324,'PVF-XI-25-GLCC - 23-488',488,NULL,0,1,0),(1629,1192,'2018-11-26','48',10,10,40,8,'2457','11:30:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 14+712 AL KM 14+904 , KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+ 989 ',4,'2018-11-27 03:05:54','2018-12-03 22:14:01',1,1,1289,'PVF-XI-26-GLCC-02-489',489,81,1,1,0),(1630,1192,'2018-11-26','48',10,10,40,8,'2457','11:30:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 14+712 AL KM 14+904 , KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+ 989 ',4,'2018-11-27 03:05:54','2018-12-03 22:14:01',1,2,1290,'PVF-XI-26-GLCC-06-490',490,NULL,0,1,0),(1631,1192,'2018-11-26','48',10,10,40,8,'2457','11:30:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 14+712 AL KM 14+904 , KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+ 989 ',4,'2018-11-27 03:05:54','2018-12-03 22:14:01',1,3,1291,'PVF-XI-26-GLCC-09-491',491,NULL,0,1,0),(1632,1192,'2018-11-26','48',10,9,40,8,'2455','12:56:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM14+989',4,'2018-11-27 03:12:40','2018-12-03 22:14:37',1,4,1292,'PVF-XI-26-GLCC-10-492',492,81,1,2,0),(1633,1192,'2018-11-26','48',10,9,40,8,'2455','12:56:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM14+989',4,'2018-11-27 03:12:40','2018-12-03 22:14:37',1,5,1296,'PVF-XI-26-GLCC-11-493',493,NULL,0,2,0),(1634,1192,'2018-11-26','48',10,9,40,8,'2455','12:56:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM14+989',4,'2018-11-27 03:12:40','2018-12-03 22:14:37',1,6,1297,'PVF-XI-26-GLCC-12-494',494,NULL,0,2,0),(1635,1192,'2018-11-26','40',10,10,40,8,'3203','15:30:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904 , 15+248 AL 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:15:16','2018-12-03 22:15:05',1,7,1298,'PVF-XI-26-GLCC-16-495',495,81,1,3,0),(1636,1192,'2018-11-26','40',10,10,40,8,'3203','15:30:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904 , 15+248 AL 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:15:16','2018-12-03 22:15:05',1,8,1299,'PVF-XI-26-GLCC-18-496',496,NULL,0,3,0),(1637,1192,'2018-11-26','40',10,10,40,8,'3203','15:30:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904 , 15+248 AL 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:15:16','2018-12-03 22:15:05',1,9,1300,'PVF-XI-26-GLCC-19-497',497,NULL,0,3,0),(1638,1193,'2018-11-26','48',10,9,40,8,'2460 ','16:30:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM   14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:20:43','2018-12-03 22:16:19',1,1,1322,'PVF-XI-26-GLCC - 13-498',498,82,1,1,0),(1639,1193,'2018-11-26','48',10,9,40,8,'2460 ','16:30:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM   14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:20:43','2018-12-03 22:16:19',1,2,1323,'PVF-XI-26-GLCC - 17-499',499,NULL,0,1,0),(1640,1193,'2018-11-26','48',10,9,40,8,'2460 ','16:30:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM   14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:20:43','2018-12-03 22:16:19',1,3,1324,'PVF-XI-26-GLCC - 23-500',500,NULL,0,1,0),(1641,1193,'2018-11-26','48',10,10,40,8,'2410','17:00:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:24:16','2018-12-03 22:16:56',1,4,1346,'PVF-XI-26-GLCC-37-501',501,82,1,2,0),(1642,1193,'2018-11-26','48',10,10,40,8,'2410','17:00:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:24:16','2018-12-03 22:16:56',1,5,1344,'PVF-XI-26-GLCC-38-502',502,NULL,0,2,0),(1643,1193,'2018-11-26','48',10,10,40,8,'2410','17:00:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO KM  14+712 AL KM 14+904, KM 15+248 AL KM 15+170 Y KM 15+108 AL KM 14+989',4,'2018-11-27 03:24:16','2018-12-03 22:16:56',1,6,1345,'PVF-XI-26-GLCC-39-503',503,NULL,0,2,0),(1644,1194,'2018-11-27','48',10,8,40,8,'2457','02:35:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM 14+989 AL 14+904,KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817',4,'2018-11-28 04:32:42','2018-12-04 21:51:34',1,1,1289,'PVF-XI-27-GLCC-02-504',504,83,1,1,0),(1645,1194,'2018-11-27','48',10,8,40,8,'2457','02:35:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM 14+989 AL 14+904,KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817',4,'2018-11-28 04:32:42','2018-12-04 21:51:34',1,2,1290,'PVF-XI-27-GLCC-06-505',505,NULL,0,1,0),(1646,1194,'2018-11-27','48',10,8,40,8,'2457','02:35:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM 14+989 AL 14+904,KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817',4,'2018-11-28 04:32:42','2018-12-04 21:51:34',1,3,1291,'PVF-XI-27-GLCC-09-506',506,NULL,0,1,0),(1647,1194,'2018-11-27','48',10,9,40,8,'2491 ','12:50:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO 15+170 AL 15+108, KM 14+989 AL 14+904,KM 14+595 AL 14+639 Y CPO  IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:37:58','2018-12-04 21:52:06',1,4,1292,'PVF-XI-27-GLCC-10-507',507,83,1,2,0),(1648,1194,'2018-11-27','48',10,9,40,8,'2491 ','12:50:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO 15+170 AL 15+108, KM 14+989 AL 14+904,KM 14+595 AL 14+639 Y CPO  IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:37:58','2018-12-04 21:52:06',1,5,1296,'PVF-XI-27-GLCC-11-508',508,NULL,0,2,0),(1649,1194,'2018-11-27','48',10,9,40,8,'2491 ','12:50:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO 15+170 AL 15+108, KM 14+989 AL 14+904,KM 14+595 AL 14+639 Y CPO  IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:37:58','2018-12-04 21:52:06',1,6,1297,'PVF-XI-27-GLCC-12-509',509,NULL,0,2,0),(1650,1194,'2018-11-27','48',10,9,40,8,'2460 ','16:42:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO 15+170 AL 15+108,KM14+989 AL 14+904,14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:43:59','2018-12-04 21:53:15',1,7,1298,'PVF-XI-27-GLCC-16-510',510,83,1,3,0),(1651,1194,'2018-11-27','48',10,9,40,8,'2460 ','16:42:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO 15+170 AL 15+108,KM14+989 AL 14+904,14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:43:59','2018-12-04 21:53:15',1,8,1299,'PVF-XI-27-GLCC-18-511',511,NULL,0,3,0),(1652,1194,'2018-11-27','48',10,9,40,8,'2460 ','16:42:00',21,21,'LOSA MR CUERPO DERECHO LADO DERECHO 15+170 AL 15+108,KM14+989 AL 14+904,14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:43:59','2018-12-04 21:53:15',1,9,1300,'PVF-XI-27-GLCC-19-512',512,NULL,0,3,0),(1653,1195,'2018-11-27','48',10,10,40,8,'2552 ','17:40:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM  14+989 AL 14+904, KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817',4,'2018-11-28 04:51:02','2018-12-04 21:57:02',1,1,1322,'PVF-XI-27-GLCC - 13-513',513,84,1,1,0),(1654,1195,'2018-11-27','48',10,10,40,8,'2552 ','17:40:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM  14+989 AL 14+904, KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817',4,'2018-11-28 04:51:02','2018-12-04 21:57:02',1,2,1323,'PVF-XI-27-GLCC - 17-514',514,NULL,0,1,0),(1655,1195,'2018-11-27','48',10,10,40,8,'2552 ','17:40:00',20,20,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM  14+989 AL 14+904, KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817',4,'2018-11-28 04:51:02','2018-12-04 21:57:02',1,3,1324,'PVF-XI-27-GLCC - 23-515',515,NULL,0,1,0),(1656,1195,'2018-11-27','48',10,9,40,8,'2455 ','18:10:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM14+989 AL 14+904, KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:56:05','2018-12-04 21:58:04',1,4,1346,'PVF-XI-27-GLCC-37-516',516,84,1,2,0),(1657,1195,'2018-11-27','48',10,9,40,8,'2455 ','18:10:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM14+989 AL 14+904, KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:56:05','2018-12-04 21:58:04',1,5,1344,'PVF-XI-27-GLCC-38-517',517,NULL,0,2,0),(1658,1195,'2018-11-27','48',10,9,40,8,'2455 ','18:10:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM 15+170 AL 15+108, KM14+989 AL 14+904, KM 14+595 AL 14+639 Y CPO IZQ LADO DER KM 14+947 AL 14+817 ',4,'2018-11-28 04:56:05','2018-12-04 21:58:04',1,6,1345,'PVF-XI-27-GLCC-39-518',518,NULL,0,2,0),(1659,1196,'2018-11-28','48',10,8,40,8,'2410','12:00:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM   14+639 AL 14+672 Y CPO IZQ LADO DER KM 14+817 AL 14+712, 14+657.50 AL 14+546.80 ',2,'2018-11-29 03:26:19','2018-11-29 22:46:37',1,1,1289,'PVF-XI-28-GLCC-02-519',519,NULL,0,1,0),(1660,1196,'2018-11-28','48',10,8,40,8,'2410','12:00:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM   14+639 AL 14+672 Y CPO IZQ LADO DER KM 14+817 AL 14+712, 14+657.50 AL 14+546.80 ',2,'2018-11-29 03:26:19','2018-11-29 22:46:37',1,2,1290,'PVF-XI-28-GLCC-06-520',520,NULL,0,1,0),(1661,1196,'2018-11-28','48',10,8,40,8,'2410','12:00:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM   14+639 AL 14+672 Y CPO IZQ LADO DER KM 14+817 AL 14+712, 14+657.50 AL 14+546.80 ',2,'2018-11-29 03:26:19','2018-11-29 22:46:37',1,3,1291,'PVF-XI-28-GLCC-09-521',521,NULL,0,1,0),(1662,1196,'2018-11-28','48',10,8,40,8,'2298','13:50:00',19,19,'LOSAR MR CUERPO DERECHO LADO DERECHO KM 14+639 AL 14+672 Y CPO IZQ LADO DER 14+817 AL 14+712 Y 14+657.50 AL 14+546.80',2,'2018-11-29 03:29:56','2018-11-29 22:46:37',1,4,1292,'PVF-XI-28-GLCC-10-522',522,NULL,0,2,0),(1663,1196,'2018-11-28','48',10,8,40,8,'2298','13:50:00',19,19,'LOSAR MR CUERPO DERECHO LADO DERECHO KM 14+639 AL 14+672 Y CPO IZQ LADO DER 14+817 AL 14+712 Y 14+657.50 AL 14+546.80',2,'2018-11-29 03:29:56','2018-11-29 22:46:37',1,5,1296,'PVF-XI-28-GLCC-11-523',523,NULL,0,2,0),(1664,1196,'2018-11-28','48',10,8,40,8,'2298','13:50:00',19,19,'LOSAR MR CUERPO DERECHO LADO DERECHO KM 14+639 AL 14+672 Y CPO IZQ LADO DER 14+817 AL 14+712 Y 14+657.50 AL 14+546.80',2,'2018-11-29 03:29:56','2018-11-29 22:46:37',1,6,1297,'PVF-XI-28-GLCC-12-524',524,NULL,0,2,0),(1665,1196,'2018-11-28','48',10,9,40,8,'2410','16:10:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM. 14+639 AL 14+672 Y CPO IZQ LADO DER KM 14+817 AL 14+712 Y KM 14+657.50 AL 14+546.80',2,'2018-11-29 03:32:15','2018-11-29 22:46:37',1,7,1298,'PVF-XI-28-GLCC-16-525',525,NULL,0,3,0),(1666,1196,'2018-11-28','48',10,9,40,8,'2410','16:10:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM. 14+639 AL 14+672 Y CPO IZQ LADO DER KM 14+817 AL 14+712 Y KM 14+657.50 AL 14+546.80',2,'2018-11-29 03:32:15','2018-11-29 22:46:37',1,8,1299,'PVF-XI-28-GLCC-18-526',526,NULL,0,3,0),(1667,1196,'2018-11-28','48',10,9,40,8,'2410','16:10:00',19,19,'LOSA MR CUERPO DERECHO LADO DERECHO KM. 14+639 AL 14+672 Y CPO IZQ LADO DER KM 14+817 AL 14+712 Y KM 14+657.50 AL 14+546.80',2,'2018-11-29 03:32:15','2018-11-29 22:46:37',1,9,1300,'PVF-XI-28-GLCC-19-527',527,NULL,0,3,0),(1668,1197,'2018-11-29','48',10,11,40,8,'2410','10:25:00',18,18,'LOSA MR CUERPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:07:35','2018-12-02 04:10:42',1,1,1289,'PVF-XI-29-GLCC-02-528',528,NULL,0,1,0),(1669,1197,'2018-11-29','48',10,11,40,8,'2410','10:25:00',18,18,'LOSA MR CUERPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:07:35','2018-12-02 04:10:42',1,2,1290,'PVF-XI-29-GLCC-06-529',529,NULL,0,1,0),(1670,1197,'2018-11-29','48',10,11,40,8,'2410','10:25:00',18,18,'LOSA MR CUERPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:07:35','2018-12-02 04:10:42',1,3,1291,'PVF-XI-29-GLCC-09-530',530,NULL,0,1,0),(1671,1197,'2018-11-29','48',10,12,40,8,'2232','12:55:00',19,19,'LOSA MR CPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:10:37','2018-12-02 04:10:42',1,4,1292,'PVF-XI-29-GLCC-10-531',531,NULL,0,2,0),(1672,1197,'2018-11-29','48',10,12,40,8,'2232','12:55:00',19,19,'LOSA MR CPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:10:37','2018-12-02 04:10:42',1,5,1296,'PVF-XI-29-GLCC-11-532',532,NULL,0,2,0),(1673,1197,'2018-11-29','48',10,12,40,8,'2232','12:55:00',19,19,'LOSA MR CPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:10:37','2018-12-02 04:10:42',1,6,1297,'PVF-XI-29-GLCC-12-533',533,NULL,0,2,0),(1674,1197,'2018-11-29','48',10,11,40,8,'2472','14:00:00',19,19,'LOSA MR CPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:13:00','2018-12-02 04:10:42',1,7,1298,'PVF-XI-29-GLCC-16-534',534,NULL,0,3,0),(1675,1197,'2018-11-29','48',10,11,40,8,'2472','14:00:00',19,19,'LOSA MR CPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:13:00','2018-12-02 04:10:42',1,8,1299,'PVF-XI-29-GLCC-18-535',535,NULL,0,3,0),(1676,1197,'2018-11-29','48',10,11,40,8,'2472','14:00:00',19,19,'LOSA MR CPO IZQ LADO DER KM 14+546.80 AL 14+421',2,'2018-11-30 01:13:00','2018-12-02 04:10:42',1,9,1300,'PVF-XI-29-GLCC-19-536',536,NULL,0,3,0),(1693,1201,'2018-11-30','150',10,10,20,1,'1','12:00:00',18,18,'BORDILLO CPO DER LADO DER KM',0,'2018-12-01 03:30:18','2018-12-05 02:55:11',1,1,70,'PVF-XI-30-@UnitIO@-1',1,NULL,0,1,0),(1694,1201,'2018-11-30','150',10,10,20,1,'1','12:00:00',18,18,'BORDILLO CPO DER LADO DER KM',0,'2018-12-01 03:30:18','2018-12-05 02:55:11',1,2,NULL,'PVF-XI-30-@UnitIO@-2',2,NULL,0,1,0),(1695,1201,'2018-11-30','150',10,10,20,1,'1','12:00:00',18,18,'BORDILLO CPO DER LADO DER KM',0,'2018-12-01 03:30:18','2018-12-05 02:55:11',1,3,NULL,'PVF-XI-30-@UnitIO@-3',3,NULL,0,1,0),(1696,1201,'2018-11-30','150',10,10,20,1,'1','12:00:00',18,18,'BORDILLO CPO DER LADO DER KM',0,'2018-12-01 03:30:18','2018-12-05 02:55:11',1,4,NULL,'PVF-XI-30-@UnitIO@-4',4,NULL,0,1,0),(1706,1203,'2018-12-01','48',10,9,40,8,'2462','10:10:00',18,20,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930 ',2,'2018-12-02 04:34:00','2018-12-04 15:21:43',1,1,1289,'PVF-XII-1-GLCC-02-546',546,NULL,0,1,0),(1707,1203,'2018-12-01','48',10,9,40,8,'2462','10:10:00',18,20,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930 ',2,'2018-12-02 04:34:00','2018-12-04 15:21:43',1,2,1290,'PVF-XII-1-GLCC-06-547',547,NULL,0,1,0),(1708,1203,'2018-12-01','48',10,9,40,8,'2462','10:10:00',18,20,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930 ',2,'2018-12-02 04:34:00','2018-12-04 15:21:43',1,3,1291,'PVF-XII-1-GLCC-09-548',548,NULL,0,1,0),(1709,1203,'2018-12-01','48',10,9,40,8,'2455','12:45:00',20,20,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930  ',2,'2018-12-02 04:37:19','2018-12-04 15:21:43',1,4,1292,'PVF-XII-1-GLCC-10-549',549,NULL,0,2,0),(1710,1203,'2018-12-01','48',10,9,40,8,'2455','12:45:00',20,20,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930  ',2,'2018-12-02 04:37:19','2018-12-04 15:21:43',1,5,1296,'PVF-XII-1-GLCC-11-550',550,NULL,0,2,0),(1711,1203,'2018-12-01','48',10,9,40,8,'2455','12:45:00',20,20,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930  ',2,'2018-12-02 04:37:19','2018-12-04 15:21:43',1,6,1297,'PVF-XII-1-GLCC-12-551',551,NULL,0,2,0),(1712,1203,'2018-12-01','48',10,8,40,8,'2460','14:55:00',21,21,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930   ',2,'2018-12-02 04:39:50','2018-12-04 15:21:43',1,7,1298,'PVF-XII-1-GLCC-16-552',552,NULL,0,3,0),(1713,1203,'2018-12-01','48',10,8,40,8,'2460','14:55:00',21,21,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930   ',2,'2018-12-02 04:39:50','2018-12-04 15:21:43',1,8,1299,'PVF-XII-1-GLCC-18-553',553,NULL,0,3,0),(1714,1203,'2018-12-01','48',10,8,40,8,'2460','14:55:00',21,21,'LOSA MR  CPO IZQ LADO IZQ KM 9+347 AL KM 9+290 Y KM 9+264 AL 9+058 Y KM 9+010 AL 8+930   ',2,'2018-12-02 04:39:50','2018-12-04 15:21:43',1,9,1300,'PVF-XII-1-GLCC-19-554',554,NULL,0,3,0),(1715,1204,'2018-12-02','48',10,9,40,8,'2472','09:55:00',18,18,'LOSA MR CPO IZQ LADO IZQ  KM 8+930 AL 8+840  ',2,'2018-12-03 04:23:29','2018-12-04 15:27:22',1,1,1289,'PVF-XII-2-GLCC-02-555',555,NULL,0,1,0),(1716,1204,'2018-12-02','48',10,9,40,8,'2472','09:55:00',18,18,'LOSA MR CPO IZQ LADO IZQ  KM 8+930 AL 8+840  ',2,'2018-12-03 04:23:29','2018-12-04 15:27:22',1,2,1290,'PVF-XII-2-GLCC-06-556',556,NULL,0,1,0),(1717,1204,'2018-12-02','48',10,9,40,8,'2472','09:55:00',18,18,'LOSA MR CPO IZQ LADO IZQ  KM 8+930 AL 8+840  ',2,'2018-12-03 04:23:29','2018-12-04 15:27:22',1,3,1291,'PVF-XII-2-GLCC-09-557',557,NULL,0,1,0),(1718,1204,'2018-12-02','48',10,10,40,8,'2462','12:50:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM 8+930 Al 8+840    ',2,'2018-12-03 04:25:25','2018-12-04 15:27:22',1,4,1292,'PVF-XII-2-GLCC-10-558',558,NULL,0,2,0),(1719,1204,'2018-12-02','48',10,10,40,8,'2462','12:50:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM 8+930 Al 8+840    ',2,'2018-12-03 04:25:25','2018-12-04 15:27:22',1,5,1296,'PVF-XII-2-GLCC-11-559',559,NULL,0,2,0),(1720,1204,'2018-12-02','48',10,10,40,8,'2462','12:50:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM 8+930 Al 8+840    ',2,'2018-12-03 04:25:25','2018-12-04 15:27:22',1,6,1297,'PVF-XII-2-GLCC-12-560',560,NULL,0,2,0),(1721,1205,'2018-12-03','48',10,10,40,8,'2389','10:50:00',18,19,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258',2,'2018-12-04 03:34:59','2018-12-05 02:53:53',1,1,1289,'PVF-XII-3-GLCC-02-561',561,NULL,0,1,0),(1722,1205,'2018-12-03','48',10,10,40,8,'2389','10:50:00',18,19,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258',2,'2018-12-04 03:34:59','2018-12-05 02:53:53',1,2,1290,'PVF-XII-3-GLCC-06-562',562,NULL,0,1,0),(1723,1205,'2018-12-03','48',10,10,40,8,'2389','10:50:00',18,19,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258',2,'2018-12-04 03:34:59','2018-12-05 02:53:53',1,3,1291,'PVF-XII-3-GLCC-09-563',563,NULL,0,1,0),(1724,1205,'2018-12-03','48',10,10,40,8,'2232','12:40:00',19,19,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258 ',2,'2018-12-04 03:36:39','2018-12-05 02:53:53',1,4,1292,'PVF-XII-3-GLCC-10-564',564,NULL,0,2,0),(1725,1205,'2018-12-03','48',10,10,40,8,'2232','12:40:00',19,19,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258 ',2,'2018-12-04 03:36:39','2018-12-05 02:53:53',1,5,1296,'PVF-XII-3-GLCC-11-565',565,NULL,0,2,0),(1726,1205,'2018-12-03','48',10,10,40,8,'2232','12:40:00',19,19,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258 ',2,'2018-12-04 03:36:39','2018-12-05 02:53:53',1,6,1297,'PVF-XII-3-GLCC-12-566',566,NULL,0,2,0),(1727,1205,'2018-12-03','48',10,10,40,8,'2298','17:00:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258 ',2,'2018-12-04 03:38:23','2018-12-05 02:53:53',1,7,1298,'PVF-XII-3-GLCC-16-567',567,NULL,0,3,0),(1728,1205,'2018-12-03','48',10,10,40,8,'2298','17:00:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258 ',2,'2018-12-04 03:38:23','2018-12-05 02:53:53',1,8,1299,'PVF-XII-3-GLCC-18-568',568,NULL,0,3,0),(1729,1205,'2018-12-03','48',10,10,40,8,'2298','17:00:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM 15+489.50 AL KM 15+258 ',2,'2018-12-04 03:38:23','2018-12-05 02:53:53',1,9,1300,'PVF-XII-3-GLCC-19-569',569,NULL,0,3,0),(1734,1207,'2018-12-04','48',10,11,40,8,'2457','12:20:00',20,20,'LOSA MR CPO IZQ LADO IZQ KM ',0,'2018-12-05 03:51:52','2018-12-05 03:56:46',1,1,1289,'PVF-XII-4-GLCC-02-570',570,NULL,0,1,0),(1735,1207,'2018-12-04','48',10,11,40,8,'2457','12:20:00',20,20,'LOSA MR CPO IZQ LADO IZQ KM ',0,'2018-12-05 03:51:52','2018-12-05 03:56:46',1,2,1290,'PVF-XII-4-GLCC-06-571',571,NULL,0,1,0),(1736,1207,'2018-12-04','48',10,11,40,8,'2457','12:20:00',20,20,'LOSA MR CPO IZQ LADO IZQ KM ',0,'2018-12-05 03:51:52','2018-12-05 03:56:46',1,3,1291,'PVF-XII-4-GLCC-09-572',572,NULL,0,1,0),(1737,1207,'2018-12-04','48',10,10,40,8,'2552','14:20:00',20,20,'LOSA MR CPO IZQ LADO IZQ KM ',0,'2018-12-05 03:58:06','2018-12-05 04:00:55',1,4,1292,'PVF-XII-4-GLCC-10-573',573,NULL,0,2,0),(1738,1207,'2018-12-04','48',10,10,40,8,'2552','14:20:00',20,20,'LOSA MR CPO IZQ LADO IZQ KM ',0,'2018-12-05 03:58:06','2018-12-05 04:00:55',1,5,1296,'PVF-XII-4-GLCC-11-574',574,NULL,0,2,0),(1739,1207,'2018-12-04','48',10,10,40,8,'2552','14:20:00',20,20,'LOSA MR CPO IZQ LADO IZQ KM ',0,'2018-12-05 03:58:06','2018-12-05 04:00:55',1,6,1297,'PVF-XII-4-GLCC-12-575',575,NULL,0,2,0),(1740,1207,'2018-12-04','48',10,10,48,8,'0','00:00:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM',0,'2018-12-05 04:01:09','2018-12-05 04:03:39',1,7,1298,'PVF-XII-4-GLCC-16-576',576,NULL,0,3,0),(1741,1207,'2018-12-04','48',10,10,48,8,'0','00:00:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM',0,'2018-12-05 04:01:09','2018-12-05 04:03:39',1,8,1299,'PVF-XII-4-GLCC-18-577',577,NULL,0,3,0),(1742,1207,'2018-12-04','48',10,10,48,8,'0','00:00:00',21,21,'LOSA MR CPO IZQ LADO IZQ KM',0,'2018-12-05 04:01:09','2018-12-05 04:03:39',1,9,1300,'PVF-XII-4-GLCC-19-578',578,NULL,0,3,0);

INSERT INTO `ensayoViga` (id_ensayoViga,registrosCampo_id,formatoCampo_id,footerEnsayo_id,condiciones,lijado,cuero,ancho1,ancho2,per1,per2,l1,l2,l3,disApoyo,disCarga,carga,createdON,lastEditedON,active,defectos,fecha,status,posFractura,velAplicacionExp,tiempoDeCarga,mr,prom,jefaLabApproval_id,pdfFinal,sentToClientFinal,dateSentToClientFinal) VALUES 
(20,1454,1161,55,'Humedo','NO','SI',15,15,15,15,45,54,38,45,15,2723,'2018-11-12 16:17:20','2018-12-03 21:35:11',1,NULL,'2018-12-03',4,1,9.23,236,36.31,30780,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(14-30-3).pdf',2,'2018-11-21'),(21,1457,1161,55,'Humedo','NO','SI',15,15,15,15,25,36,25,45,15,2725,'2018-11-12 16:17:51','2018-12-03 16:40:36',1,NULL,'2018-12-03',4,1,9.95,219,36.33,7500,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(14-38-48).pdf',2,'2018-11-21'),(22,1460,1161,55,'Humedo','NO','SI',15,15,15,15,33,42,37,45,15,2804,'2018-11-13 21:13:33','2018-12-03 21:33:52',1,NULL,'2018-12-03',4,1,9.97,225,37.39,17094,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(14-41-31).pdf',2,'2018-11-21'),(24,1463,1162,57,'Humedo','NO','SI',15,15,15,15,28,24,31,45,15,2810,'2018-11-14 16:26:41','2018-12-04 17:12:52',1,NULL,'2018-12-04',4,1,9.49,237,37.47,6944,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1162/FinalVIGAS(17-4-56).pdf',2,'2018-11-21'),(25,1470,1165,58,'Humedo','NO','SI',15,15,15,15,28,28,31,45,15,2740,'2018-11-14 17:33:39','2018-11-27 20:17:01',1,NULL,'2018-11-14',4,1,9.13,240,36.53,24304,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1165/FinalVIGAS(17-23-1).pdf',2,'2018-11-21'),(26,1473,1166,59,'Humedo','SI','NO',15,15,15,15,43,41,38,45,15,2420,'2018-11-14 17:34:48','2018-11-27 20:17:02',1,NULL,'2018-11-14',4,1,9.01,215,32.27,40.67,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1166/FinalVIGAS(14-55-16).pdf',2,'2018-11-21'),(27,1467,1163,60,'Humedo','NO','SI',15,15,15,15,22,20,24,45,15,2725,'2018-11-14 17:56:40','2018-11-27 20:17:02',1,NULL,'2018-11-14',4,1,9.69,225,36.33,10560,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1163/FinalVIGAS(17-26-36).pdf',2,'2018-11-21'),(28,1476,1166,59,'Humedo','SI','NO',15,15,15,15,38,27,32,45,15,2470,'2018-11-14 18:02:51','2018-11-27 20:17:02',1,NULL,'2018-11-14',4,1,9.23,214,32.93,32832,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1166/FinalVIGAS(14-57-14).pdf',2,'2018-11-21'),(29,1479,1166,59,'Humedo','NO','SI',15,15,15,15,31,38,25,45,15,2497,'2018-11-14 18:08:40','2018-11-27 20:17:02',1,NULL,'2018-11-14',4,1,9.16,218,33.29,29450,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1166/FinalVIGAS(14-58-26).pdf',2,'2018-11-21'),(30,1482,1167,61,'Humedo','NO','SI',15,15,15,15,36,41,37,45,15,2517,'2018-11-14 18:15:33','2018-11-27 20:17:02',1,NULL,'2018-11-14',4,1,9.37,215,33.56,54612,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1167/FinalVIGAS(17-27-43).pdf',2,'2018-11-21'),(33,1485,1169,62,'Humedo','NO','SI',15,15,15,15,36,31,31,45,15,2380,'2018-11-15 21:07:04','2018-11-27 20:17:02',1,NULL,'2018-11-15',4,1,9.11,209,31.73,32.67,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1169/FinalVIGAS(18-5-7).pdf',2,'2018-11-21'),(34,1488,1169,62,'Humedo','NO','SI',15,15,15,15,21,18,27,45,15,2410,'2018-11-15 21:50:48','2018-11-27 20:17:02',1,NULL,'2018-11-15',4,1,9.4,205,32.13,10206,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1169/FinalVIGAS(18-5-48).pdf',2,'2018-11-21'),(35,1491,1169,62,'Humedo','NO','SI',15,15,15,15,36,42,38,45,15,2447,'2018-11-15 21:55:04','2018-11-27 20:17:02',1,NULL,'2018-11-15',4,1,9.64,203,32.63,57456,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1169/FinalVIGAS(18-13-57).pdf',2,'2018-11-21'),(36,1494,1170,63,'Humedo','NO','SI',15,15,15,15,38,44,35,45,15,2469,'2018-11-16 15:01:23','2018-11-27 20:17:02',1,NULL,'2018-11-16',4,1,9.45,209,32.92,58520,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1170/FinalVIGAS(17-41-33).pdf',2,'2018-11-21'),(37,1497,1170,63,'Humedo','SI','NO',15,15,15,15,54,48,50,45,15,2610,'2018-11-16 15:06:04','2018-11-27 20:17:02',1,NULL,'2018-11-21',4,1,9.9,211,34.8,43200,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1170/FinalVIGAS(18-22-33).pdf',2,'2018-11-21'),(38,1500,1170,63,'Humedo','SI','NO',15,15,15,15,34,26,38,45,15,2570,'2018-11-16 15:08:44','2018-11-27 20:17:02',1,NULL,'2018-11-16',4,1,9.65,213,34.27,32.67,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1170/FinalVIGAS(18-20-14).pdf',2,'2018-11-21'),(39,1503,1171,64,'Humedo','NO','SI',15,15,15,15,25,33,20,45,15,2595,'2018-11-17 14:54:48','2018-11-27 20:17:02',1,NULL,'2018-11-17',4,1,9.66,215,34.6,26,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1171/FinalVIGAS(17-45-15).pdf',2,'2018-11-21'),(40,1506,1171,64,'Humedo','NO','SI',15,15,15,15,55,60,57,45,15,2485,'2018-11-17 15:09:40','2018-11-27 20:17:02',1,NULL,'2018-11-21',4,1,9.04,220,33.13,57.33,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1171/FinalVIGAS(17-45-49).pdf',2,'2018-11-21'),(43,1509,1172,66,'Humedo','SI','NO',15,15,15,15,33,40,47,45,15,2820,'2018-11-20 21:22:45','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.6,235,37.6,20680,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1172/FinalVIGAS(17-47-25).pdf',2,'2018-11-21'),(44,1512,1172,66,'Humedo','NO','SI',15,15,15,15,44,55,48,45,15,2477,'2018-11-20 21:25:51','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.09,218,33.03,38720,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1172/FinalVIGAS(17-47-57).pdf',2,'2018-11-21'),(45,1515,1172,66,'Humedo','NO','SI',15,15,15,15,40,49,47,45,15,2600,'2018-11-20 21:28:15','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.2,226,34.67,30706.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1172/FinalVIGAS(17-51-18).pdf',2,'2018-11-21'),(46,1518,1173,67,'Humedo','NO','SI',15,15,15,15,32,46,42,45,15,2630,'2018-11-20 21:31:13','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.83,214,35.07,20608,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1173/FinalVIGAS(17-49-33).pdf',2,'2018-11-21'),(47,1521,1174,68,'Humedo','SI','NO',15,15,15,15,49,58,45,45,15,2756,'2018-11-20 21:33:57','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.84,224,36.75,42630,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1174/FinalVIGAS(17-50-46).pdf',2,'2018-11-21'),(48,1524,1174,68,'Humedo','NO','SI',15,15,15,15,59,65,54,45,15,2635,'2018-11-20 21:36:07','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.62,219,35.13,69030,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1174/FinalVIGAS(17-51-16).pdf',2,'2018-11-21'),(49,1527,1174,68,'Humedo','NO','SI',15,15,15,15,55,46,49,45,15,2840,'2018-11-20 21:38:30','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.67,235,37.87,41323.333333333,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1174/FinalVIGAS(17-51-43).pdf',2,'2018-11-21'),(50,1530,1175,69,'Humedo','NO','SI',15,15,15,15,50,44,54,45,15,2715,'2018-11-20 21:40:21','2018-11-27 20:22:16',1,NULL,'2018-11-20',4,1,9.92,219,36.2,49.33,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1175/FinalVIGAS(18-34-36).pdf',2,'2018-11-21'),(51,1536,1176,70,'Humedo','NO','SI',15,15,15,15,25,38,42,45,15,2570,'2018-11-21 15:38:00','2018-11-27 20:22:16',1,NULL,'2018-11-21',4,1,9.61,214,34.27,13300,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1176/FinalVIGAS(17-56-4).pdf',2,'2018-11-21'),(52,1539,1176,70,'Humedo','NO','SI',15,15,15,15,55,56,52,45,15,2620,'2018-11-21 15:42:17','2018-11-27 20:22:16',1,NULL,'2018-11-21',4,1,9.4,223,34.93,53386.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1176/FinalVIGAS(17-53-41).pdf',2,'2018-11-21'),(53,1545,1178,71,'Humedo','SI','NO',15,15,15,15,33,40,43,45,15,2635,'2018-11-22 16:03:03','2018-11-27 20:22:16',1,NULL,'2018-11-22',4,1,9.8,215,35.13,18920,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1178/FinalVIGAS(13-11-20).pdf',2,'2018-11-27'),(54,1548,1178,71,'Humedo','NO','SI',15,15,15,15,54,48,50,45,15,2587,'2018-11-22 16:07:18','2018-11-27 20:22:16',1,NULL,'2018-11-22',4,1,9.2,225,34.49,43200,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1178/FinalVIGAS(13-11-50).pdf',2,'2018-11-27'),(55,1551,1178,71,'Humedo','SI','NO',15,15,15,15,41,36,46,45,15,2535,'2018-11-22 16:11:41','2018-11-27 20:22:16',1,NULL,'2018-11-22',4,1,9.7,209,33.8,22632,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1178/FinalVIGAS(13-14-22).pdf',2,'2018-11-27'),(56,1554,1179,72,'Humedo','NO','SI',15,15,15,15,32,34,30,45,15,2681,'2018-11-23 16:25:42','2018-11-27 20:22:16',1,NULL,'2018-11-23',4,1,9.98,215,35.75,32,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1179/FinalVIGAS(9-55-40).pdf',2,'2018-11-27'),(58,1557,1179,72,'Humedo','SI','NO',15,15,15,15,44,36,47,45,15,2453,'2018-11-23 17:23:40','2018-11-27 20:22:16',1,NULL,'2018-11-23',4,1,9.26,212,32.71,42.33,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1179/FinalVIGAS(9-56-21).pdf',2,'2018-11-27'),(59,1560,1181,73,'Humedo','SI','NO',15,15,15,15,59,51,55,45,15,2733,'2018-11-24 15:57:02','2018-11-27 23:32:07',1,NULL,'2018-11-24',4,1,9.76,224,36.44,55165,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1181/FinalVIGAS(17-30-47).pdf',1,'2018-11-27'),(60,1563,1181,73,'Humedo','NO','SI',15,15,15,15,34,35,37,45,15,2695,'2018-11-24 15:59:32','2018-11-27 23:37:13',1,NULL,'2018-11-27',4,1,9.67,223,35.93,35.33,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1181/FinalVIGAS(17-39-11).pdf',1,'2018-11-27'),(61,1566,1182,74,'Humedo','SI','NO',15,15,15,15,55,48,59,45,15,2502,'2018-11-26 16:15:33','2018-11-27 00:15:56',1,NULL,'2018-11-26',4,1,9.1,220,33.36,51920,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1182/FinalVIGAS(18-17-53).pdf',1,'2018-11-26'),(62,1569,1182,74,'Humedo','NO','SI',15,15,15,15,32,40,36,45,15,2523,'2018-11-26 19:58:04','2018-11-27 00:17:28',1,NULL,'2018-11-26',4,1,9.66,209,33.64,15360,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1182/FinalVIGAS(18-16-58).pdf',1,'2018-11-26'),(63,1572,1182,74,'Humedo','SI','NO',15,15,15,15,64,50,63,45,15,2428,'2018-11-26 20:00:12','2018-11-27 00:17:56',1,NULL,'2018-11-26',4,1,9.47,205,32.37,67200,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1182/FinalVIGAS(18-17-39).pdf',1,'2018-11-26'),(64,1575,1183,75,'Humedo','NO','SI',15,15,15,15,39,48,45,45,15,2567,'2018-11-27 16:19:34','2018-11-28 01:33:34',1,NULL,'2018-11-27',4,1,9.34,220,34.23,28080,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1183/FinalVIGAS(19-33-3).pdf',1,'2018-11-27'),(65,1578,1183,75,'Humedo','SI','NO',15,15,15,15,40,55,52,45,15,2489,'2018-11-27 16:26:04','2018-11-27 23:41:30',1,NULL,'2018-11-27',4,1,9.57,208,33.19,38133.333333333,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1183/FinalVIGAS(17-41-11).pdf',1,'2018-11-27'),(67,1585,1185,76,'Humedo','SI','NO',15,15,15,15,46,40,49,45,15,2522,'2018-11-29 16:24:37','2018-11-29 22:46:16',1,NULL,'2018-11-29',4,1,9.21,219,33.63,45,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1185/FinalVIGAS(16-45-54).pdf',1,'2018-11-29'),(68,1588,1185,76,'Humedo','NO','SI',15,15,15,15,59,48,43,45,15,2512,'2018-11-29 16:27:25','2018-11-29 22:47:22',1,NULL,'2018-11-29',4,1,9.8,205,33.49,40592,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1185/FinalVIGAS(16-47-2).pdf',1,'2018-11-29'),(69,1591,1185,76,'Humedo','NO','SI',15,15,15,15,44,55,49,45,15,2605,'2018-11-29 16:30:32','2018-11-29 22:55:56',1,NULL,'2018-11-29',4,1,9.78,213,34.73,39526.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1185/FinalVIGAS(16-47-31).pdf',1,'2018-11-29'),(70,1594,1186,77,'Humedo','SI','NO',15,15,15,15,35,35,35,45,15,2630,'2018-11-29 16:32:40','2018-12-03 15:53:33',1,NULL,'2018-11-29',4,2,9.21,215,33,14291.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1186/FinalVIGAS(9-53-6).pdf',1,'2018-12-03'),(71,1597,1187,78,'Humedo','NO','SI',15,15,15,15,58,62,55,45,15,2591,'2018-11-30 18:47:38','2018-12-03 15:55:00',1,NULL,'2018-11-30',4,1,9.42,220,34.55,65926.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1187/FinalVIGAS(16-34-13).pdf',1,'2018-12-03'),(72,1600,1187,78,'Humedo','SI','NO',15,15,15,15,33,45,42,45,15,2560,'2018-11-30 18:50:34','2018-12-03 15:58:26',1,NULL,'2018-11-30',4,1,9.52,215,34.13,20790,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1187/FinalVIGAS(9-57-12).pdf',1,'2018-12-03'),(73,1603,1187,78,'Humedo','NO','SI',15,15,15,15,52,41,39,45,15,2523,'2018-11-30 18:52:55','2018-12-03 19:57:44',1,NULL,'2018-12-03',4,1,9.85,205,33.64,44,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1187/FinalVIGAS(9-58-43).pdf',1,'2018-12-03'),(74,1455,1161,55,'Humedo','SI','NO',15,15,15,15,51,39,45,45,15,3650,'2018-12-03 16:26:50','2018-12-04 23:19:07',1,NULL,'2018-12-04',4,1,9.27,315,48.67,29835,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(11-34-18).pdf',1,'2018-12-03'),(75,1456,1161,55,'Humedo','NO','SI',15,15,15,15,25,38,33,45,15,3690,'2018-12-03 16:30:10','2018-12-04 23:23:43',1,NULL,'2018-12-04',4,1,9.52,310,49.2,32,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(12-18-29).pdf',1,'2018-12-03'),(76,1458,1161,55,'Humedo','NO','SI',15,15,15,15,55,68,51,45,15,3715,'2018-12-03 16:33:18','2018-12-03 18:20:17',1,NULL,'2018-12-03',4,1,9.14,325,49.53,58,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(12-19-56).pdf',1,'2018-12-03'),(77,1459,1161,55,'Humedo','NO','SI',15,15,15,15,40,55,37,45,15,3692,'2018-12-03 16:48:31','2018-12-03 18:25:43',1,NULL,'2018-12-03',4,1,9.09,325,49.23,27133.333333333,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(12-25-22).pdf',1,'2018-12-03'),(78,1606,1188,79,'Humedo','NO','SI',15,15,15,15,25,36,33,45,15,2622,'2018-12-03 17:43:02','2018-12-03 22:08:59',1,NULL,'2018-12-03',4,1,9.49,221,34.96,31.33,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1188/FinalVIGAS(16-8-18).pdf',1,'2018-12-03'),(79,1609,1188,79,'Humedo','SI','NO',15,15,15,15,51,40,55,45,15,2647,'2018-12-03 19:38:11','2018-12-03 22:10:51',1,NULL,'2018-12-03',4,1,9.89,214,35.29,37400,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1188/FinalVIGAS(16-13-37).pdf',1,'2018-12-03'),(80,1612,1188,79,'Humedo','NO','SI',15,15,15,15,41,55,52,45,15,2591,'2018-12-03 19:40:23','2018-12-03 22:11:25',1,NULL,'2018-12-03',4,1,9.42,220,34.55,39086.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1188/FinalVIGAS(16-14-30).pdf',1,'2018-12-03'),(81,1615,1189,80,'Humedo','NO','SI',15,15,15,15,38,47,53,45,15,2680,'2018-12-03 19:42:18','2018-12-03 22:12:53',1,NULL,'2018-12-03',4,1,9.49,226,35.73,46,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1189/FinalVIGAS(16-12-34).pdf',1,'2018-12-03'),(82,1629,1192,81,'Humedo','SI','NO',15,15,15,15,29,18,33,45,15,2640,'2018-12-03 19:44:48','2018-12-03 22:14:02',1,NULL,'2018-12-03',4,1,9.69,218,35.2,5742,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1192/FinalVIGAS(16-13-44).pdf',1,'2018-12-03'),(83,1632,1192,81,'Humedo','SI','NO',15,15,15,15,40,28,36,45,15,2580,'2018-12-03 19:47:01','2018-12-03 22:14:37',1,NULL,'2018-12-03',4,1,9.17,225,34.4,13440,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1192/FinalVIGAS(16-17-35).pdf',1,'2018-12-03'),(84,1635,1192,81,'Humedo','NO','SI',15,15,15,15,55,64,50,45,15,2617,'2018-12-03 19:48:48','2018-12-03 22:15:05',1,NULL,'2018-12-03',4,1,9.52,220,34.89,58666.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1192/FinalVIGAS(16-14-48).pdf',1,'2018-12-03'),(85,1638,1193,82,'Humedo','NO','SI',15,15,15,15,18,33,24,45,15,2490,'2018-12-03 19:50:37','2018-12-03 22:16:20',1,NULL,'2018-12-03',4,1,9.18,217,33.2,4752,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1193/FinalVIGAS(16-16-3).pdf',1,'2018-12-03'),(86,1641,1193,82,'Humedo','NO','SI',15,15,15,15,62,48,56,45,15,2535,'2018-12-03 19:52:55','2018-12-03 22:16:56',1,NULL,'2018-12-03',4,1,9.26,219,33.8,55552,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1193/FinalVIGAS(16-16-32).pdf',1,'2018-12-03'),(87,1461,1161,55,'Humedo','NO','SI',15,15,15,15,41,57,50,45,15,3680,'2018-12-04 16:47:57','2018-12-04 17:58:20',1,NULL,'2018-12-04',4,1,9.32,316,49.07,38950,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(11-57-29).pdf',1,'2018-12-04'),(88,1462,1161,55,'Humedo','NO','SI',15,15,15,15,33,21,28,45,15,3655,'2018-12-04 16:52:53','2018-12-04 17:58:46',1,NULL,'2018-12-04',4,1,9.65,303,48.73,6468,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1161/FinalVIGAS(11-58-28).pdf',1,'2018-12-04'),(89,1464,1162,57,'Humedo','NO','SI',15,15,15,15,33,45,40,45,15,3715,'2018-12-04 17:12:42','2018-12-04 21:46:53',1,NULL,'2018-12-04',4,1,9.62,309,49.53,19800,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1162/FinalVIGAS(15-44-51).pdf',1,'2018-12-04'),(90,1465,1162,57,'Humedo','NO','SI',15,15,15,15,40,52,57,45,15,3685,'2018-12-04 19:20:23','2018-12-04 21:47:55',1,NULL,'2018-12-04',4,1,9.36,315,49.13,39520,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1162/FinalVIGAS(15-47-13).pdf',1,'2018-12-04'),(91,1468,1163,60,'Humedo','NO','SI',15,15,15,15,22,12,28,45,15,3702,'2018-12-04 19:24:41','2018-12-04 21:49:20',1,NULL,'2018-12-04',4,1,9.26,320,49.36,2464,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1163/FinalVIGAS(15-48-28).pdf',1,'2018-12-04'),(92,1469,1163,60,'Humedo','NO','SI',15,15,15,15,44,32,29,45,15,3720,'2018-12-04 19:26:50','2018-12-04 21:50:30',1,NULL,'2018-12-04',4,1,9.57,311,49.6,13610.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1163/FinalVIGAS(15-49-40).pdf',1,'2018-12-04'),(93,1644,1194,83,'Humedo','SI','NO',15,15,15,15,61,52,48,45,15,2682,'2018-12-04 19:29:41','2018-12-04 21:51:34',1,NULL,'2018-12-04',4,1,9.8,219,35.76,50752,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1194/FinalVIGAS(15-51-4).pdf',1,'2018-12-04'),(94,1647,1194,83,'Humedo','NO','SI',15,15,15,15,29,38,44,45,15,2147,'2018-12-04 19:32:19','2018-12-04 21:52:26',1,NULL,'2018-12-04',4,1,9.44,182,28.63,16162.666666667,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1194/FinalVIGAS(15-51-43).pdf',1,'2018-12-04'),(95,1650,1194,83,'Humedo','SI','NO',15,15,15,15,61,50,65,45,15,2613,'2018-12-04 19:34:40','2018-12-04 21:53:15',1,NULL,'2018-12-04',4,1,9.77,214,34.84,66083.333333333,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1194/FinalVIGAS(15-52-50).pdf',1,'2018-12-04'),(96,1653,1195,84,'Humedo','NO','SI',15,15,15,15,39,25,31,45,15,2660,'2018-12-04 19:36:53','2018-12-04 21:57:02',1,NULL,'2018-12-04',4,1,9.76,218,35.47,10075,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1195/FinalVIGAS(15-56-47).pdf',1,'2018-12-04'),(97,1656,1195,84,'Humedo','SI','NO',15,15,15,15,54,44,58,45,15,2641,'2018-12-04 19:38:37','2018-12-04 21:58:04',1,NULL,'2018-12-04',4,1,9.83,215,35.21,45936,1063,'http://qualitycontrol.lacocsmex.com.mx/SystemData/FormatosFinalesData/VIGAS/1195/FinalVIGAS(15-57-13).pdf',1,'2018-12-04');

INSERT INTO `loteCorreos` (id_loteCorreos,creador_id,correosNo,status,createdON,lastEditedON,active,factua,observaciones,customMailStatus,customText,customMail,adjunto,pdfPath,xmlPath) VALUES
(5,1071,18,2,'2018-11-27 19:18:40','2018-11-27 20:24:48',1,'AD003343','PERIODO DEL 5 AL 10 NOVIEMBRE',0,'SaludosÂ¡',1,1,'SystemData/Facturas/5/pdfPath.pdf','SystemData/Facturas/5/xmlPath.xml'),
(6,1071,15,2,'2018-11-27 20:18:59','2018-11-27 20:24:30',1,'AD003362','null',0,'SaludosÂ¡',1,1,'SystemData/Facturas/6/pdfPath.pdf','SystemData/Facturas/6/xmlPath.xml');

INSERT INTO `correoDeLote` (id_correoDeLote,loteCorreos_id,pdf,status,createdON,lastEditedON,active,registrosCampo_id,formatoRegistroRev_id) VALUES
(17,5,NULL,1,'2018-11-27 19:18:40','2018-11-27 20:17:01',1,1454,NULL),
(18,5,NULL,1,'2018-11-27 19:18:40','2018-11-27 20:17:01',1,1457,NULL),
(19,5,NULL,1,'2018-11-27 19:18:40','2018-11-27 20:17:01',1,1460,NULL),
(20,5,NULL,1,'2018-11-27 19:18:40','2018-11-27 20:17:01',1,1463,NULL),
(21,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:01',1,1470,NULL),
(22,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:01',1,1473,NULL),
(23,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1467,NULL),
(24,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1476,NULL),
(25,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1479,NULL),
(26,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1482,NULL),
(27,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1485,NULL),
(28,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1488,NULL),
(29,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1491,NULL),
(30,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1494,NULL),
(31,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1497,NULL),
(32,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1500,NULL),
(33,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1503,NULL),
(34,5,NULL,1,'2018-11-27 20:13:04','2018-11-27 20:17:02',1,1506,NULL),
(35,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1509,NULL),
(36,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1512,NULL),
(37,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1515,NULL),
(38,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1518,NULL),
(39,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1521,NULL),
(40,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1524,NULL),
(41,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1527,NULL),
(42,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1530,NULL),
(43,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1536,NULL),
(44,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1539,NULL),
(45,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1545,NULL),
(46,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1548,NULL),
(47,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1551,NULL),
(48,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1554,NULL),
(49,6,NULL,1,'2018-11-27 20:18:59','2018-11-27 20:22:16',1,1557,NULL);


//// ============= DELETE DB
DROP TABLE correoDeLote;
DROP TABLE loteCorreos;
DROP TABLE ensayoCilindro;
DROP TABLE ensayoCubo;
DROP TABLE ensayoViga;
DROP TABLE registrosCampo;
DROP TABLE footerEnsayo;
DROP TABLE registrosRev;
DROP TABLE formatoCampo;
DROP TABLE formatoRegistroRev;
DROP TABLE listaAsistencia;
DROP TABLE tecnicos_ordenDeTrabajo;
DROP TABLE herramienta_ordenDeTrabajo;
DROP TABLE ordenDeTrabajo;
DROP TABLE obra;
DROP TABLE sesion;
DROP TABLE usuario;
DROP TABLE herramientas;
DROP TABLE herramienta_tipo;
DROP TABLE laboratorio_cliente;
DROP TABLE laboratorio;
DROP TABLE concretera;
DROP TABLE cliente;
DROP TABLE rol_usuario;
DROP TABLE log;
DROP TABLE systemstatus;
