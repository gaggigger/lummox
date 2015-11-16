# Lummox ratkaisun repo

## Asennus

### Palvelin ja tietokanta
- Sovellus on suunniteltu ajettavaksi Apache-palvelimella
- Sovellus käyttää localhost:3306:ssa toimivaa MySQL-tietokantaa. Asetuksia voi muokata api/config.php:ssa
- SQL dump löytyy repon juuresta (lummox.sql)

### Dependencyt
- Frontendin vaatimat lisäkilkkeet voi asentaa bowerilla 'bower install'
- API:n dependencyt asenna kansiossa /api composerilla

## Käyttö ja ominaisuudet

Lummox on verkkosovellus, jossa käyttäjä voi kirjoittaa elokuva-arvosteluja.

### Elokuvat

Sovelluksessa voi tarkastella listaa elokuvista (/films). Elokuvan nimeä tai taulukon view-nappia painamalla pääsee elokuvan sivulle.

Elokuvan sivulla on tarkempia tietoja elokuvista, sekä lista arvosteluista. Tätä kautta pääsee myös kirjoittamaan oman arvostelun.

### Käyttäjät

Sovelluksessa voi tarkastella listaa käyttäjistä. Nimeä tai reviews-nappia painamalla pääsee katsomaan käyttäjän kirjoittamia arvosteluja.

Käyttäjä voi rekisteröidä tunnuksensa antamalla sähköpostin ja salasanan. Sovellus luo käyttäjän roolilla 'unverified'. Roolia voidaan muuttaa ylläpitäjän toimesta.

Olemassaolevia tunnuksia, joilla voi kirjautua sisään (tunnus / salasana):
MasterAdmin / masterpass
someUser1 / someUser12
filmLover25 / filmLover25
AnotherUser2 / AnotherUser2

Käyttäjä voi tarkastella omia arvostelujaan profiilisivulla. Tätä kautta käyttäjä pääsee muokkaamaan arvostelujaan.

### Arvostelut

Sovellus näyttää listan kaikista arvosteluista, joiden status on 2 eli 'published'. Rekisteröimättömän käyttäjän arvostelut menevät sisään statuksella 1, eli 'pending'.

### Tekemistä

Kannattaa kokeilla kirjautua sisään esimerkiksi käyttäjänä AnotherUser2 ja kirjoittaa elokuville arvosteluja.

## Muuta

Sovellukseen on aloitettu autentikaation tekeminen, mutta se on jäänyt kesken. Arkkitehtuuri mahdollistaa kuitenkin autentikoinnin.

Autentikaatioon käytetään jwt:tä. Salasanat on suojattu backendissä. Muiden käyttäjien arvosteluja ei pääse muokkaamaan oman profiilisivun kautta räpläämällä urlia. Sovellus varmistaa, että käyttäjä on todella sama, kuin se, jonka arvostelut haetaan profiilisivulle.