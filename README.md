VENUS FRAMEWORK
===============

[![Build Status](https://travis-ci.org/las93/venus3.svg?branch=master)](https://travis-ci.org/las93/venus3)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/las93/venus3/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/las93/venus3/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/las93/venus3/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/las93/venus3/?branch=master)
[![Total Downloads](https://poser.pugx.org/las93/venus3/downloads.svg)](https://packagist.org/packages/las93/venus3)
[![Latest Stable Version](https://poser.pugx.org/las93/venus3/v/stable.svg)](https://packagist.org/packages/las93/venus3)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/eb4d1677-8e57-421b-afda-00d46c11d424/mini.png)](https://insight.sensiolabs.com/projects/eb4d1677-8e57-421b-afda-00d46c11d424)

Venus 3 Framework 

Contact judicael.paquet@gmail.com pour participer au projet ou avoir plus d'information
Contact judicael.paquet@gmail.com to participate at the project or to have more informations

===================
Français
===================

Nouveau framework PHP basé sur un concept MVC solide et très malléable.

Pour afficher Hello World, voici le Vhost apache Type à mettre en place :

<pre>
&lt;VirtualHost *:80&gt;
     ServerName localhost
     DocumentRoot E:/venus/public/Demo/
     &lt;Directory E:/venus/public/Demo/&gt;
         DirectoryIndex index.php
         AllowOverride All
         Order allow,deny
         Allow from all
     &lt;/Directory&gt;
&lt;/VirtualHost&gt;
</pre>

===================
Anglais
===================

New PHP framework based on a strong MVC concept and very malleable

To display Hello World in your browser, there is Vhost apache to write in your apache2.conf (or http.conf) :

<pre>
&lt;VirtualHost *:80&gt;
     ServerName localhost
     DocumentRoot E:/venus/public/Demo/
     &lt;Directory E:/venus/public/Demo/&gt;
         DirectoryIndex index.php
         AllowOverride All
         Order allow,deny
         Allow from all
     &lt;/Directory&gt;
&lt;/VirtualHost&gt;
</pre>
