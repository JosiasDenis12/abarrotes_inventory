-- Database schema for Abarrotes Tendejosn San Francisco Inventory System

-- Create database
CREATE DATABASE IF NOT EXISTS abarrotes_inventory;
USE abarrotes_inventory;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS `sale_item`;
DROP TABLE IF EXISTS `sale`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `supplier`;
DROP TABLE IF EXISTS `category`;
DROP TABLE IF EXISTS `auth_assignment`;
DROP TABLE IF EXISTS `auth_item_child`;
DROP TABLE IF EXISTS `auth_item`;
DROP TABLE IF EXISTS `auth_rule`;
DROP TABLE IF EXISTS `user`;

-- User table
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `role` varchar(20) NOT NULL DEFAULT 'staff',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- RBAC Tables
-- Primero creamos auth_rule ya que es referenciada por auth_item
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Luego creamos auth_item que depende de auth_rule
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Después creamos auth_item_child que depende de auth_item
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Finalmente creamos auth_assignment que depende de auth_item
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert basic roles and permissions
INSERT INTO `auth_item` (`name`, `type`, `description`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Administrador del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('manager', 1, 'Gerente de la tienda', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('staff', 1, 'Empleado de la tienda', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('view_products', 2, 'Ver productos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('create_product', 2, 'Crear productos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('update_product', 2, 'Actualizar productos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('delete_product', 2, 'Eliminar productos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('view_sales', 2, 'Ver ventas', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('create_sale', 2, 'Crear ventas', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('view_reports', 2, 'Ver reportes', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('manage_users', 2, 'Gestionar usuarios', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('view_suppliers', 2, 'Ver proveedores', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('create_supplier', 2, 'Crear proveedores', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('update_supplier', 2, 'Actualizar proveedores', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('delete_supplier', 2, 'Eliminar proveedores', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Assign permissions to roles
INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'manager'),
('manager', 'staff'),
('admin', 'view_products'),
('admin', 'create_product'),
('admin', 'update_product'),
('admin', 'delete_product'),
('admin', 'view_sales'),
('admin', 'create_sale'),
('admin', 'view_reports'),
('admin', 'manage_users'),
('manager', 'view_products'),
('manager', 'create_product'),
('manager', 'update_product'),
('manager', 'view_sales'),
('manager', 'create_sale'),
('manager', 'view_reports'),
('staff', 'view_products'),
('staff', 'view_sales'),
('staff', 'create_sale'),
('admin', 'view_suppliers'),
('admin', 'create_supplier'),
('admin', 'update_supplier'),
('admin', 'delete_supplier');

-- Assign admin role to admin user
INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', UNIX_TIMESTAMP());

-- Category table
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Supplier table
CREATE TABLE `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Product table
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `code` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `min_stock` int(11) NOT NULL DEFAULT '0',
  `unit` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk_product_category` (`category_id`),
  KEY `fk_product_supplier` (`supplier_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fk_product_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Customer table
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sale table
CREATE TABLE `sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `notes` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `fk_sale_customer` (`customer_id`),
  KEY `fk_sale_user` (`created_by`),
  CONSTRAINT `fk_sale_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  CONSTRAINT `fk_sale_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sale Item table
CREATE TABLE `sale_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_sale_item_sale` (`sale_id`),
  KEY `fk_sale_item_product` (`product_id`),
  CONSTRAINT `fk_sale_item_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `fk_sale_item_sale` FOREIGN KEY (`sale_id`) REFERENCES `sale` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert admin user
INSERT INTO `user` (`username`, `auth_key`, `password_hash`, `email`, `status`, `role`, `created_at`, `updated_at`) 
VALUES ('admin', 'admin-auth-key', '$2y$13$5s9FUlrk75gjmYrYUPZ3HOgzk99yxMOBiY78Jd3V4L18jQlsrJ5c6', 'admin@example.com', 10, 'admin', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Insert sample categories
INSERT INTO `category` (`name`, `description`) VALUES
('Abarrotes', 'Productos comestibles envasados'),
('Bebidas', 'Refrescos, jugos, agua y bebidas alcohólicas'),
('Lácteos', 'Leche, quesos, yogurt, etc.'),
('Frutas y Verduras', 'Productos frescos'),
('Limpieza', 'Productos de limpieza para el hogar'),
('Higiene Personal', 'Productos de cuidado personal');

-- Insert sample suppliers
INSERT INTO `supplier` (`name`, `contact_person`, `email`, `phone`, `address`) VALUES
('Distribuidora Nacional', 'Juan Pérez', 'ventas@disnac.com', '555-123-4567', 'Av. Industrial 123, Ciudad de México'),
('Refrescos del Valle', 'Maria Gómez', 'maria@refrescosdelvalle.com', '555-987-6543', 'Calle Bebidas 456, Guadalajara'),
('Productos Lácteos SA', 'Roberto Sánchez', 'roberto@lacteos.com', '555-456-7890', 'Carretera Lechera 789, Querétaro');