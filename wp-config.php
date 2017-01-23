<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'bouanichettiroul');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ';zTZpL,/3-eg|164tmMAxRBdsX!2tblm(dWXBX4jvS`5O3oPP9U[ !RE72Pgr;*4');
define('SECURE_AUTH_KEY',  '{+_1C)Fl?& S#>Sw%G6BolZQq<_,%sNrFuf@yeu~w;_TD7e9x^cxn5]J21IcH)NW');
define('LOGGED_IN_KEY',    'lWEa(;O#xdp0LJGu8dG-OJtq&;B+Z,R{epwA#B)exJ#hfutmgGV~BaU&4JgI^aUF');
define('NONCE_KEY',        '{oZ%q#ADkC-igUgLo6jdRP$G[#I2l+Gwkv(5+X~P*+.&xNYDgAjWPbn|+~RwTaOj');
define('AUTH_SALT',        'kjK=>5Qz|)aY~Pr%C():Wu`$j_WV>g]:L_rxZI|_/|0 c1Pp},hS a7jXo`h)qGr');
define('SECURE_AUTH_SALT', 'f:$8E9D6,`hy.`2FpuWO8HVA@l;B>2iYOCj yqNs9OsP5%#ip:eUE:`|K . bCg,');
define('LOGGED_IN_SALT',   'iN@3Bg^48!vmKXCq<-G^;4V(uG8?hB14h-.i,K** *F!-GFPwD5:K2.lVs8j>7}[');
define('NONCE_SALT',       'Un0;6[^`7)L.<jZQEV2oX`N:/DjMirDad5]K2=A=q-Cj3(yLTif9:Nr^h$0b&R9v');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'bt_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');