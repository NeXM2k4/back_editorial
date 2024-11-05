-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 05, 2024 at 12:50 PM
-- Server version: 5.7.26-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new_editorial`
--

-- --------------------------------------------------------

--
-- Table structure for table `autores`
--

CREATE TABLE `autores` (
  `nIdAutor` int(11) NOT NULL COMMENT 'Identificador del autor de artículo',
  `sNombreAutor` varchar(50) NOT NULL COMMENT 'Nombre del autor del artículo',
  `sApellidoAutor` varchar(40) NOT NULL COMMENT 'Apellido del autor del artículo',
  `nEstado` int(11) NOT NULL COMMENT 'Campo para el registro del estado de la publicación'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de autores de artículos y otro tipo de publicaciones';

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `nIdCategoria` int(11) NOT NULL COMMENT 'Campo para registro de ID de categoria',
  `sTituloCategoria` varchar(150) NOT NULL COMMENT 'Campo para registro del titulo de la categoría',
  `sDescripcion` text NOT NULL COMMENT 'Campo para registro de descripción de la publicación',
  `nPosicionMenu` int(11) NOT NULL COMMENT 'Campo para registro del numero de posicion en la que se almacenara la categoría',
  `nIdioma` int(11) NOT NULL COMMENT 'Campo para registro del idioma',
  `nIdCategoriaPadre` int(11) NOT NULL COMMENT 'Campo que se utiliza para hacer referencia a la categoria padre de esta misma tab.a',
  `link_lenguaje` varchar(255) NOT NULL COMMENT 'Campo que se utiliza para referenciar a la misma categoria pero en diferentes lenguajes',
  `nEstado` int(11) NOT NULL COMMENT 'Campo para registro de bandera de estado',
  `nTipoPublicacion` int(11) NOT NULL COMMENT 'Campo para registrar si la categoria es de libro o revista',
  `nMostrarNovedades` int(11) NOT NULL COMMENT 'Campo para almacenar si la categoria actual se muestra en destacados, 1=>Si, 2=>No',
  `nIdImenu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categorias_detalle`
--

CREATE TABLE `categorias_detalle` (
  `nIdCatDetalle` int(11) NOT NULL,
  `nIdCategoria` int(11) NOT NULL,
  `sDescripcion` text NOT NULL,
  `sConsejo` mediumtext NOT NULL,
  `sPoliticas` mediumtext NOT NULL,
  `sNormas` text,
  `sProceso` text,
  `sContacto` text,
  `nIdioma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA PARA CATEGORIAS DETALLE';

-- --------------------------------------------------------

--
-- Table structure for table `idiomas`
--

