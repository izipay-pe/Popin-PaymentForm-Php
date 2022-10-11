<?php

// Identificador de su tienda
IzipayController::setDefaultUsername("12345678");

// Clave de Test o Producción
IzipayController::setDefaultPassword("testpassword_111111111111111111111111111111111111");

// Clave Pública de Test o Producción
IzipayController::setDefaultPublicKey("2222222222222222222222222222222222222222222222222");

// Clave HMAC-SHA-256 de Test o Producción
IzipayController::setDefaultHmacSha256("33333333333333333333333333333333333333333333333");

// URL del servidor de Izipay
IzipayController::setDefaultEndpointApiRest("https://api.micuentaweb.pe");