1DV408
======


Laborationsmiljö
----------------

Jag kommer använda [Vagrant](http://www.vagrantup.com/) för att kunna köra
LAMP i en virtuell maskin. För versionshantering kommer jag använda Git och
Github (vilket du säkert redan har förstått om du läser detta) och min
texteditor kommer självklart vara [Vim](http://www.vim.org/).

Webbhotell jag kommer lägga webbplatesen på blir Loopia, och adressen är
[1DV408.klamby.com](http://1dv408.klamby.com).


Sätta upp utvecklarmiljön
-------------------------

Jag har bara testat med Ubuntu 12.04 LTS (http://files.vagrantup.com/precise32.box).

Puppetmoduler som måste installeras:

* puppetlabs/apache 
* puppetlabs/mysql

Jag har en absolut sökväg på min dator i `puppet.module_path`, så det måste
ändras. (Det borde finnas en smartare lösning.)