CREATE TABLE `idiomas` (
  `idIdioma` int(11) NOT NULL COMMENT 'id de los idiomas',
  `idioma` varchar(100) NOT NULL COMMENT 'el idioma'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `publicaciones_detalle`
--

CREATE TABLE `publicaciones_detalle` (
  `nIdDetallePublicacion` int(11) NOT NULL COMMENT 'Campo para registro del ID de detalle del artículo',
  `sTitulo` varchar(300) NOT NULL COMMENT 'Campo para registro de título del artículo del volumen de la publicación',
  `sResumen` text NOT NULL COMMENT 'Campo para registro del resumen del artículo del volumen de la publicación',
  `sURLMuestra` varchar(300) NOT NULL COMMENT 'Campo para registro del URL del archivo PDF en el repositorio',
  `Orden` int(11) NOT NULL COMMENT 'Campo para registro del orden en que aparece el elemento',
  `nIdPublicacion` int(11) NOT NULL COMMENT 'Campo que hace referencia al ID de la publicacion padre a la que pertenece el articulo/descripción',
  `nIdioma` int(11) NOT NULL COMMENT 'Campo para registro del idioma correspondiente',
  `nEstado` int(11) NOT NULL DEFAULT '1' COMMENT 'Valor para almacenar el estado del artículo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla para registro de los articulos asociados a las publicaciones';

-- --------------------------------------------------------

--
-- Table structure for table `publicaciones_encabezado`
--

CREATE TABLE `publicaciones_encabezado` (
  `nIdPublicacion` int(11) NOT NULL COMMENT 'Campo para el id de la publicación',
  `sTitulo` varchar(255) DEFAULT NULL COMMENT 'Campo para registro del titulo de la publicacion',
  `sPortada` varchar(300) DEFAULT NULL COMMENT 'Campor para registro de ruta de la imagen de la portada del volumen de la publicación',
  `sMiniatura` varchar(300) DEFAULT NULL COMMENT 'Campo de registro de imagen miniatura de la portada del volumen',
  `sEpoca` varchar(8) DEFAULT NULL COMMENT 'Campo para registro de la época de la revista',
  `sNumeroEpoca` varchar(6) DEFAULT NULL COMMENT 'Campo para registro de número dentro de la época de la revista',
  `nNumeroVolumen` int(11) DEFAULT NULL COMMENT 'Campo para registro de número del volumen de la publicación',
  `nNumeroPaginas` int(11) DEFAULT '0' COMMENT 'Campo para registro de número de paginas de la publicación',
  `dFechaVolumen` date DEFAULT NULL COMMENT 'Campo para registro de fecha de lanzamiento del volumen de la publicación',
  `sCodigoPublicacion` varchar(30) DEFAULT NULL COMMENT 'Campo para registro del ISBN de libros o ISSN de revistas',
  `nMostrarNovedades` int(11) DEFAULT '0' COMMENT 'Campo para registro que indica que la publicacion se muestra en novedades, 0-> No se muestra,  1->Si se muestra',
  `sTipoPublicacion` int(11) NOT NULL COMMENT 'Campo para registro del tipo de publicacion',
  `sAnioPublicacion` varchar(4) DEFAULT NULL COMMENT 'Campo para registro del año de publicación',
  `nIdioma` int(11) NOT NULL COMMENT 'Campo para registro del idioma respectivo',
  `nIdCategoria` int(11) NOT NULL COMMENT 'Campo para registro del ID de categoria de categorias.nIdCategoria',
  `sURL` varchar(500) NOT NULL COMMENT 'Campo para almacenar la url de acceso',
  `sURLRecurso` text COMMENT 'Campo para almacenar las URL del recurso ya sean locales o en el repositorio',
  `nEstadoPublicacion` int(11) NOT NULL COMMENT 'Campo para el registro del estado de la publicacion, donde 0 => En papelera, 1 => Publicado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de encabezado de las publicaciones realizadas (revistas, libros, etc)';

-- --------------------------------------------------------

--
-- Table structure for table `publicaciones_encabezado_bkp`
--

CREATE TABLE `publicaciones_encabezado_bkp` (
  `nIdPublicacion` int(11) NOT NULL COMMENT 'Campo para el id de la publicación',
  `sTitulo` varchar(255) DEFAULT NULL COMMENT 'Campo para registro del titulo de la publicacion',
  `sPortada` varchar(300) DEFAULT NULL COMMENT 'Campor para registro de ruta de la imagen de la portada del volumen de la publicación',
  `sMiniatura` varchar(300) DEFAULT NULL COMMENT 'Campo de registro de imagen miniatura de la portada del volumen',
  `sEpoca` varchar(8) DEFAULT NULL COMMENT 'Campo para registro de la época de la revista',
  `sNumeroEpoca` varchar(6) DEFAULT NULL COMMENT 'Campo para registro de número dentro de la época de la revista',
  `nNumeroVolumen` int(11) DEFAULT NULL COMMENT 'Campo para registro de número del volumen de la publicación',
  `nNumeroPaginas` int(11) DEFAULT '0' COMMENT 'Campo para registro de número de paginas de la publicación',
  `dFechaVolumen` date DEFAULT NULL COMMENT 'Campo para registro de fecha de lanzamiento del volumen de la publicación',
  `sCodigoPublicacion` varchar(30) DEFAULT NULL COMMENT 'Campo para registro del ISBN de libros o ISSN de revistas',
  `nMostrarNovedades` int(11) DEFAULT '0' COMMENT 'Campo para registro que indica que la publicacion se muestra en novedades, 0-> No se muestra,  1->Si se muestra',
  `sTipoPublicacion` int(11) NOT NULL COMMENT 'Campo para registro del tipo de publicacion',
  `sAnioPublicacion` varchar(4) DEFAULT NULL COMMENT 'Campo para registro del año de publicación',
  `nIdioma` int(11) NOT NULL COMMENT 'Campo para registro del idioma respectivo',
  `nIdCategoria` int(11) NOT NULL COMMENT 'Campo para registro del ID de categoria de categorias.nIdCategoria',
  `sURL` varchar(500) NOT NULL COMMENT 'Campo para almacenar la url de acceso',
  `sURLRecurso` text NOT NULL COMMENT 'Campo para almacenar las URL del recurso ya sean locales o en el repositorio',
  `nEstadoPublicacion` int(11) NOT NULL COMMENT 'Campo para el registro del estado de la publicacion, donde 0 => En papelera, 1 => Publicado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de encabezado de las publicaciones realizadas (revistas, libros, etc)';

-- --------------------------------------------------------

--
-- Table structure for table `publicacion_autor`
--

CREATE TABLE `publicacion_autor` (
  `nIdPublicacion` int(11) NOT NULL COMMENT 'Campo para registro del ID de la publicación',
  `nIdAutor` int(11) NOT NULL COMMENT 'Campo para registro de los autores de la publicación'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla para registrar los autores y las publicaciones como relación de n:n';

-- --------------------------------------------------------

--
-- Table structure for table `tipo_publicacion`
--

CREATE TABLE `tipo_publicacion` (
  `nIdTipoPublicacion` int(11) NOT NULL COMMENT 'Campo para registrar el id del tipo de publicacion',
  `sNombre` varchar(100) NOT NULL COMMENT 'Campo de registro del nombre para el tipo de publicacion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla para almacenar los posibles tipos de publicacion';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`nIdAutor`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`nIdCategoria`),
  ADD KEY `FK_categorias_idiomas` (`nIdioma`);

--
-- Indexes for table `categorias_detalle`
--
ALTER TABLE `categorias_detalle`
  ADD PRIMARY KEY (`nIdCatDetalle`),
  ADD KEY `FK_CATEGORIAS_DETALLE_CATEGORIA` (`nIdCategoria`),
  ADD KEY `FK_categorias_detalle_idiomas` (`nIdioma`);

--
-- Indexes for table `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`idIdioma`);

--
-- Indexes for table `publicaciones_detalle`
--
ALTER TABLE `publicaciones_detalle`
  ADD PRIMARY KEY (`nIdDetallePublicacion`),
  ADD KEY `publicaciones_detalle_ibfk_1` (`nIdPublicacion`),
  ADD KEY `FK_publicaciones_detalle_idiomas` (`nIdioma`);

--
-- Indexes for table `publicaciones_encabezado`
--
ALTER TABLE `publicaciones_encabezado`
  ADD PRIMARY KEY (`nIdPublicacion`),
  ADD KEY `publicaciones_encabezado_ibfk_1` (`sTipoPublicacion`),
  ADD KEY `publicaciones_encabezado_ibfk_2` (`nIdCategoria`);

--
-- Indexes for table `publicaciones_encabezado_bkp`
--
ALTER TABLE `publicaciones_encabezado_bkp`
  ADD PRIMARY KEY (`nIdPublicacion`);

--
-- Indexes for table `publicacion_autor`
--
ALTER TABLE `publicacion_autor`
  ADD KEY `publicacion_autor_ibfk_1` (`nIdPublicacion`),
  ADD KEY `publicacion_autor_ibfk_2` (`nIdAutor`);

--
-- Indexes for table `tipo_publicacion`
--
ALTER TABLE `tipo_publicacion`
  ADD PRIMARY KEY (`nIdTipoPublicacion`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `FK_categorias_idiomas` FOREIGN KEY (`nIdioma`) REFERENCES `idiomas` (`idIdioma`);

--
-- Constraints for table `categorias_detalle`
--
ALTER TABLE `categorias_detalle`
  ADD CONSTRAINT `FK_CATEGORIAS_DETALLE_CATEGORIA` FOREIGN KEY (`nIdCategoria`) REFERENCES `categorias` (`nIdCategoria`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_categorias_detalle_idiomas` FOREIGN KEY (`nIdioma`) REFERENCES `idiomas` (`idIdioma`);

--
-- Constraints for table `publicaciones_detalle`
--
ALTER TABLE `publicaciones_detalle`
  ADD CONSTRAINT `FK_publicaciones_detalle_idiomas` FOREIGN KEY (`nIdioma`) REFERENCES `idiomas` (`idIdioma`),
  ADD CONSTRAINT `publicaciones_detalle_ibfk_1` FOREIGN KEY (`nIdPublicacion`) REFERENCES `publicaciones_encabezado` (`nIdPublicacion`) ON UPDATE CASCADE;

--
-- Constraints for table `publicaciones_encabezado`
--
ALTER TABLE `publicaciones_encabezado`
  ADD CONSTRAINT `publicaciones_encabezado_ibfk_1` FOREIGN KEY (`sTipoPublicacion`) REFERENCES `tipo_publicacion` (`nIdTipoPublicacion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `publicaciones_encabezado_ibfk_2` FOREIGN KEY (`nIdCategoria`) REFERENCES `categorias` (`nIdCategoria`) ON UPDATE CASCADE;

--
-- Constraints for table `publicacion_autor`
--
ALTER TABLE `publicacion_autor`
  ADD CONSTRAINT `publicacion_autor_ibfk_1` FOREIGN KEY (`nIdPublicacion`) REFERENCES `publicaciones_encabezado` (`nIdPublicacion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `publicacion_autor_ibfk_2` FOREIGN KEY (`nIdAutor`) REFERENCES `autores` (`nIdAutor`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
